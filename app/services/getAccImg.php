<?php 
$acc_no = $_GET["acc_no"];
header("Content-type: image/png");

 echo file_get_contents(dirname(__FILE__,3) . "/uploads/{$acc_no}.png");

?>