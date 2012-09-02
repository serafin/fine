<?php

class f_c_helper_connectionClose
{

    /**
     *
     * @return f_c_helper_connectionClose
     */
    public function helper()
    {
        f::$c->response
            ->header('Content-Length', mb_strlen($this->response->body))
            ->header('Connection', 'close')
            ->send();
        return $this;
    }
    
}
