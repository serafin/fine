<?php

class f_valid_xdigit extends f_valid_abstract
{
    
    const STRING_EMPTY = 'STRING_EMPTY';
    const NOT_XDIGIT   = 'NOT_XDIGIT';

    protected $_msg = array(
        slef::STRING_EMPTY => 'Wymagana wartość',
        self::NOT_XDIGIT   => 'Wymagana wartość heksadecymalna (0-9, a-f, A-F np. 00ff00)',
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

        if (!ctype_xdigit($sValue)) {
            $this->_error(self::NOT_XDIGIT);
            return false;
        }

        return true;
    }

}