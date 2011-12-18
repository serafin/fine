<?php

class f_v_helper_formPassword extends f_v_helper_formElement
{

    public function helper($sName = 'password', $mVal = null, $aAttr = array())
    {
        return "<input" . $this->_renderAttr(
                   array('type' => 'password', 'name' => $sName, 'value' => $mVal) + $aAttr
                )
             . " />";
    }

}