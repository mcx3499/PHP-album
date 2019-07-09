<?php
require './common/init.php';
$id = input('get','id','d');
$sort = input('get','sort','s');//获取排序方式
//列出图片
$list = album_list($id,$sort);
$action = input('post','action','s');
if($id && !album_data($id)){//判断相册
	exit('相册不存在！');
}
if($action =='new'){
	album_new($id,input('post','name','s'));
}elseif ($action == 'upload') {
	album_upload($id,input($_FILES,'upload','a'));
}elseif($action == 'pic_cover'){
	album_picture_cover(input('post','action_id','d'),$id);
}elseif($action == 'pic_delete'){
	album_picture_delete(input('post','action_id','d'));
}elseif($action == 'alb_delete'){
	album_delete(input('post','action_id','d'));
}
$nav = album_nav($id);//导航栏
require './view/index.html';
