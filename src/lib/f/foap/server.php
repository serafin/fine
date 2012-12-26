<?php

class f_foap_server
{
    
    protected $_object;
    protected $_event;
    
    /**
     * @var f_c_response
     */
    protected $_response;
    
    /**
     * Statyczny konstruktor
     * 
     * @param array $config
     * @return f_foap_server
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    /**
     * Konstruktor
     * 
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }
    
    /**
     * Ustala/pobiera obiekt lub obiekty
     * 
     * @param object $oObject
     * @return f_foap_server|object
     */
    public function object($oObject = null)
    {
        if (func_num_args() == 0) {
            return $this->_object;
        }
        $this->_object = $oObject;
        return $this;
    }
    
    /**
     * Ustala/pobiera event
     * 
     * @param f_event $oEvent
     * @return f_foap_server|f_event
     */
    public function event($oEvent = null)
    {
        if (func_num_args() == 0) {
            return $this->_object;
        }
        $this->_object = $oEvent;
        return $this;
    }
    
    /**
     * Ustala/pobiera response
     * 
     * @param array|f_c_response $oResponse
     * @return f_foap_server|f_c_response
     */
    public function response($oResponse = null)
    {
        if (func_num_args() == 0) {
            return $this->_response;
        }
        $this->_response = $oResponse;
        return $this;
    }
    
    public function handle()
    {
        if (!$this->_object) {
            throw new f_c_exception_internalError();
        }
        
        if (!$this->_response) {
            $this->_response = new f_c_response();
        }
        
        $request = json_decode((string)@file_get_contents('php://input'));
        
        // validate foap request
        if (
            !is_object($request) || !isset($request->head) || !is_object($request->head)
            || !isset($request->head->type) || !isset($request->head->foap)
        ) {
            $this->_response->code(400)->body('400 Bad Request')->send();
            return;
        }
        
        /** @todo check foap version, version is stored in $request->head->foap */
        
        // define request, send foap define response
        if ($request->head->type == 'request_define') {
            $this->_response
                ->header('Content-Type', 'application/json')
                ->body(json_encode(array(
                    'head' => array(
                        'foap'  => '1',
                        'type'  => 'response_define',
                    ),
                    'body' => $this->_define(),
                )))
                ->send();
             return;
        }
        
        
        if ($request->head->type != 'request') {
            $this->_response->code(400)->body('400 Bad Request')->send();
        }
        
        
        if ($this->_event) {
            $this->_event->subject($this)->param($request->head->param)->run();
            if ($this->_event->cancel()) {
                return;
            }
        }
        
        $this->_response
            ->header('Content-Type', 'application/json')
            ->body(json_encode(array(
                'head' => array(
                    'foap'  => '1',
                    'type'  => 'response',
                ),
                'body' => call_user_func_array(array($this->_object, $request->body->method), $request->body->arg),
            )))
            ->send();
    }
    
    /**
     * @todo
     * Zwraca definicje obiektu
     * 
     * Wszystkie metody, arugmentu metod, komentarze metod
     * 
     * @return array
     */
    protected function _define()
    {
        
    }
}