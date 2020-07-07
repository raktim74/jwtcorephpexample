<?php 
class Database{
	private $host;
	private $uname;
	private $pwd;
	private $db_name;
	private $conn;
	public function connect(){
		$this->host = 'localhost';
		$this->uname = 'root';
		$this->pwd = 'root';
		$this->db_name = 'jwtexample';
		try {
			$this->conn = new mysqli($this->host, $this->uname, $this->pwd, $this->db_name);
			if ($this->conn->connect_errno){
				throw new Exception;
				exit;
			}else{
				return $this->conn;
			}

		}catch (Exception $e){
			print_r($e);
			exit;
		}
	}
}

?>