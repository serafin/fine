<?php

class nap
{

    protected $_action    = array();
    protected $_uriScheme = 'nap';
    protected $_uriDomain = 'application';
    protected $_htmlId    = 'x-nap';

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /* config */

    public function uriScheme($sUriScheme = null)
    {
        if (func_num_args() == 0) {
            return $this->_uriScheme;
        }
        $this->_uriScheme = $sUriScheme;
        return  $this;
    }

    public function uriDomain($sUriDomain = null)
    {
        if (func_num_args() == 0) {
            return $this->_uriDomain;
        }
        $this->_uriDomain = $sUriDomain;
        return  $this;
    }

    public function htmlId($sHtmlId = null)
    {
        if (func_num_args() == 0) {
            return $this->_htmlId;
        }
        $this->_htmlId = $sHtmlId;
        return  $this;
    }

    /* action */
    
    public function action($sName, $aParam = null)
    {
        $this->_action[] = array('action' => $sName, 'param' => $aParam);
    }

    /* render */

    public function renderRaw()
    {
        return $this->_action;
    }

    public function renderUri()
    {
        return $this->_uriScheme . '://'  . $this->_uriDomain . '/' . json_encode($this->_action);
    }

    public function renderHtml()
    {
        return '<input type="hidden" id="' . $this->_htmlId . '" value="'
             . htmlspecialchars(json_encode($this->_action))
             . '" />';
    }

    /* custom actions */

    public function http($sUri, $sTitle)
    {
        $this->action('http', array('uri' => $sUri, 'title' => $sTitle));
    }

    public function webjson($sUri, $sTitle)
    {
        $this->action('webjson', array('uri' => $sUri, 'title' => $sTitle));
    }

    public function map($sTitle, $fLat, $fLng)
    {
        $this->action('map', array('title' => $sTitle, 'lat' => $fLat, 'lng' => $fLng));
    }

    public function back()
    {
        $this->action('back');
    }

    public function backAndRefresh()
    {
        $this->action('backandrefresh');
    }

    public function alert($sTitle, $sText, $sButtonText)
    {
        $this->action('map', array('title' => $sTitle, 'text' => $sText, 'button' => $sButtonText));

    }

    public function auth($sAuth)
    {
        $this->action('auth', array('auth' => $sAuth));
    }

    public function logout()
    {
        $this->action('logout');
    }

}