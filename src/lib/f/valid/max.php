<?php

class f_valid_max extends f_valid_abstract
{
	
	const NOT_MAX = 'notMax';
	
    public $max;

    protected $_var = array('max');

	public function __construct($iMax, $aMsg = null)
    {
        $this->max = $iMax;
		parent::__construct($aMsg);
    }

	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
    	$iValue = (int) $mValue;
        $this->_val($iValue);
		
        if (!($iValue <= $this->max)) {
            $this->_error(self::NOT_MAX);
            return false;
        }
		
        return true;
    }
    
}