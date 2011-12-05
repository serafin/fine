<?php

class f_c_helper_uri
{
    
    public function helper($aUriParams)
    {
        return f::$c->uriBase . $this->assemble($aUriParams);
    }
    
    public function abs($aUriParams)
    {
        return f::$c->uriAbs . $this->assemble($aUriParams);
    }
    
    public function resolveRequestUri()
    {
        list($axledUri) = explode('?', substr($_SERVER['REQUEST_URI'], strlen(f::$c->uriBase)), 2);
        $_GET           = $this->resolve($axledUri) + $_GET;
    }

    public function resolve($sUri)
    {
        $param   = explode('/', $sUri);
        $aUri    = array();
        
        for ($i = 2; isset($param[$i]); $i += 2) {
            $aUri[$param[$i]] = $param[$i+1];
        }
        
        $aUri[0] = $param[0]; // controller
        $aUri[1] = $param[1]; // action
        
        return $aUri;
    }

    public function assemble($aUri)
    {
        $pairs = array(urlencode($aUri[0]) . '/' . urldecode($aUri[1]));
        
        unset ($aUri[0], $aUri[1]);
        
        foreach ($aUri as $key => $value) {
            $pairs[] = urlencode($key) . '/' . urldecode($value);
        }
        
        return implode('/', $pairs);
    }

    
    
    


}