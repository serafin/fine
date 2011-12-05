<?php

class f_valid_noChar extends f_valid_abstract
{

	const HAS_CHAR = 'hasChar';

    public $char;

    protected $_var = array('char');

    public function  __construct($asChar, $aMsg = null)
    {

		$this->char = is_array($asChar) ? $asChar : explode(' ', $asChar);
        parent::__construct($aMsg);
    }

	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);

        if (!is_array($this->char)) {
            $this->char = explode(' ', $this->char);
        }

        foreach ($this->char as $char) {
            if (strpos($sValue, $char) !== false) {
                $this->_error(self::HAS_CHAR);
                return false;
            }
        }

        return true;
    }

}