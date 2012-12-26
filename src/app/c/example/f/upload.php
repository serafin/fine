<?php

class c_example_f_upload extends f_c_action
{
    
    public function indexAction()
    {
        $this->render->off();
        $this->response->off();
        
        echo f_form::_()
                ->element(new f_form_file(array('name' => 'a')))
                ->element(new f_form_file(array('name' => 'b[]')))
                ->element(new f_form_file(array('name' => 'c[9]')))
                ->element(new f_form_file(array('name' => 'd[z]')))
                ->element(new f_form_file(array('name' => 'e[9][]')))
                ->element(new f_form_file(array('name' => 'f[z][]')))
                ->element(new f_form_submit())
        ;
        
        f_debug::dump($_FILES);
    }
    
}