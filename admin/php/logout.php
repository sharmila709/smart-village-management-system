<?php

session_start();

include("../../db.php");


if(isset($_SESSION['login_history_id']))
{

$id=$_SESSION['login_history_id'];


$stmt=$conn->prepare(
"UPDATE login_history
SET logout_time=NOW()
WHERE id=?"
);


$stmt->bind_param("i",$id);
$stmt->execute();

}


session_unset();
session_destroy();


header("Location: ../login.html");
exit();

?>