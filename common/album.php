<?php
function album_list($id,$sort){
	$sort = ($sort == 'old')? 'ASC':'DESC';
	return [
	'album'=>db_fetch_all("SELECT `id`,`name`,`cover`,`total` FROM `album` WHERE `pid` = $id ORDER BY `id` $sort"),
	'picture'=>db_fetch_all("SELECT `id`,`name`,`save` FROM `picture` WHERE `pid` = $id ORDER BY `id` $sort")
	];
}
//以数组方式返回ID的所有信息
function album_data($id){
	static $data = [0 => false];
	if(!isset($data[$id])){
		$data[$id] = db_fetch_row("SELECT `pid`,`path`,`name`,`cover`,`total` FROM `album` WHERE `id`=$id")?:false;
	}
	return $data[$id];
}
function album_new($pid,$name){
	$data = album_data($pid);
    if(substr_count($data['path'], ',') >= config('LEVEL_MAX')){
    	return tips('已达到相册最大层数');
    }
    if(!preg_match('/^.{1,12}$/', $name)){
    	return tips('超出字符限制(12)');
    }
    $name = mb_strimwidth(trim($name), 0, 12);
    db_exec('INSERT INTO `album` (`pid`,`path`,`name`) VALUE (?,?,?)','iss',[$pid,($data['path'].$pid.','),($name?:'未命名')]);
    header("Location:index.php?id=$pid");
}
function album_nav($id){//返回的是数组
	$path = preg_replace('/^0,/','',(album_data($id)['path'].$id));
	return $path ? db_fetch_all("SELECT `id`,`name` FROM `album` WHERE `id` IN ($path) ORDER BY FIELD (`id`,$path) "):[];
}
function album_upload($pid,$file){
	if(true !== ($error = upload_check($file))){
		return tips("文件上传失败:$error");
	}
    $ext = pathinfo($file['name'],PATHINFO_EXTENSION);//获取扩展名
	if(!in_array(strtolower($ext),config('ALLOW_EXT'))){//转化为小写
		return tips('上传失败，只允许扩展名：'.implode(',',config('ALLOW_EXT')));//用逗号连接扩展名数组
	}
	//设定保存目录和保存名
	$new_dir = date("Y-m/d");
    $new_name = md5(microtime(true)).".$ext";
    $upload_dir = "./uploads/$new_dir";
    if(!is_dir($upload_dir) && !mkdir($upload_dir,0777,true)){
    	return tips('创建保存目录失败');
    }
    $thumb_dir = "./thumbs/$new_dir";
    if(!is_dir($thumb_dir) && !mkdir($thumb_dir,0777,true)){
    	return tips('创建缩略图目录失败');
    }
    if(!move_uploaded_file($file['tmp_name'], "$upload_dir/$new_name")){
    	return tips('上传文件出现错误：不能保存文件');
    }    
    thumb("$upload_dir/$new_name","$thumb_dir/$new_name",config('THUMB_SIZE'));
    $name = mb_strimwidth(trim(pathinfo($file['name'],PATHINFO_FILENAME)), 0, 12);
    db_exec('INSERT INTO `picture` (`pid`,`name`,`save`) VALUE (?,?,?)','iss',[$pid,$name,"$new_dir/$new_name"]);
    $pid && album_total($pid,'+1');
    header("Location:index.php?id=$pid");
}
function album_total($id,$method = '+1'){
	$path = preg_replace('/^0,/', '', (album_data($id)['path'].$id));
	$path && db_exec("UPDATE `album` SET `total`=`total`$method WHERE `id` IN ($path)");
}
function album_picture_data($id){
	return db_fetch_row("SELECT `pid`,`name`,`save` FROM `picture` WHERE `id`= $id");
}
function album_picture_cover($id,$pid){
	if(!$data = album_picture_data($id)){
		return tips('图片不存在');
	}
	$cover_dir= "./covers/".dirname($data['save']);
	if(!is_dir($cover_dir) && !mkdir($cover_dir,0777,true)){
		return tips('创建目录失败');
	}
	$cover_del = album_data($pid)['cover'];
	is_file($cover_del) && unlink($cover_del);
	copy("./thumbs/{$data['save']}", "./covers/{$data['save']}");
    db_exec('UPDATE `album` SET `cover` =? WHERE `id` = ?','si',[$data['save'],$pid]);
    tips('设置封面成功');
}
function album_picture_delete($id){
	if(!$data = album_picture_data($id)){
		return tips('图片不存在');
	}
	db_exec("DELETE FROM `picture` WHERE `id` = $id");
	is_file("./thumbs/{$data['save']}") && unlink("./thumbs/{$data['save']}");
	is_file("./uploads/{$data['save']}") && unlink("./uploads/{$data['save']}");
	$data['pid'] && album_total($data['pid'],'-1');	
	header("Location:index.php?id={$data['pid']}");
}
function album_delete($id){
	$data = album_data($id);
	if($data['total'] >0){
		return tips('相册还存在照片！');
	}
	if(db_fetch_row("SELECT 1 FROM `picture` WHERE `pid` = $id")){
		return tips('相册还存在相册！');
	}
	db_exec("DELETE FROM `album` WHERE `id` = $id");
	$data['cover'] && is_file("./covers/{$data['cover']}") && unlink("./covers/{$data['cover']}");
	header("Location:index.php?id={$data['pid']}");
}
function album_tree($id,&$result){
	if(isset($result[$id])){//id与下级冲突
		exit("发现相册id$id 路径异常，请手动修复。");
	}
	$result[$id] = true;//ID记录到result数组里面
	foreach (db_fetch_all("SELECT `id` FROM `album` WHERE `pid`=$id")as $v){
		album_tree($v['id'],$result);//往上查询相册ID
	}
}
function album_path($id){
	$path ='';
	while($id = db_fetch_row("SELECT `pid` FROM `album` WHERE id=$id")['pid']){
		$path = "$id,$path";
	}
	if($id === null){
		exit("发现相册pid".strstr($path,',',true)."不存在，请手动修复。");
	}
	return "0,$path";
}