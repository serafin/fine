<?php

class f_v_helper_formTextarea extends f_v_helper_formElement
{

    public function helper($sName = 'textarea', $mVal = null, $aAttr = array())
    {
        return "<textarea" . $this->_renderAttr(array('name' => $sName) + $aAttr) . ">"
             . htmlspecialchars($mVal)
             . "</textarea>";
    }

}