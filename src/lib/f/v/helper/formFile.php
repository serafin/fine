<?php

class f_v_helper_formFile extends f_v_helper_formElement
{

    public function helper($sName = 'file', $mVal = null, $aAttr = array())
    {
        return "<input" . $this->_renderAttr(
                   array('type' => 'file', 'name' => $sName) + $aAttr
                )
             . " />";
    }

}