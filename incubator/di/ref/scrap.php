<?php

class f_di_ref_scrap implements f_di_ref_interface
{

    protected $_scrap;
    protected $_key;
    protected $_service;

    public static function _($sScrap, $sKey = null, $sService = null)
    {
        return new self($sScrap, $sKey, $sService);
    }

    public function __construct($sScrap, $sKey = null, $sService = null)
    {
        $this->_scrap   = $sScrap;
        $this->_key     = $sKey;
        $this->_service = $sService;
    }

    public function scrap()
    {
        return $this->_scrap;
    }

    public function key()
    {
        return $this->_key;
    }

    public function serivce()
    {
        return $this->_serivce;
    }

}
