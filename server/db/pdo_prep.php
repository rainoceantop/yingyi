<?php
namespace PdoPrep;
require "pdo_conn.php";
use PdoConn\PdoConn as PdoConn;

//定制化预处理
class PdoPrep extends PdoConn{
	private $table;        //表
	private $sql;          //sql语句
	private $params;       //预处理参数
	private $db_construct; //表结构

	private $first_equation; //当set语句包含多个等式时，False
	function __construct(){
		$this->_initData();
		$this->db_construct = require "db.columns.php";
		parent::__construct();
	}
	function _initData(){
		$this->table = "";
		$this->sql = "";
		$this->params = array();
		$this->first_equation = true;
	}
	function table($table){
		if(array_key_exists($table, $this->db_construct)){
			$this->table = $table;
			return $this;
		} else {
			exit("表格不存在或未在db.columns.php文件中注册");
		}
	}
	//select条件
	function select($columns="*"){
		$table = $this->table;
		$this->sql .= "select $columns from $table";
		return $this;
	}
	//insert条件
	function insert($values, $columns=""){
		$table = $this->table;
		$this->sql .= "insert into $table($columns) values(?,?,?)";
		if(is_array($values))
			$this->params = $values;
		else 
			$this->params = explode(",", $values);
		return $this;
	}
	//update条件
	function update(){
		$table = $this->table;
		$this->sql = "update $table set";
		return $this;
	}
	function set($key, $operator, $value){
		if($this->first_equation){
			$temp = $this->equation("", $key, $operator, $value);
			$this->first_equation = false;
			return $temp;
		} else {
			return $this->equation(",", $key, $operator, $value);
		}
	}
	//delete条件
	function remove(){
		$table = $this->table;
		$this->sql .= "delete from $table";
		return $this;
	}
	//where条件
	function where($key, $operator, $value){
		return $this->equation("where", $key, $operator, $value);
	}
	//and条件
	function _and($key, $operator, $value){
		return $this->equation("and", $key, $operator, $value);
	}
	//or条件
	function _or($key, $operator, $value){
		return $this->equation("or", $key, $operator, $value);
	}
	//等式条件
	function equation($case, $key, $operator, $value){
		if(in_array($key, $this->db_construct[$this->table])){
			$this->sql .= " $case $key $operator ?";
			$this->params[] = $value;
			return $this;
		} else {
			exit("数据库查询出错，请检查sql语句是否合法");
		}
	}
	//order条件
	function order($column, $case="asc"){
		$this->sql .= " order by $column $case";
		return $this;
	}
	//limit条件
	function limit($limit="1"){
		$this->sql .= " limit $limit";
		return $this;
	}
	//执行拼装好的sql语句
	function go(){
		//检验更新/删除条件
		$way = substr($this->sql, 0, 6);
		if($way === "update" || $way === "delete"){
			if(!stripos($this->sql, "where")){
				exit("未检测到更新/删除语句包含where条件，为了数据安全，程序已终止执行");
			}
		}
		$stmt = $this->pdo->prepare($this->sql);
		$stmt->execute($this->params);
		$this->_initData();
		return $stmt;
	}
	//直接执行sql语句
	function execute_sql($sql, $params){
		if(!is_array($params)){
			$params = explode(",", $params);
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt;
	}
}
// $a = new PdoPrep();
// $arr = array(
// "admin3",
// "收到灯具撒屌丝",
// "撒附近的说法和山东if华盛顿佛is地方还哦发货",
// );
// $b = $a->execute_sql("select * from user where user_open_id=? and user_name=?", "admin,我的啊");
// var_dump($b);
// foreach ($b as $key => $value) {
// 	print_r($value);
// }
