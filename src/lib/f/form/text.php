<?php

class f_form_text extends f_form_element
{

    protected $_type   = 'text';
    protected $_helper = 'formText';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_text
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}