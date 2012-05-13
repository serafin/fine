<?php

class f_valid_lengthMin extends f_valid_abstract
{
    
    const NOT_MIN = 'NOT_MIN';

    protected $_msg = array(
        self::NOT_MIN => "Minimalna długość/ilość: {min}",
    );
    protected $_var = array(
        '{min}' => '_min'
    );
    protected $_min;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function min($iMin = null)
    {
        if (func_num_args() == 0) {
            return $this->_min;
        }
        $this->_min = $iMin;
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

        if ($iValueLength < $this->_min) {
            $this->_error(self::NOT_MIN);
            return false;
        }

        return true;
    }

}