<?php

include("../../db.php");


// GET ID

$id=$_GET['id'];



$result=$conn->query(
"SELECT * FROM village_officials WHERE id=$id"
);


$row=$result->fetch_assoc();




// UPDATE

if(isset($_POST['update']))
{


$name=$_POST['name'];

$designation=$_POST['designation'];

$phone=$_POST['phone'];

$email=$_POST['email'];



$photo=$row['photo'];




// New photo upload

if($_FILES['photo']['name']!="")
{


if($photo!="")
{
unlink("../../assets/images/".$photo);
}



$photo=time()."_".$_FILES['photo']['name'];



move_uploaded_file(
$_FILES['photo']['tmp_name'],
"../../assets/images/".$photo
);


}






$conn->query("


UPDATE village_officials SET


name='$name',

designation='$designation',

phone='$phone',

email='$email',

photo='$photo'


WHERE id=$id


");






header("location:manage_village_officials.php");


}



?>





<!DOCTYPE html>

<html>


<head>


<title>Edit Village Official</title>
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




input{

width:100%;

padding:10px;

margin:10px 0;

box-sizing:border-box;

font-size:16px;

}





img{

width:100px;

height:100px;

border-radius:50%;

object-fit:cover;

display:block;

margin:10px auto;

max-width:100%;

}





input[type="file"]{

max-width:100%;

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



<h1>✏ Edit Village Official</h1>





<form method="post" enctype="multipart/form-data">





<input

name="name"

value="<?php echo $row['name']; ?>"

placeholder="Official Name"

required>







<input

name="designation"

value="<?php echo $row['designation']; ?>"

placeholder="Designation"

required>







<input

name="phone"

value="<?php echo $row['phone']; ?>"

placeholder="Phone">







<input

name="email"

value="<?php echo $row['email']; ?>"

placeholder="Email">







<?php

if($row['photo']!="")

{

?>

<img src="../../assets/images/<?php echo $row['photo']; ?>">


<?php

}

?>







<input

type="file"

name="photo">







<button name="update">

Update

</button>







</form>





</div>





</body>


</html>