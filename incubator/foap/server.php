<?php

class f_foap_server
{
    
    protected $_eventId;
    protected $_eventDispacher;

    public function eventId($sEventId = null)
    {

    }

    public function eventDispacher($oEventDispacher = null)
    {

    }

    public function __get($sName)
    {
        switch ($sName) {
            case 'foap':
                return $this->foap = f_foap_protocol::unserialize($_POST['foap']);
        }
    }

    public function handle()
    {
        
    }

}