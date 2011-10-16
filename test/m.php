<?php
/**
 * join z 4 parametrami (dochodzi jeden parametr)
 * nie definiujemy ref  i dep, szukam tak:
 * inicjacla _rel(), ref, dep, nie ma, to robie 1:1
 * _rel(name, polewlasne, model obcy, pole obce, where, )
 */


require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()
    ->path(array('./', '../src/lib/'))
    ->register();

f_error::_()
    ->level(E_ALL ^ E_NOTICE)
    ->render(true)
    ->renderFormat(f_error::RENDER_FORMAT_TEXT)
    ->register();

/* f::$c - main contianer*/

class c
{
    public function __get($name)
    {
        return $this->{"_$name"}();
    }

    protected function _db()
    {
        return $this->db = new f_agent(array('subject' => new stdClass(), 'event' => 'agent'));
    }

    protected function _event()
    {
        return $this->event = new f_event_dispacher();
    }

    protected function _m()
    {
        return $this->m = new f_m_dispacher(array('prefix' => 'm_'));
    }

}

f::$c = new c();

/* models */

class m_comment extends f_m
{

    public $comment_id;
    public $comment_id_post;
    public $comment_insert;
    public $comment_content;
    
    public static function _()
    {
        return new self;
    }

}

class m_img extends f_m
{

    public $img_id;
    public $img_id_user;
    public $img_insert;
    public $img_token;

    public static function _()
    {
        return new self;
    }

}

class m_pm extends f_m
{

    public $pm_id;
    public $pm_id_user_from;
    public $pm_id_user_to;
    public $pm_insert;
    public $pm_content;

    public static function _()
    {
        return new self;
    }

}

class m_post extends f_m
{

    public $post_id;
    public $post_id_user;
    public $post_insert;
    public $post_title;

    public static function _()
    {
        return new self;
    }

}

class m_select extends f_m
{

    public $select_id;
    public $select_data;

    public static function _()
    {
        return new self;
    }

}

class m_user extends f_m
{

    public $user_id;
    public $user_id_img;
    public $user_name;
    public $user_email;
    public $user_pass;

    public static function _()
    {
        return new self;
    }

}

class test_m extends f_test
{

    protected static $_query;
    protected static $_method;
    protected static $_select;

    public static function _event($oEvent)
    {
        if ($oEvent->type != 'call') {
            return;
        }
        
        if ($oEvent->name == 'escape') {
            $oEvent->val   = addslashes($oEvent->arg[0]);
            $oEvent->break = true;
            return;
        }

        if (isset(self::$_select[(string)$oEvent->arg[0]])) {
            $oEvent->val   = self::$_select[$oEvent->arg[0]];
        }

        self::$_query  = $oEvent->arg[0];
        self::$_method = $oEvent->name;
        $oEvent->break = true;
    }

    public function __construct()
    {
        f::$c->event->on('agent', array('test_m', '_event'));
        parent::__construct();
    }

    protected function _q($sQuery, $sInfo = null)
    {
        if ($sInfo === null) {
            $sInfo = 'Zapytanie SQL';
        }
        $this->_testEqual(self::$_query, $sQuery, $sInfo);
    }

    public function test()
    {

        
    }


    public function where()
    {
        $o = new m_post();

        $o->select(1);
        $this->_q("SELECT `post_id`, `post_id_user`, `post_insert`, `post_title` FROM `post` WHERE `post_id` = '1'");

        $o->select(array('user_id' => array('12', '13')));
        $this->_q("SELECT `post_id`, `post_id_user`, `post_insert`, `post_title` FROM `post` WHERE `user_id` IN ('12','13')");

        $o->select(array('user_id|NOT BETWEEN' => array('12', '13')));
        $this->_q("SELECT `post_id`, `post_id_user`, `post_insert`, `post_title` FROM `post` WHERE `user_id` NOT BETWEEN '12' AND '13'");

        $o->select(array('user_id|LIKE' => '%1'));
        $this->_q("SELECT `post_id`, `post_id_user`, `post_insert`, `post_title` FROM `post` WHERE `user_id` LIKE '%1'");

    }

    public function field()
    {
        $o = new m_post();

        $o->field('post_id post_title');
        $o->select(1234);
        $this->_q("SELECT `post_id`, `post_title` FROM `post` WHERE `post_id` = '1234'");

        $o->addField('post_insert');
        $o->select(1234);
        $this->_q("SELECT `post_id`, `post_title`, `post_insert` FROM `post` WHERE `post_id` = '1234'");
    }

//    public function join()
//    {
//        $oPm = m_pm::_()
//            ->join(array('f'  => 'user_from'), 'user_name')
//            ->join(array('t'  => 'user_to'  ), 'user_name')
//            ->join(array('fi' => 'img'      ), 'img_token', array('f' => 'user'))
//            ->join(array('ti' => 'img'      ), 'img_token', array('t' => 'user'))
//            ->param(array(
//                'pm_id_user_form' => 1234,
//                'limit'           => 25,
//            ))
//            ->selectAll()
//        ;
//        $this->_q(
//            "SELECT `pm_id`, `pm_id_user_from`, `pm_id_user_to`, `pm_insert`, `pm_content`, "
//            ."`f`.`user_name` as `f_user_name`, `t`.`user_name` as `t_user_name`, "
//            ."`fi`.`img_token` as `fi_img_token`, `ti`.`img_token` as `ti_img_token` "
//            ."FROM `pm` "
//            ."JOIN `user` as `f` ON (`pm_id_user_from` = `f`.`user_id`) "
//            ."JOIN `user` as `t` ON (`pm_id_user_to` = `t`.`user_id`) "
//            ."JOIN `img` as `fi` ON (`f`.`user_id_img` = `fi`.`img_id`) "
//            ."JOIN `img` as `ti` ON (`t`.`user_id_img` = `ti`.`img_id`) "
//            ."WHERE `pm_id_user_form` = '1234' LIMIT 25"
//        );
//
//    }
//
    public function insert()
    {
        $o = new m_post();
        $o->post_id_user = '1';
        $o->post_insert  = time() - rand (0, 30 * 24 * 60 * 60);
        $o->post_title   = "'".md5(uniqid(rand(), true));
        $o->save();

        $this->_q("INSERT INTO `post` SET `post_id` = '', `post_id_user` = '1', `post_insert` = '$o->post_insert', `post_title` = '" . f::$c->db->escape($o->post_title) . "'");
    }
//
//    public function joinByDependentModel_newFeature()
//    {
//        $oComment = new m_comment();
//        $oComment->join('post');
//        $oComment->select(1234);
//        $this->_q("SELECT `comment_id`, `comment_id_post`, `comment_insert`, `comment_content`, `post_id`, `post_id_user`, `post_insert`, `post_title` FROM `comment` JOIN `post` ON (`comment_id_post` = `post_id`) WHERE `comment_id` = '1234'");
//    }
//
//    public function dependentModelObject()
//    {
//
//        $oPost = new m_post();
//        $oPost->id(1234);
//        $oPost->comment->selectAll();
//        $this->_q("SELECT `comment_id`, `comment_id_post`, `comment_insert`, `comment_content` FROM `comment` WHERE `comment_id_post` = '1234'");
//
//        $oPost->comment->comment_insert = time();
//        $oPost->comment->comment_id_post = '9876';
//        $oPost->comment->save();
//        $this->_q("INSERT INTO `comment` SET `comment_id` = '', `comment_id_post` = '1234', `comment_insert` = '{$oPost->comment->comment_insert}', `comment_content` = ''");
//
//        $oPost->comment->insertAll(array(
//            array('comment_insert' => time(), 'comment_id_post' => '9876'),
//            array('comment_insert' => time(), 'comment_id_post' => '9876')
//        ));
//        $this->_q("INSERT INTO `comment` (`comment_insert`, `comment_id_post`) VALUES ('".time()."', '1234'), ('".time()."', '1234')");
//
//        $oUser = new m_user();
//        $oUser->id(1234);
//        $oUser->pm_to->selectAll(array('limit' => 20, 'order' => 'pm_insert DESC'));
//        $this->_q("SELECT `pm_id`, `pm_id_user_from`, `pm_id_user_to`, `pm_insert`, `pm_content` FROM `pm` WHERE `pm_id_user_to` = '1234' ORDER BY pm_insert DESC LIMIT 20");
//
//
//        $oPost = new m_post();
//        $oPost->id(1234);
//        $oPost->comment->selectAll();
//        $this->_q("SELECT `comment_id`, `comment_id_post`, `comment_insert`, `comment_content` FROM `comment` WHERE `comment_id_post` = '1234'");
//
//        unset($oPost->comment);
//        $oPost->id(123456);
//        $oPost->comment->selectAll();
//        $this->_q("SELECT `comment_id`, `comment_id_post`, `comment_insert`, `comment_content` FROM `comment` WHERE `comment_id_post` = '123456'");
//
//
//    }

    public function iterator()
    {
        self::$_select["SELECT `post_id` FROM `post`"] = array(
            array("1"), array("2"), array("3"), array("4"), array("5"));

        $oPost = new m_post();
        $oPost->field('post_id');

        $i = 0;
        foreach (m_post::_()->field('post_id')->selectCol() as $post) {
            $i++;
        }
        $this->_q("SELECT `post_id` FROM `post`");
        $this->_testEqual($i, 5, "iteracja powinna sie odbyc 5 razy");

        unset(self::$_select["SELECT `post_id` FROM `post`"]);
    }

//    public function loop()
//    {
//        self::$_select["SELECT `post_id` FROM `post`"] = array(
//            array("1"), array("2"), array("3"), array("4"), array("5"));
//        $oPost = new m_post();
//        $oPost->field('post_id');
//        $oPost->selectLoop();
//        while($oPost->selectNext()->id()) {
//            var_dump(self::$_query);
//            break;
//        }
//
//
//         unset(self::$_select["SELECT `post_id` FROM `post`"]);
//   }

   public function reservedWords()
   {
       $o = new m_select();
       $o->select(1);
       $this->_q("SELECT `select_id`, `select_data` FROM `select` WHERE `select_id` = '1'");
   }



}

new test_m();

