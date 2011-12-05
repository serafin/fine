<?php

class f_valid_notEmpty extends f_valid_abstract
{
	
	const STRING_EMPTY = 'stringEmpty';
	
	public function _()
	{
		return new self;
	}

	public function isValid($mValue)
	{
		$sValue = (string) $mValue;
		$this->_val($sValue);
		
		if (strlen($sValue) == 0) {
			$this->_error(self::STRING_EMPTY);
			return false;
		}
		
		return true;
	}
	
}