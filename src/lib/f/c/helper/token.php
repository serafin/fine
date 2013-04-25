<?php

class f_c_helper_token
{

    const TYPE_MD5  = 'md5';
    const TYPE_SHA1 = 'sha1';

    protected $_type = self::TYPE_MD5;
    
    public function helper()
    {
        return $this->token();
    }
    
    public function token()
    {
        return $this->{$this->_type}();
    }
    
    public function type($sType = null)
    {
        if (func_num_args() == 0) {
            return $this->_type;
        }
        
        $this->_type = $sType;
        return $this;
    }
    
    public function md5()
    {
        return md5($this->_uniqid());
    }
    
    public function sha1()
    {
        return sha1($this->_uniqid());
    }
    
    protected function _uniqid()
    {
        return uniqid(rand(), true);
    }

}