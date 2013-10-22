<?php

class f_form_textarea extends f_form_element
{

    protected $_type   = 'textarea';
    protected $_helper = 'formTextarea';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_textarea
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}