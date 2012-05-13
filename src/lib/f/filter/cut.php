<?php

class f_filter_cut extends f_filter_abstract
{

    protected $_length = 255;
    protected $_end    = '…'; // Unicode Character 'HORIZONTAL ELLIPSIS' (U+2026)

    public static function _()
    {
        return new self;
    }

    public function length($iLength = null)
    {
        if ($iLength === null) {
            return $this->_length;
        }
        $this->_length = $iLength;
        return $this;
    }

    public function end($sEnd = null)
    {
        if ($sEnd === null) {
            return $this->_end;
        }
        $this->_end = $sEnd;
        return $this;
    }

    public function filter($sText)
    {
        if (isset($sText[$this->_length - 1])) {
                return mb_substr($sText, 0 , $this->_length, 'UTF-8') . $this->_end;
        }
        return $sText;
    }

}