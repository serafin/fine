<?php

class f_form_file extends f_form_element
{

    protected $_type   = 'file';
    protected $_helper = 'formFile';
    
    public function form($oForm)
    {
        parent::form($oForm);
        $oForm->attr('enctype', f_form::ENCTYPE_MULTIPART);
    }

}