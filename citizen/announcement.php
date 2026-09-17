<?php
include("../db.php");
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}
$sql = "SELECT * FROM announcements ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Digital Village System - Announcements</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:linear-gradient(135deg,indigo);
            padding:30px;
        }

        .container{
            max-width:900px;
            margin:auto;
            background:#fff;
            border-radius:15px;
            padding:30px;
            box-shadow:0 8px 20px rgba(0,0,0,0.2);
        }

        h1{
            text-align:center;
            color:#1b1c1b;
            margin-bottom:10px;
        }

        .subtitle{
            text-align:center;
            color:indigo;
            margin-bottom:25px;
        }

        .announcement-card{
            background:#c9b9fb;
            border-left:6px solid indigo;
            padding:20px;
            margin-bottom:20px;
            border-radius:10px;
            transition:0.3s;
        }

        .announcement-card:hover{
            transform:scale(1.02);
            box-shadow:0 5px 10px rgba(0,0,0,0.15);
        }

        .announcement-title{
            font-size:22px;
            color:indigo;
            margin-bottom:10px;
        }

        .announcement-date{
            color:black;
            font-size:14px;
            margin-bottom:10px;
        }

        .announcement-content{
            color:black;
            line-height:1.6;
        }

        .important{
            border-left-color:indigo;
        }

        .important .announcement-title{
            color:indigo;
        }

        .upcoming{
            border-left-color:indigo;
        }

        .upcoming .announcement-title{
            color:indigo;
        }

        .notice-tag{
            display:inline-block;
            background:green;
            color:white;
            padding:5px 10px;
            border-radius:20px;
            font-size:12px;
            margin-bottom:10px;
        }

        .important .notice-tag{
            background:#f44336;
        }

        .upcoming .notice-tag{
            background:#2196F3;
        }

        .footer{
            text-align:center;
            margin-top:25px;
            color:gray;
            font-size:14px;
        }
        .back{
            display:inline-block;
            margin-bottom:10px;
            padding:8px 12px;
            background:indigo;
            color:white;
            text-decoration:none;
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
        font-size:22px;
        line-height:30px;
    }


    .subtitle{
        font-size:14px;
        margin-bottom:15px;
    }


    .back{
        display:block;
        width:100%;
        text-align:center;
        margin-bottom:15px;
        padding:10px;
        border-radius:6px;
    }


    .announcement-card{
        padding:15px;
        margin-bottom:15px;
        border-left-width:5px;
    }


    .announcement-title{
        font-size:18px;
        word-break:break-word;
    }


    .announcement-date{
        font-size:13px;
    }


    .announcement-content{
        font-size:14px;
        line-height:1.5;
        word-break:break-word;
    }


    .notice-tag{
        font-size:11px;
        padding:4px 8px;
    }


    .footer{
        font-size:12px;
        line-height:20px;
    }

}
    </style>

</head>

<body style=" font-family: 'Playfair Display', serif;">

<div class="container">

    <h1>🏡 Smart Digital Village System</h1>
    <p class="subtitle">Village Announcements & Notices</p>
    <a class="back" href="dashboard.php">← Back</a>
    <br>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
    ?>

    <div class="announcement-card ">

        <span class="notice-tag">
            <?php echo $row['category']; ?>
        </span>

        <div class="announcement-title">
            <?php echo $row['title']; ?>
        </div>

        <div class="announcement-date">
            📅Announced Date: <?php echo $row['date']; ?>
        </div>

        <div class="announcement-content">
            <?php echo $row['description']; ?>
        </div>

    </div>

    <?php
        }
    } else {
        echo "<p style='text-align:center;color:red;'>No announcements found</p>";
    }
    ?>

    <div class="footer">
        © 2026 Smart Digital Village System | Official Announcement Portal
    </div>

</div>

</body>
</html>