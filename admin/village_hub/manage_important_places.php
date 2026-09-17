<?php

include("../../db.php");


// DELETE

if(isset($_GET['delete']))
{

$id=$_GET['delete'];


$data=$conn->query(
"SELECT image FROM important_places WHERE id=$id"
)->fetch_assoc();


if($data['image']!="")
{
unlink("../../assets/images/".$data['image']);
}



$conn->query(
"DELETE FROM important_places WHERE id=$id"
);


header("location:manage_important_places.php");

}





// INSERT

if(isset($_POST['save']))
{


$name=$_POST['name'];
$location=$_POST['location'];
$description=$_POST['description'];


$image="";


if($_FILES['image']['name']!="")
{

$image=time()."_".$_FILES['image']['name'];


move_uploaded_file(
$_FILES['image']['tmp_name'],
"../../assets/images/".$image
);

}



$sql="INSERT INTO important_places

(place_name,location,description,image)

VALUES

('$name','$location','$description','$image')";


$conn->query($sql);


header("location:manage_important_places.php");

}



$result=$conn->query(
"SELECT * FROM important_places ORDER BY id DESC"
);


?>



<!DOCTYPE html>
<html>


<head>


<title>Manage Important Places</title>

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

gap:15px;

flex-wrap:wrap;

}





.header h1{

margin:0;

font-size:28px;

}





.box{

background:white;

padding:25px;

margin-top:25px;

border-radius:20px;

box-shadow:0 5px 15px #ccc;

overflow-x:auto;

box-sizing:border-box;

}





input,textarea{

width:100%;

padding:10px;

margin:8px 0;

box-sizing:border-box;

font-size:16px;

}





textarea{

min-height:100px;

}





input[type="file"]{

max-width:100%;

}





button{

background:indigo;

color:white;

padding:10px 20px;

border:0;

border-radius:10px;

cursor:pointer;

}





table{

width:100%;

border-collapse:collapse;

background:white;

margin-top:20px;

text-align:center;

min-width:700px;

}





th{

background:#eee;

}




th,td{

padding:12px;

border:1px solid #ddd;

}





img{

width:100px;

height:70px;

object-fit:cover;

border-radius:10px;

max-width:100%;

}





.action{

display:flex;

justify-content:center;

align-items:center;

gap:10px;

flex-wrap:wrap;

}





.action form{

margin:0;

}





.edit-btn{

background:green;

color:white;

padding:8px 15px;

border:0;

border-radius:8px;

}





.delete-btn{

background:red;

color:white;

padding:8px 15px;

border:0;

border-radius:8px;

}





.back-btn{

background:white;

color:indigo;

padding:10px 20px;

border-radius:10px;

text-decoration:none;

}





@media(max-width:768px){


.header{

flex-direction:column;

align-items:stretch;

text-align:center;

}



.header h1{

font-size:22px;

}



.back-btn{

display:block;

}



.box{

padding:15px;

}



table{

min-width:650px;

}



button{

font-size:14px;

padding:8px 12px;

}


}


</style>



</head>






<body>





<div class="header">


<h1>📍 Manage Important Places</h1>



<a class="back-btn" href="village_dashboard.php">

⬅ Back

</a>



</div>









<div class="box">



<form method="post" enctype="multipart/form-data">



<input
name="name"
placeholder="Place Name"
required>





<input
name="location"
placeholder="Location">





<textarea
name="description"
placeholder="Description"></textarea>





<input type="file" name="image">





<button name="save">

Add Place

</button>




</form>



</div>









<div class="box">


<h2>Place List</h2>





<table>



<tr>

<th>ID</th>

<th>Place</th>

<th>Location</th>

<th>Image</th>

<th>Action</th>


</tr>





<?php

while($row=$result->fetch_assoc())

{

?>



<tr>




<td>

<?php echo $row['id']; ?>

</td>





<td>

<?php echo $row['place_name']; ?>

</td>






<td>

<?php echo $row['location']; ?>

</td>






<td>



<?php

if($row['image']!="")

{

?>

<img src="../../assets/images/<?php echo $row['image']; ?>">


<?php

}

?>


</td>







<td>


<div class="action">





<form method="get" action="edit_important_places.php">


<input type="hidden"

name="id"

value="<?php echo $row['id']; ?>">





<button class="edit-btn">

Edit

</button>



</form>








<form method="get">


<input type="hidden"

name="delete"

value="<?php echo $row['id']; ?>">






<button class="delete-btn"

onclick="return confirm('Delete place?')">

Delete

</button>




</form>




</div>


</td>





</tr>





<?php

}

?>






</table>




</div>





</body>


</html>