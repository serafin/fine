<?php

class f_debug
{

    const LOG_STYLE_DEFAULT = 'LOG_STYLE_DEFAULT';
    const LOG_STYLE_WARNING = 'LOG_STYLE_WARNING';
    const LOG_STYLE_ERROR   = 'LOG_STYLE_ERROR';
    const LOG_STYLE_DB      = 'LOG_STYLE_DB';
    const LOG_STYLE_SYSTEM  = 'LOG_STYLE_SYSTEM';

    const LOG_TREE_NODE   = 'LOG_TREE_NODE';
    const LOG_TREE_BRANCH = 'LOG_TREE_BRANCH';
    const LOG_TREE_CLOSE  = 'LOG_TREE_CLOSE';

    const LOG_TYPE_NO_DATA    = 'LOG_TYPE_NO_DATA';
    const LOG_TYPE_DUMP       = 'LOG_TYPE_DUMP';
    const LOG_TYPE_VAL        = 'LOG_TYPE_VAL';
    const LOG_TYPE_LIST       = 'LOG_TYPE_LIST';
    const LOG_TYPE_TABLE      = 'LOG_TYPE_TABLE';
    const LOG_TYPE_CODE_PHP   = 'LOG_TYPE_CODE_PHP';
    const LOG_TYPE_CODE_HTML  = 'LOG_TYPE_CODE_HTML';
    const LOG_TYPE_CODE_SQL   = 'LOG_TYPE_CODE_SQL';
    const LOG_TYPE_TEXT_PLAIN = 'LOG_TYPE_TEXT_PLAIN';
    const LOG_TYPE_TEXT_HTML  = 'LOG_TYPE_TEXT_HTML';

    public $log = array();

    protected $_phpPV = array('_COOKIE' => '', '_ENV' => '', '_FILES' => '', '_POST' => '',
                              '_GET' => '', '_REQUEST' => '', '_SERVER' => '', '_SESSION' => '');
    protected $_offset;
    protected $_timer;
    protected $_on;
    protected $_limit = 1000;

    /** @todo remove this */
    public static function source($sFile, $iLine, $iPaddingLines = 5)
    {
        if (!is_readable($sFile)) {
            return false;
        }
        $sFile  = fopen($sFile, 'r');
        $line   = 0;
        $begin  = $iLine - $iPaddingLines;
        $end    = $iLine + $iPaddingLines;
        $format = '% '.strlen($end).'d';
        $source = '';
        while (($row = fgets($sFile)) !== false) {
            if (++$line > $end) {
                break;
            }
            if ($line >= $begin) {
                $row = htmlspecialchars($row, ENT_NOQUOTES, 'utf-8');
                $row = '<span class="f_debug-number">'.sprintf($format, $line).'</span> '.$row;
                if ($line === $iLine) {
                    $row = '<span class="f_debug-line f_debug-highlight">'.$row.'</span>';
                }
                else {
                    $row = '<span class="f_debug-line">'.$row.'</span>';
                }
                $source .= $row;
            }
        }
        fclose($sFile);
        return '<pre class="box-f_debug">'.$source.'</pre>';
    }

    public static function highlightFile($sFile, $sLanguage = null, $iLine = null, $iPaddingLines = null)
    {
        if (!is_readable($sFile)) {
            return false;
        }
        return self::highlight(file_get_contents($sFile), $sLanguage, $iLine, $iPaddingLines);
    }

    public static function highlight($sCode, $sLanguage = null, $iLine = null, $iPaddingLines = null)
    {
        f::$c->bundle->init('geshi');

		$geshi = new GeSHi();
		$geshi->set_language($sLanguage);
		$geshi->enable_keyword_links(false);
		$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
        $geshi->set_line_style(GESHI_NORMAL_LINE_NUMBERS);
        $geshi->set_source((string)$sCode);


        if ($iPaddingLines !== null && $iLine !== null) {

            // cut code
            $start = $iLine - $iPaddingLines;
            if ($start < 0) {
                $start = 0;
            }
            $sCode = implode("\n", array_slice(explode("\n", $sCode), $start, $iPaddingLines * 2));

            $geshi->set_source($sCode);

//            $geshi->start_line_numbers_at($start+1);
//            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS, $iPaddingLines);

            $geshi->set_highlight_lines_extra_style('background:#eee;');
            $geshi->highlight_lines_extra($iPaddingLines);

            $geshi->set_overall_style('font-size:14px;', true);


        }



        return $geshi->parse_code();

    }

    public static function dump($mVar, $sLabel = null, $bEcho = true)
    {
        $label = ($sLabel === null) ? '' : '<span style="color:#666; font-family:monospace;">'.trim($sLabel) . '</span> ';

        $sOutput = '<pre style="background:black;margin:5px 0px 0 0px;color:#0f0;padding:10px;text-align:left;border-radius:5px;">'
                  . $label
                  . htmlspecialchars(self::varDumpPretty($mVar), ENT_QUOTES)
                  . '</pre>';

        if ($bEcho) {
            echo($sOutput);
            return;
        }

        return $sOutput;
    }

    public static function dumpFunctionArgs($aArgs, $iArgMaxLenght = 30, $iArgsLimit = 3)
    {

        if (! $aArgs) {
            return '';
        }

        $return = array();

        for ($i = 0, $end = count($aArgs); $i < $end && $i < $iArgsLimit; $i++) {

            $arg = $aArgs[$i];

            if (is_bool($arg)) {
                $return[] = $arg ? 'true' : 'false';
            }
            else if (is_float($arg) || is_int($arg)) {
                $return[] = $arg;
            }
            else if (is_string($arg)) {
                if (strlen($arg) > $iArgMaxLenght) {
                    $arg = substr($arg, 0, $iArgMaxLenght) . '...';
                }
                $return[] = "'" . $arg . "'";
            }
            else {
                $arg = str_replace("\n", '', self::varDumpPretty($arg));
                if (strlen($arg) > $iArgMaxLenght) {
                    $arg = substr($arg, 0, $iArgMaxLenght) . '...';
                }
                $return[] =  $arg;
            }

        }

        $return = implode(', ', $return);

        return $return;
    }

    public static function varDumpPretty($mVar)
    {
        ob_start();
        var_dump($mVar);
        return preg_replace("/\]\=\>\n(\s+)/m", "] => ", ob_get_clean());
    }

    public static function scalar($mVal)
    {
        if (is_scalar($mVal)) {
            return $mVal;
        }
        return self::varDumpPretty($mVal);
    }

    public function __construct(array $config = array())
    {
        $this->_offset = new f_timer();
        $this->_offset->start();

        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }

    }

    public function init()
    {
        $this->on();
        return $this;
    }

    public function on()
    {
        $this->_on = true;
        return $this;
    }

    public function off()
    {
        $this->_on = false;
        return $this;
    }

    public function limit($iLogLimit = null)
    {
        if (func_num_args() == 0) {
            return $this->_limit;
        }
        $this->_limit = $iLogLimit;
        return $this;
    }

    public function remove()
    {
        $this->log = array();
    }


    /* Log */

    public function timer()
    {
        if (!$this->_timer) {
            $this->_timer = new f_timer();
        }
        return $this->_timer;
    }


    public function log($mData, $sLabel = null, $tType = null, $tStyle = null, $tTree = null)
    {
        if (!$this->_on) {
            return;
        }

        if (count($this->log) >= $this->_limit) {
            return;
        }


        $log = array('data' => $mData, 'label' => $sLabel, 'type' => $tType,
                     'style' => $tStyle, 'tree' => $tTree, 'offset' => $this->_offset->get());
        if ($this->_timer) {
            $log['time'] = $this->_timer->stop()->get();
            $this->_timer = null;
        }

        $log['offset'] = $this->_offset->get();

        $this->log[] = $log;
    }

    public function warn($mData, $sLabel = null, $tType = null)
    {
        $this->log($mData, $sLabel, $tType, self::LOG_STYLE_WARNING);
    }

    public function error($mData, $sLabel = null, $tType = null)
    {
        $this->log($mData, $sLabel, $tType, self::LOG_STYLE_ERROR);
    }

    public function val($mData, $sLabel = null, $tStyle = null)
    {
        $this->log($mData, $sLabel, self::LOG_TYPE_VAL, $tStyle);
    }

    public function table($mData, $sLabel = null, $tStyle = null)
    {
        $this->log($mData, $sLabel, self::LOG_TYPE_TABLE, $tStyle);
    }

    public function enum($mData, $sLabel = null, $tStyle = null)
    {
        $this->log($mData, $sLabel, self::LOG_TYPE_LIST, $tStyle);
    }

    public function show($sViewScriptPath = './lib/f/debug/show.view')
    {
        if (!$this->_on) {
            return;
        }

        $this->phpPredefinedVariablesChange();

        $oView = new f_v();
        $oView->log = $this->log;
        echo $oView->renderPath($sViewScriptPath);
    }

    /* PHP Predefined Variables */

    public function phpPredefinedVariables()
    {
        $this->log(null, '$_GET, $_POST, $_SERVER, ...', self::LOG_TYPE_NO_DATA,
                   self::LOG_STYLE_SYSTEM, self::LOG_TREE_BRANCH);
        foreach ($this->_phpPV as $k => $v) {
            $this->log(self::varDumpPretty($GLOBALS[$k]), '$' . $k, self::LOG_TYPE_CODE_PHP);
            $this->_phpPV[$k] = md5(self::varDumpPretty($GLOBALS[$k]));
        }
        $this->log(null, null, f_debug::LOG_TYPE_NO_DATA, null, f_debug::LOG_TREE_CLOSE);

    }

    public function phpPredefinedVariablesChange()
    {
        reset($this->_phpPV);
        if (strlen(current($this->_phpPV)) == 0) {
            return;
        }

        $diff = array();

        foreach ($this->_phpPV as $k => $v) {
            if ($v == md5(self::varDumpPretty($GLOBALS[$k]))) {
                continue;
            }
            $diff[] = $k;
        }

        if (!$diff) {
            return;
        }

        $this->log(null, '$_GET, $_POST, $_SERVER, ... - Changed', self::LOG_TYPE_NO_DATA,
                   self::LOG_STYLE_SYSTEM, self::LOG_TREE_BRANCH);

        foreach ($diff as $i) {
            $this->log(self::varDumpPretty($GLOBALS[$i]), '$' . $i, self::LOG_TYPE_CODE_PHP);
        }
        $this->log(null, null, self::LOG_TYPE_NO_DATA, null, self::LOG_TREE_CLOSE);
    }



}