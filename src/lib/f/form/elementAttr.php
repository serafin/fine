<?php

abstract class f_form_elementAttr
{
    
    protected $_attr  = array();
	protected $_style = array();
	protected $_class = array();

    public function css($asName, $sValue = null)
    {
		if (is_array($asName)) {
    		foreach ($asName as $k => $v) {
    			$this->_style[$k] = $v;
    		}
    		return $this;
		}
    	if ($sValue === null) {
    		return $this->_style[$asName];
    	}
    	$this->_style[$asName] = $sValue;
    	return $this;
    }

    public function removeCss ($sName = null)
    {
    	if ($sName === null) {
	    	$this->_style = array();
    	}
    	else {
    		unset($this->_style[$sName]);
    	}
   		return $this;
    }

    public function addClass($asName)
    {
    	if (is_string($asName)) {
    		$asName = explode(' ', $asName);
    	}
		foreach ($asName as $i){
			$this->_class[$i] = true;
		}
		return $this;
    }

    public function removeClass($sName = null)
    {
    	if ($sName === null) {
	    	$this->_class = array();
    	}
    	else {
			$this->_class[$sName] = null;
			unset($this->_class[$sName]);
    	}
		return $this;
    }

    public function attr($asName, $sValue = null)
    {
    	if (is_array($asName)) {
    		foreach ($asName as $k => $v) {
    			$this->_attr[$k] = $v;
    		}
    		return $this;
    	}
    	if ($sValue === null) {
    		return $this->_attr[$asName];
    	}
    	$this->_attr[$asName] = $sValue;
    	return $this;
    }

    public function removeAttr($sName = null)
    {
    	if ($sName === null) {
	    	$this->_attr = array();
    	}
    	else {
    		unset($this->_attr[$sName]);
    	}
   		return $this;
    }

    public function renderAttr()
    {
    	$sOut = '';
    	if ($this->_style) {
    	 	if (!isset($this->_attr['style'])) {
    	 		$this->_attr['style'] = '';
    	 	}
    	 	else if ($this->_attr['style'][strlen($this->_attr['style'])-1] != ';') {
    	 		$this->_attr['style'] .= ';';
    	 	}
    	 	foreach ($this->_style as $sProperty => $sValue) {
    	 		$this->_attr['style'] .= $sProperty.':'.$sValue.';';
    	 	}
    	}
    	if ($this->_class) {
    		$sTmp = '';
    		foreach ($this->_class as $sName => $bTrue) {
    			if(empty($sTmp)){
    				$sTmp .= $sName;
    			}
    			else {
    				$sTmp .= ' '.$sName;
    			}
    		}
    		if (isset($this->_attr['class'])) {
    			$this->_attr['class'] .= ' '.$sTmp;
    		}
    		else {
    			$this->_attr['class'] = $sTmp;
    		}
    	}
    	if ($this->_attr) {
			foreach ($this->_attr as $sAttrib => $sValue) {
				$sOut .= ' '.$sAttrib.'="'.$sValue.'"';
			}
		}
		return $sOut;
    }

}