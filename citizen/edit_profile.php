<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}
$user_id=$_SESSION['user_id'];
$msg = "";

/* ======================
   UPDATE PROFILE
====================== */
if(isset($_POST['update'])){

    $phone = $_POST['phone'];
    $village = $_POST['village'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    $profile_pic = $_POST['old_pic'];

    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name']!=""){

        $file = time()."_".$_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES['profile_pic']['tmp_name'],"../uploads/".$file);

        $profile_pic = $file;
    }

    $sql = "UPDATE citizen_login SET 
            phone='$phone',
            village='$village',
            dob='$dob',
            gender='$gender',
            address='$address',
            profile_pic='$profile_pic'
            WHERE id='$user_id'";

    if($conn->query($sql)){
        header("Location: profile.php");
        exit();
    } else {
        $msg = "Update failed";
    }
}

$user = $conn->query("SELECT * FROM citizen_login WHERE id='$user_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,indigo);
    padding:20px;
}

.container{
    max-width:600px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:12px;
}

input,select,textarea{
    width:100%;
    padding:10px;
    margin-top:8px;
}

label{
    font-weight:bold;
    color:indigo;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    background:indigo;
    color:white;
    border:none;
    border-radius:8px;
}

a{
    display:block;
    text-align:center;
    margin-top:10px;
}
/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .container{
        width:100%;
        padding:18px;
        border-radius:12px;
    }


    h1{
        font-size:24px;
    }


    input,
    select,
    textarea{
        font-size:14px;
        padding:10px;
    }


    label{
        font-size:14px;
    }


    textarea{
        min-height:100px;
        resize:vertical;
    }


    button{
        font-size:15px;
        padding:12px;
    }


    a{
        font-size:14px;
    }

}
</style>
</head>

<body>

<div class="container">

<h1 style="text-align:center;">✏ Edit Profile</h1>

<?php if($msg!="") echo "<p style='color:red;text-align:center;'>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="old_pic" value="<?php echo $user['profile_pic']; ?>">

<label>Profile Photo</label>
<input type="file" name="profile_pic">

<label>Phone</label>
<input type="text" name="phone" value="<?php echo $user['phone']; ?>">

<label>Village</label>
<input type="text" name="village" value="<?php echo $user['village']; ?>">

<label>DOB</label>
<input type="date" name="dob" value="<?php echo $user['dob']; ?>">

<label>Gender</label>
<select name="gender">
<option <?php if($user['gender']=="Male") echo "selected"; ?>>Male</option>
<option <?php if($user['gender']=="Female") echo "selected"; ?>>Female</option>
<option <?php if($user['gender']=="Other") echo "selected"; ?>>Other</option>
</select>

<label>Address</label>
<textarea name="address"><?php echo $user['address']; ?></textarea>

<button type="submit" name="update">Save Changes</button>

</form>

<a href="profile.php">← Back to Profile</a>

</div>

</body>
</html>