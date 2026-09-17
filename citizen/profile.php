<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* GET LOGGED-IN USER */

$stmt = $conn->prepare("
SELECT *
FROM citizen_login
WHERE id=?
");

$stmt->bind_param("i",$user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user){
    die("Citizen not found");
}


/* FIND ACTUAL CITIZEN TABLE ID */

$stmt = $conn->prepare("
SELECT *
FROM citizens
WHERE name=?
");

$stmt->bind_param("s",$user['name']);
$stmt->execute();

$citizen = $stmt->get_result()->fetch_assoc();

$cid = $citizen['id'] ?? 0;


/* PROPERTY COUNT */

$stmt = $conn->prepare("
SELECT COUNT(*) AS total
FROM properties
WHERE citizen_id=?
");

$stmt->bind_param("i",$cid);
$stmt->execute();

$prop = $stmt->get_result()->fetch_assoc();


/* TAX COUNT */

$stmt = $conn->prepare("
SELECT COUNT(*) AS total
FROM taxes
WHERE citizen_id=?
");

$stmt->bind_param("i",$cid);
$stmt->execute();

$tax = $stmt->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>

<title>My Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

body{
font-family:Arial;
background:linear-gradient(135deg,indigo,#6a0dad);
padding:20px;
}

.container{
max-width:800px;
margin:auto;
background:white;
padding:25px;
border-radius:15px;
}

.header{
text-align:center;
}

.profile-pic{
width:130px;
height:130px;
border-radius:50%;
object-fit:cover;
}


.stats{
display:flex;
gap:20px;
margin-top:20px;
}

.card{
flex:1;
background:indigo;
color:white;
padding:20px;
border-radius:10px;
text-align:center;
}


.section{
margin-top:20px;
padding:15px;
border:1px solid #ddd;
border-radius:10px;
}


.btn{
display:block;
background:indigo;
color:white;
padding:12px;
margin-top:15px;
text-align:center;
text-decoration:none;
border-radius:8px;
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


    .profile-pic{
        width:100px;
        height:100px;
    }


    .header h2{
        font-size:22px;
        margin-top:10px;
    }


    .header p{
        font-size:14px;
        word-break:break-word;
    }


    .stats{
        flex-direction:column;
        gap:15px;
    }


    .card{
        width:100%;
        padding:18px;
    }


    .card h2{
        font-size:28px;
    }


    .section{
        padding:15px;
        font-size:14px;
        line-height:25px;
    }


    .section p{
        word-break:break-word;
    }


    .btn{
        width:100%;
        padding:12px;
        font-size:15px;
    }

}
</style>

</head>


<body>


<div class="container">


<div class="header">


<?php

$img=!empty($user['profile_pic'])
?"../uploads/".$user['profile_pic']
:"https://via.placeholder.com/130";

?>


<img src="<?php echo $img;?>" class="profile-pic">


<h2>
<?php echo htmlspecialchars($user['name']); ?>
</h2>


<p>
<?php echo $user['email']; ?>
</p>


</div>




<div class="stats">


<div class="card">

<h2>
<?php echo $prop['total']; ?>
</h2>

Properties

</div>



<div class="card">

<h2>
<?php echo $tax['total']; ?>
</h2>

Taxes

</div>



</div>





<div class="section">


<p><b>Phone:</b>
<?php echo $user['phone']; ?>
</p>


<p><b>Village:</b>
<?php echo $user['village']; ?>
</p>


<p><b>DOB:</b>
<?php echo $user['dob']; ?>
</p>


<p><b>Gender:</b>
<?php echo $user['gender']; ?>
</p>


<p><b>Address:</b>
<?php echo $user['address']; ?>
</p>


</div>



<a class="btn" href="edit_profile.php">
✏ Edit Profile
</a>


<a class="btn" href="dashboard.php">
Back
</a>


</div>


</body>
</html>