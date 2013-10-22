<?php

class f_form_password extends f_form_element
{

    protected $_type   = 'password';
    protected $_helper = 'formPassword';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_password
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}