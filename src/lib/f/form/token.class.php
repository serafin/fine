<?php

class f_form_token extends f_form_abstract
{
	
	public $type = 'token';
	
	protected $_name      = '_token';
	protected $_separator = '<br />';
	protected $_uri       = 'token/token.jpg';
	
	public function __construct($sName = null, $sLabel = null, $aSetting = null)
	{
 		parent::__construct($sName, $sLabel, $aSetting);
		$this->required()->valid(new f_valid_equal($_SESSION['_token'], f_lang::_()->f_form_token->wrongCode));
 		unset($_SESSION['_token']);
		if ($this->_label === null) {
			$this->_label = f_lang::_()->f_form_token->label;
		}
	}
	
	public function render()
	{
		$this->attr(array(
			'id'    => $this->_name,
			'name'  => $this->_name,
			'type'  => 'text',
			'class' => 'token',
		));
		return '<img src="'.f_c_helper::_()->uri->helper($this->_uri).'" />' . $this->_separator . '<input'.$this->renderAttr().' />';
	}

	public function separator($sSeparator = null)
	{
		if($sSeparator === null){
			return $this->_separator;
		}
		$this->_separator = $sSeparator;
		return $this;
	}

	public function uri($sTokenImgUri = null)
	{
		if ($sTokenImgUri === null) {
			return $this->_uri;
		}
		$this->_uri = $sTokenImgUri;
		return $this;
	}
	
}