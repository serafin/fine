<?php

class f_form_submit extends f_form_abstract
{
	
	public $type = 'submit';
	
	protected $_name  = '_submit';
	protected $_label = '&nbsp;';
    protected $_view  = 'f_v_helper_formElementSubmit';
	
	public function render()
	{
		$this->addClass('submit')->attr(array(
			'id'    => $this->_name,
			'name'  => $this->_name,
			'type'  => 'submit',
			'value' => $this->_value !== null ? $this->_value : $this->_label
		));
		return '<input'.$this->renderAttr().' />';
	}
	
}