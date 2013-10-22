<?php

class f_form_submit extends f_form_element
{

    protected $_type      = 'submit';
    protected $_helper    = 'formSubmit';
    protected $_ignoreVal = true;
    
    protected $_decorForm  = array(
        'helper' => 'f_form_decor_helper',
        'tag'    => array('f_form_decor_tag', 'attr' => array('class' => 'form-element')),
    );
    
    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_submit
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
}