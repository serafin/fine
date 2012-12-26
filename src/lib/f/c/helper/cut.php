<?php

class f_c_helper_cut
{

    public function helper($sText, $iLength, $sEnd = '…')
    {
        if (isset($sText[$iLength-1])) {
            return mb_substr($sText, 0 , $iLength, 'UTF-8') . $sEnd;
        }
        
        return $sText;
    }

}
