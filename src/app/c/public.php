<?php

class c_public extends f_c_action
{
    
    public function cssAction()
    {
        /**
         * example path
         * /public/css/style.1.css
         */
        
        $this->render->off();
        
        
        list($file, $version, $extension) = explode('.', $_GET[2]);
        
        // extension must be `css`
        if ($extension != 'css') {
            $this->notFound();
        }
        
        // file must be defined in config
        if (!isset($this->config->public['css'][$file])) {
            $this->notFound();
        }
        
        // go to current file version
        if ($this->config->public['css'][$file]['v'] != $version) {
            $this->redirect("public/css/$file.{$this->config->public['css'][$file]['v']}.css");
        }
        
        
        $output = "";
        foreach (glob("./public/css/$file.css/*.css") as $i) {
            $output .= file_get_contents($i);
        }
        
        
        /**
         * @todo compress css
         */
        
        /**
         * @todo save output to file cache
         */

        
        $this->response->body = $output;
        /**
         * @todo header and send response
         */
        
        
    }
    
    
}