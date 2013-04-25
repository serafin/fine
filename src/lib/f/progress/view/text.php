<?php

/**
 * Text plain progress bar  
 * 
 * output:
 * |           25%|           50%|           75%|          100%|
 * |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 */

class f_progress_view_text implements f_di_asNew_interface
{

    /* style and additional texts */
    
    protected $_title;
    protected $_width = 60;
    protected $_done  = '';

    
    
    protected $_rendered = false;
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

    public function update(f_progress_interface $progress)
    {
        
        // label
        
        if ($this->_rendered === false) {
            
            $this->_rendered = true;

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

            ob_flush();
            flush();

        }
        
        // bar
        
        $bar = floor($progress->get() * $this->_width / $progress->all());

        
        if ($bar > $this->_send) {

            echo str_repeat('|', $bar - $this->_send);
            $this->_send = $bar;

            if ($this->_send == $this->_width) {

                if (strlen($this->_done)) {
                    echo ' ' . $this->_done;
                }

                echo "\n";
            }

            ob_flush();
            flush();
        }

        
    }

}