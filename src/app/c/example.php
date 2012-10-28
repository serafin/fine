<?php

class c_example extends f_c_action
{

    public function __construct()
    {
        $this->_c->render->off();
    }

    public function apiAction()
    {
        // init
        $this->response->head = (object)array(
            'status'    => 'ok', // ok|error`
            'error_msg' => '',
        );
        $this->event->on(f_c_response::EVENT_RESPONSE_PRE, array($this, 'apiResponsePre'));
        $this->render->off();


        // use

        $this->response->body = array();
        $this->response->head->status = 'error';

    }

    public function apiResponsePre(f_event $event)
    {
        if (isset($_GET['debug'])) {
            $this->response->header('Content-Type', 'text/html');
            $this->response->body = f_debug::dump(
                array('head' => $this->response->head,
                      'body' => $this->response->body),
                'api',
                false
            );
            
        }
        else {
            $this->response->header('Content-Type', 'application/json');
            $this->response->body = json_encode(array(
                'head' => $this->response->head,
                'body' => $this->response->body,
            ));
        }
        
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

    public function fCHelperResponseAjaxAction()
    {
        $this->responseAjax();

        $this->response->body = new stdClass();
        $this->response->body->status = 'ok';
        $this->response->body->data   = array();
    }

    public function fValidFileAction()
    {
        f_form::_(array(
            'element' => new f_form_file(array(
                'name' => 'test',
                'valid' => array(
                    new f_valid_fileExt(array('ext' => 'jpg jpeg png'))
                ),
            ))
        ));
    }

    public function fValidModelAction()
    {
        
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