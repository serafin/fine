<?php

class f_valid_alnum extends f_valid_abstract
{

	const STRING_EMPTY = 'stringEmpty';
	const NOT_ALNUM    = 'notAlnum';

    protected $_msg = array(
        self::STRING_EMPTY => "Wymagana wartość",
        self::NOT_ALNUM    => "Wymagane znaki alfabetyczne lub numeryczne (a-z, A-Z, 0-9, np. qweRTY123)",
    );

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

        if (!ctype_alnum($sValue)) {
            $this->_error(self::NOT_ALNUM);
            return false;
        }

        return true;
    }

}