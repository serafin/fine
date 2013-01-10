<?php

class f_v_helper_formButton extends f_v_helper_formElement
{

    public function helper($sName = 'button', $mVal = null, $aAttr = array())
    {
        return "<button" . $this->_renderAttr(array('name' => $sName) + $aAttr) . ">"
             . htmlspecialchars($mVal)
             . "</button>";
    }

}