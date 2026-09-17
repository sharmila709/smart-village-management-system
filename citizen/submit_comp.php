<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}

$citizen_id=$_SESSION['user_id'];
$user = $conn->query("
SELECT name
FROM citizen_login
WHERE id='$citizen_id'
")->fetch_assoc();

$citizen = $user['name'];
$msg = "";

// generate complaint ID
function generateComplaintID($conn){
    $res = $conn->query("SELECT COUNT(*) as total FROM complaints");
    $row = $res->fetch_assoc();
    return "CMP" . str_pad($row['total'] + 1, 6, "0", STR_PAD_LEFT);
}

if(isset($_POST['submit'])){
    $citizen_id = $_SESSION['user_id'];

    $id = generateComplaintID($conn);
    
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $village = $_POST['village'];

    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    $date = date("Y-m-d");
    $status = "Pending";
    $officer = "Not Assigned";

    // store full description (title + desc + location + contact info)
    $finalDesc = "Title: $title | Desc: $description | Location: $location | Mobile: $mobile | Email: $email | Village: $village";
    
    $sql = "INSERT INTO complaints 
    (id, citizen, category, priority, description, date, status, officer)
    VALUES 
    ('$id','$citizen','$category','Medium','$finalDesc','$date','$status','$officer')";

    if($conn->query($sql)){
        $msg = "Complaint submitted successfully. Your ID: $id";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Digital Village - Submit Complaint</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    background:indigo;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
    font-family:'Playfair Display', serif;
}

.container{
    width:700px;
    background:#fff;
    padding:30px;
    border-radius:15px;
}

h1{
    text-align:center;
    margin-bottom:20px;
}

label{
    display:block;
    margin-top:15px;
    font-weight:bold;
    color:indigo;
}

input,select,textarea{
    width:100%;
    padding:10px;
    border:1px solid indigo;
    border-radius:8px;
}

.btn{
    width:100%;
    margin-top:20px;
    padding:12px;
    background:indigo;
    color:white;
    border:none;
    border-radius:8px;
}
.submitbtn{
            width:20%;
            margin-left:250px;
            padding:12px;
            background:indigo;
            color:white;
            border:none;
            border-radius:8px;
            font-size:18px;
            cursor:pointer;
            margin-top:15px;
}
.submitbtn:hover{
            background:#382865;
}
.success{
    margin-top:15px;
    padding:10px;
    background:#d4edda;
    color:green;
    text-align:center;
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


    label{
        font-size:14px;
        margin-top:12px;
    }


    input,
    select,
    textarea{
        font-size:14px;
        padding:10px;
    }


    textarea{
        min-height:100px;
    }


    .btn{
        width:100%;
        font-size:15px;
        padding:12px;
    }


    .submitbtn{
        width:100%;
        margin-left:0;
        font-size:15px;
        padding:12px;
    }


    .success{
        font-size:14px;
    }

}
</style>

</head>

<body>

<div class="container">

<h1>📢 Submit Complaint</h1>

<?php if($msg != "") { ?>
    <div class="success"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

    <label>Full Name</label>
    <input
        type="text"
        name="name"
        value="<?php echo $citizen; ?>"
        readonly>
    <label>Mobile Number</label>
    <input type="tel" name="mobile" required>

    <label>Email Address</label>
    <input type="email" name="email">

    <label>Village Name</label>
    <input type="text" name="village" required>

    <label>Complaint Category</label>
    <select name="category" required>
        <option>Water Supply</option>
        <option>Electricity</option>
        <option>Road Damage</option>
        <option>Street Light</option>
        <option>Garbage Collection</option>
        <option>Drainage</option>
        <option>Other</option>
    </select>

    <label>Complaint Title</label>
    <input type="text" name="title" required>

    <label>Complaint Description</label>
    <textarea name="description" required></textarea>

    <label>Location</label>
    <input type="text" name="location">

    <button type="submit" name="submit" class="btn">
        Submit Complaint
    </button>
    <button type="button"
        class="submitbtn"
        onclick="window.location.href='dashboard.php'">
        Back
    </button>

</form>

</div>

</body>
</html>