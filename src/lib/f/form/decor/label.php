<?php

class f_form_decor_label extends f_form_decor_tag
{

    protected $_placement = self::PLACEMENT_EMBRACE;
    protected $_name      = 'label';


    public function render()
    {
        if ($this->_tag !== null) {
            $id = $this->object->id();
            if (strlen($id) > 0) {
                $this->_attr['for'] = $id;
            }
            $this->_prepateTag();
            $this->_decoration .=  $this->object->label();
        }
        else {
            $this->_decoration  = $this->_prepend . $this->object->label();
            $this->_decoration2 = $this->_append;
        }

        return $this->_render();
    }

}