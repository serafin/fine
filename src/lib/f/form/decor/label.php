<?php

class f_form_decor_label extends f_form_decor_abstract
{

    protected $_placement = self::EMBRACE;

    public function render()
    {
        $id    = $this->element->id();
        $label = $this->element->label();
        
        $this->decoration  =  "<label" . ( strlen($id) ? ' for="$id"': '') . ">$label";
        $this->decoration2 = "</label>";

        return $this->_render();
        
    }

}