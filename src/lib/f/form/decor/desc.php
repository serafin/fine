<?php

class f_form_decor_desc extends f_form_decor_tag
{

    public function render()
    {
        if ($this->_tag !== null) {
            $this->_prepateTag();
            $this->_decoration .= $this->object->desc();
        }
        else {
            $this->_decoration  = $this->_prepend . $this->object->desc();
            $this->_decoration2 = $this->_append;
        }


        return $this->_render();
    }

}