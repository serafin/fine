<?php

class f_valid_lower extends f_valid_abstract
{
	
	const STRING_EMPTY = 'stringEmpty';
	const NOT_LOWER    = 'notLower';
	
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

        if (!ctype_lower($sValue)) {
            $this->_error(self::NOT_LOWER);
            return false;
        }

        return true;
    }
    
}