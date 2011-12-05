<?php

class f_valid_lengthMax extends f_valid_abstract
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
    	if (is_array($mValue)) {
			$iValueLength = count($mValue);
	        $this->_val($iValueLength);
    	}
    	else {
			$mValue = (string) $mValue;
	        $iValueLength = strlen($mValue);
	        $this->_val($mValue);
    	}

		if ($iValueLength > $this->max) {
            $this->_error(self::NOT_MAX);
            return false;
		}
		
        return true;
    }
    
}