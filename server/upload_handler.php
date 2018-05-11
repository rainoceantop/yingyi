<?php

require_once "db/pdo_prep.php";
use PdoPrep\PdoPrep as PdoPrep;

$openid = $_POST['openid'];
$img = $_FILES['img'];
$info = $_POST['info']=="[object Undefined]"?"":$_POST['info'];
$img_name = $img['name'];
echo $info;

//获取图片后缀名
$ext = substr($img_name, strrpos($img_name, '.'));
//定义图片名称
$name = $openid.'_'.date('Ymdhisa').$img_name.$ext;
$src = "img/".$name;
//将图片保存至img文件夹
move_uploaded_file($img['tmp_name'], $src);

//将图片信息记录到数据库
$db = new PdoPrep();
$columns = "img_src,img_likes,user_id,img_info";
$values = "/server/$src,0,$openid,$info";
try{
	echo $db->table("imgs")->insert($values,$columns)->go();
}catch(PDOException $e){
	echo "插入失败：".$e;
}
