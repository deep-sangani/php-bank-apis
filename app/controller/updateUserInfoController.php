<?php 
require_once(__DIR__."/../services/StoreImg.php");

$data = json_decode(file_get_contents('php://input'), true);
$street_add = $data["street_address"];
$city = $data["city"];
$pincode = $data["postal_code"];
$state = $data["state"];
$mobile_no = $data["mobile_no"];
$acc_no = $data["acc_no"];
$img = isset($data["img"]) ? $data["img"] : null;




if($img){
    $location = dirname(__DIR__,2) . "/uploads/{$acc_no}.png";
// store img

$si = new StoreImg();
$si->delete_image($location);
$si->base64_to_jpeg($img, $location);
}

try {
    $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();


    $sql = "update customer set street_address=?,city=?,state=?,mobile_no=?,postal_code=? where acc_no=?";

    $stmt = $getconn->prepare($sql);

    $result =  $stmt->execute([ $street_add, $city, $state, $mobile_no, $pincode, $acc_no]);
    if ($result == 1) {
       
        $res = array();
        $res["status"] = "success";
        $res["message"] = "customer updated successfully";
        http_response_code(200);
        echo json_encode($res);
        
    }
} catch (PDOException $e) {
    $res = array();
    $res["status"] = "error";
    $res["message"] = $e->getMessage();
    http_response_code(500);
    echo json_encode($res);
}
