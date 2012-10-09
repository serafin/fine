<?php

class c_index extends f_c_action
{

    public function indexAction()
    {
        $this->v->msg = 'Hello World!';
    }

    public function testAction()
    {

        f_debug::dump($_GET);
        f_debug::dump($this->uri->assembleRequest(array('dupa' => 'nie')));
        $this->render->off();
        $this->response->off();
    }

}