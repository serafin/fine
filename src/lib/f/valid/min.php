<?php

class f_valid_min extends f_valid_abstract
{

	const NOT_MIN = 'notMin';
	
    public $min;

    protected $_var = array('min');

	public function __construct($iMin, $sMsgNotMin = null)
    {
        $this->min = $iMin;
        if ($sMsgNotMin !== null) {
        	$this->msg(self::NOT_MIN, $sMsgNotMin);
        }
    }
    
	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
    	$iValue = (int) $mValue;
        $this->_val($iValue);
		
        if (!($iValue >= $this->min)) {
            $this->_error(self::NOT_MIN);
            return false;
        }
		
        return true;
    }
    
}