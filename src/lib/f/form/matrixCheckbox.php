<?php

class f_form_matrixCheckbox extends f_form_element
{

    protected $_type   = 'checkbox';
    protected $_helper = 'formMatrixCheckbox';

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->_decorForm['error'] = 'f_form_decor_errorMatrix';
    }
    
    public function isValid($mValue = null)
    {

        if (func_num_args() === 1) {
            $this->val($mValue);
        }
        $value        = $this->_val;
        $this->_error = array();
        
        if ($this->_required === false && ($value === '' || $value === null)) {
            return true;
        }

        $bValid = true;
        
        foreach ($this->_option['row'] as $kr => $row){
            foreach ($this->_valid as $k => $valid) {

                // lazy load validotor
                if (!is_object($valid)) {
                    if (is_string($valid)) {
                        $this->_valid[$k] = new $valid;
                        $valid            = $this->_valid[$k];

                    }
                    else if (is_array($valid)) {
                        $class            = array_shift($valid);
                        $this->_valid[$k] = new $class($valid);
                        $valid            = $this->_valid[$k];
                    }
                }

                if (!$valid->isValid($value[$kr])) {
                    $bValid = false;
                    foreach ($valid->error() as $i) {
                        $this->_error[$kr][] = $i;
                    }
                    if ($this->_breakOnFail) {
                        break;
                    }
                }            
            }
        }
        
        return $bValid;
    }
}