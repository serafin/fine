<?php

class f_valid_between extends f_valid_abstract
{
    
    const NOT_BETWEEN = 'NOT_BETWEEN';
    
    protected $_msg = array(
        self::NOT_BETWEEN => 'Wymagana wartość pomiędzy {min} i {max}',
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
    
    public function min($fiMin = null)
    {
        if (func_num_args() == 0) {
            return $this->_min;
        }
        $this->_min = $fiMin;
        return $this;
    }

    public function max($fiMax = null)
    {
        if (func_num_args() == 0) {
            return $this->_max;
        }
        $this->_max = $fiMax;
        return $this;
    }


    public function isValid($iValue)
    {
        $this->_val($iValue);

        if ($this->_min > $iValue || $iValue > $this->_max) {
            $this->_error(self::NOT_BETWEEN);
            return false;
        }

        return true;
    }

}