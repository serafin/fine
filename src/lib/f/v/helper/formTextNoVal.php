<?php

class f_v_helper_formTextNoVal extends f_v_helper_formElement
{

    public function helper($sName = 'text', $mVal = null, $aAttr = array())
    {
        return "<input" . $this->_renderAttr(
                   array('type' => 'text', 'name' => $sName) + $aAttr
                )
             . " />";
    }

}