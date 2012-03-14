<?php

class f_test_unit
{

    private $_testMethod;
    private $_test;

    public function  __construct()
    {
        error_reporting(E_ALL^E_NOTICE);
        foreach (get_class_methods($this) as $method) {
            if ($method[0] == '_') {
                continue;
            }
            $this->_test[$this->_testMethod = $method] = array();
            $this->{$method}();
        }

        $stat = array('ok' => 0, 'error' => 0, 'all' => 0);
        foreach ((array)$this->_test as $sMethod => $aTest) {
            foreach ($aTest as $test) {
                $stat[$test['test']?'ok':'error']++;
                $stat['all']++;
            }
        }

        $name = get_class($this);
        
        if ($stat['error'] == 0) {
            echo "\033[1;37m\033[42m$name all: {$stat['all']}, ok: {$stat['ok']}, error: {$stat['error']}\033[0m\n";
        }
        else {
            echo "\033[1;37m\033[41m$name all: {$stat['all']}, ok: {$stat['ok']}, error: {$stat['error']}\033[0m\n\n";
        }

        if ($stat['error']) {
            foreach ((array)$this->_test as $sMethod => $aTest) {
                foreach ($aTest as $k => $test) {
                    if ($test['test']) {
                        continue;
                    }

                    echo "\033[0;37m\033[41m#\033[0m$sMethod";
                    
                    
                    if ($k != 0) {
                        echo "#$k";
                    }
                    if (strlen($test['info'])) {
                        echo "#{$test['info']}";
                    }
                    
                    echo "\n{$test['error']}\n\n";
                    
                    
                }
            }
        }

        echo $return;
        
        
    }

    protected function _test($bTest, $sInfo, $sError, $bEscapeError = true)
    {
        $this->_test[$this->_testMethod][] = array(
            'test'   => $bTest,
            'info'   => htmlspecialchars($sInfo),
            'error'  => $sError,
            'escape' => $bEscapeError,
            'trace'  => $bTest ? null : debug_backtrace()
        );
    }

    protected function _testEqual($mArg1, $mArg2, $sInfo = null)
    {
        $s = (mb_strlen($mArg1) + mb_strlen($mArg2) > 45) ? "\n" : " ";
//        list ($mArg1, $mArg2) = $this->_testCompare(
//                                    htmlspecialchars($mArg1),
//                                    htmlspecialchars($mArg2));
        $this->_test($mArg1 == $mArg2, $sInfo, "$mArg1$s!=$s$mArg2", false);
    }

    protected function _testNotEqual($mArg1, $mArg2, $sInfo = null)
    {
        $this->_test($mArg1 != $mArg2, $sInfo, "$mArg1==$mArg2");
    }

    protected function _testSame($mArg1, $mArg2, $sInfo = null)
    {
        $this->_test($mArg1 === $mArg2, $sInfo, "$mArg1\n!==\n$mArg2");
    }

    protected function _testNotSame($mArg1, $mArg2, $sInfo = null)
    {
        $this->_test($mArg1 !== $mArg2, $sInfo, "$mArg1\n===\n$mArg2");
    }

    protected function _testTrue($mArg, $sInfo = null)
    {
        $this->_test($mArg === true, $sInfo, "type: ".gettype($mArg) ." !== true");
    }

    protected function _testFalse($mArg, $sInfo = null)
    {
        $this->_test($mArg === false, $sInfo, "type: ".gettype($mArg) ." !== false");
    }

    protected function _testNull($mArg, $sInfo = null)
    {
        $this->_test($mArg === null, $sInfo, "type: ".gettype($mArg) ." !== null");
    }

    protected function _testNotTrue($mArg, $sInfo = null)
    {
        $this->_test($mArg !== true, $sInfo, "type: ".gettype($mArg) ." === true");
    }

    protected function _testNotFalse($mArg, $sInfo = null)
    {
        $this->_test($mArg !== false, $sInfo, "type: ".gettype($mArg) ." === false");
    }

    protected function _testNotNull($mArg, $sInfo = null)
    {
        $this->_test($mArg !== null, $sInfo, "type: ".gettype($mArg) ." === null");
    }

    private function _testCompare($s1, $s2)
    {
        $s1len  = strlen($s1);
        $s2len  = strlen($s2);
        $minlen = min(array($s1len, $s2len));

        for ($i = 0; $i < $minlen && $s1[$i] === $s2[$i]; $i++);
        $s1start = $s2start = $i;

        $s1r = strrev($s1);
        $s2r = strrev($s2);
        for ($i = 0; $i < $minlen && $s1r[$i] === $s2r[$i]; $i++);

        $s1stop = $s1len - $i;
        $s2stop = $s2len - $i;


        $stylePre = '<span style="background-color:#eee;">';
        $stylePost   = '</span>';
        $styleStart = '<span style="background-color:yellow;">';
        $styleStop   = '</span>';

        return array(
            $stylePre . substr($s1, 0, $s1start) . $styleStart . substr($s1, $s1start, ($s1stop-$s1start>0?$s1stop-$s1start:0))  . $styleStop . substr($s1, $s1stop) . $stylePost,
            $stylePre . substr($s2, 0, $s2start) . $styleStart . substr($s2, $s2start, ($s2stop-$s2start>0?$s2stop-$s2start:0))  . $styleStop . substr($s2, $s2stop) . $stylePost
        );
        
    }


}