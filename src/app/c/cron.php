<?php

class c_cron extends f_c_action
{
    
    public function dayAction()
    {
        $this->_cleanDataTmpDir();
    }
    
    /**
     * Czysci folder data/tmp/ z starych niepotrzebnych plikow
     */
    protected function _cleanDataTmpDir()
    {
        f_upload_tmp::_()->destroyAll(14 * 24 * 60 * 60);
    }
    
}