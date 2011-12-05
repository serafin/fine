<?php

class f_form_file extends f_form_abstract
{
	
	public $type = 'file';
	
	protected $_amount    = 1;
	protected $_extension = array('jpg', 'jpeg', 'png', 'gif');
	protected $_file;
	protected $_separator = '<br />';
	protected $_size      = 2097152; //2 MB

	public function amount($iAmount = 1)
	{
		if ($iAmount === null) {
			return $this->_amount;
		}
		else {
			if (!$iAmount > 0) {
				return;
			}
			if ($iAmount == 1) {
				$this->multi(false);
			}
			else {
				$this->multi(true);
			}
			$this->_amount = $iAmount;
		}
		return $this;
	}
	
    public function error()
    {
    	return $this->_error;
    }

	public function extension($asExtension)
	{
		if (!is_array($asExtension)) {
			$asExtension = explode(' ', $asExtension);
		}
		$this->_extension = $asExtension;
		return $this;
	}

	
	public function file($sFileName = null)
	{
		if ($sFileName !== null) {
			$this->_file = $sFileName;
			return $this;
		}
		else {
			return $this->_file;
		}
	}
	
	public function render()
	{
		$this->attr(array(
			'name'  => $this->_name,
			'id'    => $this->_name,
			'class' => 'file'
		));
		
		if ($this->_multi) {
			$this->attr('name', $this->_name.'[]');
			return str_repeat('<input type="file" '.$this->renderAttr().' />', $this->_amount);
		}
		else {
			if ($this->_file) {
				return '<img src="'.$this->_file.'"/>'.$this->_separator.'<input type="file" '.$this->renderAttr().' />';
			}
			else {
				return '<input type="file" '.$this->renderAttr().' />';
			}
		}
	}
	
	public function separator($sSeparator = null)
	{
		if($sSeparator === null){
			return $this->_separator;
		}
		$this->_separator = $sSeparator;
		return $this;
	}

	

	public function size($iSize = null, $sUnit = 'MB')
	{
		if ($iSize === null) {
			return $this->_size;
		}
		else {
			$this->_size = f_number::size2byte($iSize, $sUnit);
		}
	}

	protected function _isValid()
	{
		$this->_error = array();

		if (empty($_FILES)) {
			$this->_error('enctype');
		}

		if (!is_array($_FILES[$this->_name]['error'])) {
			//one file
			if ($_FILES[$this->_name]['error'] == 4) {
				if ($this->_required) {
					$this->_error('required');
					$this->_isValid = false;
				}
				else {
					$this->_isValid = true;
				}
				return;
			}
			else if ($_FILES[$this->_name]['error'] > 0) {
				$this->_error('error'.$_FILES[$this->_name]['error']);
			}
			else {
				$bValid = true;
				if ($this->filter) {
					foreach ($this->filter as $filter) {
						$_POST[$this->_name]['name'] = $filter->filter($_POST[$this->_name]['name']);
					}
				}
				if ($this->_extension !== null) {
					$sFileExtension = strtolower(end(explode('.', $_FILES[$this->_name]['name'])));
					if (!in_array($sFileExtension, $this->_extension)) {
						$this->_error('extension', array('file_name' => $_FILES[$this->_name]['name'], 'file_extension' => $sFileExtension, 'extension' => implode(', ', $this->_extension)));
						$bValid = false;
						if ($this->_breakOnFail) {
							$this->_isValid = false;
							return;
						}
					}
				}
				if ($this->_size !== null) {
					if ($_FILES[$this->_name]['size'] > $this->_size) {
						$this->_error('size', array('file_name' => $_FILES[$this->_name]['name'], 'file_size' => f_number::byte2size($_FILES[$sName]['size']), 'size' => f_number::byte2size($this->_size)));
						$bValid = false;
						if ($this->_breakOnFail) {
							$this->_isValid = false;
							return;
						}
					}
				}
				$this->_isValid = $bValid;
			}
		}
		else {
			//multi file
			$aError = array();
			foreach ($_FILES[$this->_name]['error'] as $key => $error) {
				if ($_FILES[$this->_name]['error'][$key] == 4) {
					$aError[$key] = false;
					continue;
				}
				if ($_FILES[$this->_name]['error'][$key] > 0) {
					$this->_error('error'.$_FILES[$this->_name]['error'][$key]);
					$aError[$key] = true;
					continue;
				}
				if ($this->filter) {
					foreach ($this->filter as $filter) {
						$_POST[$this->_name]['name'][$key] = $filter->filter($_POST[$this->_name]['name'][$key]);
					}
				}
				if ($this->_extension !== null) {
					$sFileExtension = strtolower(end(explode('.', $_FILES[$this->_name]['name'][$key])));
					if (!in_array($sFileExtension, $this->_extension)) {
						$this->_error('extension', array('file_name' => $_FILES[$this->_name]['name'][$key], 'file_extension' => $sFileExtension, 'extension' => implode(', ', $this->_extension)));
						$aError[$key] = true;
						if ($this->_breakOnFail) {
							continue;
						}
					}
				}
				if ($this->_size !== null) {
					if ($_FILES[$this->_name]['size'][$key] > $this->_size) {
						$this->_error('size', array('file_name' => $_FILES[$this->_name]['name'][$key], 'file_size' => f_number::byte2size($_FILES[$this->_name]['size'][$key]), 'size' => f_number::byte2size($this->_size)));
						$aError[$key] = true;
						if ($this->_breakOnFail) {
							continue;
						}
					}
				}
			}
			foreach ($aError as $key => $boolean) {
				if ($boolean) {
					$_FILES[$this->_name]['name'    ][$key] = "";
					$_FILES[$this->_name]['type'    ][$key] = "";
					$_FILES[$this->_name]['tmp_name'][$key] = "";
					$_FILES[$this->_name]['error'   ][$key] = 4;
					$_FILES[$this->_name]['size'    ][$key] = 0;
				}
			}
			if ($this->_required && count($aError) == count($_FILES[$this->_name]['error'])) {
				$this->_error('required');
				$this->_isValid = false;
			}
			else {
				$this->_isValid = true;
			}
		}
	}

	protected function _error($sKey, $aVar = null)
	{
		$sMsg = f_lang::_()->f_form_file->{$sKey};
		if ($aVar) {
			foreach ($aVar as $k => $v) {
				$sMsg = str_replace("{{$k}}", $v, $sMsg);
			}
		}
		$this->_error[] = $sMsg;
	}

	protected function _requiredMsg()
	{
		return f_lang::_()->f_form_file->required;
	}

}