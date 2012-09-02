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
    
    public function modelAction()
    {
        /** @todo */
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