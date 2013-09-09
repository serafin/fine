<?php

class f_c_helper_datafile extends f_c
{
    /**
     * main folder with files
     */
    const MAIN_FOLDER = 'data';
    
    /**
     * folder with temporary files
     */
    const TMP_FOLDER = 'tmp';
    
    /**
     * default logical folder
     */
    const DEFAULT_LOGICAL_FOLDER = 'default';
    
    /**
     * multiple of max id-number in quantity folder
     * if set or diffrent from 0, add quantity folder to path
     * 
     * @var int 
     */
    protected $_divideIntoQuantityFolders = 10000;
    
    /**
     * create scaled image based on uri path
     * 
     * @param string $sUriPath
     * @param boolean $bSaveFile
     * @return boolean/f_image
     */
    public function createImgSize($sUriPath, $bSaveFile = true)
    {
        list($sTable, , , $sFileName) = $this->extractDataByPath($sUriPath);

        $sExt = end(explode(".", $sFileName));
        list($id, , $sSize) = explode("_", substr($sFileName, 0, - (strlen($sExt)+1)), 3);

        if (!ctype_digit($id)) {
            return false;
        }

        $sModel = "m_{$sTable}";
        $model = new $sModel;
        $model->select($id);
 
        if ($model->id()) {
            return $this->createImgSizeByModel($model, $sSize, $bSaveFile);
        }
        
        return false;
    }
    
    /**
     * create scaled image based on model
     * 
     * @param f_m $model
     * @param string $sSize
     * @param boolean $bSaveFile
     * @return boolean/f_image
     */
    public function createImgSizeByModel(f_m $model, $sSize, $bSaveFile = true)
    {
        list($id, $token, $extOrg, $sTable) = $this->extractData($model);

        $config = $this->getImgConfig($sTable, $sSize);

        if ($config) {
            $sFilePath = $this->getPath($model);
            
            $oImg = new f_image();
            $oImg->load("{$sFilePath}{$id}_{$token}.{$extOrg}")
                 ->{$config['type']}($config['w'], $config['h'], $config['extend']);

            $ext = (isset($config['ext'])) ? $config['ext'] : $extOrg;
            $oImg->type($ext);
            
            // call method from class
            if (count($config['callback']) > 0){
                foreach($config['callback'] as $class => $method) {
                    if(!method_exists($class, $method)){
                        return false;
                    }

                    $oClass = new $class;
                    $oClass->{$method}($oImg);
                }
            }
            
            if ($bSaveFile) {
                $oImg->save("{$sFilePath}{$id}_{$token}_{$sSize}.{$ext}");
            }

            return $oImg;
        }
        
        return false;
    }
    
    /**
     * create temporary image
     * 
     * @param string $sPath
     * @param boolean $bSaveFile
     * @return boolean/f_image
     */
    public function createTmpImgSize($sPath, $bSaveFile = true)
    {
        list(, , , $sFileName) = $this->extractDataByPath($sPath);
        
        list($token, $option, $name) = explode('_', $sFileName, 3);
        
        foreach ($this->config->data as $sTable => $v) {
            $config = $this->getImgConfig($sTable, $option);
            
            if ($config) {
                $oImg = new f_image();
                $oImg->load(self::MAIN_FOLDER . '/' . self::TMP_FOLDER . "/{$token}__{$name}")
                     ->{$config['type']}($config['w'], $config['h'], !$config['extend']);
                
                if ($bSaveFile) {
                    $oImg->save(self::MAIN_FOLDER . '/' . self::TMP_FOLDER . "/{$token}_{$option}_{$name}");
                }
                
                return $oImg;
            }
        }
        
        return false;
    }
    
    /**
     * calculate image size
     * 
     * @param f_m $model
     * @param string $size
     * @return boolean/array
     */
    public function calculateImgSize(f_m $model, $sSize)
    {
        $sTable = $model->table();
        
        $height = null;
        $width  = null;
        if ($model->isField($sTable . '_height') && $model->isField($sTable . '_width')) {
            $height = $model->{$sTable . '_height'};
            $width  = $model->{$sTable . '_width'};
        }
        else {
            $img = f_image::_()->load('.' . $this->uri($model));

            if ($img->resource()) {
                $height = $img->height();
                $width  = $img->width();
            }
        }

        $config = $this->getImgConfig($sTable, $sSize);
        
        if ($width && $height && $config) {
            $result = $config['type'] == 'thumb'
                ? $this->_calculateThumb($width, $height, $config['w'], $config['h'])
                : $this->_calculateResize($width, $height, $config['w'], $config['h'], !$config['extend']);
            
            return $result;
        }
        
        return false;
    }
    
    /**
     * check if it's full or short image configuration
     * and get it
     * 
     * @param string $sTable
     * @param string $sSize
     * @return boolean/ array
     */
    public function getImgConfig($sTable, $sSize)
    {
        if ($this->config->data[$sTable] && $sSize) {
            foreach ($this->config->data[$sTable] as $config) {
                foreach ($config as $size => $value) {
                    if (is_array($value) && $size == $sSize) { 
                        return $value;
                    }
                    elseif ($value == $sSize) { 
                        return $this->_resolveImgSize($value);
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * destroy file
     * 
     * @param f_m $model
     */
    public function destroy(f_m $model)
    {
        list($id, $token, $ext, $table) = $this->extractData($model);
        $sFilePath = $this->getPath($model);
        
        // orginal file
        if (file_exists("{$sFilePath}{$id}_{$token}.{$ext}")) {
            unlink("{$sFilePath}{$id}_{$token}.{$ext}");
        }
        
        // scaled file
        if($this->config->data[$table]) {
            foreach ($this->config->data[$table] as $i) {
                foreach ($i as $k => $v) {
                    $ext2 = $ext;
                    if(is_array($v)) {
                        if($v['ext']) {
                            $ext2 = $v['ext'];
                        }
                        $v = $k;
                    }

                    if (file_exists("{$sFilePath}{$id}_{$token}_{$v}.{$ext2}")) {
                        unlink("{$sFilePath}{$id}_{$token}_{$v}.{$ext2}");
                    }
                }
            }
        }
    }
    
    /**
     * store file
     * 
     * @param f_m $model
     * @param string $sSrcFilePath
     * @return boolean
     */
    public function storeFile(f_m $model, $sSrcFilePath)
    {
        if (is_file($sSrcFilePath)) {
            $resource = file_get_contents($sSrcFilePath);

            if ($resource) {
                $table = $model->table();

                if (!$model->id()) {
                    return false;
                }
                
                $bFlagSaveModel = false;
                if (!$model->{$table . '_token'}) {
                    $model->{$table . '_token'} = $this->token();
                    $bFlagSaveModel = true;
                }
                if (!$model->{$table . '_ext'}) {
                    $ext = pathinfo($sSrcFilePath, PATHINFO_EXTENSION);
                    $model->{$table . '_ext'} = $ext ? $ext : '';
                    $bFlagSaveModel = true;
                }
                if($model->isField($table . '_folder')) {
                    if (!$model->{$table . '_folder'}) {
                        $model->{$table . '_folder'} = self::DEFAULT_LOGICAL_FOLDER;
                        $bFlagSaveModel = true;
                    }
                }
                if($model->isField($table . '_size')) {
                    if (!$model->{$table . '_size'}) {
                        $size = filesize($sSrcFilePath);
                        $model->{$table . '_size'} = $size ? $size : 0;
                        $bFlagSaveModel = true;
                    }
                }
                if($bFlagSaveModel) {
                    $model->save();
                }

                list($id, $token, $ext) = $this->extractData($model);
                $sFilePath = $this->getPath($model);

                if ($this->_setFolders($sFilePath)) { 
                    $sFileName = "{$sFilePath}{$id}_{$token}." . ($ext ? $ext : '');
                    if (file_put_contents($sFileName, $resource)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * store image
     * 
     * @param f_m $model
     * @param f_image $img
     * @return boolean
     */
    public function storeImg(f_m $model, f_image $img)
    {
        if ($img->resource()) {
            $table = $model->table();

            if (!$model->id()) {
                return false;
            }
            
            $bFlagSaveModel = false;
            if (!$model->{$table . '_token'}) {
                $model->{$table . '_token'} = $this->token();
                $bFlagSaveModel = true;
            }
            if (!$model->{$table . '_ext'}) {
                $model->{$table . '_ext'} = $img->type();
                $bFlagSaveModel = true;
            }
            if($model->isField($table . '_height')) {
                if (!$model->{$table . '_height'}) {
                    $model->{$table . '_height'} = $img->height();
                    $bFlagSaveModel = true;
                }
            }
            if($model->isField($table . '_width')) {
                if (!$model->{$table . '_width'}) {
                    $model->{$table . '_width'} = $img->width();
                    $bFlagSaveModel = true;
                }
            }
            if($model->isField($table . '_folder')) {
                if (!$model->{$table . '_folder'}) {
                    $model->{$table . '_folder'} = self::DEFAULT_LOGICAL_FOLDER;
                    $bFlagSaveModel = true;
                }
            }
            if($bFlagSaveModel) {
                $model->save();
            }

            list($id, $token, $ext) = $this->extractData($model);
            $sFilePath = $this->getPath($model);

            if ($this->_setFolders($sFilePath)) {
                $sImgName = "{$sFilePath}{$id}_{$token}.{$ext}";
                $img->save($sImgName);
                
                if($model->isField($table . '_size')) {
                    if (!$model->{$table . '_size'}) {
                        $size = filesize($sImgName);
                        if($size) {
                            $model->{$table . '_size'} = $size;
                            $model->save();
                        }
                    }
                }

                return true;
            }
        }
        
        return false;
    }
    
    /**
     * extract file data
     * 
     * @param f_m/array $aoModel
     * @return array
     */
    public function extractData($aoModel)
    {
        if(is_object($aoModel)) {
            $aData = $aoModel->val();
            $table = $aoModel->table();
        }
        elseif(!empty($aoModel['model'])) {
            $aData = $aoModel;
            $table = $aoModel['model'];
        }

        if(count($aData) > 0 && !empty($table)) {
            return array(
                $aData[$table . '_id'], // id
                $aData[$table . '_token'], // token
                !empty($aData[$table . '_ext']) ? $aData[$table . '_ext'] : '', // extension
                $table, // type
                array_key_exists($table . '_folder', $aData) 
                    ? (!empty($aData[$table . '_folder']) ? $aData[$table . $table . '_folder'] : self::DEFAULT_LOGICAL_FOLDER) . '/'
                    : null, // logical folder
            );
        }
        
        return false;
    }
    
    /**
     * extract file data from path
     * 
     * @param string $sPath
     * @return array
     */
    public function extractDataByPath($sPath)
    {
        $aPath = explode('/', preg_replace('#(^' . substr($this->uriAbs, 0, -1) . '\/)|(^\.?\/?)#', '', $sPath));
        $iCount = count($aPath) - 1;

        $sFileName = $aPath[$iCount];
        unset($aPath[$iCount]);

        list($mainFolder, $table, $folder1, $folder2) = $aPath;
        
        $logicalFolder  = null;
        $quantityFolder = null;
        if ($this->_divideIntoQuantityFolders && $folder2) {
            $logicalFolder  = $folder1;
            $quantityFolder = $folder2;
        }
        elseif ($this->_divideIntoQuantityFolders && $folder1) {
            $quantityFolder = $folder1;
        }
        elseif ($folder1) {
            $logicalFolder = $folder1;
        }
        
        return array(
            $table,
            $logicalFolder,
            $quantityFolder,
            $sFileName,
            $mainFolder,
        );
    }

    /**
     * return file path
     * 
     * @param f_m/ array $aoModel
     * @return string ./data/{model}/{logicalFolder}/{quantityFolder}/
     */
    public function getPath($aoModel)
    {
        if(is_object($aoModel)) {
            $aData = $aoModel->val();
            $table = $aoModel->table();
        }
        elseif(!empty($aoModel['model'])) {
            $aData = $aoModel;
            $table = $aoModel['model'];
        }
        if(count($aData) > 0 && !empty($table)) {
            $logicalFolder = array_key_exists($table . '_folder', $aData) 
                ? (!empty($aData[$table . '_folder']) ? $aData[$table . '_folder'] : self::DEFAULT_LOGICAL_FOLDER) . '/'
                : '';
            $quantityFolder = $this->_getQuantityFolder($aData[$table . '_id']);

            return './' . self::MAIN_FOLDER . "/{$table}/" . $logicalFolder . $quantityFolder;
        }
        
        return false;
    }
    
    /**
     * return uri path to file
     * 
     * @param f_m/array $aoModel
     * @return string /data/{model}/{logicalFolder}/{quantityFolder}/{id}_{token}_{size}.{ext}
     */
    public function uri($aoModel, $sSize = null)
    {
        list($id, $token, $ext) = $this->extractData($aoModel);
        $sFilePath = $this->getPath($aoModel);
        
        return substr($sFilePath,1) 
            . "{$id}_{$token}"
            . ($sSize ? "_{$sSize}" : "")
            . ".{$ext}";
    }
    
    /**
     * calculate resized image size
     * 
     * @param int $iOldW
     * @param int $iOldH
     * @param int $iNewW
     * @param int $iNewH
     * @param boolean $bExtend
     * @return array
     */
    protected function _calculateResize($iOldW, $iOldH, $iNewW, $iNewH, $bExtend = true)
    {
        if ($bExtend === false) {
            if ($iOldW <= $iNewW && $iOldH <= $iNewH) {
		return array('w' => $iOldW, 'h' => $iOldH);
            }
	}
        
	$w = (int)$iNewW;
	$h = (int)($w * $iOldH / $iOldW);
        
	if ($h > $iNewH) {
            $h = (int)$iNewH;
            $w = (int)($h * $iOldW / $iOldH);
        }        
        
        return array('w' => $w, 'h' => $h);
    }
        
    /**
     * calculate thumb image size-name
     * 
     * @param int $iOldW
     * @param int $iOldH
     * @param int $iNewW
     * @param int $iNewH
     * @return array
     */
    protected function _calculateThumb($iOldW, $iOldH, $iNewW, $iNewH)
    {
        $w = (int)$iNewW;
	$h = (int)($w * $iOldH / $iOldW);
        
	if ($h <= $iNewH) {
            $h = (int)$iNewH;
            $w = (int)($h * $iOldW / $iOldH);
            $x = (int)($w - $iNewW) / 2;
	}
	else {
            $y = (int)($h - $iNewH) / 2;
	}
        
        $w -= $x*2;
        $h -= $y*2;

        return array('w' => $w, 'h' => $h);
    }
    
    /**
     * return quntity folder name if it's set
     * 
     * @param int $id - model id
     * @return string
     */
    protected function _getQuantityFolder($id)
    {
        $folder = '';
        
        if ($this->_divideIntoQuantityFolders) {
            $folder = (floor((int)$id / $this->_divideIntoQuantityFolders) + 1) . '/';
        }
        
        return $folder;
    }
    
    /**
     * extract image data from size
     * 
     * @param string $sSize
     * @return array
     */
    protected function _resolveImgSize($sSize)
    {
        $pattern = '/(?P<w>[0-9]{1,4})x?(?P<h>[0-9]{0,4})([rt]?)/';
        preg_match($pattern, $sSize, $matches);
        
        if ($matches['h'] === '') {
            $matches['h'] = $matches['w'];
            $matches[3]   = 't';
        }
        if (isset($matches[3]) && ($matches[3] == 't')) {
            $type = 'thumb';
            $extend = false;
        }
        else {
            $type = 'resize';
            $extend = true;
        }
        
        return array(
            'w'      => $matches['w'],
            'h'      => $matches['h'],
            'type'   => $type,
            'extend' => $extend
        );
    }
    
    /**
     * do exist folders from file path
     * if not create them
     * 
     * @param string $dirname
     * @return boolean
     */
    protected function _setFolders($dirname)
    {
        if (!file_exists($dirname)) {
            return mkdir($dirname, 0777, true);
        }
        
        return true;
    }
    
}