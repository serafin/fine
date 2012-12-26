<?php

class c_example_ajax extends f_c_action
{

    public function sampleAction()
    {
        $this->responseAjax();

        $this->response->body = array(
            array('id' => 1, 'title' => 'One'),
            array('id' => 2, 'title' => 'Two'),
        );
    }
    
}