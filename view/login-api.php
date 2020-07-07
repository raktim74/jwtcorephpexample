<?php 
ini_set("display_errors", 1);
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charst: UTF-8');
include_once '../config/database.php';
include_once '../class/User.php';
$db = new Database();
$user_obj = new User($db->connect());
if ($_SERVER["REQUEST_METHOD"] === "POST"){
	$data = json_decode(file_get_contents("php://input"));
	if (!empty($data->email)){
		$user_obj->email = $data->email;
		$loginUser = $user_obj->login();
		if (!empty($loginUser)){
			$name = $loginUser['name'];
			$email = $loginUser['email'];
			$password = $loginUser['password'];
			if (password_verify($data->password, $password)){
				$iss = 'localhost';
				$iat = time();
				$nbf = $iat + 10;
				$exp = $iat + 180;
				$aud = "myusers";
				$user_data = array(
					"name" => $name,
					"email" => $email,
					"id" => $loginUser['id']
				);
				$secret = 'iron maiden';
				$payload_info = array(
					'iss' => $iss,
					'iat' => $iat,
					'nbf' => $nbf,
					'exp' => $exp,
					'aud' => $aud,
					'data' => $user_data

				);
				$jwt = JWT::encode($payload_info, $secret, 'HS512');
				http_response_code(200);
				echo json_encode(["status" => 1, "token" => $jwt, "message" => "User Logged In Successfully"]);
			}else{
				http_response_code(500);
				echo json_encode(["status" => 0, "message" => "Authentication failed"]);
			}
		}else{
			http_response_code(500);
			echo json_encode(["status" => 0, "message" => "Either username and password is wrong"]);
		}
	}else{
		http_response_code(500);
		echo json_encode(["status" => 0, "message" => "Both username and email required"]);
	}
}else{
	http_response_code(500);
	echo json_encode(["status" => 0, "message" => "Undefined Request Method"]);
}
?>