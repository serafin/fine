<?php

class f_valid_email extends f_valid_abstract
{
    
    const STRING_EMPTY = 'STRING_EMPTY';
    const NOT_EMAIL = 'NOT_EMAIL';

    protected $_msg = array(
        self::STRING_EMPTY => 'Wymagana wartość',
        self::NOT_EMAIL => 'Podany wartość (\'{val}\') nie jest prawidłowym adresem e-mail',
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

        if (!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/', $sValue)) {
            $this->_error(self::NOT_EMAIL);
            return false;
        }

        return true;
    }

}