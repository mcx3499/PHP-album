<?php 
//连接数据库
function db_connect(){
	static $link = null;
	if(!$link){
		$config = array_merge(['host'=>'','user'=>'','pass'=>'','dbname'=>'','port'=>''],config('DB_CONNECT'));//确保数组数据顺序对应
		if(!$link = call_user_func_array('mysqli_connect', $config)){//$config是数组使用回调函数才能不报错
			exit("连接数据库出错：".mysqli_connect_error());
		}
		mysqli_set_charset($link,config('DB_CHARSET'));

	}
	return $link;
}
//预处理执行SQL语句的函数
function db_query($sql,$type='',array $data=[]){
	$link = db_connect();
	//预处理
	if(!$stmt = mysqli_prepare($link,$sql)){
		exit("预处理失败：".mysqli_error($link));
	}
	//数据绑定
	if(!empty($data)){
	$params = [$stmt,$type];
	foreach($data as &$params[]);
	call_user_func_array('mysqli_stmt_bind_param', $params);
    }
    //执行
	mysqli_stmt_execute($stmt);
	return $stmt;
}
//从预处理获取结果
//1)获取所有行
function db_fetch_all($sql,$type='',array $data = []){
	$stmt = db_query($sql,$type,$data);
	return mysqli_fetch_all(mysqli_stmt_get_result($stmt),MYSQLI_ASSOC);
}
//2)获取一行结果
function db_fetch_row($sql,$type='',array $data = []){
	$stmt = db_query($sql,$type,$data);
	return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
function db_exec($sql,$type='',array $data=[]){
	$stmt = db_query($sql,$type,$data);
	return (strtoupper(substr(trim($sql),0,6))=='INSERT')? mysqli_stmt_insert_id($stmt):mysqli_stmt_affected_rows($stmt);
}
//为字符转义
function db_escape_like($like){
	return strtr($like,['%'=>'/%','_'=>'/_','//'=>'////']);
}
?>