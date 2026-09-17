<?php
include("../db.php");
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}
$msg="";


if(isset($_POST['submit'])) {


$name = $_POST['owner'];
$price = $_POST['value'];
$village = $_POST['village'];
$type = $_POST['type'];
$status = $_POST['status'];


// logged in citizen

$citizen_id = $_SESSION['user_id'];
$date=date("Y-m-d");



$sql="
INSERT INTO properties
(
property_code,
citizen_id,
name,
property_type,
price,
village,
status,
date
)

VALUES
(
NULL,
'$citizen_id',
'$name',
'$type',
'$price',
'$village',
'$status',
'$date'
)
";


if($conn->query($sql)){


$lastId=$conn->insert_id;


$code="P00GE".$lastId;


$conn->query("
UPDATE properties
SET property_code='$code'
WHERE id=$lastId
");


$msg="Property Registered Successfully! ID: $code";


}
else{

$msg=$conn->error;

}


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Digital Village System - Property Details</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:linear-gradient(135deg,indigo);
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
        }

        .container{
            width:700px;
            background:#fff;
            padding:30px;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.3);
        }

        h2{
            text-align:center;
            color:black;
            margin-bottom:25px;
        }

        .form-group{
            margin-bottom:15px;
        }

        label{
            display:block;
            font-weight:bold;
            margin-bottom:5px;
            color:indigo;
        }

        input,
        select,
        textarea{
            width:100%;
            padding:10px;
            border:1px solid indigo;
            border-radius:8px;
            font-size:15px;
        }

        textarea{
            resize:none;
        }

        .submit-btn{
            width:100%;
            padding:12px;
            background:indigo;
            color:white;
            border:none;
            border-radius:8px;
            font-size:18px;
            cursor:pointer;
            margin-top:15px;
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
        .submit-btn:hover{
            background:#382865;
        }
        .submitbtn:hover{
            background:#382865;
        }

        .footer{
            text-align:center;
            margin-top:20px;
            color:gray;
            font-size:13px;
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


    input,
    select,
    textarea{
        font-size:14px;
        padding:10px;
    }


    .submit-btn{
        width:100%;
        margin-left:0;
        font-size:15px;
        padding:12px;
    }


    .submitbtn{
        width:100%;
        margin-left:0;
        font-size:15px;
        padding:12px;
    }


    .footer{
        font-size:12px;
    }


    .msg{
        font-size:14px;
    }

}
    </style>
</head>

<body style="font-family: 'Playfair Display', serif;">

<div class="container">

    <h1><center>Property Registration</center></h1>

    <?php if($msg != "") { ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="POST">

        <div class="form-group">
            <label>Owner Name</label>
            <input type="text" name="owner" required>
        </div>
        <div class="form-group">

            <label>Property Type</label>

            <select name="type" required>

            <option value="">Select Type</option>

            <option value="House">House</option>

            <option value="Land">Land</option>

            <option value="Shop">Shop</option>

            </select>

        </div>

        <div class="form-group">
            <label>Estimated Value (₹)</label>
            <input type="number" name="value" required>
        </div>

        <div class="form-group">
            <label>Village Name</label>
            <input type="text" name="village" required>
        </div>

        <div class="form-group">
            <label>Ownership Status</label>
            <select name="status" required>
                <option value="">Select Status</option>
                <option>Owned</option>
                <option>Joint Ownership</option>
                <option>Leased</option>
                <option>Inherited</option>
            </select>
        </div>

        <button type="submit" name="submit" class="submit-btn">
            Register Property
        </button>
        

    <button type="button"
        class="submit-btn"
        onclick="window.location.href='my_properties.php'">
        View My Properties
    </button>
    <button type="button"
        class="submitbtn"
        onclick="window.location.href='dashboard.php'">
        Back
    </button>
    

    </form>

    <div class="footer">
        © 2026 Smart Digital Village System
    </div>

</div>

</body>
</html>