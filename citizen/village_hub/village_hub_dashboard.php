<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

<title>Village Information Hub</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

body{
font-family:'Playfair Display', serif;
background:white;
}

.header{
    background:indigo;
    color:white;
    padding:25px;
    border-radius:10px;
    position:relative;
}

.back-btn{
    position:absolute;
    top:30px;
    right:20px;
    background:white;
    color:indigo;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}

.back-btn:hover{
    background:#f0f0f0;
}

.container{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
margin-top:30px;
}


.card{

background:white;
padding:30px;
border-radius:18px;
box-shadow:0 5px 15px #ccc;
text-align:center;
transition:.3s;

}


.card:hover{

transform:translateY(-8px);

}


a{
text-decoration:none;
color:#333;
}



.icon{

font-size:50px;

}
/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .header{
        padding:20px;
        text-align:center;
    }


    .header h1{
        font-size:24px;
        line-height:30px;
    }


    .header p{
        font-size:14px;
    }


    .back-btn{
        position:static;
        display:block;
        width:100%;
        margin-top:15px;
        text-align:center;
    }


    .container{
        grid-template-columns:1fr;
        gap:15px;
        margin-top:20px;
    }


    .card{
        width:100%;
        padding:22px;
        border-radius:15px;
    }


    .icon{
        font-size:42px;
    }


    .card h2{
        font-size:20px;
    }


    .card p{
        font-size:14px;
    }

}
</style>

</head>


<body>


<div class="header">


<h1>Village Information Hub</h1>

<p>Complete village details and public information</p>
<a href="../dashboard.php" class="back-btn">← Back</a>


</div>



<div class="container">


<a href="village_profile.php">

<div class="card">

<div class="icon">🏡</div>

<h2>Village Profile</h2>

<p>Know about our village</p>

</div>

</a>



<a href="emergency_contacts.php">

<div class="card">

<div class="icon">🚑</div>

<h2>Emergency Contacts</h2>

<p>Important helpline numbers</p>

</div>

</a>



<a href="public_facilities.php">

<div class="card">

<div class="icon">🏥</div>

<h2>Public Facilities</h2>

<p>Available facilities</p>

</div>

</a>



<a href="govt_schemes.php">

<div class="card">

<div class="icon">📜</div>

<h2>Government Schemes</h2>

<p>Benefits and schemes</p>

</div>

</a>



<a href="important_places.php">

<div class="card">

<div class="icon">📍</div>

<h2>Important Places</h2>

<p>Places around village</p>

</div>

</a>




<a href="village_officials.php">

<div class="card">

<div class="icon">👥</div>

<h2>Village Officials</h2>

<p>Contact officials</p>

</div>

</a>



</div>


</body>
</html>