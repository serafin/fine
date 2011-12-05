<?php

class f_valid_between extends f_valid_abstract 
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

   public function isValid($iValue)
    {
        $this->_val($iValue);
		
        if ($this->_min > $iValue || $iValue > $this->_max) {
            $this->_error(self::NOT_BETWEEN);
            return false;
        }
		
        return true;
    }
	
}