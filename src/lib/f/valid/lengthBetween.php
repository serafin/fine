<?php

class f_valid_lengthBetween extends f_valid_abstract
{

	const NOT_BETWEEN = 'notBetween';
		
    public $min;
    public $max;

    protected $_var = array('min', 'max');

    public function __construct($iMin, $iMax, $aMsg = null)
    {
        $this->min = $iMin;
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
		    $this->_val($iValueCount);
    	}
    	else {
    		$mValue = (string) $mValue;
	        $iValueLength = strlen($mValue);
	        $this->_val($mValue);
    	}
		
	    if ($this->min > $iValueLength || $iValueLength > $this->max) {
	    	$this->_error(self::NOT_BETWEEN);
	        return false;
	    }
		
        return true;
    }
    
}