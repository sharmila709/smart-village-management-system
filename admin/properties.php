<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
header("Location: login.html");
exit();
}


/* ADD PROPERTY */

if(isset($_POST['add'])){


$code="PROP".time().rand(10,99);

$citizen_id=$_POST['citizen_id'];
$name=$_POST['name'];
$type=$_POST['property_type'];
$price=$_POST['price'];
$village=$_POST['village'];
$status=$_POST['status'];
$date=date("Y-m-d");


$conn->query("
INSERT INTO properties
(property_code,citizen_id,name,property_type,price,village,status,date)

VALUES

('$code',
'$citizen_id',
'$name',
'$type',
'$price',
'$village',
'$status',
'$date')
");


echo "<script>alert('Property Added');</script>";

}



/* DELETE */

if(isset($_GET['delete'])){

$id=$_GET['delete'];

$conn->query("
DELETE FROM properties WHERE id='$id'
");

header("Location: properties.php");

}




/* EDIT */

$edit=null;


if(isset($_GET['edit'])){

$id=$_GET['edit'];

$edit=$conn->query("
SELECT * FROM properties WHERE id='$id'
")->fetch_assoc();

}



/* UPDATE */


if(isset($_POST['update'])){


$id=$_POST['id'];

$citizen_id=$_POST['citizen_id'];
$name=$_POST['name'];
$type=$_POST['property_type'];
$price=$_POST['price'];
$village=$_POST['village'];

$status=$_POST['status'];


$conn->query("
UPDATE properties SET

citizen_id='$citizen_id',
name='$name',
property_type='$type',
price='$price',
village='$village',
status='$status'

WHERE id='$id'

");


header("Location: properties.php");

}





/* DISPLAY */


$result=$conn->query("

SELECT 
properties.*,
citizens.name AS citizen_name

FROM properties

LEFT JOIN citizens

ON properties.citizen_id=citizens.id

ORDER BY properties.id DESC

");

?>

<!DOCTYPE html>

<html>

<head>

<title>Property Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<style>
*{
    box-sizing:border-box;
}

body{
    margin:0;
    background:#f3f4f6;
    font-family:'Playfair Display',serif;
    overflow-x:hidden;
}

/* ================= SIDEBAR ================= */

.sidebar{
    width:240px;
    height:100vh;
    background:indigo;
    color:white;
    position:fixed;
    top:0;
    left:0;
    padding-top:15px;
    overflow-y:auto;
}

.sidebar ul{
    list-style:none;
    padding:0;
    margin:0;
}

.sidebar ul li{
    margin:12px 0;
    font-size:larger;
}

.sidebar ul li a{
    padding:10px 20px;
    display:block;
    color:white;
    text-decoration:none;
}

.sidebar ul li a:hover{
}

/* ================= MAIN ================= */

.main{
    margin-left:240px;
    padding:20px;
}

/* ================= HEADING ================= */

.heading{
    background:indigo;
    color:white;
    padding:18px;
    text-align:center;
    border-radius:10px;
    font-size:30px;
}

/* ================= FORM BOX ================= */

.box{
    background:white;
    padding:20px;
    margin-top:20px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
}

form{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:center;
}

input,
select{
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    min-width:180px;
}

button:not(.menu-btn){
    background:indigo;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}

/* ================= TABLE ================= */

.table-box{
    width:100%;
    overflow-x:auto;
    margin-top:20px;
    background:white;
    border-radius:12px;
}

table{
    width:100%;
    min-width:1000px;
    border-collapse:collapse;
}

th{
    background:indigo;
    color:white;
    padding:14px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f9fafb;
}

/* ================= ACTION BUTTONS ================= */

.action{
    display:flex;
    justify-content:center;
    gap:8px;
}

.edit-btn{
    background:green;
    color:white;
    padding:8px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.delete-btn{
    background:red;
    color:white;
    padding:8px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* ================= MOBILE MENU ================= */

.menu-btn{
    display:none;
}

.overlay{
    display:none;
}

/* ================= MOBILE VIEW ================= */

@media(max-width:768px){

body{
    overflow-x:hidden;
}

/* MENU BUTTON */

.menu-btn{
    display:block;
    position:fixed;
    top:10px;
    left:10px;
    z-index:3000;

    width:35px !important;
    height:35px !important;

    padding:0 !important;

    background:indigo;
    color:white;

    border:none;
    border-radius:6px;

    font-size:18px;
    line-height:35px;
}

/* OVERLAY */

.overlay{
    position:fixed;
    top:0;
    left:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,.4);

    z-index:2000;

    display:none;
}

.overlay.active{
    display:block;
}

/* DRAWER SIDEBAR */

.sidebar{
    width:240px;
    height:100vh;

    position:fixed;

    top:0;
    left:-260px;

    background:indigo;

    z-index:2500;

    transition:.3s;

    padding-top:60px;
}

.sidebar.active{
    left:0;
}

.sidebar ul{
    display:block;
}

.sidebar li{
    margin:12px 0;
    text-align:center;
}

.sidebar a{
    display:block;
    color:white;
    padding:12px 15px;
    text-decoration:none;
    font-size:16px;
}

/* MAIN */

.main{
    margin-left:0 !important;
    width:100% !important;
    padding:60px 10px 10px !important;
}

/* HEADING */

.heading{
    font-size:22px;
}

/* FORM */

form{
    flex-direction:column;
    align-items:stretch;
}

input,
select,
button:not(.menu-btn){
    width:100%;
    box-sizing:border-box;
}

/* TABLE */

.table-box{
    width:100%;
    overflow-x:auto;
}

table{
    min-width:900px;
}

/* ACTION BUTTONS */

.action{
    flex-direction:column;
}

.edit-btn,
.delete-btn{
    width:100%;
}

/* HIDE HORIZONTAL PAGE SCROLL */

html,
body{
    overflow-x:hidden;
}

}
</style>
<body>


<button class="menu-btn" onclick="toggleSidebar()">☰</button>

<div class="overlay" onclick="toggleSidebar()"></div>


<div class="sidebar">
<ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="citizens.php">Citizens</a></li>
    <li><a href="properties.php">Properties</a></li>
    <li><a href="taxes.php">Taxes</a></li>
    <li><a href="complaints.php">Complaints</a></li>
    <li><a href="workers.php">Workers</a></li>
    <li><a href="expenses.php">Expenses</a></li>
    <li><a href="announcements.php">Announcements</a></li>
    <li><a href="reports.php">Report</a></li>
    <li><a href="village_hub/village_dashboard.php">Information Hub</a></li>
    <li><a href="login_history.php">Login History</a></li>
    <li><a href="php/logout.php"> Logout </a><li>

</ul>
</div>



<div class="main">


<h2 class="heading">
🏠 Property Management
</h2>



<div class="box">


<form method="POST">


<?php if($edit){ ?>

<input type="hidden" name="id"
value="<?= $edit['id'] ?>">

<?php } ?>



<select name="citizen_id" required>

<option>
Select Citizen
</option>


<?php

$c=$conn->query("SELECT * FROM citizens");


while($row=$c->fetch_assoc()){


?>

<option value="<?= $row['id'] ?>">

<?= $row['name'] ?>

</option>


<?php } ?>

</select>



<input 
name="name"
placeholder="Property Name"
value="<?= $edit['name']??'' ?>"
required>



<select name="property_type">


<option>House</option>
<option>Land</option>
<option>Shop</option>


</select>



<input 
type="number"
name="price"
placeholder="Value"
value="<?= $edit['price']??'' ?>">




<input 
name="village"
placeholder="village"
value="<?= $edit['village']??'' ?>"
required>


<select name="status">

<option>Available</option>
<option>Sold</option>

</select>



<?php if($edit){ ?>

<button name="update">
Update
</button>

<?php } else { ?>

<button name="add">
Add Property
</button>

<?php } ?>


</form>


</div>




<div class="table-box">
<table>


<tr>

<th>ID</th>
<th>Property ID</th>
<th>Citizen</th>
<th>Property Name</th>
<th>Type</th>
<th>Price</th>
<th>Village</th>
<th>Status</th>
<th>Action</th>


</tr>



<?php while($r=$result->fetch_assoc()){ ?>


<tr>


<td><?= $r['id'] ?></td>


<td><?= $r['property_code'] ?></td>


<td>

<?= $r['citizen_name'] ?? "Citizen" ?>

</td>


<td><?= $r['name'] ?></td>


<td><?= $r['property_type'] ?></td>


<td>
₹<?= number_format($r['price']) ?>
</td>


<td><?= $r['village'] ?></td>


<td><?= $r['status'] ?></td>


<td>


<div class="action">



<a href="?edit=<?= $r['id'] ?>">

<button class="edit-btn">

Edit

</button>

</a>





<a href="?delete=<?= $r['id'] ?>"
onclick="return confirm('Delete Property?')">


<button class="delete-btn">

Delete

</button>


</a>




</div>


</td>


</tr>


<?php } ?>


</table>



</div>

<script>

function toggleSidebar(){

document.querySelector('.sidebar')
.classList.toggle('active');

document.querySelector('.overlay')
.classList.toggle('active');

}

</script>
</body>
</html>