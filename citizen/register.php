<?php
session_start();
include("../db.php");

$msg = "";

if(isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];


    if($password != $confirm){

        $msg = "Passwords do not match!";

    }
    else{

        $check = $conn->query("
            SELECT * FROM citizen_login 
            WHERE email='$email'
        ");


        if($check->num_rows > 0){

            $msg = "Email already exists!";

        }
        else{

            $sql = "
            INSERT INTO citizen_login
            (name,email,password)
            VALUES
            ('$name','$email','$password')
            ";


            if($conn->query($sql)){

                echo "
                <script>
                alert('Registration Successful');
                window.location='loginpage.php';
                </script>
                ";

                exit();

            }
            else{

                $msg = "Registration Failed : ".$conn->error;

            }

        } // close duplicate email else

    } // close password else

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Smart Digital Village - Registration</title>


<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}


body{

    background:linear-gradient(135deg,indigo,#6a0dad);

    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    padding:20px;

    font-family:'Playfair Display',serif;

}



.register-container{

    width:420px;

    background:white;

    padding:30px;

    border-radius:15px;

    box-shadow:0 8px 20px rgba(0,0,0,0.3);

}



.logo{

    text-align:center;

    font-size:60px;

}



h2{

    text-align:center;

    margin:10px;

    color:indigo;

}


.subtitle{

    text-align:center;

    color:gray;

    margin-bottom:20px;

}



.input-group{

    margin-bottom:15px;

}


label{

    display:block;

    margin-bottom:5px;

    color:indigo;

    font-weight:bold;

}



input{

    width:100%;

    padding:12px;

    border:1px solid indigo;

    border-radius:8px;

}



.btn{

    width:100%;

    padding:12px;

    background:indigo;

    color:white;

    border:none;

    border-radius:8px;

    cursor:pointer;

    font-size:17px;

}



.btn:hover{

    background:#382865;

}



.msg{

    text-align:center;

    color:red;

    margin-bottom:15px;

}



.login-link{

    text-align:center;

    margin-top:15px;

}



.footer{

    text-align:center;

    margin-top:20px;

    color:gray;

    font-size:13px;

}



@media(max-width:600px){

.register-container{

    width:95%;

}


input,
button{

    width:100%;

}

}


</style>


</head>



<body>


<div class="register-container">


<div class="logo">
🏡
</div>


<h2>
Smart Digital Village System
</h2>


<p class="subtitle">
Create Citizen Account
</p>



<?php if($msg!=""){ ?>

<div class="msg">
<?php echo $msg; ?>
</div>

<?php } ?>




<form method="POST">



<div class="input-group">

<label>Name</label>

<input 
type="text"
name="name"
required>

</div>




<div class="input-group">

<label>Email</label>

<input 
type="email"
name="email"
required>

</div>




<div class="input-group">

<label>Password</label>

<input 
type="password"
name="password"
required>

</div>




<div class="input-group">

<label>Confirm Password</label>

<input 
type="password"
name="confirm"
required>

</div>



<button 
class="btn"
name="register">

Register

</button>



</form>




<div class="login-link">

Already have account?

<a href="loginpage.php">
Login Here
</a>

</div>



<div class="footer">

© 2026 Smart Digital Village System

</div>



</div>


</body>

</html>