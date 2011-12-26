<?php

class f_error
{
    
    const ERROR_NOT_FOUND = 'NOT_FOUND';
    const ERROR_NO_ACCESS = 'NO_ACCESS';
    const ERROR_INTERNAL  = 'INTERNAL';

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

    public $type;
    public $error;
    public $exception;
    public $exceptionObject;
    
    protected $_throwError;
    protected $_renderFormat = 'html';

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

    public function helper($sErrorType)
    {
        $this->type       = $sErrorType;
        $oErrorController = new c_error();
        $oErrorController->error();
        exit;
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
            throw new f_error_exception(array(
                'msg'       => $str,
                'file'      => $file,
                'line'      => $line,
                'errorType' => isset(self::$phpError[$no]) ? self::$phpError[$no] : $no,
            ));
        }
        else {

            $this->_clear();

            $trace = debug_backtrace();
            array_shift($trace); // remove trace for this fuction call

            $this->error = (object) array(
                'msg'       => $str,
                'code'      => $no,
                'file'      => $file,
                'line'      => $line,
                'errorType' => self::$phpError[$no],
                'trace'     => $trace
            );
            
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
            $this->exceptionObject  = $exception;
            $this->exception        = new stdClass();
            $properties = array();
            if ($this->exceptionObject instanceof f_exception) {
                $properties = $this->exceptionObject->getProperties();
            }


            $this->exception->exception = get_class($this->exceptionObject);
            if (isset($properties['type'])) {
                $this->exception->type  = $properties['type'];
            }
            $this->exception->msg       = $this->exceptionObject->getMessage();
            $this->exception->code      = $this->exceptionObject->getCode();
            $this->exception->file      = $this->exceptionObject->getFile();
            $this->exception->line      = $this->exceptionObject->getLine();
            $this->exception->trace     = $this->_traceArgs($this->exceptionObject->getTrace());
            $previous                   = $this->exceptionObject->getPrevious();
            if (null !== $previous) {
                $this->exception->previous = $previous;
            }

            foreach ($properties as $k => $v) {
                $this->exception->$k = $v;
            }

            if ($this->exceptionObject instanceof f_error_exception) {
                array_shift($this->exception->trace); // removing handler call
            }

            $this->onException();
            if ($this->log()) {
                error_log($this->_renderExceptionAsString());
            }
            if ($this->render()) {
                $this->_renderException();
            }
            else {
                $this->helper(self::ERROR_INTERNAL);
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
        $this->_filterConfidentialData('error');
    }

    public function onException()
    {
        $this->_filterConfidentialData('exception');
    }

    protected function _filterConfidentialData($type)
    {
        $sConnectMsg = "mysql_connect(): Access denied for user ";

        if (strncmp($this->{$type}->msg, $sConnectMsg, strlen($sConnectMsg)) == 0) {
            $trace =& $this->{$type}->trace;
            foreach ((array)$trace as $t_k => $t_v) {
                foreach ((array)$t_v['args'] as $k => $v) {
                    $trace[$t_k]['args'][$k] = "*";
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

        $view = "<style type=\"text/css\">"
              . "body {background:#2C001E;margin:0; patding:0; font:14px courier new, monospace; color:#000;} "
              . ".head { color:#f00; padding:10px 20px 10px 20px;}"
              . ".head td, .head th { color:#fff;vertical-align:top; font-size:16px; font-weight:normal;vertical-align:top; line-height:100%;}"
              . ".head th { color:#9C6A8E; padding-right:5px; line-height:100%; text-align:right;border-right:solid 2px #190011; }"
              . "code .highlight { background:#190011; }"
              . "code .number { color:#9C6A8E; }"
              . "code .line { display:block; line-height:140%; padding:0;}"
              . "code { color:#aaa; font-size:13px; margin:0; line-height:140%; font-family:courier new; }"
              . "pre  { margin:0;}"
              . ".trace {  color:#fff;padding:0 0 5px 0;  font-size:16px; border-bottom:solid 2px #190011; margin-bottom:5px;}"
              . "</style>"
        ;
        $view .= "<div style=\"background:#190011;color:#fff;padding:5px 10px;text-align:right;color:#aaa;font-size:13px;\">Fine ".f::VERSION."</div>";
        $view .= "<div class=\"head\"><table cellspacing=10>";
        foreach ((array)$this->exception as $k => $v) {
            if ($k == 'trace') {
                continue;
            }
            $view .= "<tr><th>".$k."</th><td>" . $v . "</td></tr>";
        }
        $view .= "<tr><th>Source</th><td>"
               . f_debug::source($this->exception->file, $this->exception->line) . "</td></tr>";

        $view .= "<tr><th>Trace</th><td><table cellspacing=10>";
        foreach ($this->exception->trace as $k => $v) {
            $view .= "<tr><th>[$k]</th><td><div class=\"trace\">"
                   . "" . $v['class'] . $v['type'] . $v['function']
                   . "(" . $this->_traceArgs($v['args']) .")"
                   . " " . $v['file'] . ":" . $v['line']
                   . "</div>"
                   . f_debug::source($v['file'], $v['line'])
                   . "</td></tr>";
        }
        $view .= "<tr><th>[".(++$k)."]</th><td><div class=\"trace\"> {main}</div></td></tr>";
        $view .= "</table>";
        $view .= "</td></tr></table></div>";

        echo $view;
    }

    protected function _renderError()
    {
        echo $this->_renderErrorAsString();
    }

    protected function _renderExceptionAsString()
    {
        return $this->_formatAsString(
            $this->exception->exception, $this->exception->type, $this->exception->code,
            $this->exception->msg      , $this->exception->file, $this->exception->line,
            $this->exception->trace
        );
    }

    protected function _renderErrorAsString()
    {
        return $this->_formatAsString(
            $this->error->errorType, $this->error->type, $this->error->code,
            $this->error->msg      , $this->error->file, $this->error->line,
            $this->error->trace
        );
    }

    protected function _formatAsString($type, $subtype, $code, $msg, $file, $line, $trace)
    {
        $return = "\n[$type:$subtype:$code] $msg $file:$line\n";

        foreach ($trace as $k => $v) {

            $return .= " [$k] " . $v['class'] . $v['type'] . $v['function']
                     . "(" . $this->_traceArgs($v['args']) .")"
                     . " " .$v['file'] . ":" . $v['line'];

            ;
        }
        $return .= "[".(++$k)."] {main}\n";

        return $return;
    }

    protected function _traceArgs($aArgs)
    {
        if (! is_array($aArgs)) {
            return array();
        }
        foreach ($aArgs as $argKey => $argValue) {
            if (is_object($argValue)) {
                $aArgs[$argKey] = get_class($argValue);
            }
        }
        return $aArgs;
    }

    protected function _clear()
    {
        $this->type            = null;
        $this->error           = null;
        $this->exception       = null;
        $this->exceptionObject = null;
    }

}