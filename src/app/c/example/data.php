<?php

class c_example_data extends f_c_action
{
    /**
     * add quantity folder to path
     * 
     * @var boolean 
     */
    protected $_divideIntoQuantityFolders = true;
    
    /**
     * multiple of max id-number in quantity folder
     * 
     * @var int 
     */
    protected $_maxIdInQuantityFolders = 10000;

    public static function resolveImgSize($sSize)
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
    
    public function __construct()
    {
        $this->render->off();
    }
    
    /**
     * pattern for logicalFolder #[a-zA-Z0-9]+#
     * 
     * @return type
     */
    public function indexAction()
    {
        if (isset($this->param)) {
            $this->data = $this->param['data'];
            $this->file = $this->param['file'];
            if($this->param['logicalFolder']) {
                $logicalFolder = $this->param['logicalFolder'];
            }
            if($this->param['quantityFolder']) {
                $quantityFolder = $this->param['quantityFolder'];
            }
        }
        else {
            $this->data = $_GET[1];
            if((preg_match('#'.$this->data.'\/[a-zA-Z0-9]+\/[0-9]+\/#', $_GET[1].'/'.$_GET[2].'/'.$_GET[3].'/') && $this->_divideIntoQuantityFolders) //is set quantity folder
                || (preg_match('#'.$this->data.'\/[a-zA-Z0-9]+\/#', $_GET[1].'/'.$_GET[2].'/') && !$this->_divideIntoQuantityFolders) // isn't set quantity folder
            ) {
                $logicalFolder = $_GET[2];
            }
            if($logicalFolder && $this->_divideIntoQuantityFolders) {
                $quantityFolder = $_GET[3];
            }
            elseif($this->_divideIntoQuantityFolders) {
                $quantityFolder = $_GET[2];
            }
            if($logicalFolder && $quantityFolder) {
                $this->file = $_GET[4];
            }
            elseif($logicalFolder || $quantityFolder) {
                $this->file = $_GET[3];
            }
            else {
                $this->file = $_GET[2];
            }
        }

        $filename = 'data/' . $this->data . '/'
            . ($logicalFolder ? $logicalFolder . '/' : '')
            . ($quantityFolder ? $quantityFolder . '/' : '')
            . $this->file;
        if (file_exists($_SERVER['DOCUMENT_ROOT']  . '/' . $filename)) {
            f_image::_()->load($filename)->render(95);
            return;
        }
        
        if (! $this->config->data[$this->data]) {
            $this->notFound();
        }

        $ext = end(explode(".", $this->file));
        list($this->id, $this->token, $this->size) = explode("_", substr($this->file, 0, - (strlen($ext)+1)), 3);

        if (!$this->config->data[$this->data]['imgsize'][$this->size] && !in_array($this->size,$this->config->data[$this->data]['imgsize'])) {
            $this->notFound();
        }
        
        //sprawdzanie czy konfiguracja pełna czy skrócona
        $this->config = is_array($this->config->data[$this->data]['imgsize'][$this->size])
            ? $this->config->data[$this->data]['imgsize'][$this->size]
            : $this->resolveImgSize($this->size);

        if (!ctype_digit($this->id) || !isset($this->config)) {
            $this->notFound();
        }

        $sModel = "m_{$this->data}";
        $this->model = new $sModel;
        $this->model->select($this->id);
        
        if (! $this->model->id()) {
            $this->notFound();
        }

        //sprawdzanie $this->config['fx']
        if(isset($this->config['fx'])){            
            if(!method_exists('c_data',$this->config['fx'] )){
                $this->notFound();
            }
            $this->{$this->config['fx']}();
        }
        else{
            $width  = $this->config['w'];
            $height = $this->config['h'];
            $extOrg = ($this->model->isField($this->data . '_ext') && !empty($this->model->{$this->data . '_ext'}))
                ? $this->model->{$this->data . '_ext'}
                : 'jpg';  
            $ext = (isset($this->config['ext']))
                ? $this->config['ext']
                : $extOrg;
            $type    = $this->config['type'];
            $extend  = $this->config['extend'];
            $quality = isset($this->config['quality']) ? $this->config['quality'] : 95;
        }    
        
        $path = substr($this->uri($this->model), 1);
        f_image::_()
            ->load("{$path}{$this->id}_{$this->token}.{$extOrg}")
            ->{$type}($width, $height, $extend)
            ->save("{$path}{$this->id}_{$this->token}_{$this->size}.{$ext}", $quality)
            ->render($quality);
    }
    
    public function tmpAction()
    {   
        list($token, $option, $name) = explode('_', $_GET[2], 3);
        
        foreach ($this->config->data as $model) {
            foreach ($model['imgsize'] as $size => $value) {
                
                if (is_array($value) && $size == $option) { 
                    $this->config =  $value;
                }
                elseif ($value == $option) { 
                    $this->config = $this->resolveImgSize($value);
                }
                else {
                    continue;
                }
                
                $width   = $this->config['w'];
                $height  = $this->config['h'];
                $type    = $this->config['type'];
                $extend  = $this->config['extend'];
                
                f_image::_()
                    ->load("data/tmp/{$token}__{$name}")
                    ->{$type}($width, $height, !$extend)
                    ->save("data/tmp/{$token}_{$option}_{$name}", 95)
                    ->render(95)
                ;
            }
        }
    }
    
    /**
     * calculate img size
     * 
     * @param array/object $aoModel
     * @param string $size
     * @return array
     */
    public function calculateImgSize($aoModel, $size)
    {
        extract($this->_getData($aoModel));
        $img = f_image::_()->load(substr($this->uri($aoModel),1));

        if($img->resource()) {
            if($img->width() == 0 || $img->height() == 0) {
                return;
            }

            //sprawdzanie czy konfiguracja pełna czy skrócona
            if($this->config->data[$type]) {
                foreach($this->config->data[$type] as $config) {
                    $this->config = is_array($config[$size])
                        ? $config[$size]
                        : $this->resolveImgSize($size);
                }
            }

            $result = $this->config['type'] == 'thumb'
                ? $this->_calculateThumb($img->width(), $img->height(), $this->config['width'], $this->config['height'])
                : $this->_calculateResize($img->width(), $img->height(), $this->config['width'], $this->config['height'], !$this->config['extend']);

            return $result;
        }
        
        return;
    }
    
    
    /**
     * return file path
     * 
     * @param array/object $aoModel
     * @return string ./data/{model}/{logicalFolder}/{quantityFolder}/
     */
    public function getFilePath($aoModel)
    {
        if(is_array($aoModel)) {
            $type = reset(explode('_', key($aoModel)));
            $logicalFolder = $aoModel[$type . '_folder'] != '' ? $aoModel[$type . '_folder'] . '/' : '';
            $quantityFolder = $this->_getQuantityFolder($aoModel[$type . '_id']);
        }
        elseif(is_object($aoModel)) {
            $type = $aoModel->table();
            $logicalFolder = $aoModel->isField($type . '_folder') ? ($aoModel->{$type . '_folder'} != '' ? $aoModel->{$type . '_folder'} . '/' : '') : '';
            $quantityFolder = $this->_getQuantityFolder($aoModel->id());
        }
        
        return "./data/{$type}/" . $logicalFolder . $quantityFolder;
    }
    
    /**
     * store file
     * 
     * @param f_m $model
     * @param string $sSrcPath
     * @return boolean
     */
    public function storeFile(f_m $model, $sSrcPath)
    {
        $resource = file_get_contents($sSrcPath);
        if($resource) {
            $table = $model->table();
            if(!$model->{$table . '_id'}) {
                return false;
            }
            if(!$model->{$table . '_token'}) {
                $model->{$table . '_token'} = $this->token();
                $model->save();
            }
            if(!$model->{$table . '_ext'}) {
                $ext = pathinfo($sSrcPath, PATHINFO_EXTENSION);
                $model->{$table . '_ext'} = $ext ? $ext : '';
                $model->save();
            }
            
            $path = $this->getFilePath($model);
            if($this->_isFolders($path)) { 
                $filename = $path
                    . "{$model->{$table . '_id'}}_{$model->{$table . '_token'}}"
                    . $model->isField($table . '_ext') ? (!empty($model->{$table . '_ext'}) ? $model->{$table . '_ext'} : '') : '';

                if(file_put_contents($filename, $resource)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * store image file
     * 
     * @param f_m $model
     * @param f_image $img
     * @param int $quality
     * @return boolean
     */
    public function storeImg(f_m $model, f_image $img, $quality = 95)
    {
        $table = $model->table();
        if(!$model->{$table . '_id'}) {
            return false;
        }
        if(!$model->{$table . '_token'}) {
            $model->{$table . '_token'} = $this->token();
            $model->save();
        }
        if(!$model->{$table . '_ext'}) {
            $model->{$table . '_ext'} = $img->type();
            $model->save();
        }
        
        $path = $this->getFilePath($model);
        if($this->_isFolders($path)) {
            $ext = $model->isField($table . '_ext') ? (!empty($model->{$table . '_ext'}) ? $model->{$table . '_ext'} : 'jpg') : 'jpg';
            $imgname = $path
                . "{$model->{$table . '_id'}}_{$model->{$table . '_token'}} .{$ext}";

            $img->save($imgname, $quality);
            return true;
        }
        
        return false;
    }
    
    /**
     * call unlink-files function
     * 
     * @param array/object $aoModel
     */
    public function unlink($aoModel)
    {
        extract($this->_getData($aoModel));
        
        $this->unlinkRaw($id, $token, $folder, $ext, $type);
    }
    
    /**
     * unlink files
     * 
     * @param string $id - model id
     * @param string $token - file token
     * @param string $logicalFolder - logical folder name
     * @param string $ext - file extension
     * @param string $type - model table name
     */
    public function unlinkRaw($id, $token, $logicalFolder = null, $ext = 'jpg', $type = 'img')
    {
        $path = "./data/{$type}/"
            . ($logicalFolder ? $logicalFolder . '/' : '')
            . $this->_getQuantityFolder($id);
        
        // orginal file
        if (file_exists("{$path}{$id}_{$token}.{$ext}")) {
            unlink("{$path}{$id}_{$token}.{$ext}");
        }
        
        // change file
        foreach ($this->config->data[$type] as $i) {
            foreach($i as $k => $v) {
                $ext2 = $ext;
                if(is_array($v)) {
                    if($v['ext']) {
                        $ext2 = $v['ext'];
                    }
                    $v = $k;
                }
                
                if (file_exists("{$path}{$id}_{$token}_{$v}.{$ext2}")) {
                    unlink("{$path}{$id}_{$token}_{$v}.{$ext2}");
                }
            }
        }
    }
    
    /**
     * return uri to file
     * 
     * @param array/object $aoModel
     * @param string $size
     * @return string /data/{model}/{logicalFolder}/{quantityFolder}/{id}_{token}_{size}.{ext}
     */
    public function uri($aoModel, $size = null)
    {
        extract($this->_getData($aoModel));
        
        return substr($this->getFilePath($aoModel),1)
            . "{$id}_{$token}"
            . ($size ? '_' . $size : '')
            . ($ext ? '.' . $ext : '');
    }

    /**
     * calculate resize img size
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
		return array($iOldW, $iOldH);
            }
	}
        
	$w = $iNewW;
	$h = (int)($w * $iOldH / $iOldW);
        
	if ($h > $iNewH) {
            $h = $iNewH;
            $w = (int)($h * $iOldW / $iOldH);
        }        
        
        return array('w' => $w, 'h' => $h);
    }
    
    /**
     * calculate thumb img size
     * 
     * @param int $iOldW
     * @param int $iOldH
     * @param int $iNewW
     * @param int $iNewH
     * @return array
     */
    protected function _calculateThumb($iOldW, $iOldH, $iNewW, $iNewH)
    {
        $w = $iNewW;
	$h = (int)($w * $iOldH / $iOldW);
        
	if ($h <= $iNewH) {
            $h = $iNewH;
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
     * extract data
     * 
     * @param array/object $aoModel
     * @return array
     */
    protected function _getData($aoModel)
    {
        $result = array();
        
        if(is_array($aoModel)) {
            $type = reset(explode('_', key($aoModel)));
            $result = array(
                'type'   => $type,
                'id'     => $aoModel[$type . '_id'],
                'token'  => $aoModel[$type . '_token'],
                'folder' => $aoModel[$type . '_folder'] != '' ? $aoModel[$type . '_folder'] : null,
                'ext'    => $aoModel[$type . '_ext'] != '' ? $aoModel[$type . '_ext'] : 'jpg',
            );
        }
        elseif(is_object($aoModel)) {
            $table = $aoModel->table();
            $result = array(
                'type' => $table,
                'id' => $aoModel->id(),
                'token' => $aoModel->{$table . '_token'},
                'folder' => $aoModel->isField($table . '_folder') ? ($aoModel->{$table . '_folder'} != '' ? $aoModel->{$table . '_folder'} : null) : null,
                'ext' => $aoModel->isField($table . '_ext') ? ($aoModel->{$table . '_ext'} != '' ? $aoModel->{$table . '_ext'} : 'jpg') : 'jpg',
            );
        }

        return $result;
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
        
        if($this->_divideIntoQuantityFolders) {
            $folder = (floor((int)$id / $this->_maxIdInQuantityFolders) + 1) . '/';
        }
        
        return $folder;
    }

    /**
     * do exist folders from file path
     * if not create them
     * 
     * @param string $dirname
     * @return boolean
     */
    protected function _isFolders($dirname)
    {
        if(!file_exists($dirname)) {
            return mkdir($dirname, 0777, true);
        }
        
        return true;
    }
    
}