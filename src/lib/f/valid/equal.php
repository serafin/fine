<?php

class f_valid_equal extends f_valid_abstract
{
    
    const NOT_EQUAL = 'NOT_EQUAL';

    protected $_msg = array(
        self::NOT_EQUAL => "Błędna wartość",
    );
    protected $_var = array(
        '{equal}' => '_equal'
    );
    protected $_equal;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function equal($mEqualValue = null)
    {
        if (func_num_args() == 0) {
            return $this->_equal;
        }
        $this->_equal = $mEqualValue;
        return $this;
    }

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);

        if ($sValue != $this->_equal) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }

        return true;
    }

}