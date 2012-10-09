<?php

class f_valid_regExp extends f_valid_abstract
{

    const NOT_MATCH = 'NOT_MATCH';

    protected $_msg =  array(
        self::NOT_MATCH => "Wartość '{val}' musi pasowac do wzorca '{pattern}' ",
    );
    protected $_var = array(
        '{pattern}' => '_pattern'
    );
    protected $_pattern;
    
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function pattern($sPattern = null)
    {
        if (func_num_args() == 0) {
            return $this->_pattern;
        }
        $this->_pattern = $sPattern;
        return $this;
    }

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);

        if (!@preg_match($this->_pattern, $sValue)) {
            $this->_error(self::NOT_MATCH);
            return false;
        }

        return true;
    }
    
}