<?php

class f_v_helper_formEmail extends f_v_helper_formElement
{

    public function helper($sName = 'email', $mVal = null, $aAttr = array())
    {
        return "<input" . f_v_helper_formElement::_renderAttr(
                   array('type' => 'email', 'name' => $sName, 'value' => $mVal) + $aAttr
                )
             . " />";
    }

}