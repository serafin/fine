<?php

class f_form_file extends f_form_element
{

    protected $_type   = 'file';
    protected $_helper = 'formFile';
    protected $_attr   = array('class' => 'form-file');
    
    public function form($oForm)
    {
        $oForm->attr('enctype', f_form::ENCTYPE_MULTIPART);
        parent::form($oForm);
    }

}