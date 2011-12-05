<?php

class f_form_checkbox extends f_form_abstract 
{

	public $type = 'checkbox';

	private $_separator = '<br />';
	private $_option    = array();
	
	public function render()
	{
		$this->addClass('checkbox')->attr(array(
			'id'   => $this->_name,
			'type' => 'checkbox'
		));
			
		if ($this->_multi) {
			$this->attr('name', $this->_name.'[]');
			$aSelected = array();
			if ($_POST) {
				if (isset($_POST[$this->_name]) && $_POST[$this->_name]) {
					foreach ($_POST[$this->_name] as $i) {
						$aSelected[$i] = 1;
					}
				}
			}
			else if (is_array($this->_value)) {
				foreach ($this->_value as $i) {
					$aSelected[$i] = 1;
				}
			}
			$aElement = array();
			foreach ($this->_option as $k => $v) {
				isset($aSelected[$k]) ? $this->attr('checked','checked') : $this->removeAttr('checked');
				$this->attr('value', $k);
				$aElement[] = "<label><input".$this->renderAttr()." /> $v</label>";
			}
			return implode($this->_separator, $aElement);
		}
		else {
			$this->attr('name', $this->_name);
			if (is_string($this->_value)) {
				$this->attr('value', $this->_value);
			}
			if (
				(
					isset($_POST[$this->_name])
					&& (
						($this->_value === null && $_POST[$this->_name] == 'on')
						|| ($_POST[$this->_name] == $this->_value && $this->_value !== null)
					)
				)
				|| (!$_POST && $this->_value)
			) {
				$this->attr('checked', 'checked');
			}
			return '<input'.$this->renderAttr().' />';
		}
	}
	
	public function option($aOptions = null)
	{
		if($aOptions === null){
			return $this->_option;
		}
		$this->_option = $aOptions;
		$this->_multi  = (boolean) $aOptions;
		return $this;
	}
	
	public function separator($sSeparator = null)
	{
		if($sSeparator === null){
			return $this->_separator;
		}
		$this->_separator = $sSeparator;
		return $this;
	}

	protected function _requiredMsg()
	{
		return $this->_multi ? f_lang::_()->f_form_checkbox->requiredMulti : f_lang::_()->f_form_checkbox->required;
	}
	
}