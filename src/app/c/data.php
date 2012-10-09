<?php

class c_data extends f_c_action
{
    
    public function __construct()
    {
        $this->render->off();
    }

    public function indexAction()
    {
        /**
         * @todo przerobic pod nowego configa
         */

        if (isset($this->param)) {
            $this->data    = $this->param['data'];
            $this->file    = $this->param['file'];
        }
        else {
            $this->data    = $_GET[1];
            $this->file    = $_GET[2];
        }
        
        if (file_exists($_SERVER['DOCUMENT_ROOT']  . '/data/' . $this->data . '/' . $this->file)) {
        	f_image::_()->load('data/' . $this->data . '/' . $this->file)->render(95);
        	return;
        }
        
        if (! $this->config->data[$this->data]) {
            $this->notFound();
        }

        $ext = end(explode(".", $this->file));
        list($this->id, $this->token, $this->size) = explode("_", substr($this->file, 0, - (strlen($ext)+1)), 3);
        $this->config = $this->config->data[$this->data]['imgsize'][$this->size];

        if (!ctype_digit($this->id) || !isset($this->config)) {
            $this->notFound();
        }

        $sModel = "m_$this->data";
        $this->model = new $sModel;
        $this->model->select($this->id);
        
        if (! $this->model->id()) {
            $this->notFound();
        }

        if(isset($this->config['fx'])){
            if(!method_exists('c_data',$this->config['fx'] )){
                $this->notFound();
            }
            $this->{$this->config['fx']}();
        }
        else {
            f_image::_()
                ->load("data/{$this->data}/{$this->id}_{$this->token}.{$ext}")
                ->{$this->config['type']}($this->config['width'], $this->config['height'], $this->config['extend'])
                ->save("data/$this->data/{$this->id}_{$this->token}_{$this->size}.{$ext}", 95)
                ->render(95)
            ;
        }

    }
}