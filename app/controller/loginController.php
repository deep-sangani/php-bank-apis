
<?php 

try {
   
    if ($data = json_decode(file_get_contents('php://input'), true)) {

        $eid = $data["empid"];
        $pass = $data["pass"];
       
        $dbconn = new Dbconn();
        $getconn = $dbconn->getconnection();
    
    
        $sql = 'select * from emp where empid=? and password=?';
        $stmt = $getconn->prepare($sql);
        $stmt->execute([$eid, $pass]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        

        
    
        if ($result) {
            $result = array();
            $result["status"] = "success";
            $result["message"] = "Login Successfull";
            $result["token"] = generate_jwt(array("empid" => $eid,'exp'=>(time() +60*60 )));
            echo json_encode($result);
           
        } else {
            throw new Exception("username or password is wrong  !!");
        }
    }
} catch (Exception $th) {
    //throw $th;
    http_response_code(400);
    $result = array();
    $result["status"] = "error";
    $result["message"] =$th->getMessage(); 
    echo json_encode($result);
}

?>