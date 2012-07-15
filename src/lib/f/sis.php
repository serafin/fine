<?php

/**
 * # Single Instance Script
 *
 * ## Example:
 *  $sis = new f_sis();
 *  $sis->id('./cron.sis');
 *  if ($sis->begin()) {
 *      // do single instance cron job
 *  }
 *
 * ## Sis begins:
 *      - when `begin` method returns true,
 *      - when method `begin` does not throws f_sis_exception_running (if `throwException` is on).
 * ## Sis ends:
 *      - when php script ends,
 *      - if sis object `__destruct` mehtod is called (eg. unset($sis)),
 *      - on demand using method `end`.
 *
 * ## How it works
 * `PID` (process ID) of current script is stored in file `id` (id method).
 * In next call sis checks if proccess with id equal to `PID` already works.
 *
 * Inspiration: http://blog.crazytje.be/single-instance-php-script/
 *
 * ## Example 2 - using exceptions:
 *  try {
 *      f_sis::_()->id('./cron.sis')->throwException(true)->begin();
 *      // do single instance cron job
 *  }
 *  catch(f_sis_exception_running $e) {
 *  }
 *
 */

class f_sis
{

    protected $_id;
    protected $_cleanup        = false;
    protected $_throwException = false;

    /**
     * Static constructor
     *
     * @param array $config
     * @return f_sis
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->_cleanup) {
            $this->end();
        }
    }

    /**
     * Set/get path to file with pid
     *
     * @param string $sPidFile
     * @return f_sis|string
     */
    public function id($sPidFile = null)
    {
        if (func_num_args() == 0) {
            return $this->_id;
        }
        $this->_id = $sPidFile;
        return $this;
    }

    /**
     * Set/get for throw exception when sis is already running
     *
     * @param boolean $bThrowException
     * @return f_sis|boolean
     */
    public function throwExcetpion($bThrowException = null)
    {
        if (func_num_args() == 0) {
            return $this->_throwException;
        }
        $this->_throwException = $bThrowException;
        return $this;
    }

    /**
     * Begin sis
     * 
     * Sis by id passed in `id` method
     *
     * @throws f_sis_exception_notWritable If pid file is not writable
     * @throws f_sis_exception_running If sis already running
     * @return boolean True on success or false on failure
     */
    public function begin()
    {
        if (file_exists($this->_id)) {
            if (!is_writable($this->_id)) {
                throw new f_sis_exception_notWritable("File `{$this->_id}` not writable");
            }
            $pid = trim(file_get_contents($this->_id));
            if (posix_kill($pid, 0)) {
                if ($this->_isProcessAlive($pid)) {
                    if ($this->_throwException) {
                        throw new f_sis_exception_running("Sis `{$this->_id}` already running");
                    }
                    return false;
                }
            }
        }
        else {
            if (!is_writable(dirname($this->_id))) {
                throw new f_sis_exception_notWritable('Direcotry `' . dirname($this->_id) . '` not writable');
            }
        }

        file_put_contents($this->_id, getmypid());
        $this->_cleanup = true;
        return true;
    }

    /**
     * Ends sis
     */
    public function end()
    {
        $this->_cleanup = false;
        if (file_exists($this->_id)) {
            unlink($this->_id);
        }
    }

    public function _isProcessAlive($iPID)
    {
        $state = array();
        exec('ps ' . $iPID, $state);
        return count($state) >= 2;
    }

}