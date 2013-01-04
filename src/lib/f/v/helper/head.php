<?php

class f_v_helper_head
{
    
    public $template = array(
        'charset' => array(
            'mode'      => 'item',
            'template'  => "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset={charset}\">\n",
            'var'       => 'charset',
        ),
        'title' => array(
            'mode'      => 'list',
            'template'  => "{fragment}",
            'var'       => 'fragment',
            'prepend'   => "\t<title>",
            'separator' => " | ",
            'append'    => "</title>\n",
        ),
        'keywords' => array(
            'mode'      => 'list',
            'template'  => "{words}",
            'var'       => 'words',
            'prepend'   => "\t<meta name=\"keywords\" content=\"",
            'separator' => ', ',
            'append'    => "\" />\n",
        ),
        'description' => array(
            'mode'      => 'list',
            'template'  => "{description}",
            'var'       => 'description',
            'prepend'   => "\t<meta name=\"description\" content=\"",
            'separator' => '. ',
            'append'    => "\" />\n",
        ),
        'favicon' => array(
            'mode'      => 'item',
            'template'  => "\t<link href=\"{href}\" rel=\"shortcut icon\">\n",
            'var'       => 'href',
        ),
        'rss' => array(
            'mode'      => 'list',
            'template'  => "\t<link href=\"{href}\" title=\"{title}\" type=\"application/rss+xml\" rel=\"alternate\" {attr}/>\n",
            'var'       => 'href',
            'val'       => array('attr' => ''),
        ),
        'atom' => array(
            'mode'      => 'list',
            'template'  => "\n<link href=\"{href}\" title=\"{title}\" type=\"application/atom+xml\" rel=\"alternate\" {attr}/>",
            'var'       => 'href',
            'val'       => array('attr' => ''),
        ),
        'css' => array(
            'mode'      => 'list',
            'template'  => "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"{href}\" {attr}/>\n",
            'var'       => 'href',
            'val'       => array('attr' => ''),
        ),
        'js' => array(
            'mode'      => 'list',
            'template'  => "\t<script src=\"{src}\" type=\"text/javascript\"{attr}>{content}</script>\n",
            'var'       => 'src',
            'val'       => array('attr' => '', 'content' => ''),
        ),
        'jscode' => array(
            'mode'      => 'list',
            'template'  => "{content}",
            'var'       => 'content',
            'prepend'   => "\t<script type=\"text/javascript\">\n",
            'separator' => "\n",
            'append'    => "\n</script>\n",
        ),
        'jsblock' => array(
            'mode'      => 'list',
            'template'  => "\t<script type=\"text/javascript\"{attr}>\n{content}\n\t</script>\n",
            'var'       => 'content',
            'val'       => array('attr' => ''),
        ),
        'csscode' => array(
            'mode'      => 'list',
            'template'  => "{content}",
            'var'       => 'content',
            'prepend'   => "\t<style type=\"text/css\">\n",
            'separator' => "\n",
            'append'    => "\n\t</style>\n",
        ),
        'cssblock' => array(
            'mode'      => 'list',
            'template'  => "\t<style type=\"text/css\"{attr}>\n{content}\n\t</style>\n",
            'var'       => 'content',
            'val'       => array('attr' => ''),
        ),
        'ogTitle' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"og:title\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
        'ogType' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"og:type\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
        'ogUrl' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"og:url\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
        'ogImage' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"og:image\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
        'ogSiteName' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"og:site_name\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
        'fbAppId' => array(
            'mode'      => 'item',
            'template'  => "\t<meta property=\"fb:app_id\" content=\"{content}\">\n",
            'var'       => 'content',
        ),
    );
    protected $_data = array();
    
    
    public static function _()
    {
        return new self;
    }

    public function __call($name, $arguments) 
    {
        $this->head($name, $arguments[0]);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function toString()
    {
        return $this->render();
    }

    public function helper()
    {
        return $this->render();
    }
    
    public function render()
    {
        $output = '';
        
        foreach ($this->template as $type => $template) {
            $output .= $this->renderType($type);
        }
        
        return $output;
    }
    
    public function renderType($sType)
    {
        $data = $this->_data[$sType];
        $tpl  = $this->template[$sType];
        
        if (! isset($data)) {
            return '';
        }
        
        if ($tpl['mode'] == 'item') {
            
            $data    += (array)$tpl['val'];
            $sOutput = $tpl['template'];
            foreach ($data as $var => $val) {
                $sOutput = str_replace("{{$var}}", htmlspecialchars($val), $sOutput);
            }
            
            return $sOutput;
            
        }
        else {
            
            $aOutput = array();
            foreach ($data as $i) {
                $i       += (array)$tpl['val'];
                $sOutput = $tpl['template'];
                foreach ($i as $var => $val) {
                    $sOutput = str_replace("{{$var}}", htmlspecialchars($val), $sOutput);
                }
                $aOutput[] = $sOutput;
            }
            
            return 
                (string)$tpl['prepend'] 
              . implode((string)$tpl['separator'], $aOutput) 
              . (string)$tpl['append'];
            
        }
        
    }

    public function head($sType, $asArg = null)
    {
        if (is_string($asArg)) {
            $asArg = array($this->template[$sType]['var'] => $asArg);
        }
        
        if ($this->template[$sType]['mode'] == 'item') {
            $this->_data[$sType] = $asArg;
        }
        else {
            $this->_data[$sType][] = $asArg;
        }
        
        return $this;
    }
    
    public function remove($sType)
    {
        unset($this->_data[$sType]);
    }

    /* html main */
    
    public function charset($sCharset)
    {
        return $this->head('charset', $sCharset);
    }

    public function title($sTitle)
    {
        return $this->head('title', $sTitle);
    }

    public function keywords($sKeywords)
    {
        return $this->head('keywords', $sKeywords);
    }

    public function description($sDescription)
    {
        return $this->head('description', $sDescription);
    }

    public function favicon($sFavicon)
    {
        return $this->head('favicon', $sFavicon);
    }

    /* feeds */
    
    public function rss($sUri, $sTitle = null)
    {
        return $this->head('rss', array('href' => $sUri, 'title' => $sTitle));
    }

    public function atom($sUri, $sTitle = null)
    {
        return $this->head('atom', array('href' => $sUri, 'title' =>  $sTitle));
    }

    /* css */
    
    public function css($sUri)
    {
        return $this->head('css', $sUri);
    }
    
    public function csscode($sContent)
    {
        return $this->head('csscode', $sContent);
    }

    public function cssblock($sContent)
    {
        return $this->head('cssblock', $sContent);
    }
    
    public function cssi($sStyle)
    {
        $this->css('/public/css/' . $sStyle . '/v' . f::$c->config->public['css'][$sStyle]['v'] . '.css');
    }

    /* js */
    
    public function js($sUri, $sContent = null)
    {
        return $this->head('js', array('src' => $sUri, 'content' => $sContent));
    }

    public function jscode($sContent)
    {
        return $this->head('jscode', $sContent);
    }

    public function jsblock($sContent)
    {
        return $this->head('jsblock', $sContent);
    }

    /* Open Graph protocol http://ogp.me/ */ 
    
    public function ogTitle($sContent)
    {
        return $this->head('ogTitle', $sContent);
    }
    
    public function ogType($sContent)
    {
        return $this->head('ogType', $sContent);
    }
    
    public function ogUrl($sContent)
    {
        return $this->head('ogUrl', $sContent);
    }
    
    public function ogImage($sContent)
    {
        return $this->head('ogImage', $sContent);
    }
    
    public function ogSiteName($sContent)
    {
        return $this->head('ogImage', $sContent);
    }
    
    /* facebook */
    
    public function fbAppId($sContent)
    {
        return $this->head('fbAppId', $sContent);
    }
    
}
