<?php

class f_form_select extends f_form_abstract
{
	
	public $type = 'select';
	
	protected $_tree          = array();
	protected $_treeParent    = array();
	protected $_treeStart     = '0';
	protected $_treeException = array();
	protected $_optionBuffor  = '';
	protected $_option        = array();
	protected $_selected      = array();
	
	public function render()
	{
		$this->attr('id', $this->_name);
		
		if ($this->_multi) {
			$this->attr(array(
				'multiple' => 'multiple',
				'name'     => "$this->_name[]",
			));
			if ($_POST) {
				if (isset($_POST[$this->_name]) && $_POST[$this->_name]) {
					foreach ($_POST[$this->_name] as $i) {
						$this->_selected[$i] = 1;
					}
				}
			}
			else if (is_array($this->_value)) {
				foreach ($this->_value as $i) {
					$this->_selected[$i] = 1;
				}
			}
		}
		else {
			$this->attr('name', $this->_name);
			$this->_selected[($_POST)?($_POST[$this->_name]):$this->_value] = 1;
		}
		
		if ($this->_option) {
			foreach ($this->_option as $k => $v){
				$this->_optionBuffor .= '<option value="'.htmlspecialchars($k).'"'
											.((isset($this->_selected[$k]))?' selected="selected"':'').'>'
											.htmlspecialchars($v).'</option>';
			}
		}
		if ($this->_tree) {
			$this->_tree($this->_treeStart);
		}
		
		return '<select' . $this->renderAttr() . '>' . $this->_optionBuffor . '</select>';
	}
	
	public function option($aOptions = null)
	{
		if ($aOptions === null) {
			return $this->_option;
		}
		$this->_option = $aOptions;
		return $this;
	}
	
	/**
	 * Ustala opcje o hierarchicznej strukturze 
	 *
	 * @param array $aOptions Dwu wymiarowa tablica, gdzie drugi wymiar o struktorze:  array(0 => 'id', 1=>'parent_id', 2=>'option_content');
	 */
	public function optionTree($aOptions)
	{
		foreach ($aOptions as $i) {
			$this->_tree[$i['id']] = $i;
			$this->_treeParent[$i['parent']][] = $i['id'];
		}
		return $this;
	}
	
	public function optionTreeStart($iStartId = '0')
	{
		$this->_treeStart = $iStartId;
		return $this;
	}

	public function optionTreeException($aiException = null)
	{
		if ($aiException === null) {
			return array_keys($this->_treeException);
		}
		else if ($aiException === false) {
			$this->_treeException = array();
		}
		else {
			if (! is_array($aiException)) {
				$aiException = array($aiException);
			}
			foreach ($aiException as $i) {
				$this->_treeException[$i] = true;
			}
		}
		return $this;
	}
	
	private function _tree($parent, $nav = '', $depth = 0)
	{
		foreach ($this->_treeParent[$parent] as $i => $id) {
			if (isset($this->_treeException[$id])) {
				unset($this->_treeParent[$parent][$i]);
			}
		}
		
		$this->_treeParent[$parent] = array_merge($this->_treeParent[$parent]);

		$last = count($this->_treeParent[$parent]) - 1;

		foreach ($this->_treeParent[$parent] as $i => $id) {
			if (! isset($this->_treeException[$id])) {
				$this->_optionBuffor .=
					'<option value="'.htmlspecialchars($this->_tree[$id]['id']).'"'
					.((isset($this->_selected[$id])) ? ' selected="selected"' : '')
					.' title="'.htmlspecialchars($this->_tree[$id]['title']).'">'
					. ($depth == 0 ? '' : $nav . '' .($last != $i ? '&nbsp;&#9507;' : '&nbsp;&#9495;' ))
					. ' ' . htmlspecialchars($this->_tree[$id]['content']).'</option>';

				if (isset($this->_treeParent[$id])) {
					$this->_tree($id, $nav . ($last != $i ? '&nbsp;&#9475;' : ($depth==0?'':'&nbsp;&nbsp;&nbsp;&nbsp;')), $depth + 1);
				}
			}
		}
	}
	
}
