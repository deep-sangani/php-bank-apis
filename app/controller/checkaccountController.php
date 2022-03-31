<?php include_once(dirname(__FILE__,2)."/services/Checkacc.php");
$data = json_decode(file_get_contents('php://input'), true);
$acc_no = $data["acc_no"];


$checkacc = new Checkacc();
$result = $checkacc->verifiedAcc($acc_no);

if ($result) {
    echo json_encode(array("status" => "success", "message" => "Account number verified","data" => $result));
    
} else {
    http_response_code(404);
    echo json_encode(array("status" => "error", "message" => "Account number not verified"));
}
