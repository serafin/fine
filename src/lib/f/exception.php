<?php

class f_exception extends Exception
{

    const BAD_FUNCTION_CALL = 'BAD_FUNCTION_CALL';
    const BAD_METHOD_CALL   = 'BAD_METHOD_CALL';
    const DOMAIN            = 'DOMAIN';
    const INVALID_ARGUMENT  = 'INVALID_ARGUMENT';
    const LENGTH            = 'LENGTH';
    const LOGIC             = 'LOGIC';
    const OUT_OF_BOUNDS     = 'OUT_OF_BOUNDS';
    const OUT_OF_RANGE      = 'OUT_OF_RANGE';
    const OVERFLOW          = 'OVERFLOW';
    const RANGE             = 'RANGE';
    const RUNTIME           = 'RUNTIME';
    const UNDERFLOW         = 'UNDERFLOW';
    const UNEXPECTED_VALUE  = 'UNEXPECTED_VALUE';

    /**
     * @var null|Exception
     */
    private $_previous = null;

    /**
     * @var array
     */
    protected $_properties = array();

    /**
     * Construct the exception
     *
     * @param  string $msg
     * @param  int $code
     * @param  Exception $previous
     * @return void
     */
    public function __construct(array $config = array())
    {
        $msg      = $config['msg'];
        $code     = $config['code'];
        $previous = $config['previous'];
        unset($config['msg'], $config['code'], $config['previous']);
        
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            parent::__construct($msg, $code);
            $this->_previous = $previous;
        }
        else {
            parent::__construct($msg, $code, $previous);
        }

        $this->_properties = $config;
    }

    /**
     * Overloading
     *
     * For PHP < 5.3.0, provides access to the getPrevious() method.
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        if ('getprevious' == strtolower($method)) {
            return $this->_getPrevious();
        }
        return null;
    }

    /**
     * String representation of the exception
     *
     * @return string
     */
    public function __toString()
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            if (null !== ($e = $this->getPrevious())) {
                return $e->__toString() . " [Next] " . parent::__toString();
            }
        }
        return parent::__toString();
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * Returns previous Exception
     *
     * @return Exception|null
     */
    protected function _getPrevious()
    {
        return $this->_previous;
    }
}
