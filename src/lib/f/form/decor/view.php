<?php

class f_form_decor_view extends f_form_decor_abstract
{

    public function  __construct($mConfig = null)
    {
        if ($mConfig !== null) {
            if (is_string($mConfig)) {
                $this->_option['view'] = $mConfig;
            }
            else {
                parent::__construct($mConfig);
            }
        }
    }

    public function render($sRender = '')
    {
        ob_start();
        f::$c->v->render($this->_option['view']);
        $sDecor = ob_get_clean();

        return $this->_render($sRender, $sDecor);
    }

}