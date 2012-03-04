<?php

class f_valid_max extends f_valid_abstract
{
    
    const NOT_MAX = 'NOT_MAX';

    protected $_var = array(
        '{max}' => '_max'
    );
    protected $_max;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function max($fiMax = null)
    {
        if (func_num_args() == 0) {
            return $this->_max;
        }
        $this->_max = $fiMax;
        return $this;
    }
    
    public function isValid($mValue)
    {
        $iValue = (int) $mValue;
        $this->_val($iValue);

        if (!($iValue <= $this->_max)) {
            $this->_error(self::NOT_MAX);
            return false;
        }

        return true;
    }

}