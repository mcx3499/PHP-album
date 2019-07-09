<?php
require './common/init.php';
$start  = '0';
$size = '100';
$data = db_fetch_all("SELECT `id`,`pid`,`path`,`total` FROM `album` LIMIT $start,$size");
echo "第".($start+1)."到".($start+$size)."行数据检查完成。";//查询album表所有数据
foreach($data as $v){
	$result = [];
	album_tree($v['id'],$result);//所有相册ID是否冲突
	//检查total字段
	$pids = implode(',',array_keys($result));//相册ID拼接
	$total = db_fetch_row("SELECT COUNT(*) as `t` FROM `picture` WHERE `pid` IN ($pids)")['t'];
	if($v['total'] != $total){
		echo "ID={$v['id']}的字段有误，修复";
		echo db_exec("UPDATE `album` SET `total`='$total' WHERE `id`={$v['id']}")? '成功':'<b>失败</b>','。<br />';
	}
    //检查path字段
	$path = album_path($v['id']);
	if($v['path'] !=$path){
		echo "ID={$v['id']}的path字段有误，修复";
		echo db_exec("UPDATE `album` SET `path`='$path' WHERE `id`={$v['id']}")? '成功':'<b>失败</b>','。<br />';
	}
}