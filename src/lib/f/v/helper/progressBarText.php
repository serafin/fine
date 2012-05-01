<?php

/**
 * Text plain progress bar  
 * 
 * example:
 *      $progress = new f_v_helper_progressBarText();
 *      $progress->end(300);
 *      $progress->render();
 *      for ($i = 0; $i < 300; $i++) {
 *          $progress->up();
 *          usleep(10000);
 *      }
 * 
 * output:
 * |           25%|           50%|           75%|          100%|
 * |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 */

class f_v_helper_progressBarText implements f_di_asNew_interface
{


    protected $_title;
    protected $_width = 60;
    protected $_done  = '';
    protected $_end   = 1;

    protected $_progress = 0;
    protected $_send     = 0;

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

    public function end($iNumber = null)
    {
        if (func_num_args() == 0) {
            return $this->_end;
        }
        $this->_end = $iNumber;
        return $this;
    }

    public function title($sTitle = null)
    {
        if (func_num_args() == 0) {
            return $this->_title;
        }
        $this->_title = $sTitle;
        return $this;
    }

    public function done($sDoneText = null)
    {
        if (func_num_args() == 0) {
            return $this->_done;
        }
        $this->_done = $sDoneText;
        return $this;
    }

    public function width($iWidth = null)
    {
        if (func_num_args() == 0) {
            return $this->_width;
        }
        $this->_width = $iWidth;
        return $this;
    }


    public function render()
    {
        $survey = (int)($this->_width/4);
        if ($survey < 5) {
            $survey = 5;
        }

        $this->_width = $survey * 4;

        // first line

        if (strlen($this->_title) > 0) {
            echo $this->_title . ' ';
        }

        echo '|';

        echo str_pad('25%|', $survey, ' ', STR_PAD_LEFT);
        echo str_pad('50%|', $survey, ' ', STR_PAD_LEFT);
        echo str_pad('75%|', $survey, ' ', STR_PAD_LEFT);
        echo str_pad('100%|', $survey, ' ', STR_PAD_LEFT);

        echo "\n";

        // second line

        if (strlen($this->_title) > 0) {
            echo str_repeat(' ', strlen($this->_title)) . ' ';
        }
        echo '|';

        flush();

        return $this;
    }

    public function set($iNumber)
    {
        if ($iNumber <= $this->_progress) {
            return $this;
        }
        $this->_progress = $iNumber;

        $this->_bar();

        return $this;;
    }

    public function up($iNumber = 1)
    {
        $this->_progress += $iNumber;

        $this->_bar();

        return $this;
    }

    protected function _bar()
    {

        $bar = floor($this->_progress * $this->_width / $this->_end);

        if ($bar > $this->_send) {

            echo str_repeat('|', $bar - $this->_send);
            $this->_send = $bar;

            if ($this->_send == $this->_width) {

                if (strlen($this->_done)) {
                    echo ' ' . $this->_done;
                }

                echo "\n";
            }

            flush();
        }

    }

}