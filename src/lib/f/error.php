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
            array_shift($trace); // remove trace for this fuction call

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
        $view = '<style type="text/css">'
              . 'body {margin:0; parring:0;}'
              . '.box-f_error { color:#f00;padding:50px 20px;background:#222;margin:0;  font:14px courier new, monospace; color:#000; }'
              . '.box-f_error .f_error-head { color:#f00; font-family:arial,helvetica,tahoma;font-size:100px; padding:0 20px 20px 20px;}'
              . '.box-f_error .f_error-foot { color:#3f3f3f;text-align:right;font:28px cursive;padding:0 20px 0 0;}'
              . '.box-f_error .f_error-table { width:100%;border-collapse:collapse; }'
              . '.box-f_error .f_error-th { padding: 10px 15px 10px 15px;color:#888;vertical-align:top; font-size:18px;font-weight:normal;vertical-align:top;text-align:right; line-height:100%;}'
              . '.box-f_error .f_error-td { padding: 10px 15px 10px 15px;color:#eee;vertical-align:top; font-size:18px; font-weight:normal;vertical-align:top; line-height:100%;}'
              . '.box-f_error .f_error-td .f_error-th { padding: 5px 10px 20px 0px;font-size:14px;}'
              . '.box-f_error .f_error-td .f_error-td { padding: 5px 10px 20px 0px;font-size:14px;color:#888;}'
              . '.box-f_error .f_error-trace { background:#111; padding:10px; font-size:16px;margin:30px 0 10px 0; border-radius:10px;}'
              . '.box-f_debug { padding:0 5px 0; color:#eee; font-size:14px; line-height:100%; margin:0; font-family:courier new; }'
              . '.box-f_debug .f_debug-highlight { background:#2f2f2f; border-radius:5px;}'
              . '.box-f_debug .f_debug-number { color:#666; }'
              . '.box-f_debug .f_debug-line { display:block; line-height:100%; color:#eee; padding:3px 0;}'
              . '</style>'
        ;
        $view .= '<div class="box-f_error"><div class="f_error-head">:(</div><table>';
        $view .= '<tr><th class="f_error-th">Exception</th><td class="f_error-td">' . get_class($this->exception) . '</td></tr>';
        $view .= '<tr><th class="f_error-th">Code     </th><td class="f_error-td">' . $this->code . '</td></tr>';
        $view .= '<tr><th class="f_error-th">Message  </th><td class="f_error-td">' . $this->msg . '</td></tr>';
        $view .= '<tr><th class="f_error-th">File     </th><td class="f_error-td">' . $this->file . '</td></tr>';
        $view .= '<tr><th class="f_error-th">Line     </th><td class="f_error-td">' . $this->line . '</td></tr>';
        $view .= '<tr><th class="f_error-th">Source   </th><td class="f_error-td">' . f_debug::source($this->file, $this->line) . '</td></tr>';
        
        $view .= '<tr><td colspan="2"  class="f_error-td">';
        foreach ($this->trace as $k => $v) {
            $view .= '<div class="f_error-trace">#' . $k . ' '
                   . $v['class'] . $v['type'] . $v['function']
                   . '(' . htmlspecialchars($this->_traceArgs($v['args'])) .')'
                   . ' ' . $v['file'] . ':' . $v['line']
                   . '</div>'
                   . f_debug::source($v['file'], $v['line'])
                   . '';
        }
        $view .= '<div class="f_error-trace">#'.(++$k).'</div> {main}</td></tr>';
        $view .= '<tr><td colspan="2" class="f_error-foot">Fine '.f::VERSION.'</td></tr>';
        $view .= '</table>';
        $view .= '</td></tr></table></div>';

        echo $view;
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
                     . "(" . $this->_traceArgs($v['args']) .")"
                     . " " .$v['file'] . ":" . $v['line'];

            ;
        }
        $return .= "\n#".(++$k)." {main}\n";

        return $return;
    }

    protected function _traceArgs($aArgs, $depth = 2, $limit = 3, $keys = false)
    {
        
        if (! $aArgs) {
            return '';
        }
        
        $i = 0;
        
        $return = '';
        
        foreach ($aArgs as $k => $v) {
            
            if ($i != 0) {
                $return .= ', ';
            }
            if ($i == $limit) {
                $return .= '...';
                break;
            }
            
            if ($keys) {
                $return .= "$k => ";
            }
            
            
            if (is_object($v)) {
                $return .= get_class($argValue);
            }
            else if (is_array($v)) {
                if ($depth) {
                    $return .= '['. $this->_traceArgs($v, --$depth, $limit, true) . ']';
                }
                else {
                    $return .= '[...]';
                }
            }
            else {
                $v = (string)$v;
                if (strlen($v) > 20) {
                    $v = substr($v, 0, 20) . '...';
                }
                    
                $return .= $v;
            }
            
            $i++;
        }
        
        return $return;
    }

    protected function _clear()
    {
        $this->type            = null;
        $this->error           = null;
        $this->exception       = null;
        $this->exceptionObject = null;
    }

}