<?php

class c_data extends f_c_action
{

    public function indexAction()
    {
        $this->render->off();
        
        /*
        $aUri = explode('?', $_SERVER['REQUEST_URI'], 2);
        $sFileName = $aUri[0];
        
        $aParam = array();
        if($aUri[1]) {
            foreach(explode('&', $aUri[1]) as $param) {
                list($key, $index) = explode('=', $param);
                $aParam[$key] = $index;
            }
        }
         * 
         */
        $sFileName = $_SERVER['REQUEST_URI'];

        if(file_exists($sFileName)) {
            f_image::_()->load($sFileName)->render();
        }

        $img = $this->datafile->createImgSize($sFileName);

        if($img && $img->resource()) {
            $img->render();
        }
        
        $this->notFound();
    }

    public function tmpAction()
    {   
        $this->render->off();
        
        $img = $this->datafile->createTmpImgSize($_SERVER['REQUEST_URI']);

        if($img && $img->resource()) {
            $img->render();
        }
        
        $this->notFound();
    }

}