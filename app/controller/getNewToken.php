<?php

$headers = apache_request_headers();
$token = $headers['Authorization'];

$tokenParts = explode('.', $token);
	
$payload = base64_decode($tokenParts[0]);
$empid = json_decode($payload)->empid;

if($empid){
    $jwt = generate_jwt(array("empid" => $empid,'exp'=>(time() +60 )));
    http_response_code(200);
    echo json_encode(array("token" => $jwt));

}

