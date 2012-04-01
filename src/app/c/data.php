<?php

class c_data extends f_c_action 
{
    
    protected $_configKey;
    protected $_configVal;
    protected $_model;
    protected $_imageInput;
    protected $_imageOutput;

    public function indexAction()
    {
        $this->render->off();
        $this->response->off();
        
        
        /**
         * example path
         * /data/{model}/{model_id}_{model_token}_{configKey}.[jpg|{model_ext}]
         */
        
        
        
    }
    
    
}