<?php

class f_valid_fieldEqual extends f_valid_abstract
{
	
	const NOT_EQUAL = 'notEqual';
	
    public $name;
    public $desc;
	
    protected $_var = array('name', 'desc');

    public function __construct($sName, $sDesc, $sMsgNotEqual = null)
    {
        $this->name = $sName;
        $this->desc = $sDesc;
		parent::__construct($aMsg);
    }
    
	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);
        
        if ($sValue != $_POST[$this->name]) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }
		
        return true;
    }
    
}