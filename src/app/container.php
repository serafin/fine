<?php

class container extends f_c_container
{

    /**
     * Konfigi
     *
     * @return f_config
     */
    protected function _config()
    {
        $this->config       = new f_config(array('path' =>  'app/config/'));
        $this->config->main = $this->config->main[$this->env];
        return $this->config;
    }

    /**
     * Baza danych mysql
     *
     * @return f_db_mysql
     */
    protected function _db()
    {
        $config   = $this->config->main['db'];
        $this->db = new f_db_mysql();
        $this->db->connect($config['host'], $config['user'], $config['pass']);
        $this->db->selectDb($config['name']);
        $this->db->query("SET NAMES '{$config['charset']}'");
        return $this->db;
    }

    protected function _debug()
    {
        $this->debug    = new f_debug();
        $this->db       = new f_debug_db(array('db' => $this->db, 'label' => '$f::c->db->'));
        f_debug_dispacher::_()->register();

        $this->debug->on();
        $this->debug->phpPredefinedVariables();
        
        return $this->debug;
    }

    protected function _dispacher()
    {
        $this->dispacher = new f_c_dispacher();
        $this->dispacher->controller($this->request->get(0));
        $this->dispacher->action($this->request->get(1));
        return $this->dispacher;
    }
    
    protected function _env()
    {
        return $this->env = $_SERVER['ENV'] == 'dev' ? 'dev' : 'prod';
    }

    protected function _error()
    {
        return $this->error = new f_error($this->config->main['error']);
    }

    protected function _event()
    {
        return $this->event = new f_event_dispacher();
    }

    protected function _flash()
    {
        $this->flash = new f_c_helper_flash();
        $this->flash->storage($_SESSION['flash']);
        return $this->flash;
    }

    protected function _reg()
    {
        return $this->reg = new stdClass();
    }

    protected function _request()
    {
        return $this->request = new f_c_request();
    }

    protected function _response()
    {
        return $this->response = new f_c_response();
    }

    /**
     * Renderowanie widoku
     *
     * @return f_c_render
     */
    protected function _render()
    {
        $this->render             = new f_c_render();
        $this->render->dispacher  = $this->dispacher;
        $this->render->viewObject = $this->v;
        $this->render->response   = $this->response;
        return $this->render;
    }

    protected function _v()
    {
        $this->v = new f_v();
        $this->v->_c = $this->vc;
        return $this->v;
    }

    protected function _vc()
    {
        return $this->vc = new v_container();
    }

    protected function _uri()
    {
        return $this->uri = new f_c_helper_uri();
    }
    
    protected function _uriAbs()
    {
        return $this->uriAbs = "http://" . $_SERVER['SERVER_NAME'] . $this->uriBase;
    }

    protected function _uriBase()
    {
        return $this->uriBase = "/";
    }

}
