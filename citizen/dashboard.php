<?php
include("../db.php");
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}
// COUNTS
$complaints = $conn->query("SELECT COUNT(*) as c FROM complaints")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE status='Pending'")->fetch_assoc()['c'];
$taxes = $conn->query("SELECT COUNT(*) as c FROM taxes")->fetch_assoc()['c'];
$announcements = $conn->query("SELECT COUNT(*) as c FROM announcements")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Digital Village System Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f6f9;
}

.header{
    background:indigo;
    height:80px;
    color:rgb(240, 235, 235);
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
}

.welcome{
    background:whitesmoke;
    margin:20px;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.welcome h2{
    color:indigo;
}

.dashboard{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
    padding:20px;
}

.card{
    background:#EFE9FF;
    padding:25px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 10px rgba(29, 26, 26, 0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.icon{
    font-size:50px;
    margin-bottom:15px;
}

.card h3{
    color:black;
    margin-bottom:10px;
}

.card p{
    color:black;
    margin-bottom:15px;
}

.btn{
    display:inline-block;
    background:indigo;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:6px;
}
.butn{
    display:inline-block;
    background:indigo;
    color:white;
    padding:10px 18px;
    text-decoration:none;
    margin-left:550px;
    border-radius:6px;
}

.butn:hover{
    background:#382865;
}
.btn:hover{
    background:#382865;
}

.count{
    font-size:22px;
    font-weight:bold;
    margin:10px 0;
}

.footer{
    text-align:center;
    padding:15px;
    background:indigo;
    color:white;
    margin-top:20px;
}
/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    .header{
        height:auto;
        padding:15px 10px;
        font-size:20px;
        line-height:30px;
    }

    .welcome{
        margin:12px;
        padding:15px;
        text-align:center;
    }

    .welcome h2{
        font-size:22px;
    }

    .welcome p{
        font-size:14px;
    }

    .butn{
        display:block;
        margin:15px auto 0 auto;
        width:120px;
        text-align:center;
        padding:8px 12px;
    }


    .dashboard{
        grid-template-columns:1fr;
        padding:12px;
        gap:15px;
    }


    .card{
        padding:20px;
        border-radius:10px;
    }


    .icon{
        font-size:40px;
        margin-bottom:10px;
    }


    .card h3{
        font-size:20px;
    }


    .card p{
        font-size:14px;
    }


    .count{
        font-size:18px;
    }


    .btn{
        width:100%;
        padding:10px;
    }


    .footer{
        font-size:14px;
        padding:12px;
    }

}
</style>
</head>

<body style="font-family: 'Playfair Display', serif;">

<div class="header">
    🏡 Smart Digital Village System
</div>

<div class="welcome">
    <h2>Welcome, Citizen!</h2>
    <p>Access all village services digitally from one place.                <a href="../index.html" class="butn">Back</a></p>
    
</div>

<div class="dashboard">

    <div class="card">
        <div class="icon">👤</div>
        <h3>Profile</h3>
        <p>View and update your personal information.</p>
        <a href="profile.php" class="btn">Open</a>
    </div>

    <div class="card">
        <div class="icon">🏠</div>
        <h3>Properties</h3>
        <p>Manage your property details and records.</p>
        <a href="my_properties.php" class="btn">Open</a>
    </div>

    <div class="card">
        <div class="icon">💰</div>
        <h3>Tax Details</h3>
        <p>View and pay property and village taxes.</p>
        <a href="tax_details.php" class="btn">Open</a>
    </div>

    <div class="card">
        <div class="icon">📢</div>
        <h3>Announcements</h3>
        <p>Read the latest village announcements.</p>
        <div class="count"><?php echo $announcements; ?> Notices</div>
        <a href="announcement.php" class="btn">Open</a>
    </div>

    <div class="card">
        <div class="icon">📝</div>
        <h3>Submit Complaint</h3>
        <p>Report issues and track their status.</p>
        <a href="submit_comp.php" class="btn">Open</a>
    </div>

    <div class="card">
        <div class="icon">📋</div>
        <h3>Complaint History</h3>
        <p>View all your previous complaints.</p>
        <a href="complaint_hist.php" class="btn">Open</a>
    </div>
    <div class="card">
        <div class="icon">🏠</div>
        <h3>Information Hub</h3>
        <p>Know about village informations and details.</p>
        <a href="village_hub/village_hub_dashboard.php" class="btn">Open</a>
    </div>
    <div class="card">
        <div class="icon">⚙️</div>
        <h3>Settings</h3>
        <p>Manage account and notification settings.</p>
        <a href="settings.php" class="btn">Open</a>
    </div>

</div>

<div class="footer">
    © 2026 Smart Digital Village System
</div>

</body>
</html>