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
        
        if (!$this->config->data[$this->data]['imgsize'][$this->size] && !in_array($this->size,$this->config->data[$this->data]['imgsize'])) {
            $this->notFound();
        }
        
        //sprawdzanie czy konfiguracja pełna czy skrócona
        if(is_array($this->config->data[$this->data]['imgsize'][$this->size])){ 
            $this->config =  $this->config->data[$this->data]['imgsize'][$this->size];
        }
        else{ 
            $this->config = $this->resolveImgSize($this->size);
        }

        if (!ctype_digit($this->id) || !isset($this->config)) {
            $this->notFound();
        }

        $sModel = "m_$this->data";
        $this->model = new $sModel;
        $this->model->select($this->id);
        
        if (! $this->model->id()) {
            $this->notFound();
        }

        //sprawdzanie $this->config['fx']
        if(isset($this->config['fx'])){            
            if(!method_exists('c_data',$this->config['fx'] )){
                $this->notFound();
            }
            $this->{$this->config['fx']}();
        }
        else{
            $width  = $this->config['w'];
            $height = $this->config['h'];
            if(isset($this->config['ext'])){
                $ext = $this->config['ext'];
            }
            else{
                $ext = 'jpg';
            }
            $type   = $this->config['type'];
            $extend = $this->config['extend'];
            if(isset($this->config['quality'])){
                $quality = $this->config['quality'];
            }
            else{
                $quality = 95;
            }
        }            

        f_image::_()
            ->load("data/{$this->data}/{$this->id}_{$this->token}.{$ext}")
            ->{$type}($width, $height, $extend)
            ->save("data/$this->data/{$this->id}_{$this->token}_{$this->size}.{$ext}", $quality)
            ->render($quality)
        ;

    }
    
    public function tmpAction()
    {   
        list($token, $option, $name) = explode('_', $_GET[2], 3);
        
        foreach ($this->config->data as $model) {
            foreach ($model['imgsize'] as $size => $value) {
                
                if (is_array($value) && $size == $option) { 
                    $this->config =  $value;
                }
                elseif ($value == $option) { 
                    $this->config = $this->resolveImgSize($value);
                }
                else {
                    continue;
                }
                
                $width   = $this->config['w'];
                $height  = $this->config['h'];
                $type    = $this->config['type'];
                $extend  = $this->config['extend'];
                
                f_image::_()
                    ->load("data/tmp/{$token}__{$name}")
                    ->{$type}($width, $height, !$extend)
                    ->save("data/tmp/{$token}_{$option}_{$name}", 95)
                    ->render(95)
                ;
            }
        }
    }
    
    public static function resolveImgSize($sSize)
    {
        $pattern = '/(?P<w>[0-9]{1,4})x?(?P<h>[0-9]{0,4})([rt]?)/';
        preg_match($pattern, $sSize, $matches);
        
        if ($matches['h'] === '') {
            $matches['h'] = $matches['w'];
            $matches[3]   = 't';
        }
        if (isset($matches[3]) && ($matches[3] == 't')) {
            $type = 'thumb';
            $extend = false;
        }
        else {
            $type = 'resize';
            $extend = true;
        }
        
        return array(
            'w'      => $matches['w'],
            'h'      => $matches['h'],
            'type'   => $type,
            'extend' => $extend
        );
    }
}