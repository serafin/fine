<?php

class f_form_hidden extends f_form_abstract
{
	
	public $type = 'hidden';
	
	public function render()
	{
		$this->attr(array(
			'id'    => $this->_name,
			'name'  => $this->_name,
			'type'  => 'hidden',
			'value' => $_POST ? htmlspecialchars($_POST[$this->_name]) : $this->_value
		));
		return '<input'.$this->renderAttr().' />';
	}
	
}