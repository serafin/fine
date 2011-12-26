<?php

class f_form_hidden extends f_form_element
{

    protected $_type       = 'hidden';
    protected $_viewHelper = 'formHidden';

    protected $_decorForm  = array(
        'viewHelper' => 'f_form_decor_viewHelper',
    );
    
}