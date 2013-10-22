<?php

class f_form_hidden extends f_form_element
{

    protected $_type   = 'hidden';
    protected $_helper = 'formHidden';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_hidden
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    protected $_decorForm  = array(
        'helper' => 'f_form_decor_helper',
    );
    
}