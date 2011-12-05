<?php

class f_form_textarea extends f_form_abstract
{
	
	public $type = 'textarea';
	
	public function render()
	{
		$this->attr(array(
			'name' => $this->_name,
			'id'   => $this->_name,
		));
		return '<textarea'.$this->renderAttr().'>'.($_POST ? htmlspecialchars($_POST[$this->_name]) : $this->_value).'</textarea>';
	}
	
}