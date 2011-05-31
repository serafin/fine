<?php

class f_event_dispacher
{

    protected $_listener = array();

    public function on($asEventId, $kListener)
    {
        if (! is_array($asEventId)) {
            $asEventId = array($asEventId);
        }
        foreach ($asEventId as $i) {
            $this->_listener[$i][] = $kListener;
        }
    }

    public function is($sEventId)
    {
        return isset($this->_listener[$sEventId]);
    }

    public function run($oEvent)
    {
        $id = $oEvent->id();
        if (isset ($this->_listener[$id])) {
            foreach ($this->_listener[$id] as $i) {
                call_user_func($i, $oEvent);
            }
        }
    }

    public function runSafe($oEvent)
    {
        $id = $oEvent->id();
        if (isset($this->_listener[$id])) {
            
            $aListener = $this->_listener[$id];
            unset($this->_listener[$id]);

            foreach ($aListener as $i) {
                call_user_func($i, $aoEvent);
            }
            
            $this->_listener[$id] = $aListener;
        }
    }

    public function remove($sEventId, $kListener = null)
    {
        if ($kListener === null) {
            unset($this->_listener[$sEventId]);
        }
        else {
            if (isset($this->_listener[$sEventId])) {
                foreach ($this->_listener[$sEventId] as $k => $v) {
                    if ($kListener === $v) {
                        unset($this->_listener[$sEventId][$k]);
                    }
                }
            }
            if (empty($this->_listener[$sEventId])) {
                unset($this->_listener[$sEventId]);
            }
        }
    }

}