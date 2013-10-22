<?php

class f_form_radio extends f_form_element
{

    protected $_type   = 'radio';
    protected $_helper = 'formRadio';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_radio
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}