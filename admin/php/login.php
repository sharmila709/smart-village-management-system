<?php
session_start();
include("db.php");

// ✅ check inputs
if(!isset($_POST['email']) || !isset($_POST['password'])){
    echo "error";
    exit();
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// ❌ empty check
if($email == "" || $password == ""){
    echo "error";
    exit();
}

// ✅ prepared statement (SECURE)
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();

    // ✅ password check (for now plain text)
   if($password === $row['password']){

    $_SESSION['admin'] = $email;


    // LOGIN TRACKING

    $stmt2 = $conn->prepare(
    "INSERT INTO login_history(email,user_type)
    VALUES(?,?)"
    );


    $role="Admin";


    $stmt2->bind_param("ss",$email,$role);
    $stmt2->execute();


    $_SESSION['login_history_id']=$conn->insert_id;



    header("Location: ../dashboard.php");
    exit();

} else {
        echo "error";
    }
} else {
    echo "error";
}
?>