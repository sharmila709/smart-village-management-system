<?php
include("../db.php");
session_start();

if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$msg = "";

// UPDATE PROFILE
if(isset($_POST['save_profile'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE citizen_login SET 
            name='$name',
            email='$email',
            phone='$phone'
            WHERE id='$user_id'";

    $conn->query($sql);
    $msg = "Profile updated successfully!";
}

// CHANGE PASSWORD
if(isset($_POST['change_password'])) {

    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $check = $conn->query("SELECT password FROM citizen_login WHERE id='$user_id'");
    $data = $check->fetch_assoc();

    if($data['password'] != $current) {
        $msg = "Current password is wrong!";
    }
    else if($new != $confirm) {
        $msg = "New passwords do not match!";
    }
    else {
        $conn->query("UPDATE citizen_login SET password='$new' WHERE id='$user_id'");
        $msg = "Password updated successfully!";
    }
}

// LOAD USER
$stmt = $conn->prepare(
"SELECT * FROM citizen_login WHERE id=?"
);

$stmt->bind_param("i",$user_id);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


if(!$user){
    die("User profile not found");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Digital Village - Settings</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:indigo;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.container{
    width:700px;
    background:white;
    border-radius:15px;
    padding:30px;
}

h1{
    text-align:center;
    margin-bottom:25px;
}

.section{
    margin-bottom:25px;
    padding-bottom:20px;
    border-bottom:1px solid indigo;
}

label{
    display:block;
    margin-top:12px;
    margin-bottom:5px;
    font-weight:bold;
}

input, select{
    width:100%;
    padding:10px;
    border:1px solid indigo;
    border-radius:8px;
}

.toggle{
    display:flex;
    justify-content:space-between;
    margin:10px 0;
}

.buttons{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
}

button{
    padding:12px 25px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.save{
    background:indigo;
    color:white;
}

.logout{
    background:red;
    color:white;
}
.back{
    background:indigo;
    color:white;
}
.back:hover{
    background:#382865;
}
.msg{
    text-align:center;
    color:green;
    margin-bottom:10px;
}
/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
        min-height:100vh;
    }


    .container{
        width:100%;
        padding:20px;
        border-radius:12px;
    }


    h1{
        font-size:24px;
    }


    h2{
        font-size:20px;
    }


    .section{
        padding-bottom:15px;
        margin-bottom:20px;
    }


    input,
    select{
        font-size:14px;
        padding:10px;
    }


    button{
        width:100%;
        padding:12px;
        margin-top:12px;
    }


    .buttons{
        flex-direction:column;
        gap:10px;
    }


    .save,
    .logout,
    .back{
        width:100%;
    }


    .msg{
        font-size:14px;
    }

}
</style>

</head>

<body style="font-family: 'Playfair Display', serif;">

<div class="container">

    <h1>⚙️ Settings</h1>

    <?php if($msg != "") { ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php } ?>

    <!-- PROFILE -->
    <form method="POST">

    <div class="section">

        <h2>Profile Information</h2>

        <label>Full Name</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>">

        <label>Email Address</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>">

        <label>Mobile Number</label>
        <input type="tel" name="phone" value="<?php echo $user['phone']; ?>">

        <button class="save" type="submit" name="save_profile">Save Profile</button>

    </div>

    </form>

    <!-- PASSWORD -->
    <form method="POST">

    <div class="section">

        <h2>Change Password</h2>

        <label>Current Password</label>
        <input type="password" name="current">

        <label>New Password</label>
        <input type="password" name="new">

        <label>Confirm Password</label>
        <input type="password" name="confirm">

        <button class="save" type="submit" name="change_password">Update Password</button>

    </div>

    </form>

    <!-- LOGOUT -->
    <div class="buttons">

        <button class="logout" onclick="logoutUser()">
            Logout
        </button>
        <button class="back" onclick="window.location.href='dashboard.php'">
            Back
        </button>

    </div>

</div>

<script>
function logoutUser(){
    window.location.href = "logout.php";
}
</script>

</body>
</html>