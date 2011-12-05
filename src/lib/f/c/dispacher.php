<?php

class f_c_dispacher extends f_c
{
    
    public 

    public $controller;
    public $action;

	public function run()
    {
        $oRequest    = $this->_c->request;
        $sController = isset($oRequest->controller[0]) ? $oRequest->controller : 'index';
        $sAction     = isset($oRequest->action[0])     ? $oRequest->action     : 'index';

		$sFile = f::$pathApp.'c/' . str_replace('_', '/', $sController) . '.php';

    	if (! is_file($file)) {
			$this->_c->notFound->helper();
		}

		$sClass      = "c_{$sController}";
		$oController = new $sClass;
        $sMethod     = "{$sAction}Action";

        if (! $oController instanceof f_c_action_interface) {
			$this->_c->notFound->helper();
        }
        
        if (! method_exists($oController, $sMethod)) {
            if ($sMethod !== 'indexAction' && ! method_exists($oController, 'indexAction')) {
                $this->_c->notFound->helper();
            }
            $sMethod = 'indexAction';
            $sAction = 'index';
        }
        
        $this->controller = $sController;
        $this->action     = $sAction;

        if ($this->_c->event->is('f.dispacher')) {
            if ($this->_c->event->notify(new f_event($this, 'f.dispacher'))->break) {
                return;
            }
        }

        $oController->{$sMethod}();
    }

}