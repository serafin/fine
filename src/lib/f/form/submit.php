<?php

class f_form_submit extends f_form_element
{

    protected $_type       = 'submit';
    protected $_viewHelper = 'formSubmit';
    protected $_attr       = array('class' => 'submit');
    protected $_ignoreVal  = true;
    
    protected $_decorForm  = array(
        'viewHelper' => 'f_form_decor_viewHelper',
        'tag'        => array('f_form_decor_tag', 'attr' => 'class="form_element"'),
    );

}