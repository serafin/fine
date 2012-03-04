<?php

class f_valid_digit extends f_valid_abstract
{
    
    const STRING_EMPTY = 'STRING_EMPTY';
    const NOT_DIGIT    = 'NOT_DIGIT';
    
    protected $_msg = array(
        self::STRING_EMPTY => 'Wymagana wartość',
        self::NOT_DIGIT    => 'Wymagana wartość numeryczna (0-9, np. 123)',
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
        if (!ctype_digit($sValue)) {
            $this->_error(self::NOT_DIGIT);
            return false;
        }

        return true;
    }

}