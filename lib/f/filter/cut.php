<?php

class f_filter_cut extends f_filter_abstract
{

    protected $_lenght = 255;
    protected $_end    = '...';

    public static function _()
    {
        return new self;
    }

    public function lenght($iLenght = null)
    {
        if ($iLenght === null) {
            return $this->_lenght;
        }
        $this->_lenght = $iLenght;
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
        if (isset($sText[$this->_lenght - 1])) {
                return mb_substr($sText, 0 , $this->_lenght, 'UTF-8') . $this->_end;
        }
        return $sText;
    }

}