<?php

abstract class f_v_helper_formElement
{

    protected function _renderAttr($aAttr)
    {
        $render = "";
        foreach ($aAttr as $k => $v) {
            $render .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
        }
        return $render;
    }

}