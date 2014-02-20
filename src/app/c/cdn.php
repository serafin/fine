<?php

class c_cdn extends f_c_action
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
    
    public function publicAction()
    {
        $this->render->off();
        
        if ($_GET[2] != 'css' && $_GET[2] != 'js') {
            $this->notFound();
        }
        
        $this->publicFiles2oneFile
            ->fileName($_GET[3])
            ->{$_GET[2]}();
    }
    
}