<?php

class f_test
{

    private $_testError  = 0;
    private $_testOk     = 0;
    private $_testOffset = 0;
    private $_testMethod = '';

    public function  __construct()
    {
        /* time start */
        list($usec, $sec) = explode(" ", microtime());
        $timeStart = ((float)$usec + (float)$sec);

        /* header */
        echo "#" . get_class($this) . "\n";

        /* run all object tests */
        foreach (get_class_methods($this) as $method) {
            if ($method[0] == '_') {
                continue;
            }
            $this->_testMethod = $method;
            $this->_testOffset = 0;
            $this->{$method}();
        }

        /* time stop */
        list($usec, $sec) = explode(" ", microtime());
        $timeStop = ((float)$usec + (float)$sec);

        /* stauts */
        echo "error: {$this->_testError}; "
           . "ok: {$this->_testOk}; "
           . "time: " . round($timeStop-$timeStart, 4) ."s; "
           . "memory: " . round(memory_get_usage(true)/(1024*1024), 2). "MB; "
           . "\n";
    }

    protected function _test($bTest, $sInfo, $sError)
    {
        
        $this->_testOffset++;
        
        if ($bTest) {
            $this->_testOk++;
            return;
        }
        
        $this->_testError++;

        echo  "#" . get_class($this)
            . "#{$this->_testMethod}"
            . "#{$this->_testOffset}"
            . "#{$sInfo}\n"
            . "#$sError\n";


    }

    protected function _testEqual($mArg1, $mArg2, $sInfo = null)
    {
        $s = (mb_strlen($mArg1) + mb_strlen($mArg2) > 45) ? "\n" : " ";
        $this->_test($mArg1 == $mArg2, $sInfo, "$mArg1$s!=$s$mArg2");
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

}

