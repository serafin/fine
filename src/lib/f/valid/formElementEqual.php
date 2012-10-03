<?php

class f_valid_formElementEqual extends f_valid_abstract
{
    
    const NOT_EQUAL = 'NOT_EQUAL';

    protected $_msg = array(
        self::NOT_EQUAL => 'Wymagana wartość identyczna wartości pola \'{label}\'',
    );
    protected $_var = array(
        '{label}' => '_label',
        '{desc}'  => '_desc',
        '{name}'  => '_name',
    );
    /** @var f_form_element */
    protected $_element;
    protected $_label;
    protected $_desc;
    protected $_name;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function element($oElement = null)
    {
        if (func_num_args() == 0) {
            return $this->_element;
        }
        $this->_element = $oElement;
        return $this;
    }
    
    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);
        
        $this->_label = $this->_element->label();
        $this->_desc  = $this->_element->desc();
        $this->_name  = $this->_element->name();

        if ($sValue != $this->_element->val()) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }

        return true;
    }

}