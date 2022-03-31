<?php 
$data = json_decode(file_get_contents('php://input'), true);
$type = isset($data["type"]) ? $data["type"] : "";


try {
   
    $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();
    $sql='';
    $stmt='';
    if($type=='deposit'){
        $sql = 'SELECT * FROM customer INNER JOIN transaction ON customer.acc_no = transaction.acc_no where transaction.type=? and transaction.amount >0 ORDER BY transaction.date DESC ';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([$type]);
    }
    else if($type=='withdraw'){
        $sql = 'SELECT * FROM customer INNER JOIN transaction ON customer.acc_no = transaction.acc_no where transaction.type=? and transaction.amount >0  ORDER BY transaction.date DESC';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([$type]);
    }else if($type==''){
        $sql = 'SELECT * FROM customer INNER JOIN transaction ON customer.acc_no = transaction.acc_no ORDER BY transaction.date DESC';
        $stmt = $getconn->query($sql);
    }
   
  
    $trans_array = [];
    while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
        $obj = array();
      $obj['name'] = $result->first_name ." ".$result->last_name;
      $obj['email'] = $result->email_address;
      $obj['accountNumber'] = $result->acc_no;
      $obj['amount'] = $result->amount;
      $obj['date'] = date("d F, Y ", strtotime($result->date));
      $obj['time'] = date("h:i:s a", strtotime($result->date));
      $obj['particulars'] = $result->particulers;
      $obj['type'] = $result->type;
      $obj['trasnsactionId'] = $result->transaction_id;
      $obj['mobileNo'] = $result->mobile_no;
      $obj['balance'] = $result->balance;
        array_push($trans_array, $obj);
    }
    $result = array();
    $result['status'] = 'success';
    $result['message'] ='fetch transaction successfully';
    $result['data']= $trans_array;
    echo json_encode($result);
} catch (PDOException $e) {
    $res = array();
    $res["status"] = "error";
    $res["message"] = $e;
    
    http_response_code(500);
    echo json_encode($res);
}
