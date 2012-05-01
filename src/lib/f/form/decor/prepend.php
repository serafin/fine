<?php

class f_form_decor_append extends f_form_decor_abstract
{

    protected $_placement = self::PLACEMENT_PREPEND;
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
        $this->_decoration = $this->_content;
        return $this->_render();
    }

}
