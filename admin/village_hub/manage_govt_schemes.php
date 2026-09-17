<?php

include("../../db.php");


// DELETE

if(isset($_GET['delete']))
{

$id=$_GET['delete'];

$conn->query("DELETE FROM government_schemes WHERE id=$id");

header("location:manage_govt_schemes.php");

}



// INSERT

if(isset($_POST['save']))
{

$name=$_POST['name'];
$eligibility=$_POST['eligibility'];
$description=$_POST['description'];


$sql="INSERT INTO government_schemes
(scheme_name,eligibility,description)

VALUES

('$name','$eligibility','$description')";


$conn->query($sql);


header("location:manage_govt_schemes.php");

}



$result=$conn->query("SELECT * FROM government_schemes ORDER BY id DESC");


?>


<!DOCTYPE html>
<html>


<head>

<title>Manage Government Schemes</title>
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

border-collapse:collapse;

margin-top:20px;

text-align:center;

min-width:750px;

}





th{

background:#eee;

}




th,td{

padding:12px;

border:1px solid #ddd;

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

text-align:center;

align-items:stretch;

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

min-width:700px;

}



button{

padding:8px 12px;

font-size:14px;

}


}

</style>


</head>





<body>




<div class="header">


<h1>📜 Manage Government Schemes</h1>



<a class="back-btn" href="village_dashboard.php">

⬅ Back

</a>



</div>









<div class="box">



<form method="post">



<input
name="name"
placeholder="Scheme Name"
required>




<textarea
name="eligibility"
placeholder="Eligibility"></textarea>





<textarea
name="description"
placeholder="Description"></textarea>





<button name="save">

Add Scheme

</button>



</form>



</div>










<div class="box">


<h2>Scheme List</h2>




<table>


<tr>

<th>ID</th>
<th>Scheme</th>
<th>Eligibility</th>
<th>Description</th>
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

<?php echo $row['scheme_name']; ?>

</td>




<td>

<?php echo $row['eligibility']; ?>

</td>



<td>

<?php echo $row['description']; ?>

</td>





<td>


<div class="action">



<form method="get" action="edit_govt_schemes.php">


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

onclick="return confirm('Delete scheme?')">

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