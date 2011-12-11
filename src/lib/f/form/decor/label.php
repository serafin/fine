<?php

class f_form_decor_label extends f_form_decor_abstract
{

    protected $_placement = self::PREPEND;

    public function render()
    {
        $id    = $this->element->id();
        $label = $this->element->label();
        
        $this->decoration  = strlen($id) ? "<label for=\"$id\">" : "<label>";
        $this->decoration2 = "$label</label>";

        return $this->_render();
        
    }

}