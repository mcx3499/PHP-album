<?php 
//接收外部输入
function input($method,$name,$type='s',$default= ''){
  switch ($method) {
  	case 'get':$method = $_GET;break;  	
  	case 'post':$method = $_POST;break;
  }
  $data = isset($method[$name]) ? $method[$name]:$default;
  switch ($type) {
  	case 's':
  		return is_string($data) ? $data:$default;
  	case 'd':
  	    return (int)$data;
  	case 'a':
  	    return is_array($data) ? $data:[]; 	
  	default:
  		trigger_error('不存在的过滤类型"'.$type.'"');
  }
}
//读取配置
function config($name){
	static $config = null;
	if(!$config){
		$config = require './common/config.php';
	}
	return isset($config[$name])? $config[$name]:''; 
} 
function tips($str=null){
  static $tips = null;
  return $str ? ($tips=$str):$tips;
}
//返回上传错误情况
function upload_check($file){
  $error = isset($file['error'])? $file['error']:UPLOAD_ERR_NO_FILE;
  switch($error){
    case UPLOAD_ERR_OK:return is_uploaded_file($file['tmp_name'])?:'非法文件'; 
    case UPLOAD_ERR_INI_SIZE:return '文件大小超过服务器限制';
    case UPLOAD_ERR_FORM_SIZE:return '文件大小超过表单限制';
    case UPLOAD_ERR_PARTIAL:return '文件只有部分被上传';
    case UPLOAD_ERR_NO_FILE:return '没有文件被上传';
    case UPLOAD_ERR_NO_TMP_DIR:return '找不到临时目录';
    case UPLOAD_ERR_CANT_WRITE:return '文件写入失败';
    default: return '未知错误';
  }
}
function thumb($file,$save,$limit){
  $func = ['image/png'=>function ($file,$img= null){
    return $img ? imagepng($img,$file):imagecreatefrompng($file);
  },
           'image/jpeg'=>function ($file,$img= null){
    return $img ? imagejpeg($img,$file):imagecreatefromjpeg($file);
  }];
  $info = getimagesize($file);
  list($width,$height) = $info;
  $mime = $info['mime'];
  if(!in_array($mime,['image/png','image/jpeg'])){
    trigger_error('创建缩略图失败，不支持的图片类型。',E_USER_WARNING);
    return false;
  }
  //设置原图抓取坐标起点
  if($width>$height){
    $size = $height;
    $x = ($width-$size)/2;
    $y = 0;
  }else{
    $size = $width;
    $x = 0;
    $y = ($width-$size)/2;
  }
  $img = $func[$mime]($file);
  $thumb = imagecreatetruecolor($limit, $limit);
  imagecopyresampled($thumb, $img, 0, 0, $x, $y, $limit, $limit, $size, $size);//limit和size不一致会进行等比例缩放
  return $func[$mime]($save,$thumb);
}
?>

