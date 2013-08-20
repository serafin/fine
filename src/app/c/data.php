<?php

class c_data extends f_c_action
{

    public function indexAction()
    {
        $this->render->off();
        
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