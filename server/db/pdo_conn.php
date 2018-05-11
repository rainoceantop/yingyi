<?php
namespace PdoConn;


class PdoConn{

	private $db_info;
	private $dbtype;
	private $host;
	private $dbname;
	private $user;
	private $pswd;
	private $dsn;
	public  $pdo;

	function __construct(){
		//获取数据库配置信息
		$this->db_info = require "db.config.php";
		$this->dbtype  = $this->db_info['dbtype'];
		$this->host    = $this->db_info['host'];
		$this->dbname  = $this->db_info['dbname'];
		$this->user    = $this->db_info['user'];
		$this->pswd    = $this->db_info['pswd'];
		$this->dsn     = $this->dbtype.":host=".$this->host.";dbname=".$this->dbname;
		$this->_connect($this->dsn, $this->user, $this->pswd);
	}
	private function _connect($dsn, $user, $pswd){
		try {
			$this->pdo = new \PDO($dsn, $user, $pswd);
		} catch (PDOException $e) {
			exit("连接失败".$e->getMessage());
		}
	}
}
