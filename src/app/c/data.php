<?php

class c_data extends f_c_action
{
    
    public function indexAction()
    {
        $this->render->off();

        $img = $this->datafile->createImgSize($_SERVER['REQUEST_URI']);

        if (!$img || !$img->resource()) {
            $this->notFound();
        }
        
        $img->render();
    }

    public function tmpAction()
    {   
        $this->render->off();
        
        $img = $this->datafile->createTmpImgSize($_SERVER['REQUEST_URI']);

        if (!$img || !$img->resource()) {
            $this->notFound();
        }
        
        $img->render();
    }
    
}