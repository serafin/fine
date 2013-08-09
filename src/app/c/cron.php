<?php

class c_cron extends f_c_action
{
    
    public function dayAction()
    {
        $this->_cleanDataTmpDir();
    }
    
    public function hourAction()
    {
        $this->_errorNotify();
    }
    
    /**
     * Czysci folder data/tmp/ z starych niepotrzebnych plikow
     */
    protected function _cleanDataTmpDir()
    {
        f_upload_tmp::_()->destroyAll(14 * 24 * 60 * 60);
    }
    
    /**
     * Wysyla powiadomienie ze 100 ostatnimi bledami aplikacji z error_log na podstawie 
     * sciezki podanej w configu /app/config/main.php['prod']['error_notify']['path'] z serweru produkcyjnego
     * na adresy email podane w configu /app/config/main.php['prod']['error_notify']['email']
     */    
    protected function _errorNotify()
    {
        // get error notification config
        $config = $this->config->main['error_notify'];
 
        // path to error_log file
        $path = !empty($config['path']) ? $config['path'] : ini_get('error_log');
        
        if ($this->env == 'prod' && !empty($path) && count($config['email']) > 0 && is_writable('./tmp')) {
            
            // get current modifiaction time of error_log file        
            $timemod = '';
            $file = popen("stat -c %y .." . (substr($path, 0, 1) != '/' ? '/' : '') . $path, "r");
            while (!feof($file)) {
                $timemod .= fread($file, 1024);
            }
            pclose($file);
            $timemod = strtotime(reset(explode('.',trim($timemod))));

            if (!empty($timemod)) {
                
                // file with last checked modification time of error_log file
                $tmp = './tmp/error_notify/lastchecked';
                if (file_exists($tmp)) {
                    $lastmod = file_get_contents($tmp);
                }
                else {
                    mkdir('./tmp/error_notify/', 0777);
                    file_put_contents($tmp, $timemod);
                    chmod($tmp, 0777);
                    $lastmod = $timemod - 1;
                }
                
                //last checked modification time is older than current modifiaction time of error_log file
                if ($lastmod && $lastmod < $timemod) {
                    
                    $text =  date('Y-m-d H:i:s', $timemod) . '
====================

Error Log
====================

';
                    // read last 100 lines from error_log file
                    $file = popen("tail -n 100 .." . (substr($path, 0, 1) != '/' ? '/' : '') . $path, "r");
                    while (!feof($file)) {
                        $text .= fread($file, 1024);
                    }
                    pclose($file);
                    $text .= '

SERVER
====================
                   
' . f_debug::varDumpPretty($_SERVER);;
                    
                    // send mails
                    foreach ($config['email'] as $email) {
                        mail(
                            $email, 
                            '[errornotify] ' . $_SERVER['SERVER_NAME'] . ' ' . date('Y-m-d H:i:s', $timemod),
                            str_replace('\n', '', $text)
                        );
                    }
                    
                    // save last modification time of error_log file in cache
                    file_put_contents($tmp, $timemod);
                }
            }
        }
    }
    
}