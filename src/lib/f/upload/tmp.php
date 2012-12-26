<?php

class f_upload_tmp
{
    
    const DIR = './data/tmp/';
    
    protected $_path;
    protected $_upload;


    public function create()
    {
        
        
    }
    
    public function path()
    {
        
    }
    
    public function option()
    {
    }
    
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
    
    public function destroyAll($iSeconds = 604800) 
    {
        
    }
    
    public function extension()
    {
        
    }
    
    public function extensionLower()
    {
        
    }
    
    
    
}
