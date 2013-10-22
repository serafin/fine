<?php

class f_form_select extends f_form_element
{

    protected $_type   = 'select';
    protected $_helper = 'formSelect';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_select
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}