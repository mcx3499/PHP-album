<?php 
require './common/init.php';
$id = input('get','id','d');

$data = album_picture_data($id);
if(!$data){
	exit("该图片不存在");
}
$pid = $data['pid'];
$nav = album_nav($pid);
$prev = db_fetch_row("SELECT `id` FROM `picture` WHERE `pid`=$pid AND `id`>$id  LIMIT 1")['id'];
$next = db_fetch_row("SELECT `id` FROM `picture` WHERE `pid`=$pid AND `id`<$id ORDER BY `id` DESC LIMIT 1")['id'];
require './view/show.html';
?>