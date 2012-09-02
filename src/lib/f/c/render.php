<?php

class f_c_render extends f_c
{

    CONST EVENT_RENDER_PRE  = 'render_pre';
    CONST EVENT_RENDER_POST = 'render_post';

    /** 
     * render result kept for next view level
     * @var string
     */
    public $content;

    /**
     * view was renderd?
     * @var boolean
     */
    protected $_renderOnce = false;

    /**
     * View levels
     *
     * Order matters
     *
     * @var array
     */
    protected $_level = array(
        'view'   => array('dir' => 'app/v/script', 'file' => null),
        'layout' => array('dir' => 'app/v/layout', 'file' => null),
        'head'   => array('dir' => 'app/v/head',   'file' => null),
    );

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return self
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Konstruktor
     *
     * @param array $config
     * @return self
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function __call($sName, $aArg)
    {

        if (substr($sName, -3) == 'Dir') {
            $method = 'levelDir';
            $level  = substr($sName, 0, -3);
        }
        else {
            $method = 'levelFile';
            $level  = $sName;
        }

        return count($aArg)
                ? $this->{$method}($level, $aArg[0])
                : $this->{$method}($level);
    }

    /**
     * Renderuje widok i ustawia go jako cialo odpowiedzi
     *
     * @param string $sViewScript
     */
    public function helper($sViewScript = null)
    {
        $this->render($sViewScript);
    }

    public function content()
    {
        return $this->content;
    }
    
    public function levelFile($sLevelName, $sFile = null)
    {
        // getter
        if (func_num_args() == 1) {
            $this->_level[$sLevelName]['file'];
        }

        // setter
        if (!isset($this->_level[$sLevelName])) {
            $this->_level[$sLevelName]  = array('dir' => '.', 'file' => null);
        }
        $this->_level[$sLevelName]['file'] = $sFile;
        return $this;
    }

    public function levelDir($sLevelName, $sDir = null)
    {
        // getter
        if (func_num_args() == 1) {
            $this->_level[$sLevelName]['dir'];
        }

        // setter
        if (!isset($this->_level[$sLevelName])) {
            $this->_level[$sLevelName]  = array('dir' => '.', 'file' => null);
        }
        $this->_level[$sLevelName]['dir'] = $sDir;
        return $this;
    }

    public function view($sViewFile = null)
    {
        if (func_num_args() == 0) {
            return $this->levelFile('view');
        }
        $this->levelFile('view', $sViewFile);
        return $this;
    }

    public function viewDir($sViewDir = null)
    {
        if (func_num_args() == 0) {
            return $this->levelDir('view');
        }
        $this->levelDir('view', $sViewDir);
        return $this;
    }

    public function layout($sLayoutFile = null)
    {
        if (func_num_args() == 0) {
            return $this->levelFile('layout');
        }
        $this->levelFile('layout', $sLayoutFile);
        return $this;
    }

    public function layoutDir($sLayoutDir = null)
    {
        if (func_num_args() == 0) {
            return $this->levelDir('layout');
        }
        $this->levelDir('layout', $sLayoutDir);
        return $this;
    }

    public function head($sHeadFile = null)
    {
        if (func_num_args() == 0) {
            return $this->levelFile('head');
        }
        $this->levelFile('head', $sHeadFile);
        return $this;
    }

    public function headDir($sHeadDir = null)
    {
        if (func_num_args() == 0) {
            return $this->levelDir('head');
        }
        $this->levelDir('head', $sHeadDir);
        return $this;
    }

    public function renderOnce($sViewScript = null)
    {
        if ($this->_renderOnce === true) {
            return;
        }
        $this->render($sViewScript);
    }
    
    public function off()
    {
        $this->_renderOnce = true;
    }

    public function render($sViewScript = null)
    {
        /** @event render_pre */
        if ($this->event->is(self::EVENT_RENDER_PRE)) {
            $this->event->run($event = new f_event(array('id' => self::EVENT_RENDER_PRE, 'subject' => $this)));
            if ($event->cancel()) {
                return;
            }
        }
        
        // setting flag that view was rendered
        $this->_renderOnce = true;
        
        // handle passed argument
        if ($sViewScript !== null) {
            $this->_level['view']['file'] = $sViewScript;
        }
        
        // auto resolve view script if not set
        if ($this->_level['view']['file'] === null) {
            $this->_level['view']['file'] = str_replace('_', DIRECTORY_SEPARATOR, $this->dispacher->controller())
                                          . DIRECTORY_SEPARATOR
                                          . str_replace('_', DIRECTORY_SEPARATOR, $this->dispacher->action());
        }

        // render all levels
        while ($level = current($this->_level)) {

            $dir   = $level['dir'];
            $file  = $level['file'];

            if (strlen($file) > 0) {
                $this->content = $this->v->renderPath("$dir/$file");
            }

            next($this->_level);
        }
        reset($this->_level);

        // attaches rendered content to response body
        $this->response->body = $this->content;
        
        /** @event render_post */
        if ($this->event->is(self::EVENT_RENDER_POST)) {
            $this->event->run(new f_event(array('id' => self::EVENT_RENDER_POST, 'subject' => $this)));
        }

    }

}
