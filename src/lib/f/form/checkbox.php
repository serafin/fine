<?php

class f_form_checkbox extends f_form_element
{

    protected $_type       = 'checkbox';
    protected $_viewHelper = 'formCheckbox';
    protected $_attr       = array('class' => 'checkbox');
    
    protected $_decorForm     = array(
        'viewHelper' => 'f_form_decor_viewHelper',
        'label'      => array('f_form_decor_label', 'placement' => 'EMBRACE'),
        'error'      => 'f_form_decor_error',
        'desc'       => 'f_form_decor_desc',
        'tag'        => array('f_form_decor_tag', 'attr' => 'class="form_element"'),
    );
    
    public function decorDefault()
    {
        if ($this->_form !== null && !$this->_isArray) {
            $this->decor = $this->_decorForm;
        }
        else {
            parent::decorDefault();
        }
    }

}