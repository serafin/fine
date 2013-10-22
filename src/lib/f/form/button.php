<?php

class f_form_button extends f_form_element
{

    protected $_type      = 'button';
    protected $_helper    = 'formButton';
    protected $_ignoreVal = true;

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_button
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}