<?php

class c_example_f_v_helper_head /* extends f_c_action */
{
    
    public function indexAction()
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