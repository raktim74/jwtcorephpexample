<?php 
ini_set("display_errors", 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Content-Type: application/json; charst: UTF-8');
include_once '../config/database.php';
include_once '../class/User.php';
$db = new Database();
$user_obj = new User($db->connect());
if ($_SERVER['REQUEST_METHOD'] === "POST"){
	$data = json_decode(file_get_contents("php://input"));
	if (!empty($data->name) && !empty($data->email) && !empty($data->password)){
		$user_obj->name = $data->name;
		$user_obj->email = $data->email;
		$user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);
		if ($user_obj->isDuplicateUser()){
			http_response_code(200);
			echo json_encode(["status" => 1, "message" => "User already exists"]);
			exit;
		}
		if ($user_obj->create_user()){
			http_response_code(200);
			echo json_encode(["status" => 1, "message" => "User is successfully created"]);
		}else{
			http_response_code(500);
			echo json_encode(["status" => 0, "message" => "Error while inserting record"]);
		}
	}else{
		http_response_code(500);
		echo json_encode(["status" => 0, "message" => "Mandatory fields are required"]);
	}
}else{
	http_response_code(500);
	echo json_encode(["status" => 0, "message" => "Undefined Request Method"]);
}

?>