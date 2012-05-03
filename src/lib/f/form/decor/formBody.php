<?php

class f_form_decor_formBody extends f_form_decor_abstract
{

    protected $_separator;

    public function separator($sSeparator = null)
    {
        if (func_num_args() == 0) {
            return $this->_separator;
        }
        $this->_separator = $sSeparator;
        return $this;
    }


    public function render()
    {
        $decoration = array();
        foreach ($this->object->_ as $element) {
            /* @var $element f_form_element */
            if ($element->ignoreRender()) {
                continue;
            }
            $decoration[] = $element->render();
        }

        $this->_decoration = implode($this->_separator, $decoration);
        
        return $this->_render();
    }

}