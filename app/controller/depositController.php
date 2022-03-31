<?php



$data = json_decode(file_get_contents('php://input'), true);
$acc_no = $data["acc_no"];
$deposit_amt = $data["deposit_amt"];
$particulars = $data["particulars"];
$transactionid= $data["transactionid"];

// fetch old record from database

try {

    $dbconn = new Dbconn();
    $getconn = $dbconn->getconnection();

    $sql = 'select balance from customer where acc_no=?';
    $stmt = $getconn->prepare($sql);
    $stmt->execute([$acc_no]);
    $result = $stmt->fetch(PDO::FETCH_OBJ)->balance;
    $balance = doubleval($result) + doubleval($deposit_amt);
    $sql = 'update customer set balance = ? where acc_no = ?';
    $stmt = $getconn->prepare($sql);
    $stmt->execute([$balance, $acc_no]);
    $result = $stmt->rowCount();
    if ($result == 1) {
        // set data in transaction table
        $sql = 'insert into transaction(date,acc_no,particulers,balance,transaction_id,type,amount) values(?,?,?,?,?,?,?)';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([date('Y-m-d H:i:s'), $acc_no, $particulars, $balance,$transactionid,'deposit',$deposit_amt]);
        $result = $stmt->rowCount();
        if ($result == 1) {
            

           
            $res = array();
            $res["status"] = "success";
            $res["message"] = "balance is deposited successfully";
            $res["acc_no"] = $acc_no;
            $res["balance"] = $balance;
           
            
            echo json_encode($res);

        }else{
            $res = array();
            $res["status"] = "error";
            $res["message"] = "error in inserting data in transaction table";
            http_response_code(500);
            echo json_encode($res);
        }
    }
} catch (PDOException $e) {
    $res = array();
    $res["status"] = "error";
    $res["message"] = $e->getMessage();
    http_response_code(500);
    echo json_encode($res);

    // echo $e;
}
