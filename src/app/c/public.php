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
     * nazwa folderu
     * 
     * @var string 
     */
    protected $_dir;
    
    /** 
     * nazwa pliku
     * 
     * @var string 
     */
    protected $_file;
    
    /**
     * wzorzec nazwy pliku
     * 
     * @var string 
     */
    protected $_filePatten;
    
    public function __construct()
    {
        $this->render->off();
    }
    
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
    public function cssAction()
    {
        $this->_implodeFiles('css');
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
        $this->_implodeFiles('js');
    }
    
    protected function _implodeFiles($type)
    {
        $this->_isEnvDev = $this->env == 'dev';
        
        // setup input /public/{$type}/{$inputDir}/v{$inputVersion}.{$type}
        $this->_dir      = $_GET[2];
        $this->_file     = $_GET[3];
        $this->_filePatten = '/^v[0-9]*\.' . $type . '$/';
        $version = $type == 'js' ? (int)substr($this->_file, 1, -3) : (int)substr($this->_file, 1, -4);

        // file and version format ok?
        if (!preg_match($this->_filePatten, $this->_file)) {
            $this->notFound();
        }
        
        // is this file under JSI/CSSI system?
        if (!isset($this->config->public[$type][$this->_dir]['v'])) {
            $this->notFound();
        }

        // is this current version? if not, go to current file version
        if ($this->config->public[$type][$this->_dir]['v'] != $version) {
            $this->redirect->uri(array('public', $type, $this->_dir, 'v' . $this->config->public[$type][$this->_dir]['v'] . '.' . $type));
        }

        // output
        $output = $type == 'js' ? $this->_jsImplodeFiles() : $this->_cssImplodeFiles();

        if (!$this->_isEnvDev) {
            // save cache
            file_put_contents('public/' . $type . '/' . $this->_dir . '/v' . $version . '.' . $type, $output);
 
            // remove out-of-date cache
            $outofdate = 'public/' . $type . '/' . $this->_dir . '/v' . ($version - 1) . '.' . $type;
            if ($version > 1 && is_file($outofdate)) {
                unlink($outofdate);
            }
        }

        // send file to client
        $this->response
            ->header('Content-Type', ($type == 'js' ? 'text/javascript; charset=utf-8' : 'text/css'))
            ->body($output)
            ->send();
    }
    
    protected function _cssImplodeFiles()
    {
        // output
        $output = "";
        
        // implode all css files in `/public/css/$dir/*.css`
        foreach (glob("./public/css/{$this->_dir}/*.css") as $i) {
 
            // restricted css file name
            if (preg_match($this->_filePatten, basename($i))) {
                continue;
            }
 
            // add comment
            if ($this->_isEnvDev) {
                $output .= "\n/* {$this->_dir}/" .  basename($i) . " */\n";
            }
 
            $output .= file_get_contents($i);
 
            if ($this->_isEnvDev) {
                $output .= "\n\n";
            }
        }
 
        // replace_regexp
        if (isset($this->config->public['css'][$this->_dir]['replace_regexp'])) {
            foreach ($this->config->public['css'][$this->_dir]['replace_regexp'] as $k => $v) {
                $output = preg_replace($k, $v, $output);
            }
        }
        
        return $output;
    }
    
    protected function _jsImplodeFiles()
    {
        // output
        $output = "";
        
        // file
        foreach(array('file_prepend', 'file', 'file_append') as $i) {
            if ($this->config->public['js'][$this->_dir][$i]) {
                foreach ($this->config->public['js'][$this->_dir][$i] as $file) {
                    if (!preg_match("#^https?://#", $file)) {
                        $file =  'http://'. $_SERVER['SERVER_NAME']. $file;
                    }

                    // add comment
                    if ($this->_isEnvDev) {
                        $output .= "\n/* {$file} */\n";
                    }

                    $output .= @file_get_contents($file);
                    $output .= ";\n";

                    if ($this->_isEnvDev) {
                        $output .= "\n\n";
                    }
                }
            }
        }

        return $output;
    }
    
    protected function _implodeFiles2($type)
    {
        $isEnvDev = $this->env == 'dev';
        
        // setup input /public/{$type}/{$inputDir}/v{$inputVersion}.{$type}
        $dir      = $_GET[2];
        $file     = $_GET[3];
        $filePatten = '/^v[0-9]*\.' . $type . '$/';
        $version = $type == 'js' ? (int)substr($file, 1, -3) : (int)substr($file, 1, -4);

        // file and version format ok?
        if (!preg_match($filePatten, $file)) {
            $this->notFound();
        }
        
        // is this file under JSI/CSSI system?
        if (!isset($this->config->public[$type][$dir]['v'])) {
            $this->notFound();
        }

        // is this current version? if not, go to current file version
        if ($this->config->public[$type][$dir]['v'] != $version) {
            $this->redirect->uri(array('public', $type, $dir, 'v' . $this->config->public[$type][$dir]['v'] . '.' . $type));
        }
        
        // output
        $output = "";
        
        // implode files from config
        foreach(array('file_prepend', 'file', 'file_append') as $v) {
            if ($this->config->public[$type][$dir][$v]) {
                foreach ($this->config->public[$type][$dir][$v] as $i) {
                    if (!preg_match("#^https?://#", $i)) {
                        $i =  'http://'. $_SERVER['SERVER_NAME']. $i;
                    }

                    // add comment
                    if ($isEnvDev) {
                        $output .= "\n/* {$i} */\n";
                    }

                    $output .= @file_get_contents($i);
                    if($type == 'js') {
                        $output .= ";\n";
                    }

                    // end adding comment
                    if ($isEnvDev) {
                        $output .= "\n\n";
                    }
                }
            }
        }
        
        // implode files from `public` folder
        foreach (glob("./public/{$type}/{$dir}/*.{$type}") as $i) {
            // restricted file name
            if (preg_match($filePatten, basename($i))) {
                continue;
            }
            
            // add comment
            if ($isEnvDev) {
                $output .= "\n/* {$dir}/" .  basename($i) . " */\n";
            }
            
            $output .= file_get_contents($i);
            
            // end adding comment
            if ($isEnvDev) {
                $output .= "\n\n";
            }
        }
        
        // replace_regexp
        if (isset($this->config->public[$type][$dir]['replace_regexp'])) {
            foreach ($this->config->public[$type][$dir]['replace_regexp'] as $k => $v) {
                $output = preg_replace($k, $v, $output);
            }
        }
        
        if (!$isEnvDev) {
            // save cache
            file_put_contents('public/' . $type . '/' . $dir . '/v' . $version . '.' . $type, $output);
 
            // remove out-of-date cache
            $outofdate = 'public/' . $type . '/' . $dir . '/v' . ($version - 1) . '.' . $type;
            if ($version > 1 && is_file($outofdate)) {
                unlink($outofdate);
            }
        }

        // send file to client
        $this->response
            ->header('Content-Type', ($type == 'js' ? 'text/javascript; charset=utf-8' : 'text/css'))
            ->body($output)
            ->send();
        
    }
}