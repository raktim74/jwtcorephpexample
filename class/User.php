<?php 
class User{
	public $name;
	public $email;
	public $password;
	private $conn;
	private $user_tbl;
	public function __construct($db){	
		$this->conn = $db;
		$this->user_tbl = 'users';

	}
	public function create_user(){
		$user_query= "INSERT INTO $this->user_tbl SET name = ?, email = ?, password = ?";
		$user_obj_query = $this->conn->prepare($user_query);
		$user_obj_query->bind_param("sss", $this->name, $this->email, $this->password);
		if ($user_obj_query->execute()){
			return true;
		}
		return false;
	}
	public function isDuplicateUser(){
		$user_query = "SELECT * FROM $this->user_tbl WHERE email = ?";
		$user_obj_query = $this->conn->prepare($user_query);
		$user_obj_query->bind_param("s", $this->email);
		$user_obj_query->execute();
		if ($user_obj_query->get_result()->num_rows > 0){
			return true;
		}
		return false;
	}
	public function login(){
		$user_query = "SELECT * FROM $this->user_tbl WHERE email = ?";
		$user_obj_query = $this->conn->prepare($user_query);
		$user_obj_query->bind_param("s", $this->email);
		if ($user_obj_query->execute()){
			$user_data = $user_obj_query->get_result();
			return $user_data->fetch_assoc();
		}
		return array();
	}
}



?>