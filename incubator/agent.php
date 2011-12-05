<?php

class f_agent
{
    
    protected $___agent;

    /**
     * @param array $aConfig
     *      #subject - obiekt obserwowany,
     *      #event - event dispacher,
     *      #id - id eventu
     */
    public function  __construct($aConfig)
    {
        $this->___agent = (object) array(
            'subject' => $aConfig['subject'],
            'event'   => $aConfig['event'],
            'id'      => $aConfig['id'],
        );
    }

    public function  __set($sName,  $sValue)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->id,
            'param'   => array(
                'type'  => 'set',
                'name'  => $sName,
                'val'   => $sValue,
                'break' => false
            )
        ));
        $this->___agent->event->notify($oEvent);
        if ($oEvent->break) {
            return;
        }
        $this->___agent->subject->{$oEvent->name} = $oEvent->val;
    }

    public function  __get($sName)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->id,
            'param'   => array(
                'type'       => 'get',
                'name'       => $sName,
                'break'      => false
            )
        ));
        $this->___agent->event->notify($oEvent);

        if (!$oEvent->break && !isset($oEvent->val)) {
            $oEvent->val = $this->___agent->subject->{$oEvent->name};
        }
        
        return $oEvent->val;
    }

    public function  __call($sName,  $aArg)
    {
        $oEvent = new f_event(array(
            'subject' => $this->___agent->subject,
            'id'      => $this->___agent->id,
            'param'   => array(
                'type'       => 'call',
                'name'       => $sName,
                'arg'        => $aArg,
                'break'      => false,
            )
        ));
        $this->___agent->event->notify($oEvent);

        if (!$oEvent->break && !isset($oEvent->val)) {
            $oEvent->val = $this->___agent->subject->{$oEvent->name}();
        }

        return $oEvent->val;
    }

}