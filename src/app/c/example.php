<?php

class c_example extends f_c_action
{

    public function __construct()
    {
        $this->_c->render->off();
    }
    
    public function connectionCloseWithHelperAction()
    {
        return;
        
        $this->render
            ->view('example/connectionCloseWithHelperAction')
            ->render();
        
        $this->connectionClose();
        
        // do some stuff ...
    }
    
    public function connectionCloseRawAction()
    {
        return;
        
        $this->render
            ->view('example/connectionCloseRaw')
            ->render();
        
        $this->response
            ->header('Content-Length', mb_strlen($this->response->body))
            ->header('Connectiaon', 'close')
            ->send();
        
        // do some stuff ...
        
    }
    
    public function fVHelperHeadAction()
    {
        /* @var $head f_v_helper_head */
        $head = $this->v->_c->head;

        $head->template['title']['separator'] = ' :: ';

        $head->title('T1');
        $head->title('T2');

        $head->charset('utf-8');

        $head->js('/public/js/jquery.js');

        $head->css('/public/css/style.css');

        $head->csscode('.box-main main-p { color: red; } ');

        $head->csscode('.box-main main-p { color: red; } ');

        $head->rss('/newsy/rss', 'Newsy');

        $head->description('Opis strony');
        
        $head->keywords('Abc');
        $head->keywords('Def');
        $head->keywords('ghi, jkl, mno');

        $head->favicon('/favicon.ico');

        $this->response
            ->header('Content-Type', 'text/plain')
            ->body($head->render())
            ->send();

    }
    
}