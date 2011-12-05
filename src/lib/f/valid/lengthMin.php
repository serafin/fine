<?php

class f_valid_lengthMin extends f_valid_abstract
{
	
	const NOT_MIN = 'notMin';
	
    public $min;

    protected $_var = array('min');

    public function __construct($iMin, $aMsg = null)
    {
        $this->min = $iMin;
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
		
        if ($iValueLength < $this->min) {
            $this->_error(self::NOT_MIN);
            return false;
        }
		
        return true;
    }
    
}