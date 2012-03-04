<?php

class f_valid_upper extends f_valid_abstract
{
    
    const STRING_EMPTY = 'STRING_EMPTY';
    const NOT_UPPER    = 'NOT_UPPER';

    protected $_msg = array(
        self::STRING_EMPTY => 'Wymagana wartość',
        self::NOT_UPPER    => 'Wymagane duże znaki alfabetyczne (A-Z, np. ABNM)',
    );

    public static function _(array $config = array())
    {
        return new self($config);
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