<?php
include("../db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Village Map</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Playfair Display', serif;
}

/* SIDEBAR (SAME STYLE AS YOUR CHAT PAGE) */
.sidebar{
    width:250px;
    height:100vh;
    background:indigo;
    color:whitesmoke;
    padding:20px;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    padding:10px;
    margin:8px 0;
    font-size:larger;
}

.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:block;
}

/* MAIN */
.main{
    margin-left:265px;
    padding:20px;
}

/* HEADER (same vibe as chatbot navbar) */
.header{
    background:indigo;
    color:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

/* MAP BOX */
.map-box{
    width:100%;
    height:500px;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}

iframe{
    width:100%;
    height:100%;
    border:0;
}

/* RESPONSIVE */
/* ================= MOBILE VIEW FIX ================= */
.back-btn{
display:none;
background:white;
color:indigo;
text-decoration:none;
padding:8px 12px;
border-radius:8px;
font-size:14px;
font-weight:bold;
}

.header-top{
display:flex;
align-items:center;
justify-content:center;
gap:12px;
}
@media(max-width:900px){

    body{
        overflow-x:hidden;
    }

    .back-btn{
display:block;
}

.header-top{
justify-content:flex-start;
gap:10px;
}

.header h1{
font-size:20px;
margin:0;
}

.header p{
text-align:left;
margin-top:10px;
}
    .sidebar{
        display:none;
    }


    .main{
        margin-left:0 !important;
        padding:10px;
        width:100%;
    }


    .header{
        padding:15px;
        text-align:center;
    }


       .map-box{
    width:100%;
    height:70vh;
    min-height:350px;
}
    


    iframe{
        width:100%;
        height:100%;
    }

}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<ul>
    <li><a href="ai_dashboard.php">AI Dashboard</a></li>
    <li><a href="village_map.php">Village Map</a></li>
    <li><a href="complaint_analytics.php">Complaint Analytics</a></li>
    <li><a href="tax_analytics.php">Tax Analytics</a></li>
    <li><a href="chatbox.php">AI Chatbot</a></li>
    <li><a href="predictions.php">AI Insights</a></li>
    <li>
    <a href="../index.html">
    Logout
    </a>
</li>
</ul>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">

<div class="header-top">

<a href="javascript:history.back()" class="back-btn">
Back
</a>
<br>
<h1>Village Map</h1>

</div>
<br>
<p>Live Google Map view of your village area</p>

</div>

<!-- MAP -->
<div class="map-box">

<!-- 📍 Replace location if needed -->
<iframe 
src="https://maps.google.com/maps?q=Rettiarpatti,Tirunelveli,Tamil%20Nadu&z=15&output=embed"
allowfullscreen=""
loading="lazy">
</iframe>

</div>

</div>

</body>
</html>