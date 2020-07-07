<?php
ini_set("display_errors", 1);
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json; charst: UTF-8');
include_once '../config/database.php';
include_once '../class/User.php';
$db = new Database();
$user_obj = new User($db->connect());
if ($_SERVER["REQUEST_METHOD"] === 'GET'){
	$headers = getallheaders();
	$jwt = $headers["Authorization"];
	if ($jwt != ''){
		try{
			$secret = 'iron maiden';
			$decoded_data = JWT::decode($jwt, $secret, array('HS512'));
			http_response_code(200);
			echo json_encode(["status" => 1, "message" => "Authenticated success", "data" => $decoded_data->data]);
		}catch (Exception $ex){
			http_response_code(500);
			echo json_encode(["status" => 1, "message" => $ex->getMessage()]);
		}

	}else{
		http_response_code(500);
		echo json_encode(["status" => 0, "message" => "Token not available"]);
	}

}else{
	http_response_code(500);
	echo json_encode(['status' => 0, 'message' => 'Undefined Method']);
}
?>