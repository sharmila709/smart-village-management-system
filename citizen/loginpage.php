<?php
include("../db.php");
session_start();

/* If already logged in */

$error = "";

if(isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // STEP 1: Check user using prepared statement
    $stmt = $conn->prepare("SELECT id, name, email, password FROM citizen_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // STEP 2: Check password (plain text for now)
        if($password === $user['password']) {
            $_SESSION['user_id']=$user['id'];
            $_SESSION['user_name']=$user['name'];
            $_SESSION['citizen_id']=$user['id'];
            // LOGIN TRACKING

$stmt2 = $conn->prepare(
"INSERT INTO login_history(email,user_type)
VALUES(?,?)"
);

$role="Citizen";


$stmt2->bind_param("ss",$email,$role);
$stmt2->execute();


$_SESSION['login_history_id']=$conn->insert_id;
            
            header("Location: dashboard.php");
            exit();
            

        } else {
            $error = "Invalid password!";
        }

    } else {
        $error = "User not found!";
    }
}
?>