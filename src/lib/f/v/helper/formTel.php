<?php

class f_v_helper_formTel extends f_v_helper_formElement
{

    public function helper($sName = 'tel', $mVal = null, $aAttr = array())
    {
        return "<input" . f_v_helper_formElement::_renderAttr(
                   array('type' => 'tel', 'name' => $sName, 'value' => $mVal) + $aAttr
                )
             . " />";
    }

}