<?php

class f_foap_client
{
    
    protected $_uri;
    protected $_param;
    protected $_object;


    public function uri($sUri = null)
    {
        if (func_num_args() == 0) {
            return $this->_uri;
        }
        $this->_uri = $sUri;
        return $this;
    }
    
    public function param($asKey = null, $sValue = null)
    {
        switch (func_num_args()) {

            case 0:
                // get all params
                return $this->_param;

            case 1:
                // set params from array
                if (is_array($asKey)) {
                    foreach ($asKey as $k => $v) {
                        if (is_int($k)) {
                            $this->_param[] = $v;
                        }
                        else {
                            $this->_param[$k] = $v;
                        }
                    }
                    return $this;
                }

                // get param by key
                return $this->_param[$asKey];

            case 2:
                // set param
                $this->_param[$asKey] = $sValue;
                return $this;
        }

    }
    
    /**
     * 
     * @return f_foap_client_object
     */
    public function object()
    {
        if ($this->_object == null) {
            $this->_object = new f_foap_client_object(array('_client' => $this));
        }
        
        return $this->_object;
    }
    
    public function call($sName, $aArguments)
    {
        
        $request = json_encode(array(
            'head' => array(
                'foap'  => '1',
                'type'  => 'request',
                'param' => $this->_param,
            ),
            'body' => array(
                'method' => $sName,
                'arg'    => $aArguments
            ),
        ));
        
        $uri = f_c_helper_uri::parse($this->_uri);
        
        $r = fsockopen($uri['host'], $uri['scheme'] == 'https' ? 443 : 80);

        fwrite($r, 
            "POST {$uri['path']} HTTP/1.1\r\n"
            . "Host: {$uri['host']}\r\n"
            . "Content-Type: application/x-www-form-urlencoded\r\n"
            . "Content-Length: " . mb_strlen($request) . "\r\n"
            . "Connection: close\r\n"
            . "\r\n"
            . $request);

        $response = "";
        while (!feof($r)) {
            $response .= fgets($r, 1024);
        }
        
        fclose($r);
        
        $body = '';
        list(,$body) = explode("\r\n\r\n", $response);
        $body = json_decode($body);
        
        return $body->body;
        
    }
}