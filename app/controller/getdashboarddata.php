<?php


try {
     $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();
    $sql = 'SELECT SUM(amount) as deposit FROM transaction where type='."'deposit'";
    $stmt = $getconn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
   $data = array();
   $data['total'] = array(
         'deposit' => $result->deposit,
        
    );
    $sql = 'SELECT SUM(amount) as withdraw FROM transaction where type='."'withdraw'";
    $stmt = $getconn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $data['total']['withdraw'] = $result->withdraw;
   
    $sql = 'SELECT account_type,COUNT(*) as total FROM customer GROUP BY account_type';
    $stmt = $getconn->query($sql);
    $accounttype = array();
   while( $result = $stmt->fetch(PDO::FETCH_OBJ)){
      
    $accounttype[$result->account_type] = $result->total;
   }
   $data['accounttype'] = $accounttype;
   

    $res = array();
    $res["status"] = "success";
    $res["message"] = "fetch transaction successfully";
    $res["data"] = $data;
    echo json_encode($res);


} catch (Exception $e) {
    
    $res = array();
    $res["status"] = "error";
    $res["message"] = $e->getMessage();
    http_response_code(500);
    echo json_encode($res);

}

