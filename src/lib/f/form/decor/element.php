<?php

class f_form_decor_element extends f_form_decor_default
{

    protected $_element;

    public function element($oElement = null)
    {
        if (func_num_args() == 0) {
            return $this->_element;
        }
        $this->_element = $oElement;
        return $this;
    }

    public function render()
    {
        $this->_decoration = $this->_element->render();
        return $this->_render();
    }

}