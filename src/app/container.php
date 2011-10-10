<?php

class app_c extends f_c_container
{

    protected function _config()
    {
        $this->config = new f_config(array(
            'path' => f::$pathApp . 'config/',
        ));
        $this->config->main = $this->config->main->{f::$env};
        return $this->config;
    }

    protected function _db()
    {
        $db = $this->config->main->db;
        $this->db = new f_db_mysql();
        $this->db->connect($db->host, $db->user, $db->pass);
        $this->db->selectDb($db->name);
        $this->db->query("SET NAMES '{$db->charset}'");
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
        $this->dispacher->event   = $this->event;
        return $this->dispacher;
    }
    
    protected function _env()
    {
        $this->env = $_SERVER['ENV'] == 'dev' ? 'dev' : 'prod';
        return $this->env;
    }

    protected function _error()
    {
        $this->error = new f_error($this->config->main->error);
        $this->error->register();
        return $this->error;
    }

    protected function _event()
    {
        $this->event = new f_event_dispacher();
        return $this->event;
    }

    protected function _flash()
    {
        $this->flash           = new f_helper_flash();
        $this->flash->session  = $this->session->space('flash');
        $this->flash->redirect = $this->redirect;
        return $this->flash;
    }

    protected function _m()
    {
        $this->m = new f_m_dispacher();
        $this->m->prefix("m_");
        return $this->m;
    }

    protected function _reg()
    {
        return $this->reg = new stdClass();
    }

    protected function _request()
    {
        if ($_FILES) {
            /* @todo */
        }
        return $this->request = new f_c_request();
        
    }

    protected function _response()
    {
        return $this->response = new f_c_response();
    }

    protected function _router()
    {
        $this->router          = new app_c_router();
        $this->router->request = $this->request;
        return $this->router;
    }

    protected function _render()
    {
        $this->render            = new f_c_helper_render();
        $this->render->dispacher = $this->dispacher;
        $this->render->view      = $this->v;
        $this->render->layout    = $this->layout;
        $this->render->response  = $this->respones;
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
        $this->uri = new f_helper_uri(array(
            'request' => $this->request,
            'router'  => $this->router,
        ));
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
