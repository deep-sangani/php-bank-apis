<?php
class Dbconn
{
  function getconnection()
  {
    $servername = "localhost:8889";
    $username = "root";
    $password = "root";
    $dbname = "bank";
    $dsn = 'mysql:host=' . $servername . ';dbname=' . $dbname;
    // Create connection
    $conn = "";
    try {

      $conn = new PDO($dsn, $username, $password);
      // Check connection
      $conn->setAttribute(PDO::FETCH_OBJ, PDO::ERRMODE_EXCEPTION,);
      // echo "database connected";

    } catch (PDOException $e) {
      throw new PDOException($e->getMessage());
    }
    return $conn;
  }
}
