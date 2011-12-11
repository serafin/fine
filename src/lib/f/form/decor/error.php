<?php

class f_form_decor_error extends f_form_decor_abstract
{

    /**
     * Additional elements - to show errors from other elements
     * @var array
     */
    protected $_element = array();
    
    /**
     * Ignore owner element?
     * @var boolean
     */
    protected $_ignoreOwner = false;

    public function element($aoAdditionalElement = null)
    {
        if (func_num_args() === 0) {
            return $this->_element;
        }
        if (! is_array($aoAdditionalElement)) {
            $aoAdditionalElement = array($aoAdditionalElement);
        }
        $this->_element = $aoAdditionalElement;
        return $this;
    }

    public function ignoreOwner($bIgnore = null)
    {
        if ($bIgnore === null) {
            return $this->_ignoreOwner;
        }
        $this->_ignoreOwner = (boolean)$bIgnore;
        return $this;
    }


    public function render()
    {
        $errors = array();
        if ($this->_ignoreOwner === false) {
            $errors = $this->element->error();
        }
        foreach ((array)$this->_element as $i) {
            $errors += $i->error();
        }

        if ($errors) {
            $this->decoration .= '<div class="form_decor_error"><ul>';
            foreach ($errors as $error) {
                $this->decoration .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $this->decoration .= '</ul></div>';
        }

        return $this->_render();

    }

}