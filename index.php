<?php 
//   error_reporting(0);
$request = $_SERVER['REQUEST_URI'];
require("./app/database/connection.php");
require_once("./app/services/tokens.php");
date_default_timezone_set('Asia/Kolkata');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Max-Age: 360000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("content-type: application/json");

if(strtoupper($_SERVER["REQUEST_METHOD"]) == "OPTIONS"){
    echo json_encode([]);
    exit;
}




switch ($request){
    case '/login':
        require("./app/controller/loginController.php");
        break;
    case '/getNewToken':
        require("./app/controller/getNewToken.php");
        break;
    default:
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        if(is_jwt_valid($token)){
            
        require("./routes.php");
        routes($request);
        
        
    }else{
        http_response_code(401);
        echo json_encode(['error' => 'Invalid Token']);
            exit;
    }
    break;
}
//  header('Location:resources/views/login.php');
?>