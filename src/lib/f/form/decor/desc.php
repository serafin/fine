<?php

class f_form_decor_desc extends f_form_decor_abstract
{

    protected $_placement = self::APPEND;

    public function render()
    {
        $this->decoration = '<span class="form_decor_desc">' . $this->element->desc() . '</span>';

        return $this->_render();
    }

}