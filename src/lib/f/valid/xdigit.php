<?php

class f_valid_xdigit extends f_valid_abstract
{

	const STRING_EMPTY = 'stringEmpty';
	const NOT_XDIGIT   = 'notXdigit';

    /** @todo tresci w jedzyku pl */
    protected $_msg = array(
        f_valid_xdigit::STRING_EMPTY => "Dieses Feld darf nicht leer sein?",
        f_valid_xdigit::NOT_XDIGIT   => "Dieses Feld darf nur Hexadezimale zahlen enthalten (0-9, a-f, A-F)",
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

        if (!ctype_xdigit($sValue)) {
            $this->_error(self::NOT_XDIGIT);
            return false;
        }
		
        return true;
    }
    
}