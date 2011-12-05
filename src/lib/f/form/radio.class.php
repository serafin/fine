<?php

class f_form_radio extends f_form_abstract
{
	
	public $type = 'radio';
	
	protected $_separator = '<br />';
	protected $_option    = array();
	
	public function render()
	{
		$this->addClass('radio')->attr(array(
			'name' => $this->_name,
			'type' => 'radio'
		));
		$sValue   = $_POST ? $_POST[$this->_name] : $this->_value;
		$aElement = array();
		foreach ($this->_option as $k => $v) {
			($sValue == $k && $sValue !== null) ? $this->attr('checked','checked') : $this->removeAttr('checked');
			$this->attr('value', $k);
			$aElement[] = '<input '.$this->renderAttr().' /> '.$v;
		}
		return implode($this->_separator, $aElement);
	}
	
	public function option($aOptions = null)
	{
		if ($aOptions === null) {
			return $this->_option;
		}
		$this->_option = $aOptions;
		return $this;
	}
	
	public function separator($sSeparator = null)
	{
		if ($sSeparator === null) {
			return $this->_separator;
		}
		$this->_separator = $sSeparator;
		return $this;
	}

	protected function _requiredMsg()
	{
		return f_lang::_()->f_form_radio->required;
	}
	
}