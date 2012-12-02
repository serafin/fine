<?php

class f_v_helper_formHidden extends f_v_helper_formElement
{

    public function helper($sName = 'hidden', $mVal = null, $aAttr = array())
    {
        return "<input" . $this->_renderAttr(
                   array('type' => 'hidden', 'name' => $sName, 'value' => $mVal) + $aAttr
                )
             . " />";
    }

}