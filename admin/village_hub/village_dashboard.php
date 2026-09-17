<?php

include("../../db.php");

?>

<!DOCTYPE html>
<html>

<head>

<title>Village Hub Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

body{

font-family:'Playfair Display',serif;

background:#f7f7f7;

margin:0;

padding:0;

}



.header{

background:indigo;

color:white;

padding:25px;

border-radius:12px;

display:flex;

justify-content:space-between;

align-items:center;

gap:20px;

flex-wrap:wrap;

}



.header h1{

margin:0;

font-size:30px;

}



.container{

display:grid;

grid-template-columns:repeat(3,1fr);

gap:25px;

margin-top:30px;

padding:0 15px;

}



.card{

background:white;

padding:30px;

border-radius:20px;

box-shadow:0 5px 15px #ccc;

text-align:center;

transition:.3s;

height:100%;

box-sizing:border-box;

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



.back-btn{

background:white;

color:indigo;

padding:10px 20px;

border-radius:8px;

text-decoration:none;

font-size:16px;

}




@media(max-width:900px){


.container{

grid-template-columns:repeat(2,1fr);

}



}




@media(max-width:600px){


.header{

flex-direction:column;

text-align:center;

align-items:stretch;

}



.header h1{

font-size:22px;

}



.container{

grid-template-columns:1fr;

gap:18px;

}



.card{

padding:25px;

}



.icon{

font-size:45px;

}



.back-btn{

display:block;

}



}

</style>

</head>


<body>

<div class="header">

<div>
<h1>Village Information Hub - Admin</h1>
<p>Manage village information</p>
</div>


<a href="../dashboard.php" class="back-btn">⬅ Back</a>


</div>




<div class="container">



<a href="manage_village_profile.php">

<div class="card">

<div class="icon">🏡</div>

<h2>Village Profile</h2>

<p>Manage village details</p>

</div>

</a>





<a href="manage_emergency_contacts.php">

<div class="card">

<div class="icon">🚑</div>

<h2>Emergency Contacts</h2>

<p>Manage helpline numbers</p>

</div>

</a>





<a href="manage_public_facilities.php">

<div class="card">

<div class="icon">🏥</div>

<h2>Public Facilities</h2>

<p>Manage facilities</p>

</div>

</a>





<a href="manage_govt_schemes.php">

<div class="card">

<div class="icon">📜</div>

<h2>Government Schemes</h2>

<p>Manage schemes</p>

</div>

</a>





<a href="manage_important_places.php">

<div class="card">

<div class="icon">📍</div>

<h2>Important Places</h2>

<p>Manage places</p>

</div>

</a>





<a href="manage_village_officials.php">

<div class="card">

<div class="icon">👥</div>

<h2>Village Officials</h2>

<p>Manage officials</p>

</div>

</a>




</div>


</body>

</html>