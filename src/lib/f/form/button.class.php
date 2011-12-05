<?php

class f_form_button extends f_form_abstract
{
	
	public $type = 'button';

	protected $_name  = '_button';
	protected $_label = '&nbsp;';
    protected $_view  = 'f_v_helper_formElementSubmit';
	
	public function render()
	{
		$this->addClass('button')->attr(array(
			'type'  => 'button',
			'id'    => $this->_name,
			'name'  => $this->_name,
			'value' => $this->_value
		));
		return '<input'.$this->renderAttr().' />';
	}
	
}