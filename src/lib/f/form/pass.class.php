<?php

class f_form_pass extends f_form_abstract
{
	
	public $type = 'pass';
	
	protected $_usePostValue = false;
	
	public function render()
	{
		$this->addClass('password')->attr(array(
			'id'    => $this->_name,
			'name'  => $this->_name,
			'type'  => 'password',
			'value' => $this->_usePostValue ? ( $_POST ? htmlspecialchars( $_POST[$this->_name] ) : $this->_value) : ''
		));
		return '<input'.$this->renderAttr().' />';
	}
	
	public function usePostValue($bUsePostValue = true)
	{
		$this->_usePostValue = (boolean) $bUsePostValue;
	}
	
}