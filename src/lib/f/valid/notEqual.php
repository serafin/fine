<?php

class f_valid_notEqual extends f_valid_abstract
{
	
	const EQUAL = 'equal';

    protected $_msg = array(
        self::EQUAL => "Wartość musi być różna od '{val}'",
    );
    /** @todo */
    protected $_var;
	protected $_valueEqual;
	
	public function __construct($sValue, $sMsgEqual = null)
	{
		$this->valueEqual = $sValue;
		if ($sMsgEqual !== null) {
			$this->msg(self::EQUAL, $sMsgEqual);
		}
	}
	
	public function _()
	{
		return new self;
	}

	public function isValid($mValue) 
	{
		$sValue = (string) $mValue;
		$this->_val($sValue);
		if ($sValue == $this->valueEqual) {
			$this->_error(self::EQUAL);
			return false;
		}
		return true;
	}
	
}