<?php

require "db/pdo_prep.php";
use PdoPrep\PdoPrep as PdoPrep;


$openid = $_GET["openid"];
// $openid="admin";

//尝试获取信息
try{
	$db = new PdoPrep();
	$user = $db->table("user")->select()->where("user_open_id","=",$openid)->limit()->go()->fetch();
	$imgs = $db->table("imgs")->select()->where("user_id","=",$openid)->order("img_id", "desc")->go()->fetchAll();
  // var_dump($user);
  // var_dump($imgs);
}catch(PDOException $e){
	echo "信息获取失败：".$e;
  // echo "hah it's me :D";
}
$res = array(
	"user"=>$user,
	"imgs"=>$imgs
);
echo json_encode($res);