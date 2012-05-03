<?php

class f_form_decor_form extends f_form_decor_tag
{

    protected $_name = 'form';

    public function render()
    {
        $this->_attr = $this->object->attr() + $this->_attr;

        $this->_prepateTag();

        return $this->_render();
    }

}
