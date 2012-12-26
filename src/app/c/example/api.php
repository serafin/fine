<?php

/**
 * Przyklad api, ktore zawsze odpowiada jsonem
 */
class c_example_api extends f_c_action
{

    public function __construct()
    {
        $this->render->off();
        
        $this->response->head = (object)array(
            'status'    => 'ok', // ok|error`
            'error_msg' => '',
        );
        
        $this->event->on(f_c_response::EVENT_RESPONSE_PRE, array($this, 'responsePre'));
    }
    
    /**
     * Lepiej jak api nie ma zdefiniowanej metody index
     */
    public function indexAction()
    {
        $this->notFound();
    }
    
    public function sampleSuccessAction()
    {
        $this->response->body = array();
    }

    public function sampleErrorAction()
    {
        if (true) {
            return $this->_error('Some error');
        }
        else if (true) {
            return $this->_error('Some error2');
        }
        else if (true) {
            return $this->_error('Some error3');
        }
        
        $this->response->body = array();
    }

    public function responsePre(f_event $event)
    {
        if (isset($_GET['debug'])) { // debug
            /** @todo zastapic f_debug::dump metoda f_debug::source */
            $this->response->header('Content-Type', 'text/html; charset=utf-8');
            $this->response->body = f_debug::dump(
                array('head' => $this->response->head,
                      'body' => $this->response->body),
                'api',
                false
            );
            return;
        }
        
        $this->response->header('Content-Type', 'application/json');
        $this->response->body = json_encode(array(
            'head' => $this->response->head,
            'body' => $this->response->body,
        ));
        
    }
    
    /**
     * Ustawia status odpowiedzi na error i dodaje tresc bledu do [head][error_msg]
     * 
     * @param string $msg
     */
    protected function _error($msg)
    {
        $this->response->status    = 'error';
        $this->response->error_msg = $msg;
        return null;
    }
    
}