<?php

include("../../db.php");


// GET ID

$id=$_GET['id'];



$result=$conn->query(
"SELECT * FROM government_schemes WHERE id=$id"
);


$row=$result->fetch_assoc();





// UPDATE

if(isset($_POST['update']))
{


$name=$_POST['name'];
$eligibility=$_POST['eligibility'];
$description=$_POST['description'];




$conn->query("

UPDATE government_schemes SET


scheme_name='$name',
eligibility='$eligibility',
description='$description'


WHERE id=$id


");



header("location:manage_govt_schemes.php");


}



?>




<!DOCTYPE html>

<html>


<head>


<title>Edit Government Scheme</title>
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

width:70%;

margin:40px auto;

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


<h1>✏ Edit Government Scheme</h1>




<form method="post">





<input

name="name"

value="<?php echo $row['scheme_name']; ?>"

placeholder="Scheme Name"

required>





<textarea

name="eligibility"

placeholder="Eligibility">

<?php echo $row['eligibility']; ?>

</textarea>






<textarea

name="description"

placeholder="Description">

<?php echo $row['description']; ?>

</textarea>







<button name="update">

Update

</button>





</form>




</div>






</body>


</html>