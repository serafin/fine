<?php

class f_tool_nbprojectPhpDoc
{

    /**
     * Static konstructor
     *
     * @param array $config
     * @return f_tool_nbproject_container
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * @param type $sContainerClass
     * @return string Php
     */
    public function renderContainer($sContainerClass = 'container')
    {

        $completion = array();

        $completion[] = "@property $sContainerClass \$_c Main application container";

        /* f_di services */

        $reflectionClass = new ReflectionClass($sContainerClass);

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {

            /* @var $reflectionMethod ReflectionMethod */

            $doc = $this->_docFromComment($reflectionMethod->getDocComment());

            if ($doc === false) {
                continue;
            }

            if (substr($reflectionMethod->getName(), 0, 1) != '_') {
                continue;
            }

            // @property
            $property     = substr($reflectionMethod->getName(), 1);
            $completion[] = "@property {$doc['return']['type']} \$$property {$doc['desc']}";


            // @method
            $helperReflectionClass = new ReflectionClass($doc['return']['type']);
            if (!$helperReflectionClass->getName()) {
                continue;
            }

            if (!$helperReflectionClass->hasMethod('helper')) {
                continue;
            }

            $hdoc = $this->_docFromComment($helperReflectionClass->getMethod('helper')->getDocComment());
            if ($hdoc === false) {
                continue;
            }

            $args = array();
            foreach ((array)$hdoc['param'] as $arg) {
                $args[] = "{$arg['type']} {$arg['name']}";
            }
            $args         = implode(', ', $args);
            if (!isset($hdoc['return'])) {
                $hdoc['return']['type'] = 'void';
            }

            $completion[] = "@method {$hdoc['return']['type']} $property() $property($args) {$hdoc['desc']}";

        }

        /* helpers */

        foreach (glob('lib/f/c/helper/*.php') as $file) {


            $class           = str_replace('/', '_', substr($file, 4, -4));
            $helper          = substr($class, strlen('f_c_helper_'));
            $reflectionClass = new ReflectionClass($class);

            // @property
            $completion[] = "@property {$class} \$$helper";

            // @method

            if (!$reflectionClass->hasMethod('helper')) {
                continue;
            }

            $hdoc = $this->_docFromComment($reflectionClass->getMethod('helper')->getDocComment());
            if ($hdoc === false) {
                continue;
            }

            $args = array();
            foreach ((array)$hdoc['param'] as $arg) {
                $args[] = "{$arg['type']} {$arg['name']}";
            }
            $args         = implode(', ', $args);
            if (!isset($hdoc['return'])) {
                $hdoc['return']['type'] = 'void';
            }

            $completion[] = "@method {$hdoc['return']['type']} $helper() $helper($args) {$hdoc['desc']}";


        }


        return "/**\n * " . implode("\n * ", $completion) . "\n */\nclass f_c \n{}\n\n"
             . "/**\n * " . implode("\n * ", $completion) . "\n */\nclass container \n{}\n";

    }

    protected function _docFromComment($sPhpDocComment)
    {
        if ($sPhpDocComment === false) {
            return false;
        }

        $comment = $sPhpDocComment;
        $comment = substr($comment, 3, -2);
        $comment = trim($comment);
        $comment = preg_split("/\s*\*\s*@/", $comment);

        $doc = array();
        foreach ($comment as $index => $item) {

            if ($index === 0) {
                $item = preg_replace('/^\s*\*\s*/', ' ', $item);
                $item = preg_replace('/\n\s*\*\s*/', "\n", $item);
                list($item) = explode("\n", $item, 2);
                $item = trim($item);
                $doc['desc'] = $item;
                continue;
            }

            list ($param, $content) = explode(' ', $item, 2);

            if ($param === 'return') {
                list($type, $desc) = explode(' ', $content, 2);
                $doc['return'] = array('type' => trim($type), 'desc' => trim($desc));
            }
            else if ($param === 'param') {
                list($type, $name, $desc) = explode(' ', $content, 3);
                $doc['param'][] = array('name' => trim($name), 'type' => trim($type), 'desc' => trim($desc));
            }

        }
        return $doc;
    }

}