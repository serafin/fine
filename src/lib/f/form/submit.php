<?php

class f_form_submit extends f_form_element
{

    protected $_type      = 'submit';
    protected $_helper    = 'formSubmit';
    protected $_attr      = array('class' => 'form-submit');
    protected $_ignoreVal = true;
    
    protected $_decorForm  = array(
        'helper' => 'f_form_decor_helper',
        'tag'    => 'f_form_decor_tag',
    );

}