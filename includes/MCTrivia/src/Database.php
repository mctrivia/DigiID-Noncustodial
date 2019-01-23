<?php
namespace MCTrivia;



class Database {
	private $_mysqli;
	
	public function __construct() {
		try {
			$this->_mysqli=new \mysqli(HOST, USER, PASS, DATA);
			$failed=$this->_mysqli->connect_error;
		} catch (\Exception $e) {
			$failed=true;
		}
		if ($failed) throw new \Exception("Database error");
	}
	
	public function __call($method, $params) {
		return $this->_mysqli->$method(...$params);
		$error=$this->_mysqli->error;
		if ($error) throw new \Exception($error);
	}
	
	public function error() {
		return $this->_mysqli->error;
	}
}



/*
 remember on statements
 $this->_stmt->execute();
if ($store) $this->_stmt->store_result();


	//$this->bind_param('s',$txid);
	//$this->bind_result($balance);
	
	$this->_stmt->fetch();
	
*/