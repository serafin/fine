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
              . "body {background:#222;margin:0; padding:0; font:14px courier new, monospace; color:#000;} "
              . ".box-f_error { color:#f00;padding:50px; }"
              . ".box-f_error .f_error-head { color:#f00; font-family:arial,helvetica,tahoma;font-size:100px; padding:0 20px 20px 20px;}"
              . ".box-f_error table { width:100%;border-collapse:collapse; }"
              . ".box-f_error th { padding: 10px 15px 10px 15px;color:#888;vertical-align:top; font-size:18px;font-weight:normal;vertical-align:top;text-align:right; line-height:100%;}"
              . ".box-f_error td { padding: 10px 15px 10px 15px;color:#eee;vertical-align:top; font-size:18px; font-weight:normal;vertical-align:top; line-height:100%;}"
              . ".box-f_error td th { padding: 5px 10px 20px 0px;font-size:14px;}"
              . ".box-f_error td td { padding: 5px 10px 20px 0px;font-size:14px;color:#888;}"
              . ".box-f_error .f_error-trace { background:#111; padding:10px; font-size:16px;margin:30px 0 10px 0; border-radius:10px;}"
              . ".box-f_debug { padding:0 5px 0; color:#eee; font-size:14px; line-height:100%; margin:0; font-family:courier new; }"
              . ".box-f_debug .f_debug-highlight { background:#2f2f2f; border-radius:5px;}"
              . ".box-f_debug .f_debug-number { color:#666; }"
              . ".box-f_debug .f_debug-line { display:block; line-height:100%; color:#eee; padding:3px 0;}"
              . "</style>"
        ;
        $view .= "<div class=\"box-f_error\"><div class=\"f_error-head\">:(</div><table>";
        
        foreach ((array)$this->exception as $k => $v) {
            if ($k == 'trace') {
                continue;
            }
            $view .= "<tr><th>".$k."</th><td>" . $v . "</td></tr>";
        }
        $view .= "<tr><th>Source</th><td>"
               . f_debug::source($this->exception->file, $this->exception->line) . "</td></tr>";

        $view .= "<tr><td colspan=\"2\">";
        foreach ($this->exception->trace as $k => $v) {
            $view .= "<div class=\"f_error-trace\">[$k] "
                   . "" . $v['class'] . $v['type'] . $v['function']
                   . "(" . $this->_traceArgs($v['args']) .")"
                   . " " . $v['file'] . ":" . $v['line']
                   . "</div>"
                   . f_debug::source($v['file'], $v['line'])
                   . "";
        }
        $view .= "<div class=\"f_error-trace\">[".(++$k)."]</div> {main}</td></tr>";
        $view .= "<tr><td colspan=\"2\" style=\"color:#2f2f2f;text-align:right;font:26px cursive;\">Fine ".f::VERSION."</td></tr>";
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