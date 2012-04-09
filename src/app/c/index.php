<?php

class c_index extends f_c_action 
{
    
    public function indexAction()
    {
//        $oSetup = new c_setup();
//        $oSetup->checkAction();
        $this->debug;
        
        $this->db->query('SELECT * FROM post');
        
        $this->db->query('SELECT * FROM post');
        
        $this->debug->show();
        
    }
}
