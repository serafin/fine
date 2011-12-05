<?php

class f_valid_equal extends f_valid_abstract
{

	const NOT_EQUAL = 'notEqual';
	
	public $valueEqual;
	
	public function __construct($sValue, $aMsg = null)
	{
		$this->valueEqual = $sValue;
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

		if ($sValue != $this->valueEqual) {
			$this->_error(self::NOT_EQUAL);
			return false;
		}
		
		return true;
	}
	
}