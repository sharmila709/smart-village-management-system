<?php

include("../../db.php");


// DELETE

if(isset($_GET['delete']))
{
$id=$_GET['delete'];

$conn->query("DELETE FROM village_profile WHERE id=$id");

header("location:manage_village_profile.php");
}



// INSERT

if(isset($_POST['save']))
{

$village=$_POST['village'];
$panchayat=$_POST['panchayat'];
$district=$_POST['district'];
$state=$_POST['state'];
$population=$_POST['population'];
$families=$_POST['families'];
$occupation=$_POST['occupation'];
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



$sql="INSERT INTO village_profile
(village_name,panchayat_name,district,state,population,total_families,occupation,description,office_image)

VALUES

('$village','$panchayat','$district','$state','$population','$families','$occupation','$description','$image')";


$conn->query($sql);


header("location:manage_village_profile.php");

}



$result=$conn->query("SELECT * FROM village_profile");

?>



<!DOCTYPE html>
<html>

<head>

<title>Manage Village Profile</title>

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

min-height:120px;

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

background:white;

margin-top:20px;

border-collapse:collapse;

text-align:center;

min-width:700px;

}





th{

background:#eee;

}





td,th{

padding:12px;

border:1px solid #ddd;

}





img{

width:100px;

height:70px;

object-fit:cover;

border-radius:8px;

max-width:100%;

}





.action{

display:flex;

justify-content:center;

gap:10px;

flex-wrap:wrap;

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


<h1>🏡 Manage Village Profile</h1>


<a class="back-btn" href="village_dashboard.php">

⬅ Back

</a>


</div>





<div class="box">



<form method="post" enctype="multipart/form-data">


<input name="village" placeholder="Village Name" required>


<input name="panchayat" placeholder="Panchayat Name" required>


<input name="district" placeholder="District" required>


<input name="state" placeholder="State" required>


<input type="number" name="population" placeholder="Population">


<input type="number" name="families" placeholder="Total Families">


<input name="occupation" placeholder="Main Occupation">



<textarea name="description" placeholder="Description"></textarea>



<input type="file" name="image">


<button name="save">

Add Profile

</button>


</form>


</div>





<div class="box">


<h2>Existing Village Profile</h2>


<table>


<tr>

<th>ID</th>
<th>Village</th>
<th>Panchayat</th>
<th>Image</th>
<th>Action</th>

</tr>



<?php while($row=$result->fetch_assoc()){ ?>


<tr>


<td>

<?php echo $row['id']; ?>

</td>



<td>

<?php echo $row['village_name']; ?>

</td>



<td>

<?php echo $row['panchayat_name']; ?>

</td>



<td>


<?php

if($row['office_image']!="")
{

?>

<img src="../../assets/images/<?php echo $row['office_image']; ?>">


<?php } ?>


</td>



<td>


<div class="action">


<button class="edit-btn"
onclick="location.href='edit_village_profile.php?id=<?php echo $row['id']; ?>'">

Edit

</button>




<button class="delete-btn"
onclick="if(confirm('Delete?')) location.href='?delete=<?php echo $row['id']; ?>'">

Delete

</button>


</div>


</td>



</tr>


<?php } ?>


</table>



</div>



</body>

</html>