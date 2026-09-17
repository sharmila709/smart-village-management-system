<?php

include("../../db.php");


// GET ID

$id=$_GET['id'];



$result=$conn->query(
"SELECT * FROM village_profile WHERE id=$id"
);


$row=$result->fetch_assoc();




// UPDATE

if(isset($_POST['update']))
{


$village=$_POST['village'];
$panchayat=$_POST['panchayat'];
$district=$_POST['district'];
$state=$_POST['state'];
$population=$_POST['population'];
$families=$_POST['families'];
$occupation=$_POST['occupation'];
$description=$_POST['description'];




$conn->query("

UPDATE village_profile SET

village_name='$village',
panchayat_name='$panchayat',
district='$district',
state='$state',
population='$population',
total_families='$families',
occupation='$occupation',
description='$description'

WHERE id=$id

");



header("location:manage_village_profile.php");


}


?>



<!DOCTYPE html>
<html>

<head>

<title>Edit Village Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>


body{

font-family:'Playfair Display',serif;

background:#f7f7f7;

margin:0;
padding:0;

}




.box{

background:white;

padding:25px;

margin:40px auto;

width:70%;

border-radius:20px;

box-shadow:0 5px 15px #ccc;

box-sizing:border-box;

}





input,textarea{

width:100%;

padding:10px;

margin:10px 0;

box-sizing:border-box;

font-size:16px;

}





textarea{

min-height:120px;

resize:vertical;

}




button{

background:indigo;

color:white;

padding:12px 25px;

border:0;

border-radius:10px;

cursor:pointer;

font-size:16px;

}





@media(max-width:768px){


.box{

width:95%;

margin:20px auto;

padding:18px;

}



h1{

font-size:23px;

text-align:center;

}




button{

width:100%;

}


}



</style>


</head>


<body>



<div class="box">


<h1>✏ Edit Village Profile</h1>



<form method="post">


<input name="village"
value="<?php echo $row['village_name']; ?>">



<input name="panchayat"
value="<?php echo $row['panchayat_name']; ?>">



<input name="district"
value="<?php echo $row['district']; ?>">



<input name="state"
value="<?php echo $row['state']; ?>">



<input type="number"
name="population"
value="<?php echo $row['population']; ?>">



<input type="number"
name="families"
value="<?php echo $row['total_families']; ?>">



<input name="occupation"
value="<?php echo $row['occupation']; ?>">



<textarea name="description">

<?php echo $row['description']; ?>

</textarea>



<button name="update">

Update

</button>



</form>



</div>



</body>

</html>