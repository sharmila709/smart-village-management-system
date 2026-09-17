<?php

session_start();

include("../db.php");


if(!isset($_POST['login'])){
    header("Location: login.html");
    exit();
}


$email = trim($_POST['email']);
$password = trim($_POST['password']);
$type = $_POST['user_type'];



/* ADMIN LOGIN */

if($type=="admin")
{

    $stmt = $conn->prepare(
        "SELECT * FROM admin_users WHERE email=?"
    );

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();


    if($result->num_rows > 0)
    {

        $admin = $result->fetch_assoc();


        if($password === $admin['password'])
{

$_SESSION['ai_login'] = true;
$_SESSION['ai_role'] = "Admin";
$_SESSION['ai_email'] = $email;


// LOGIN TRACKING

$stmt2 = $conn->prepare(
"INSERT INTO login_history(email,user_type)
VALUES(?,?)"
);

$role="Admin";

$stmt2->bind_param("ss",$email,$role);
$stmt2->execute();


$_SESSION['login_history_id']=$conn->insert_id;



header("Location: ai_dashboard.php");
exit();

}
    }

}




/* CITIZEN LOGIN */

if($type=="citizen")
{


    $stmt = $conn->prepare(
        "SELECT id,name,email,password 
         FROM citizen_login 
         WHERE email=?"
    );


    $stmt->bind_param("s",$email);
    $stmt->execute();


    $result = $stmt->get_result();



    if($result->num_rows > 0)
    {


        $citizen = $result->fetch_assoc();



        if($password === $citizen['password'])
        {


            $_SESSION['ai_login'] = true;
            $_SESSION['ai_role'] = "Citizen";
            $_SESSION['ai_email'] = $email;
               $_SESSION['ai_name'] = $citizen['name'];
            // LOGIN TRACKING

$stmt2 = $conn->prepare(
"INSERT INTO login_history(email,user_type)
VALUES(?,?)"
);

$role="Citizen";

$stmt2->bind_param("ss",$email,$role);
$stmt2->execute();


$_SESSION['login_history_id']=$conn->insert_id;
         



            header("Location: ai_dashboard.php");
            exit();

        }


    }


}



/* FAILED */

echo "
<script>
alert('Invalid Email or Password');
window.location='login.html';
</script>
";

?>