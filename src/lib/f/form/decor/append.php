<?php

class f_form_decor_append extends f_form_decor_abstract
{

    public function  __construct($mConfig = null)
    {
        if ($mConfig !== null) {
            if (is_string($mConfig)) {
                $this->_option['html'] = $mConfig;
            }
            else {
                parent::__construct($mConfig);
            }
        }
    }

    public function render($sRender = '')
    {
        return $this->_option['html'] . $this->_option['separator'] . $sRender;
    }

}