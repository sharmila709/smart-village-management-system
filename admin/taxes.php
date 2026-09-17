<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}


/* =========================
 TAX CALCULATION
========================= */

function calculateTax($type,$price,$area=0){

    switch($type){

        case "House":
            return $price * 0.10;

        case "Shop":
            return $price * 0.15;

        case "Land":
            // ₹2 per sq.ft
            return $area * 2;

        case "Water":
            return 500;

        case "Sanitation":
            return 300;

        default:
            return 0;
    }
}




/* =========================
 GENERATE TAX
========================= */

if(isset($_POST['add'])){


$citizen=$_POST['citizen_id'];
$type=$_POST['type'];
$due=$_POST['due_date'];

$property_id=NULL;
$property_name=$type;
$price=0;
$area=0;


$selected_property=$_POST['property_id'] ?? "";


/* PROPERTY TAX */

if(in_array($type,["House","Land","Shop"])){


$p=mysqli_query($conn,"
SELECT *
FROM properties
WHERE id='$selected_property'
");


if(mysqli_num_rows($p)==0){

echo "<script>
alert('Please select property');
window.location='taxes.php';
</script>";

exit();

}


$property=mysqli_fetch_assoc($p);


$property_id=$property['id'];

$property_name=$property['name'];

$price=$property['price'];



if($type=="Land"){

$area=$property['area'];

}



}




$amount=calculateTax(
$type,
$price,
$area
);



mysqli_query($conn,"
INSERT INTO taxes
(
citizen_id,
property_id,
property_name,
type,
amount,
fine,
total,
due_date,
status
)

VALUES
(
'$citizen',
'$property_id',
'$property_name',
'$type',
'$amount',
0,
'$amount',
'$due',
'Pending'
)

");


echo "<script>
alert('Tax Generated Successfully');
window.location='taxes.php';
</script>";

}







/* =========================
 ADD FINE LATER
========================= */

if(isset($_GET['fine'])){

$id=$_GET['fine'];


$data=mysqli_query($conn,"
SELECT total FROM taxes
WHERE id='$id'
");

$row=mysqli_fetch_assoc($data);


$newFine=100;

$newTotal=$row['total']+$newFine;



mysqli_query($conn,"
UPDATE taxes SET
fine='$newFine',
total='$newTotal'
WHERE id='$id'
");


header("Location: taxes.php");
exit();

}





/* STATUS */

/* STATUS */

if(isset($_GET['toggle'])){


$id=$_GET['toggle'];


$q=mysqli_query($conn,"
SELECT status FROM taxes WHERE id='$id'
");


$row=mysqli_fetch_assoc($q);



$new =
($row['status']=="Pending")
?
"Paid"
:
"Pending";



if($new=="Paid"){


mysqli_query($conn,"
UPDATE taxes SET

status='Paid',
payment_date=CURDATE()

WHERE id='$id'

");


}
else{


mysqli_query($conn,"
UPDATE taxes SET

status='Pending',
payment_date=NULL

WHERE id='$id'

");


}



header("Location: taxes.php");
exit();

}




/* DELETE */

if(isset($_GET['delete'])){


$id=$_GET['delete'];


mysqli_query($conn,"
DELETE FROM taxes WHERE id='$id'
");


header("Location: taxes.php");

exit();

}





$search=$_GET['search'] ?? "";



$result=mysqli_query($conn,"
SELECT 
t.*,
c.name cname,
p.name pname

FROM taxes t

JOIN citizens c
ON t.citizen_id=c.id

LEFT JOIN properties p
ON t.property_id=p.id


WHERE 
c.name LIKE '%$search%'
OR t.type LIKE '%$search%'

ORDER BY t.id DESC

");

?>

<!DOCTYPE html>
<html>

<head>

<title>Tax Management</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f3f4f6;
    font-family:'Playfair Display',serif;
    overflow-x:hidden;
}

/* =========================
   SIDEBAR
========================= */

.sidebar{
    width:240px;
    height:100vh;
    background:indigo;
    color:white;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;
    z-index:1000;
    padding-top:15px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:5px 0;
}

.sidebar ul li a{
    display:block;
    color:white;
    text-decoration:none;
    padding:13px 20px;
    font-size:larger;
}

.sidebar ul li a:hover{
    }

/* =========================
   MAIN
========================= */

.main{
    margin-left:240px;
    padding:25px;
}

/* =========================
   HEADING
========================= */

.heading{
    background:indigo;
    color:white;
    text-align:center;
    padding:18px;
    border-radius:12px;
    font-size:30px;
    margin-bottom:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.15);
}

/* =========================
   FORM BOX
========================= */

.form-box{
    background:white;
    padding:22px;
    border-radius:12px;
    margin-bottom:20px;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

.form-box form{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:center;
}

input,
select{
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    min-width:180px;
    font-size:15px;
    outline:none;
}

input:focus,
select:focus{
    border-color:indigo;
}

/* =========================
   BUTTONS
========================= */

button:not(.menu-btn){
    background:indigo;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:8px;
    cursor:pointer;
    transition:.3s;
    font-weight:600;
}

button:not(.menu-btn):hover{
    transform:translateY(-2px);
}

/* =========================
   TABLE
========================= */

.table-box{
    background:white;
    border-radius:12px;
    overflow-x:auto;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

.table-box::-webkit-scrollbar{
    height:8px;
}

.table-box::-webkit-scrollbar-thumb{
    background:indigo;
    border-radius:10px;
}

table{
    width:100%;
    min-width:1200px;
    border-collapse:collapse;
}

th{
    background:indigo;
    color:white;
    padding:15px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #e5e7eb;
    white-space:nowrap;
}

tr:hover{
    background:#f9fafb;
}

/* =========================
   STATUS BADGES
========================= */

.paid{
    background:#16a34a;
    color:white;
    padding:6px 14px;
    border-radius:20px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
}

.pending{
    background:#dc2626;
    color:white;
    padding:6px 14px;
    border-radius:20px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
}

.fine{
    background:#f59e0b;
    color:white;
    padding:6px 14px;
    border-radius:20px;
    font-weight:bold;
}

.a{
    text-decoration:none;
}

/* =========================
   ACTION BUTTONS
========================= */

.action-btn{
    display:inline-block;
    padding:8px 14px;
    margin:3px;
    border-radius:20px;
    text-decoration:none;
    color:white;
    font-size:14px;
    font-weight:bold;
    transition:.3s;
}

.action-btn:hover{
    transform:translateY(-2px);
}

.fine-btn{
    background:#f59e0b;
}

.delete-btn{
    background:#dc2626;
}

/* =========================
   MOBILE
========================= */

.menu-btn{
    display:none;
}

.overlay{
    display:none;
}

@media(max-width:768px){

.menu-btn{
    display:block;
    position:fixed;
    top:10px;
    left:10px;
    width:35px;
    height:35px;
    border:none;
    border-radius:6px;
    background:indigo;
    color:white;
    font-size:18px;
    z-index:3000;
    padding:0;
}

.overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.4);
    display:none;
    z-index:2000;
}

.overlay.active{
    display:block;
}

/* Drawer Sidebar */

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    top:0;
    left:-260px;
    z-index:2500;
    transition:.3s;
    padding-top:60px;
}

.sidebar.active{
    left:0;
}

.sidebar ul li{
    text-align:center;
}

.sidebar ul li a{
    padding:12px 20px;
}

/* Main */

.main{
    margin-left:0 !important;
    width:100%;
    padding:60px 10px 10px;
}

/* Heading */

.heading{
    font-size:22px;
    padding:14px;
}

/* Forms */

.form-box form{
    flex-direction:column;
    align-items:stretch;
}

.form-box input,
.form-box select,
.form-box button{
    width:100%;
}

/* Table */

.table-box{
    width:100%;
    overflow-x:auto;
}

table{
    min-width:1100px;
}

/* Action buttons */

.action-btn{
    display:block;
    margin:5px auto;
    text-align:center;
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
💰 Tax Management
</h2>



<div class="form-box">


<form method="POST">


<select name="citizen_id" required>

<option>Select Citizen</option>

<?php

$c=mysqli_query($conn,"SELECT * FROM citizens");

while($x=mysqli_fetch_assoc($c)){

?>

<option value="<?= $x['id'] ?>">

<?= $x['name'] ?>

</option>

<?php } ?>

</select>



<select name="type" id="type" onchange="loadProperties()" required>

<option value="">Select Type</option>
<option>House</option>
<option>Land</option>
<option>Shop</option>
<option>Water</option>
<option>Sanitation</option>

</select>


<select name="property_id" id="property">

<option value="">
Select Property
</option>

</select>

<input type="date" name="due_date" required>



<button name="add">
Generate Tax
</button>


</form>


</div>





<div class="table-box">

<table>


<tr>

<th>ID</th>
<th>Citizen</th>
<th>Property</th>
<th>Type</th>
<th>Amount</th>
<th>Fine</th>
<th>Total</th>
<th>Due</th>
<th>Status</th>
<th>Action</th>

</tr>




<?php while($row=mysqli_fetch_assoc($result)){ ?>


<tr>


<td><?= $row['id']?></td>

<td><?= $row['cname']?></td>

<td><?= $row['pname'] ?? "N/A" ?></td>

<td><?= $row['type']?></td>

<td>₹<?= $row['amount']?></td>

<td>

<?php

if($row['fine']>0)
echo "<span class='fine'>₹".$row['fine']."</span>";
else
echo "₹0";

?>

</td>


<td>
₹<?= $row['total']?>
</td>


<td>
<?= $row['due_date']?>
</td>



<td>

<a class="a" href="?toggle=<?=$row['id']?>">


<span class="<?=$row['status']=="Paid"?'paid':'pending'?>">

<?=$row['status']?>

</span>

</a>


</td>



<td>

<a href="?fine=<?=$row['id']?>"
class="action-btn fine-btn"
onclick="return confirm('Add ₹100 fine for this tax?');">

 Add Fine

</a>


<a href="?delete=<?=$row['id']?>"
class="action-btn delete-btn"
onclick="return confirm('Delete this tax record?');">

 Delete

</a>


</td>
</tr>


<?php } ?>


</table>


</div>


</div>

<script>

function loadProperties(){

let citizen =
document.querySelector("[name='citizen_id']").value;


let type =
document.getElementById("type").value;



if(type=="House" || type=="Land" || type=="Shop"){


fetch(
"get_properties.php?citizen="+citizen+"&type="+type
)

.then(response=>response.text())

.then(data=>{

document.getElementById("property").innerHTML=data;

});

}
else{


document.getElementById("property").innerHTML=
"<option>No Property Needed</option>";

}


}


</script>
<script>

function toggleSidebar(){

document.querySelector(".sidebar")
.classList.toggle("active");

document.querySelector(".overlay")
.classList.toggle("active");

}

</script>
</body>

</html>