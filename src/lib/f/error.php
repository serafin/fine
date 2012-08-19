<?php

class f_error
{
    
    const RENDER_FORMAT_HTML = 'RENDER_FORAMT_HTML';
    const RENDER_FORMAT_TEXT = 'RENDER_FORAMT_TEXT';

    public static $phpError = array(
        E_ERROR              => 'Fatal Error',
        E_USER_ERROR         => 'User Error',
        E_PARSE              => 'Parse Error',
        E_WARNING            => 'Warning',
        E_USER_WARNING       => 'User Warning',
        E_STRICT             => 'Strict',
        E_NOTICE             => 'Notice',
        E_RECOVERABLE_ERROR  => 'Recoverable Error',
    );

    /**
     * @var string Error or exception message
     */
    public $msg;
    
    /**
     * @var int Exception code, default 0
     */
    public $code;
    
    /**
     * @var string File
     */
    public $file;
    
    /**
     * @var int Line
     */
    public $line;
    
    /**
     * @var int|string PHP Error code or name
     */
    public $errorType;
    
    /**
     * @var array Error or Exception stack trace
     */
    public $trace;
    
    /**
     * @var Exception 
     */
    public $exception;
    
    protected $_throwError;
    protected $_renderFormat = self::RENDER_FORMAT_HTML;

    public static function _()
    {
        return new self;
    }

    public function  __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function register()
    {
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
    }

    public function render($bRenderErrorAndException = null)
    {
        if ($bRenderErrorAndException === null) {
            return ini_get('display_errors');
        }
        ini_set('display_errors', $bRenderErrorAndException);
        return $this;
    }

    public function level($iErrorReportingLevel = null)
    {
        if ($iErrorReportingLevel === null) {
            return ini_get('error_reporting');
        }
        ini_set('error_reporting', $iErrorReportingLevel);
        return $this;
    }

    public function log($bLogErrors = null)
    {
        if ($bLogErrors === null) {
            return ini_get('log_errors');
        }
        ini_set('log_errors', $bLogErrors);
        return $this;
    }

    public function throwError($iLevel = null)
    {
        if ($iLevel === null) {
            return $this->_throwError;
        }
        $this->_throwError = $iLevel;
        return $this;
    }

    public function renderFormat($tRenderFormat = null)
    {
        if ($tRenderFormat === null) {
            return $this->_renderFormat;
        }
        $this->_renderFormat = $tRenderFormat;
        return $this;
    }

    public function handleError($no, $str, $file, $line)
    {
        if (! ($no & $this->level())) {
            return false;
        }

        if ($no & $this->_throwError) {
            throw new ErrorException($str, 0, $no, $file, $line);
        }
        else {

            $this->_clear();
            
            $this->msg       = $str;
            $this->code      = $no;
            $this->file      = $file;
            $this->line      = $line;
            $this->errorType = self::$phpError[$no];
            $this->trace     = debug_backtrace();
            array_shift($this->trace); // remove trace for this fuction call

            $this->onError();

            if ($this->log()) {
                error_log($this->_renderErrorAsString());
            }

            if ($this->render()) {
                $this->_renderError();
            }
            
            return true;
        }
    }

    public function handleException($exception)
    {

        $this->_clear();
        
        try {
            $this->exception = $exception;
            $this->msg       = $exception->getMessage();
            $this->code      = $exception->getCode();
            $this->file      = $exception->getFile();
            $this->line      = $exception->getLine();
            $this->trace     = $exception->getTrace();

            if ($this->exception instanceof ErrorException) {
                array_shift($this->trace); // removing handler call
            }

            $oControllerError = new c_error();
            $oControllerError->error();
            
            $this->onException();
            if ($this->log()) {
                error_log($this->_renderExceptionAsString());
            }
            if ($this->render()) {
                $this->_renderException();
            }
        }
        catch (Exception $e) {
            if ($this->log()) {
                error_log((string)$e);
            }

            if ($this->render()) {
                echo $e;
            }
        }
    }

    public function onError()
    {
        $this->_filterConfidentialData();
    }

    public function onException()
    {
        $this->_filterConfidentialData();
    }

    protected function _filterConfidentialData()
    {
        $sConnectMsg = "mysql_connect(): Access denied for user ";

        if (strncmp($this->msg, $sConnectMsg, strlen($sConnectMsg)) == 0) {
            $trace =& $this->trace;
            foreach ((array)$trace as $t_k => $t_v) {
                foreach ((array)$t_v['args'] as $k => $v) {
                    $trace[$t_k]['args'][$k] = "***";
                }
            }
        }
    }

    protected function _renderException()
    {
        if ($this->_renderFormat == self::RENDER_FORMAT_TEXT) {
            $this->_renderExceptionAsString();
            return;
        }

        if (! headers_sent()) {
            header('Content-Type: text/html; charset=utf-8', TRUE, 500);
        }

        $oView = new f_v();
        $oView->exception = $this->exception;
        $oView->msg       = $this->msg;
        $oView->code      = $this->code;
        $oView->file      = $this->file;
        $oView->line      = $this->line;
        $oView->trace     = $this->trace;
        $oView->error     = $this;
        echo $oView->renderPath('./lib/f/error/exception.view');


    }

    protected function _renderError()
    {
        echo $this->_renderErrorAsString();
    }

    protected function _renderExceptionAsString()
    {
        return $this->_formatAsString(
            get_class($this->exception), $this->code, $this->msg,
            $this->file,                 $this->line, $this->trace
        );
    }

    protected function _renderErrorAsString()
    {
        return $this->_formatAsString(
            $this->errorType, $this->code, $this->msg, 
            $this->file,      $this->line, $this->trace
        );
    }

    protected function _formatAsString($type, $code, $msg, $file, $line, $trace)
    {
        $return = "\n#$type $code $msg $file:$line";

        foreach ($trace as $k => $v) {

            $return .= "\n#$k " . $v['class'] . $v['type'] . $v['function']
                     . "(" . f_debug::dumpFunctionArgs($v['args']) .")"
                     . " " .$v['file'] . ":" . $v['line'];

            ;
        }
        $return .= "\n#".(++$k)." {main}\n";

        return $return;
    }

    protected function _clear()
    {
        $this->msg             = null;
        $this->code            = null;
        $this->file            = null;
        $this->line            = null;
        $this->type            = null;
        $this->error           = null;
        $this->exception       = null;
    }

}