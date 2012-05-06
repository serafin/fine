<?php

class f_form_checkbox extends f_form_element
{

    protected $_type      = 'checkbox';
    protected $_helper    = 'formCheckbox';
    protected $_attr      = array('class' => 'form-checkbox');
    protected $_decorForm = array(
        'helper' => 'f_form_decor_helper',
        'label'  => array('f_form_decor_label', 'placement' => 'PLACEMENT_EMBRACE'),
        'error'  => 'f_form_decor_error',
        'desc'   => 'f_form_decor_desc',
        'tag'    => array('f_form_decor_tag', 'attr' => array('class' => 'form-element')),
    );

    public function decorDefault()
    {
        if ($this->_form !== null && !$this->_isArray) {
            $this->_decor = $this->_decorForm;
        }
        else {
            parent::decorDefault();
        }
    }

}