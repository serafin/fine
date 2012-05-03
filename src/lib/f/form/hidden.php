<?php

class f_form_hidden extends f_form_element
{

    protected $_type   = 'hidden';
    protected $_helper = 'formHidden';

    protected $_decorForm  = array(
        'helper' => 'f_form_decor_helper',
    );
    
}