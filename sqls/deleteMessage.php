<?php

require_once('db.inc');
require_once('settings.inc');



// Get the id passed by the application
$id = $_GET['id'];

$query = "update tbl_uddeim set totrash=1 where id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id',$id);
$error = 1;
if($stmt->execute())
{
   $error = 0;
}
$data->error = $error;
echo json_encode($data);
?>
