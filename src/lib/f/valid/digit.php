<?php

class f_valid_digit extends f_valid_abstract
{

	const STRING_EMPTY = 'stringEmpty';
	const NOT_DIGIT    = 'notDigit';
	
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
        if (!ctype_digit($sValue)){
            $this->_error(self::NOT_DIGIT);
            return false;
        }
		
        return true;
    }
    
}