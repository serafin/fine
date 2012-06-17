<?php

class f_valid_lengthBetween extends f_valid_abstract
{
    
    const NOT_BETWEEN = 'NOT_BETWEEN';

    protected $_msg = array(
        self::NOT_BETWEEN => "Wymagana długość/ilość pomiędzy '{min}' i '{max}'",
    );
    protected $_var = array(
        '{min}' => '_min',
        '{max}' => '_max'
    );
    protected $_min;
    protected $_max;

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
            $this->_val($iValueCount);
        }
        else {
            $mValue = (string) $mValue;
            $iValueLength = strlen($mValue);
            $this->_val($mValue);
        }

        if ($this->_min > $iValueLength || $iValueLength > $this->_max) {
            $this->_error(self::NOT_BETWEEN);
            return false;
        }

        return true;
    }

}