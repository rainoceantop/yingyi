<?php
namespace PdoFunc;
require "pdo_conn.php";
use PdoConn\PdoConn as PdoConn;

class PdoFunc extends PdoConn{
	private $initSql = array(
		"way" => "",
		"where" => "",
		"order" => "",
		"limit" => "",
	);
	private $sql;
	function __construct(){
		$this->sql = $this->initSql;
		parent::__construct();
	}
	//通用查询
	function query_by_sql($sql, $case="e"){
		if($case == "e")
			return $this->e($sql);
		else if($case == "q")
			return $this->q($sql);
		else
			return;
	}
	//查询
	function select($table, $column="*"){
		//column.table,where,condition,limit,groupby,having
		$this->sql["way"] = "select ".$column." from ".$table;
		return $this;
	}
	//插入
	function insert($table){
		//获取传入参数个数
		$num_args = func_num_args();
		if($num_args==2){
			//2个参数：表和值
			//获取值
			$values = func_get_arg(1);
			//判断值是数组或字符串
			if(is_array($values)){
				//数组情况
				$values = $this->array_item_add_single_quote_to_str($values);
			} else {
				//字符串情况
				$values = explode(",", $values);
				$values = $this->array_item_add_single_quote_to_str($values);
			}
			$this->sql["way"] = "insert into ".$table." values(".$values.")";
		} else {
			//3个参数：表、列和值
			//获取列
			$columns = func_get_arg(1);
			//获取值
			$values = func_get_arg(2);
			//判断值是数组或字符串
			if(is_array($values)){
				//数组情况
				$values = $this->array_item_add_single_quote_to_str($values);
			} else {
				//字符串情况
				$values = explode(",", $values);
				$values = $this->array_item_add_single_quote_to_str($values);
			}
			$this->sql["way"] = "insert into ".$table."(".$columns.") values(".$values.")";
		}
		return $this;
	}
	//将数组的每个值添加单引号转成字符串
	function array_item_add_single_quote_to_str($array){
		return implode(",", array_map(function($v){
			return "'".$v."'";
		}, $array));
	}
	//更新
	function update($table, $set, $where){
		$this->sql["way"] = "update ".$table." set ".$set." where ".$where;
		return $this;
	}
	//删除
	function remove($table, $where){
		$this->sql["way"] = "delete from ".$table." where ".$where;
		return $this;
	}
	//删除所有
	function remove_all($table){
		$this->sql["way"] = "delete from ".$table;
		return $this;
	}
	//where条件
	function where($key, $operator, $val){
		$val = "'".$val."'";
		$this->sql["where"] = "where $key $operator $val";
		return $this;
	}
	//limit条件
	function limit($limit=1){
		$this->sql["limit"] = "limit ".$limit;
		return $this;
	}
	//order条件
	function order($order, $case="desc"){
		$this->sql["order"] = "order by ".$order." ".$case;
		return $this;
	}
	//执行方式入口
	function go($case="e"){
		$sql = implode(" ", $this->sql);
		//执行完一条语句后初始化sql
		$this->sql = $this->initSql;
		$res = null;
		eval("\$res = \$this->\$case(\$sql);");
		return $res;
	}
	//执行exec
	function e($sql){
		try{
			return $this->pdo->exec($sql);
		}catch(PDOException $e){
			exit("数据库操作出错：".$e);
		}
	}
	//执行query
	function q($sql){
		try{
			return $this->pdo->query($sql);
		}catch(PDOException $e){
			exit("数据库操作出错：".$e);
		}
	}
}

// $pdo = new PdoFunc();
// $res = $pdo->select("user")->where("user_open_id='admin'")->limit()->go("q");
// foreach($res as $index => $row){
// 	echo $row['user_signature'];
// }