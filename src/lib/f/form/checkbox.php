<?php

class f_form_checkbox extends f_form_element
{

    protected $_type       = 'checkbox';
    protected $_viewHelper = 'formCheckbox';
    protected $_attr       = array('class' => 'checkbox');

    public function decorDefaultInit()
    {
        if (! $this->_isArray) {
            $this->decor = self::$_configStatic['checkboxOne_decore'];
        }
        else {
            parent::decorDefaultInit();
        }
    }

}