<?php

return array(
    'dev' => array(
        'env' => 'dev',
        'db' => array (
            'host'    => 'localhost',
            'name'    => 'XXXXXXXXXXXXXXX',
            'user'    => 'XXXXXXXXXXXXXXX',
            'pass'    => 'XXXXXXXXXXXXXXX',
            'charset' => 'utf8'
        ),
        'error' => array(
            'render'     => true,
            'level'      => E_ALL ^ E_NOTICE,
            'log'        => true,
            'throwError' => E_ALL ^ E_NOTICE,
        ),
    ),
    'prod' => array(
        'env' => 'prod',
        'error' => array(
            'render'     => false,
            'level'      => E_ALL ^ E_NOTICE,
            'log'        => true,
            'throwError' => 0,
        ),
        'error_notify' => array(
            'path' => '', /*fe. /statistic/logs/error_log */
            'email' => array(
                /* fe.
                'xxx1@xxxx.xx',
                'xxx2@xxxx.xx',
                 * 
                 */
             )
        ),
        'db' => array (
            'host'    => 'localhost',
            'name'    => 'XXXXXXXXXXXXXXX',
            'user'    => 'XXXXXXXXXXXXXXX',
            'pass'    => 'XXXXXXXXXXXXXXX',
            'charset' => 'utf8'
        ),
    ),
);