<?php

class f_c_helper_head
{
    
    public $headTemplate  = array(
        'css' => array(
            'mode'      => 'list',
            'template'  => '<link rel="stylesheet" type="text/css" href="{href}" {attr}/>',
            'var'       => '{href}',
            'val'       => array('{attr}' => ''),
            'prepend'   => "\t",
            'separator' => "\n\t",
            'append'    => "\n",
        ),
        'js' => array(
            'mode'      => 'list',
            'template'  => '<script src="{src}" type="text/javascript"{attr}>{content}</script>',
            'var'       => '{href}',
            'val'       => array('{attr}' => '', '{content}' => ''),
            'prepend'   => "\t",
            'separator' => "\n\t",
            'append'    => "\n",
        ),
        'jsInline' => array(/** @todo nazwe jakas porzadna ustawic */
            'mode'      => 'list',
            'template'  => "{content}",
            'var'       => '{content}',
            'prepend'   => "\t<script type=\"text/javascript\">\n",
            'separator' => "\n",
            'append'    => "\n</script>\n",
        ),
        'cssInline' => array(/** @todo nazwe jakas porzadna ustawic */
            'mode'      => 'list',
            'template'  => "{content}",
            'var'       => '{content}',
            'prepend'   => "\t<style type=\"text/css\">\n",
            'separator' => "\n",
            'append'    => "\n</style>\n",
        ),
        'charset' => array(
            'mode'      => 'item',
            'template'  => "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset={charset}\">\n",
            'var'       => '{charset}',
        ),
        'favicon' => array(
            'mode'      => 'item',
            'template'  => "\t<link rel=\"shortcut icon\" href=\"/{href}\">\n",
            'var'       => '{href}',
        ),
        'title' => array(
            'mode'      => 'list',
            'template'  => "{title}",
            'var'       => '{title}',
            'prepend'   => "\t<title>",
            'separator' => " | ",
            'append'    => "</title>\n",
        ),
        /** 
         * @todo
         *  - description
         *  - keywords
         *  - language
         *  - rss
         *  - atom
         *  - robots
         *  - jsBlock
         *  - cssBlock
         */
    );
    protected $_data = array();
    
    
    public function helper(){} // render all
    public function render($sType = null){} // render all or type

    // zarzadzanie danymi
    public function head($sType, $asArg = null){}
    public function remove();
    
}
