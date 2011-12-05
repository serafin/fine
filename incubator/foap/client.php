<?php

class f_foap_client
{

    protected $_config = array(
        'uri'     => '',
        'version' => '1',
    );
    protected $_http;

    public function  __construct($aConfig = array())
    {
        foreach ($aConfig as $k => $v) {
            $this->_config[$k] = $v;
        }
    }

    public function __call($sMethod, $aArg)
    {
        $this->_call($sMethod, $aArg);
    }

    protected function _call($sMethod, $aArg)
    {
        if ($this->_http === null) {
            $this->_http = new f_http_client(array(
                'uri'    => $this->_config['uri'],
                'method' => f_http_client::METHOD_POST
            ));
        }

        $this->_http
            ->post('foap', f_foap_format::serialize(array('fopa' => array(
                'head' => $this->_config,
                'body' => array('action' => $sMethod, 'args' => $aArg)
            ))))
            ->request()
        ;

        if (! $this->_http->response->isSuccess()) {
            $this->error = $this->_http->response->code();
            return;
        }

        $aResponse = f_foap_format::unserialize($this->_http->response->body());

        // if head...

        return $aResponse['body'];
    }

    

}