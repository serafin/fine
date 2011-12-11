<?php

class f_c_render extends f_c
{

    /** 
     * render result kept for next view level
     * @var string
     */
    public $content;
    
    
    /* level 1 - view */
    
    /** 
     * @var string
     *  */
    public $viewPath = 'app/v/script';
    
    /** 
     * @var string|null 
     */
    public $view;
    
    /* level 2 - layout (default off) */   
    
    /** 
     * @var string 
     */
    public $layoutPath = 'app/v/layout';
    
    /** 
     * @var string|null 
     */
    public $layout = null;
    
    /* level 3 - head (default off) */
    
    /** 
     * @var string 
     */
    public $headPath = 'app/v/head';
    
    /** 
     * @var string|null 
     */
    public $head = false;
    
    /**
     * view was renderd?
     * @var boolean
     */
    protected $_renderOnce = false;
    

    public function helper($sViewScript = null)
    {
        $this->render($sViewScript);
    }
    
    public function view($sViewScript = null)
    {
        if (func_num_args() == 0) {
            return $this->view;
        }
        $this->view = $sViewScript;
        return $this;
    }
    
    public function layout($sLayoutScript = null)
    {
        if (func_num_args() == 0) {
            return $this->layout;
        }
        $this->layout = $sLayoutScript;
        return $this;
    }
    
    public function head($sHeadScript = null)
    {
        if (func_num_args() == 0) {
            return $this->head;
        }
        $this->head = $sHeadScript;
        return $this;
    }
    
    public function renderOnce()
    {
        if ($this->_renderOnce === true) {
            return;
        }
        $this->render();
    }

    public function render($sViewScript = null)
    {
        // event prender_pre
        if ($this->event->is('render_pre')) {
            $this->event->run($event = new f_event(array('id' => 'render_pre', 'subject' => $this)));
            if ($event->cancel()) {
                return;
            }
        }
        
        // setting flag that view was rendered
        $this->_renderOnce = true;
        
        // handling passed argument
        if ($sViewScript !== null) {
            $this->view = $sViewScript;
        }
        
        // auto resolve view script if not set
        if ($this->view === null) {
            $this->view = str_replace('_', DIRECTORY_SEPARATOR, $this->dispacher->controller) 
                        . DIRECTORY_SEPARATOR
                        . str_replace('_', DIRECTORY_SEPARATOR, $this->dispacher->action);
        }
        
        // render view
        ob_start();
        $this->v->renderPath("$this->viewPath/$this->view.php");
        $this->content = ob_get_clean();
        
        // render layout
        if ($this->layout !== null) {
            ob_start();
            $this->v->renderPath("$this->layoutPath/$this->layout.php");
            $this->content = ob_get_clean();
        }
        
        // render head
        if ($this->layout !== null) {
            ob_start();
            $this->v->renderPath("$this->headPath/$this->head.php");
            $this->content = ob_get_clean();
        }
        
        $this->response->body = $this->content;
        
        // event render_post
        if ($this->event->is('render_pre')) {
            $this->event->run(new f_event(array('id' => 'render_post', 'subject' => $this)));
        }

    }


}