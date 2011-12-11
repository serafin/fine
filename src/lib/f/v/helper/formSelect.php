<?php

class f_v_helper_formSelect extends f_v_helper_formElement
{

    public function helper($sName = 'select', $mVal = null, $aAttr = array(),
                           $aOption = array(), $sSeparator = null)
    {
        $return        = array();
        $basePrepend   = "";
        $baseAppend    = "";
        $baseTag       = array();
        if (isset($aAttr['_prepend'])) {
            $basePrepend = $aAttr['_prepend'];
            unset($aAttr['_prepend']);
        }
        if (isset($aAttr['_append'])) {
            $baseAppend = $aAttr['_append'];
            unset($aAttr['_append']);
        }
        if (isset($aAttr['_option'])) {
            $baseTag = $aAttr['_option'];
            unset($aAttr['_option']);
        }

        if (!is_array($mVal)) {
            $mVal = array($mVal);
        }

        foreach ($aOption as $key => $option) {
            $prepend   = $basePrepend;
            $append    = $baseAppend;
            $tag       = $baseTag;
            $input     = array(
                'value' => $key,
            );
            if (in_array($key, $mVal)) {
                $input['selected'] = 'selected';
            }
            else {
                unset($input['selected']);
            }
            $input += $baseTag;

            if (! is_array($option)) {
                $text = $option;
            }
            else {
                if (isset($option['_prepend'])) {
                    $prepend = $option['_prepend'];
                    unset($option['_prepend']);
                }
                if (isset($option['_append'])) {
                    $append = $option['_append'];
                    unset($option['_append']);
                }
                if (isset($option['_option'])) {
                    $tag = $option['_option'] + $baseTag;
                    unset($option['_option']);
                }
                $text = $option['_text'];
                unset($option['_text']);
                $input += $option;
            }

            $return[] = "<option" . $this->_renderAttr($input + $tag) . ">"
                      . $prepend
                      . $text
                      . $append
                      . "</option>";

        }
        
        $select = array('name'  => $sName);
        if (substr($sName, -2) == '[]') {
            $select['multiple'] = 'multiple';
        }
        return '<select' . $this->_renderAttr($select + $aAttr) . '>'
             . implode($sSeparator, $return)
             . '</select>';
    }

}