<?php

class f_valid_email extends f_valid_abstract
{
	
	const STRING_EMPTY = 'stringEmpty';
	const NOT_EMAIL    = 'notEmail';
	
	public function _()
	{
		return new self;
	}

	public function isValid($mValue)
	{
		$sValue = (string) $mValue;
		$this->_val($sValue);

		if ('' === $sValue) {
			$this->_error(self::STRING_EMPTY);
			return false;
		}

		if (!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/', $sValue)) {
			$this->_error(self::NOT_EMAIL);
			return false;
		}
		
		return true;
	}
	
}