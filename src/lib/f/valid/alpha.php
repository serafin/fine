<?php

class f_valid_alpha extends f_valid_abstract
{

    const STRING_EMPTY = 'stringEmpty';
    const NOT_ALPHA    = 'notAlpha';

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

        if (!ctype_alpha($sValue)) {
            $this->_error(self::NOT_ALPHA);
            return false;
        }

        return true;
    }
	
}