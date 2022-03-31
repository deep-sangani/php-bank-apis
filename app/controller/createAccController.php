
<?php 

require_once(__DIR__."/../services/CreateAccno.php");
require_once(__DIR__."/../services/StoreImg.php");
if ($data = json_decode(file_get_contents('php://input'), true)) {
    
    // fetch all the req body
    $first_name = $data["first_name"];
    $last_name = $data["last_name"];
    $email_address = $data["email_address"];
     $country = $data["country"];
    $street_address = $data["street_address"];
    $city = $data["city"];
    $state = $data["state"];
    $postal_code = $data["postal_code"];
    $account_type = $data["account_type"];
    $aadhar_card = $data["aadhar_card"];
    $pan_card = $data["pan_card"];
    $mobile_no = $data["mobile_no"];
    $img = $data["img"];
    $target_dir = __DIR__ . "/uploads/";


    // $filename = $_FILES['customer_photo']['name'];
    // $file_ext = explode(".", $filename)[1];

    // genrate account no
    $ca = new CreateAccount();
    $acc_no = $ca->createacc();
     $location = dirname(__DIR__,2) . "/uploads/{$acc_no}.png";
    // store img
    $si = new StoreImg();
    $si->base64_to_jpeg($img, $location);


    // make sql query and save data to database

    try {
        $dbconn = new Dbconn();
        $getconn = $dbconn->getconnection();
        $sql = 'insert into customer(first_name,last_name,email_address,street_address,city,state,account_type,pan_card,mobile_no,aadhar_card,postal_code,acc_no) values (?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([$first_name, $last_name, $email_address, $street_address, $city, $state, $account_type, $pan_card, $mobile_no, $aadhar_card, $postal_code, $acc_no]);
        
        $res = array();
        $res["status"] = "success";
        $res["message"] = "create account successfully";
        $res["acc_no"] = $acc_no;
        // $res["acc_img_url"] = "http://localhost:3000/app/services/getAccImg.php?acc_no={$acc_no}";
        http_response_code(200);
        mb_http_output('UTF-8');
        echo json_encode($res);
    } catch (PDOException $e) {
        $res = array();
        $res["status"] = "error";
        $res["message"] = "create account failed";
        $res["error"] = $e->getMessage();
        http_response_code(500);
        echo json_encode($res);

        // echo "exception : " . $e->getMessage();
    }
}else{
    $res = array();
    $res["status"] = "error";
    $res["message"] = "create account failed";
    $res["error"] = "no data found";
    http_response_code(500);
    echo json_encode($res);
}
?>