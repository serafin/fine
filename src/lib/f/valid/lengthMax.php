<?php

class f_valid_lengthMax extends f_valid_abstract
{
    
    const NOT_MAX = 'NOT_MAX';

    protected $_msg = array(
        self::NOT_MAX => "Maksymalna długość/ilość: {max}",
    );
    protected $_var = array(
        '{max}' => '_max'
    );
    protected $_max;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function max($iMax = null)
    {
        if (func_num_args() == 0) {
            return $this->_max;
        }
        $this->_max = $iMax;
        return $this;
    }

    public function isValid($mValue)
    {
        if (is_array($mValue)) {
            $iValueLength = count($mValue);
            $this->_val($iValueLength);
        }
        else {
            $mValue = (string) $mValue;
            $iValueLength = strlen($mValue);
            $this->_val($mValue);
        }

        if ($iValueLength > $this->_max) {
            $this->_error(self::NOT_MAX);
            return false;
        }

        return true;
    }

}