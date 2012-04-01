<?php

/** @todo */

class f_c_request
{
    
    protected $_param = array();

    public function __isset($sKey)
    {
        switch (true) {
            case isset($this->_param[$sKey]):
                return true;
            case isset($_GET[$sKey]):
                return true;
            case isset($_POST[$sKey]):
                return true;
            default:
                return false;
        }
    }
    
    public function __get($sKey)
    {
        $this->param($sKey);
    }
    
    public function __set($sKey, $mValue)
    {
        $this->param($sKey, $mValue);
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    public function isPut()
    {
        return $_SERVER['REQUEST_METHOD'] == 'PUT';
    }

    public function isDelete()
    {
        return $_SERVER['REQUEST_METHOD'] == 'DELETE';
    }

    public function isHead()
    {
        return $_SERVER['REQUEST_METHOD'] == 'HEAD';
    }

    public function isOptions()
    {
        return $_SERVER['REQUEST_METHOD'] == 'OPTIONS';
    }
    
    public function isAjax()
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function isFlash()
    {
        return strstr(strtolower($_SERVER['USER_AGENT']), ' flash');
    }

    public function isHttps()
    {
        return $_SERVER['HTTPS'] == 'on';
    }
    
    public function ip($bCheckProxy = true)
    {
        if ($bCheckProxy && isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if ($bCheckProxy && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return end(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        return $_SERVER['REMOTE_ADDR'];
    }
    
    public function bodyRaw()
    {
        $body = file_get_contents('php://input');
        return strlen(trim($body)) > 0 ? $body : false;
    }

    public function header($header)
    {

        // Try to get it from the $_SERVER array first
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        // This seems to be the only way to get the Authorization header on
        // Apache
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }

        return false;
    }

    public function scheme()
    {
        return ($_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
    }

    public function get($sKey)
    {
        return $_GET[$sKey];
    }

    public function post($sKey)
    {
        return $_POST[$sKey];
    }

    public function cookie($sKey)
    {
        return $_COOKIE[$sKey];
    }

    public function server($sKey)
    {
        return $_SERVER[$sKey];
    }

    public function env($sKey)
    {
        return $_ENV[$sKey];
    }

    public function param($sKey, $mValue = null)
    {
        if (func_num_args() == 2) {
            $this->_param[$sKey] = $mValue;
        }
        else {
            
            switch (true) {
                case isset($this->_param[$sKey]):
                    return $this->_param[$sKey];
                case isset($_GET[$sKey]):
                    return $_GET[$sKey];
                case isset($_POST[$sKey]):
                    return $_POST[$sKey];
                default:
                    return null;
            }     
            
        }
    }
    
    public function uri()
    {
        
    }

    public function uriBase()
    {
        
    }
    
    public function uriAbs()
    {
        
    }
    
    public function path()
    {
        
    }


}