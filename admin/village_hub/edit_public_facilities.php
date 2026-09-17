<?php

include("../../db.php");


// GET ID

$id=$_GET['id'];



$result=$conn->query(
"SELECT * FROM public_facilities WHERE id=$id"
);


$row=$result->fetch_assoc();





// UPDATE

if(isset($_POST['update']))
{


$name=$_POST['name'];

$location=$_POST['location'];

$timings=$_POST['timings'];

$description=$_POST['description'];



$image=$row['image'];




// New image

if($_FILES['image']['name']!="")
{


if($image!="")
{
unlink("../../assets/images/".$image);
}



$image=time()."_".$_FILES['image']['name'];



move_uploaded_file(
$_FILES['image']['tmp_name'],
"../../assets/images/".$image
);


}






$conn->query("


UPDATE public_facilities SET


facility_name='$name',

location='$location',

timings='$timings',

description='$description',

image='$image'


WHERE id=$id


");





header("location:manage_public_facilities.php");


}



?>




<!DOCTYPE html>

<html>


<head>


<title>Edit Public Facility</title>
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





img{

width:120px;

height:80px;

object-fit:cover;

border-radius:10px;

max-width:100%;

display:block;

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




img{

margin:auto;

}




button{

width:100%;

}


}


</style>


</head>






<body>





<div class="box">



<h1>✏ Edit Public Facility</h1>





<form method="post" enctype="multipart/form-data">






<input

name="name"

value="<?php echo $row['facility_name']; ?>"

placeholder="Facility Name"

required>







<input

name="location"

value="<?php echo $row['location']; ?>"

placeholder="Location">






<input

name="timings"

value="<?php echo $row['timings']; ?>"

placeholder="Timings">








<textarea

name="description"

placeholder="Description">

<?php echo $row['description']; ?>

</textarea>







<?php

if($row['image']!="")

{

?>

<img src="../../assets/images/<?php echo $row['image']; ?>">


<?php

}

?>






<input type="file" name="image">







<button name="update">

Update

</button>





</form>





</div>





</body>


</html>