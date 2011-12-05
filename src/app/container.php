<?php

class app_container extends f_c_container
{

    protected function _config()
    {
        $this->config       = new f_config(array('path' =>  'app/config/'));
        $this->config->main = $this->config->main[$this->env];
        return $this->config;
    }

    protected function _db()
    {
        $config   = $this->config->main['db'];
        $this->db = new f_db_mysql();
        $this->db->connect($config->host, $config->user, $config->pass);
        $this->db->selectDb($config->name);
        $this->db->query("SET NAMES '{$config->charset}'");
        return $this->db;
    }
    
    protected function _debug()
    {
        $this->debug = new f_debug();
        $this->db    = new f_debug_component_db(array('component' => $this->db));
        return $this->debug;
    }

    protected function _dispacher()
    {
        $this->dispacher          = new f_c_dispacher();
        $this->dispacher->request = $this->request;
        return $this->dispacher;
    }
    
    protected function _env()
    {
        return $this->env = $_SERVER['ENV'] == 'dev' ? 'dev' : 'prod';
    }

    protected function _error()
    {
        
        $this->error = new f_error($this->config->main['error']);
        return $this->error;
    }

    protected function _event()
    {
        return $this->event = new f_event_dispacher();
    }

    protected function _flash()
    {
        $this->flash           = new f_c_helper_flash();
        $this->flash->session  = $this->session->space('flash');
        return $this->flash;
    }

    protected function _reg()
    {
        return $this->reg = new stdClass();
    }

    protected function _request()
    {
        if ($_FILES) {
            $_POST += $_FILES;
        }
        return $this->request = new f_c_request();
        
    }

    protected function _response()
    {
        return $this->response = new f_c_response();
    }

    protected function _router()
    {
        $this->router          = new f_c_router();
        $this->router->request = $this->request;
        return $this->router;
    }

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
        return $this->vc = new app_v_c();
    }

    protected function _uri()
    {
        $this->uri = new f_c_helper_uri();
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
