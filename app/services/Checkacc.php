<?php 
class Checkacc
{

    function verifiedAcc( $acc_no)
    {
        try {
            $sql = 'select * from customer where acc_no=?';
            $dbconn = new Dbconn();
            $getconn = $dbconn->getconnection();
            $stmt = $getconn->prepare($sql);
            $stmt->execute([$acc_no]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return ($result);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
