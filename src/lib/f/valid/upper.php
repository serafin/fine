<?php

class f_valid_upper extends f_valid_abstract
{

	const STRING_EMPTY = 'stringEmpty';
	const NOT_UPPER    = 'notUpper';

    /** @todo */
    protected $_msg = array(
        self::STRING_EMPTY => "Dieses Feld darf nicht leer sein",
        self::NOT_UPPER    => "Dieses Feld darf nur Großbuchstaben enthalten (A-Z)",
    );

	public static function _()
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

        if (!ctype_upper($sValue)) {
            $this->_error(self::NOT_UPPER);
            return false;
        }

        return true;
    }
    
}