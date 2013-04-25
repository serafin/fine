<?php

class f_upload_tmp
{
    
    const DIR = 'data/tmp/';
    
    protected $_token;
    protected $_name;
    protected $_upload;

    /**
     * Static constructor
     *
     * @return f_upload_tmp
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    /**
     * Constructor
     */
    function __construct(array $config = array()) 
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * Set/get upload
     * 
     * @param f_upload $oUpload
     * @return f_upload_tmp|f_upload
     */
    public function upload($oUpload = null)
    {
        if (func_num_args() == 0) {
            if ($this->_upload == null) {
                $this->_upload = new f_upload();
            }
            return $this->_upload;
        }
        
        $this->_upload = $oUpload;
        return $this;
    }
    
    /**
     * Set/get path
     * 
     * @param string $param
     * @return f_upload_tmp|string 
     */
    public function path($sPath = null) 
    {
        if (func_num_args() == 0) {
            return $this->_path();
        }
        
        $this->_token = '';
        $this->_name  = '';
        
        list($this->_token, , $this->_name) = explode('_', substr($sPath, strlen(self::DIR)), 3);
        
        return $this;
    }
    
    /**
     * Get path witch option
     * 
     * @param string $sOption
     * @return string
     */
    public function option($sOption)
    {
        return $this->_path($sOption);
    }
    
    public function extension()
    {
       return end(explode('.', $this->_name));
    }
    
    public function extensionLower()
    {
        return strtolower($this->extension());
    }
    
    public function create()
    {
        $upload = $this->upload();
        
        if (!$upload->is()) {
            return $this;
        }
        
        $this->_token = f::$c->token();
        $this->_name  = $upload->name();
        
        $upload->move($this->_path());
        
        return $this;
    }
    
    public function save($sNewPath) 
    {
        copy($this->_path(), $sNewPath);
        return $this;
    }
    
    public function destroy()
    {
        foreach (new DirectoryIterator(self::DIR) as /* @var $file SplFileInfo */ $file) {
            $len = strlen($this->_token . '_');
            if (strncmp($this->_token . '_', $file->getFilename(), $len) != 0) {
                continue;
            }
            unlink($file->getPathname());
        }
    }
    
    public function destroyAll($iOlderThan = 604800) 
    {
        $now = time();
        
        foreach (new DirectoryIterator(self::DIR) as /* @var $file SplFileInfo */ $file) {
            
            if ($file->isDot()) {
                continue;
            }
            
            if ($file->getMTime() >= $now - $iOlderThan) {
                continue;
            }
            
            unlink($file->getPathname());
        }
    }
    
    protected function _path($sOption = '')
    {
        return self::DIR . $this->_token . '_' . $sOption . '_' . $this->_name;
    }
    
}
