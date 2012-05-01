<?php

class f_valid_fileExt extends f_valid_abstract
{
    
    const VALUE_EMPTY = 'VALUE_EMPTY';
    const NOT_VALID   = 'NOT_VALID';

    protected $_msg = array(
        self::VALUE_EMPTY => 'Wymagana wartość',
        self::NOT_VALID   => 'Podany wartość (\'{val}\') nie jest prawidłowym adresem e-mail',
    );
    protected $_var = array(
        '{ext}' => '_extMsgFormat'
    );
    protected $_ext;
    protected $_extMsgFormat;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function ext($asExt = null)
    {
        // getter
        if (func_num_args() == 0) {
            return $this->_ext;
        }
        
        // setter
        if (!is_array($asExt)) {
            $asExt = explode(' ', $asExt);
        }
        $this->_ext = $asExt;
        
        // ext for message 
        if (!$this->_ext) {
            $this->_extMsgFormat = '';
        }
        else {
            $this->_extMsgFormat = "'" . implode("', '", $this->_ext) . "'";
        }
        
        return $this;
    }

    public function isValid($mValue)
    {
        /** @todo */
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