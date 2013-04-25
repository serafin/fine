<?php

class c_setup extends f_c_action
{

    public function __construct()
    {
        if (f::$c->env != 'dev') {
            throw new RuntimeException('Setup can by run only in dev env');
        }
        f::$c->render->off();
    }

    public function checkAction()
    {
     
        /** 
         * @todo sprawdzic prawa zapisu do ./data, ./tmp, ./cache - jezeli instieja
         * 
         */
    }
    
    /**
     * Tworzy nieistniejace modele dla tabeli $this->config->main['db']['name']
     */
    public function modelAction()
    {
        $this->response->header('Content-type', 'text/plain');
        
        foreach ($this->db->rows('SHOW TABLES') as $v) {

            
            $table = $v['Tables_in_' . $this->config->main['db']['name']];
            $file  = 'app/m/' . $table . '.php';

            if (is_file($file)) {
                $this->response->body .= "$table skipped (file exists)\n";
                continue;
            }

            $desc = $this->db->rows('DESCRIBE `' . $table .'`');

            $php = "<?php "
                 . "\n"
                 . "\nclass m_$table extends f_m"
                 . "\n{"
                 . "\n";
            
            foreach ($desc as $field){
                $php .= "\n    " . 'public $' . $field['Field'] . ";";
            }
            
            $php .= "\n"
                  . "\n    /**"
                  . "\n     * Static constructor"
                  . "\n     *"
                  . "\n     * @param array \$config"
                  . "\n     * @return m_$table"
                  . "\n     */"
                  . "\n    public static function _(array \$config = array())"
                  . "\n    {"
                  . "\n        return new self(\$config);"
                  . "\n    }"
                  . "\n" 
                  . "\n}";

            file_put_contents($file, $php);
            chmod($file, 0777);
            chown($file, 'www-data');
            $this->response->body .=  "$table created\n";
            
        }
    }

    public function nbprojectAction()
    {
        $tool = new f_tool_nbprojectPhpDoc();

        $nbproject = "<?php\n\n";

        // main container
        $nbproject .= $tool->renderContainer() . "\n";

        // view files
        $nbproject .= "/* @var \$this f_v */\n\n";

        file_put_contents('nbproject.php', $nbproject);
        chmod('nbproject.php', 0777);

        /** @todo */
        // view container

        echo f_debug::highlight($nbproject, 'php');
    }

}