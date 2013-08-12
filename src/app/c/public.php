<?php

class c_public extends f_c_action
{

    /**
     * czy srodowisko deweloperskie
     * 
     * @var boolean 
     */
    protected $_isEnvDev;

    /**
     * rozszerzenie pliku
     * 
     * @var string 
     */
    protected $_type;
    
    /**
     * wzorzec nazwy pliku
     * 
     * @var string 
     */
    protected $_filePattern;
    
    /**
     * # CSSI (CSS Implode) - Pakowanie plikow css w jeden i wersjonowanie.
     *
     * ## Wymagania
     *
     *  - pliki css o rozszerzeniu `css` sa w folderze /public/css/{folder}
     *  - nazwy plikow o wzorcu `^v[0-1]+\.css$` sa zastrzezone
     *  - wpis w configu /app/config/public.php['css'][{folder}]['v'] = {integer}
     *  - adres do pliku css to: `/public/css/{folder}/v{$v}.css`
     *    gdzie `$v` to `/app/config/public.php['css'][{folder}]['v']`
     * 
     * ## Aktualizacja plikow
     *
     * ### Srodowisko deweloperskie
     *
     * Modyfikujemy pliki w folderze. Nie podbijamy wersji cssa/jsa. Po uruchomieniu `/public/css/{folder}/v{$v}.css` plik generuje 
     * sie dynamicznie i nie jest cachowany. W pliku sa dodatkowe komentarze informujace o tym, z ktorego pliku dana tresc jest.
     *
     * ### Srodowisko produkcyjne
     *
     * Modyfikujemy pliki w folderze. Podbijamy wersje cssa/jsa o jeden. Po uruchomieniu `/public/css/{folder}/v{$v}.css` plik generuje 
     * sie dynamicznie i jest cachowany pod adresem `/public/css/{folder}/v{$v}.css`. Przy nastepnym wywolaniu apache podsyla bezposrednio 
     * plik i nie uruchamia skryptu aplikacji, akcji `c_public->cssAction()`. Poprzednia wersja cache jest usuwana. Kiedy wywolamy adres 
     * `/public/css/{folder}/v{$v}.css`, gdzie $v jest rozne od wersji wpisanej w konfigu, to nastapi przekierowa na aktualna wersje cssa. 
     * Podbijanie wersji css najlepiej robic przed samym wgrywaniem plikow na serwer produkcyjny.
     *
     */
    function cssAction()
    {
        $this->render->off();
        $this->_type = 'css';
        $this->_implodeFiles();
    }
    
    /**
     * # JSI (JS Implode) - Pakowanie plikow js w jeden i wersjonowanie.
     *
     * ## Wymagania
     *
     *  - pliki js o rozszerzeniu `js` sa w folderze /public/js/{folder}
     *  - nazwy plikow o wzorcu `^v[0-1]+\.js$` sa zastrzezone
     *  - wpis w configu /app/config/public.php['js'][{folder}]['v'] = {integer}
     *  - adres do pliku js to: `/public/js/{folder}/v{$v}.js`
     *    gdzie `$v` to `/app/config/public.php['js'][{folder}]['v']`
     *
     * ## Aktualizacja plikow
     *
     * ### Srodowisko deweloperskie
     *
     * Modyfikujemy pliki w folderze. Nie podbijamy wersji cssa/jsa. Po uruchomieniu `/public/js/{folder}/v{$v}.js` plik generuje 
     * sie dynamicznie i nie jest cachowany. W pliku sa dodatkowe komentarze informujace o tym, z ktorego pliku dana tresc jest.
     *
     * ### Srodowisko produkcyjne
     *
     * Modyfikujemy pliki w folderze. Podbijamy wersje cssa/jsa o jeden. Po uruchomieniu `/public/js/{folder}/v{$v}.js` plik generuje 
     * sie dynamicznie i jest cachowany pod adresem `/public/js/{folder}/v{$v}.js`. Przy nastepnym wywolaniu apache podsyla bezposrednio 
     * plik i nie uruchamia skryptu aplikacji, akcji `c_public->jsAction()`. Poprzednia wersja cache jest usuwana. Kiedy wywolamy adres 
     * `/public/js/{folder}/v{$v}.js`, gdzie $v jest rozne od wersji wpisanej w konfigu, to nastapi przekierowa na aktualna wersje jsa. 
     * Podbijanie wersji js najlepiej robic przed samym wgrywaniem plikow na serwer produkcyjny.
     *
     */
    public function jsAction()
    {
        $this->render->off();
        $this->_type = 'js';
        $this->_implodeFiles();
    }
    
    public function _implodeFiles()
    {
        $this->_isEnvDev = $this->env == 'dev';
        
        // setup input /public/{$this->_type}/{$inputDir}/v{$inputVersion}.{$this->_type}
        $dir      = $_GET[2];
        $file     = $_GET[3];
        $this->_filePattern = '/^v[0-9]*\.' . $this->_type . '$/';
        $version = $this->_type == 'js' ? (int)substr($file, 1, -3) : (int)substr($file, 1, -4);
        

        // file and version format ok?
        if (!preg_match($this->_filePattern, $file)) {
            $this->notFound();
        }
        
        // is this file under JSI/CSSI system?
        if (!isset($this->config->public[$this->_type][$dir]['v'])) {
            $this->notFound();
        }

        // is this current version? if not, go to current file version
        if ($this->config->public[$this->_type][$dir]['v'] != $version) {
            $this->redirect->uri(array('public', $this->_type, $dir, 'v' . $this->config->public[$this->_type][$dir]['v'] . '.' . $this->_type));
        }
        
        // output
        $output = "";
        
        // implode files from config
        foreach (array('file_prepend', 'file_append') as $v) {
            if ($this->config->public[$this->_type][$dir][$v]) {
                foreach ($this->config->public[$this->_type][$dir][$v] as $i) {
                    
                    if (!preg_match("#^https?://#", $i)) {
                        $i =  'http://' . $_SERVER['SERVER_NAME'] . $i;
                    }
                    
                    // add comment
                    if ($this->_isEnvDev) {
                        $output .= "\n/* {$i} /\n\n";
                    }

                    $output .= @file_get_contents($i);

                    if ($this->_type == 'js') {
                        $output .= ";";
                    }

                    // end adding comment
                    if ($this->_isEnvDev) {
                        $output .= "\n\n";
                    }
                }
            }
        }

        // implode all files from `/public/{$this->_type}/$dir/*.{$this->_type}`
        $output .= $this->_readFolder("./public/{$this->_type}/{$dir}");
        
        // replace_regexp
        if (isset($this->config->public[$this->_type][$dir]['replace_regexp'])) {
            foreach ($this->config->public[$this->_type][$dir]['replace_regexp'] as $k => $v) {
                $output = preg_replace($k, $v, $output);
            }
        }
        
        if (!$this->_isEnvDev) {
            // save cache
            file_put_contents('public/' . $this->_type . '/' . $dir . '/v' . $version . '.' . $this->_type, $output);
 
            // remove out-of-date cache
            $outofdate = 'public/' . $this->_type . '/' . $dir . '/v' . ($version - 1) . '.' . $this->_type;
            if ($version > 1 && is_file($outofdate)) {
                unlink($outofdate);
            }
        }

        // send file to client
        $this->response
            ->header('Content-Type', ($this->_type == 'js' ? 'text/javascript; charset=utf-8' : 'text/css'))
            ->body($output)
            ->send();
    }
    
    protected function _readFolder($path)
    {
        $output = "";
        
        $dirList = scandir($path);
        
        if (count($dirList) > 0) {
            foreach ($dirList as $v) {
                if($v != "." && $v != "..") {
                    $filepath = $path . '/' . $v;
                    
                    if (is_dir($filepath)) {
                        $output .= $this->_readFolder($filepath);
                    }
                    else {
                        if (preg_match($this->_filePattern, basename($filepath))) {
                            continue;
                        }
                        if (end(explode('.', $filepath)) != $this->_type) {
                            continue;
                        }
                        
                        // add comment
                        if ($this->_isEnvDev) {
                            $output .= "\n/* {$filepath} \n";
                        }

                        $output .= file_get_contents($filepath);

                        // end adding comment
                        if ($this->_isEnvDev) {
                            $output .= "\n\n";
                        }
                    }
                }
            }
        }

        return $output;
    }
}