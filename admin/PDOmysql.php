<?php
//======================================================================
//   ■：MySQL クラス
//======================================================================
class MySQL{
	// VARIABLES
	var $m_Con;
	var $m_HostName = "";
	var $m_UserName = "";
	var $m_Password = "";
	var $m_Database = "";
	var $m_Rows = 0;
	var $stmt;
	//CONSTRUCTER
	function __construct($host, $user, $pass, $db){
		$this->m_HostName=$host;
		$this->m_UserName=$user;
		$this->m_Password=$pass;
		$this->m_Database=$db;
		//MYSQLへ接続
		try{
			$this->m_Con = new PDO('mysql:dbname='.$this->m_Database.';'.
			'host='.$this->m_HostName.';'.
			'charset=utf8',
			$this->m_UserName,
			$this->m_Password);
		}catch (PDOException $e) {
			error("MYSQLの接続に失敗しました:".$e->getMessage());
		}
	}
	//---------------------------
	// SQLクエリの処理
	//---------------------------
	function query($sql){
		$this->stmt = $this->m_Con->query($sql);
		if (!$this->stmt){
			error("MySQLでエラーが発生しました。<br><b>{$sql}</b><br>" .$this->m_Con->errorInfo()[2]);
		}
		//return $this->m_Rows;
	}
	//---------------------------
	// 検索結果をfetch
	//---------------------------
	function fetch(){
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	//---------------------------
	// 行数
	//---------------------------
	function rows(){
		return $this->stmt->rowCount();
	}
	//---------------------------
	// MySQLをクローズ
	//---------------------------
	function close(){
		$this->m_Con=null;
	}

	function fetchAll(){
		return $this->stmt->fetchAll();
	}
	/*
	//---------------------------
	// 変更された行の数を得る
	//---------------------------
	function affected_rows(){
		return mysql_affected_rows();
	}
	//---------------------------
	// 列数
	//---------------------------
	function cols(){
		return mysql_num_fields($this->m_Rows);
	}
	//---------------------------
	// 検索結果の開放
	//---------------------------
	function free(){
		mysql_free_result($this->m_Rows);
	}
	//---------------------------
	// エラーメッセージ
	//---------------------------
	function errors(){
		return mysql_errno().": ".mysql_error();
	}
	//---------------------------
	// エラーナンバー
	//---------------------------
	function errorno(){
		return mysql_errno();
	}
	*/
}

function error($s){
	file_put_contents ( "sql_log.txt", "ERROR:".$s."\n", FILE_APPEND );
	die($s);
}
?>
