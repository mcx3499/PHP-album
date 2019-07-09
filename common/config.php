<?php
return [
    'DB_CONNECT'=>[
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'dbname'=>'php_album',
        'port' => '3306'
        ],
    'DB_CHARSET'=>'utf-8',
    'LEVEL_MAX'=>5,//最大相册层
    'ALLOW_EXT'=>['jpg','png','jpeg'],//允许的扩展名
    'THUMB_SIZE'=>200,//缩略图大小设置
];
?>