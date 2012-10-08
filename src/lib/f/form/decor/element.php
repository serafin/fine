<?php

class f_form_decor_element extends f_form_decor_default
{

    protected $_element;

    /**
     * Ustawia/pobiera elementy
     *
     * @param array|f_form_element $oaElement Element lub tablica elementow
     * @return array|f_form_decor_element
     */
    public function element($oaElement = null)
    {
        if (func_num_args() == 0) {
            return $this->_element;
        }

        if (!is_array($oaElement)) {
            $oaElement = array($oaElement);
        }

        $this->_element = $oaElement;
        
        return $this;
    }

    public function render()
    {
        $this->_decoration = "";
        foreach ((array)$this->_element as $element) {
            /* @var $element f_form_element */
            $this->_decoration .= $element->render();
        }
        return $this->_render();
    }

}