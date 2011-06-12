<?php

class f_agent
{
    
    protected $___agent;

    /**
     * @param array $aConfig
     *      subject - obiekt obserwowany,
     *      event - event dispacher,
     *      id - id eventu
     */
    public function  __construct(array $config = array())
    {
        $this->___agent = (object) $config;
    }

    public function  __set($sName, $sValue)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->event,
            'param'   => array(
                'type' => 'set',
                'name' => $sName,
                'val'  => $sValue,
            )
        ));

        f::$c->event->run($oEvent);

        if ($oEvent->cencel()) {
            return;
        }

        $this->___agent->subject->{$oEvent->name} = $oEvent->val;
    }

    public function  __get($sName)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->event,
            'param'   => array(
                'type' => 'get',
                'name' => $sName,
            )
        ));

        f::$c->event->run($oEvent);

        if (!$oEvent->cancel() && !isset($oEvent->val)) {
            $oEvent->val = $this->___agent->subject->{$oEvent->name};
        }
        
        return $oEvent->val;
    }

    public function  __call($sName,  $aArg)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->event,
            'param'   => array(
                'type'       => 'call',
                'name'       => $sName,
                'arg'        => $aArg,
            )
        ));

        f::$c->event->run($oEvent);

        if (!$oEvent->cancel() && !isset($oEvent->val)) {
            $oEvent->val = $this->___agent->subject->{$oEvent->name}();
        }

        return $oEvent->val;
    }

}