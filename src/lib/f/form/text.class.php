<?php

class f_form_text extends f_form_element
{
	
	public function renderHelper()
	{
		$this
            ->addClass('text')
            ->attr(array(
                'type'  => 'text',
                'name'  => $this->_name,
                'value' => $_POST ? htmlspecialchars($this->_getVal($_POST, $this->_name)) : $this->_escapeVal($this->_value)
    		));
		return '<input' . $this->renderAttr() . ' />';
	}

}