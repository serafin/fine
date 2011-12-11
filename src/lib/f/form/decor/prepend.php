<?php

class f_form_decor_append extends f_form_decor_abstract
{

    protected $_placement = self::PREPEND;
    protected $_content;

    public function content($sContent = null)
    {
        if ($sContent === null) {
            return $this->_content;
        }
        $this->_content = $sContent;
        return $this;
    }


    public function render()
    {
        $this->decoration = $this->_content;
        return $this->_render();
    }

}
