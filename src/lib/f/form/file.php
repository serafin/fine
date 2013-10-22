<?php

class f_form_file extends f_form_element
{

    protected $_type   = 'file';
    protected $_helper = 'formFile';
    
    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return f_form_file
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function form($oForm)
    {
        parent::form($oForm);
        $oForm->attr('enctype', f_form::ENCTYPE_MULTIPART);
    }

}