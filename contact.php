<?php


require_once('../includes/connect.php');

try {
  $sql = "INSERT INTO contac_meassges (name, email, messsage, created) VALUES (:name, :email, :messsage, :created)";
$result = $db->prepare($sql);
$values = array(':name'      => $_SESSION['name'],
                ':email'    => $_POST['email'],
                ':messsage'    => $_POST['messsage'],
                ':messsage'  => $_POST['messsage']
                
                );
$res = $result->execute($values) or die(print_r($result->errorInfo(), true));
} catch (\Throwable $th) {
  throw $th;
}


