<?php

interface f_longPolling_interface
{

    /**
     * Parametry
     * 
     * @param type $asKey
     * @param type $sVal
     */
    public function param($asKey = null, $sVal = null);
    
    /**
     * Ustala/pobiera maksymalny czas trwania jednego zadania long polling
     * 
     * @param int $iSeconds
     * @return self|int  
     */
    public function limit($iSeconds = null);
    
    /**
     * Ustala/pobiera interval w sekundach, co ile ma byc odpalany callback
     * 
     * @param int $iSeconds
     * @return self|int  
     */
    public function interval($iSeconds = null);
    
    /**
     * Ustala/pobiera interval w microsekundach, co ile ma byc odpalany callback
     * 
     * @param int $iMicroseconds
     * @return self|int  
     */
    public function intervalMicroseconds($iMicroseconds = null);
    
    /**
     * Ustala/pobiera interval w microsekundach, co ile ma byc odpalany callback
     * 
     * @param callback $kCallback
     * @return self|callback  
     */
    public function callback($kCallback = null);
    
    /**
     * Ustala/pobiera obiekt odpowiedzi
     * 
     * Callback musi byc w postaci `myCallback(f_longPolling_interface $handler)`
     * 
     * @param self|f_c_response $response
     * 
     */
    public function response(f_c_response $response = null);
    
    /**
     * Ustala/pobiera flage przerwania wywowylania callback
     * 
     * @param type $bEnd
     */
    public function end($bEnd = null);
    
    /**
     * Uruchamia glowne sterowanie long polling z wykorzystaniem callback i intervalu
     */
    public function handle();
    
}
