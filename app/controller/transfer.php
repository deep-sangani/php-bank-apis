<?php
$data = json_decode(file_get_contents('php://input'), true);


$from = $data['from'];
$to = $data['to'];
$amount = $data['amount'];
$particulars = $data['particulars'];
$ref = $data['ref'];
function debit($getconn, $acc_no, $amount,$particulars,$ref)
{
    $sql = "select * from customer where acc_no=?";
    $stmt = $getconn->prepare($sql);
    $stmt->execute([$acc_no]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $balance = $result->balance;
    if($balance<$amount){
      $res = [];
        $res['status'] = 'error';
        $res['message'] = 'Insufficient Balance in Transfer Account';
        echo json_encode($res);
        exit();
    }
    $balance = doubleval($balance) - doubleval($amount);
    $sql = "update customer set balance=? where acc_no=?";
    $stmt = $getconn->prepare($sql);
   $result =$stmt->execute([$balance, $acc_no]);
   
    if($result){
        $sql = 'insert into transaction(date,acc_no,particulers,balance,transaction_id,type,amount) values(?,?,?,?,?,?,?)';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([date('Y-m-d H:i:s'), $acc_no, $particulars, $balance,$ref,'debit',$amount]);
        $result = $stmt->rowCount();
        if($result){
            return true;
        }else{
            return false;
        }
    }

}

function credit($getconn, $acc_no, $amount,$particulars,$ref)
{
    $sql = "select * from customer where acc_no=?";
    $stmt = $getconn->prepare($sql);
    $stmt->execute([$acc_no]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $balance = $result->balance;
    $balance = doubleval($balance) + doubleval($amount);
    $sql = "update customer set balance=? where acc_no=?";
    $stmt = $getconn->prepare($sql);
   $result=$stmt->execute([$amount, $acc_no]);

    if($result){
       
        $sql = 'insert into transaction(date,acc_no,particulers,balance,transaction_id,type,amount) values(?,?,?,?,?,?,?)';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([date('Y-m-d H:i:s'), $acc_no, $particulars, $balance,$ref,'credit',$amount]);
        $result = $stmt->rowCount();
        if($result){
            return true;
        }else{
            return false;
        }
    }

}




try{
    $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();
    $debitResult = debit($getconn,$from,$amount,$particulars,$ref);
    if($debitResult){
        $creditResult = credit($getconn,$to,$amount,$particulars,$ref);
        if($creditResult){
            $res = array();
            $res["status"] = "success";
            $res["message"] = "transaction is success";
            http_response_code(200);
            echo json_encode($res);
        }else{
            $res = array();
            $res["status"] = "error";
            $res["message"] = "error in credit";
            http_response_code(500);
            echo json_encode($res);
        }
    }else{
        $res = array();
        $res["status"] = "error";
        $res["message"] = "error in debit";
        http_response_code(500);
        echo json_encode($res);
    }

}catch(Exception $e){
    
    http_response_code(400);
    $result = array();
    $result["status"] = "error";
    $result["message"] =$e->getMessage(); 
    echo json_encode($result);
}