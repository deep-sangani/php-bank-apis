<?php 




try {
    $sql = 'select * from customer';
    $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();
    $stmt = $getconn->query($sql);
   $temp = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $data =array();
         $data['name'] = $row->first_name." ".$row->last_name;
         $data['first_name'] =$row->first_name;
         $data['last_name'] = $row->last_name;
         $data['email'] = $row->email_address;
         $data['acc_no'] = $row->acc_no;
         $data['mobile_no'] = $row->mobile_no;
         $data['balance'] = $row->balance;
         $data['account_type'] = $row->account_type;
        $data['created_at'] = date("d F, Y", strtotime($row->created_at));
        $data['street_address'] = $row->street_address;
        $data['city'] = $row->city;
        $data['state'] = $row->state;
        $data['pan_card'] = $row->pan_card;
        $data['aadhar_card'] = $row->aadhar_card;
        $data['postal_code'] = $row->postal_code;
        array_push($temp, $data);
    }

    $result = array();
    $result['status'] = 'success';
    $result['message'] ='fetch customer successfully';
    $result['data']= $temp;
    echo json_encode($result);


} catch (PDOException $e) {
    $res = array();
    $res["status"] = "error";
    $res["message"] = $e->getMessage();
    http_response_code(500);
    echo json_encode($res);
}
