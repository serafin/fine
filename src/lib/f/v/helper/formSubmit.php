<?php

class f_v_helper_formSubmit extends f_v_helper_formElement
{

    public function helper($sName = 'submit', $mVal = null, $aAttr = array(),
                           $aOption = array(), $sSeparator = null)
    {
        if (!$aOption) {

            $main = array(
                'type' => 'submit',
                'name' => ($sName !== null ? $sName : 'submit'),
            );
            $main['value'] = $mVal !== null
                           ? $mVal
                           : ($aAttr['value'] !== null ? $aAttr['value'] : $main['name']);
            
            return "<input" . $this->_renderAttr($main + $aAttr) . " />";
        }
        else {
            $return = array();
            
            foreach ($aOption as $k => $v) {
                if (is_array($v)) {
                    $custom = array(
                        'type'  => 'submit',
                        'name'  => "{$sName}[$k]",
                        'value' => $v['value'],
                    );
                    if (isset($v['type'])) {
                        $custom = array('type' => $v['type']) + $custom;
                    }
                    $custom += $v;
                }
                else {
                    $custom = array(
                        'type'  => 'submit',
                        'name'  => "{$sName}[$k]",
                        'value' => $v,
                    );
                }

                $return[] = "<input" . $this->_renderAttr($custom + $aAttr) . " />";
            }

            return implode($sSeparator, $return);
        }
        
    }

}