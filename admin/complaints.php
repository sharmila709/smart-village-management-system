<?php
session_start();
include("php/db.php");


if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}


/* =========================
   UPDATE STATUS + OFFICER
========================= */

if(isset($_POST['update'])){


    $id = $_POST['id'];
    $status = $_POST['status'];
    $officer = $_POST['officer'];
    $zone = $_POST['zone'];


    $sql="
    UPDATE complaints SET

    status='$status',
    officer='$officer',
    zone='$zone'

    WHERE id='$id'
    ";


    if($conn->query($sql)){

        echo "
        <script>
        alert('Complaint Updated Successfully');
        window.location='complaints.php';
        </script>";

    }

}



/* =========================
   LOAD EDIT DATA
========================= */


$edit=null;


if(isset($_GET['edit'])){


    $id=$_GET['edit'];


    $res=$conn->query(
    "SELECT * FROM complaints WHERE id='$id'"
    );


    $edit=$res->fetch_assoc();

}





/* =========================
   SEARCH
========================= */


$query="SELECT * FROM complaints";


if(isset($_GET['search'])){

    $s=$_GET['search'];

    $query="
    SELECT * FROM complaints
    WHERE citizen LIKE '%$s%'
    OR id LIKE '%$s%'
    ";

}




/* STATUS FILTER */

if(isset($_GET['status']) && $_GET['status']!=""){


    $st=$_GET['status'];


    $query="
    SELECT * FROM complaints
    WHERE status='$st'
    ";

}



$result=$conn->query($query);


?>



<!DOCTYPE html>

<html>

<head>

<title>Complaint Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>


body{
margin:0;
background:#f3f4f6;
font-family:'Playfair Display',serif;
}

.sidebar {
            width: 240px;
            height: 100%;
            background: indigo;
            color: white;
            padding-top: 15px;
            position: fixed;
            overflow-y:auto;
        }

/* SIDEBAR MENU */
.sidebar ul { list-style: none; padding: 0; }

.sidebar ul li { margin: 12px 0; 
 font-size:larger;}

.sidebar ul li a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
}




.main{

margin-left:240px;
padding:20px;

}



.heading{

background:indigo;
color:white;
text-align:center;
font-size:32px;
padding:18px;
border-radius:10px;

}



.form-box{

background:white;
padding:20px;
margin-top:20px;
border-radius:12px;

}




input,select,textarea{

width:100%;
padding:12px;
margin-top:8px;

border:1px solid #ddd;
border-radius:8px;

}



button{

background:indigo;
color:white;
border:none;
padding:12px 20px;
border-radius:8px;
cursor:pointer;

}



.table-container{
    width:100%;
    overflow-x:auto;
    margin-top:20px;
    background:white;
    border-radius:10px;
}


table{
    width:1100px;
    min-width:100%;
    background:white;
    border-collapse:collapse;
}


th{

background:indigo;
color:white;
padding:15px;

}



td{

padding:12px;
border-bottom:1px solid #ddd;
text-align:center;

}



.fa-edit{

color:green;
font-size:20px;

}
/* ==========================
   MOBILE VIEW
========================== */
.menu-btn{
    display:none;
}

.overlay{
    display:none;
}
@media(max-width:768px){


body{
    overflow-x:hidden;
}



/* SIDEBAR TO TOP MENU */

.menu-btn{
    display:block;
    position:fixed;
    top:15px;
    left:15px;
    z-index:3000;

    background:indigo !important;
    color:white !important;

    width:45px !important;
    height:45px !important;

    padding:0 !important;
    margin:0 !important;

    font-size:22px !important;
    line-height:45px;

    border:none !important;
    border-radius:8px !important;

    text-align:center;
}

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




.sidebar{

width:240px ;
height:100vh ;
position:fixed ;
top:0;
left:-260px;
z-index:2500;
background:indigo;
padding-top:20px;
transition:.3s;

}



.sidebar.active{
    left:0;
}




.sidebar ul{
    display:block;
}



.sidebar li{
    margin:10px 0 ;
    text-align:center;
}



.sidebar a{
    padding:12px 20px !important;
    font-size:16px !important;
}


/* MAIN */
.main{
    margin-left:0 !important;
    padding:70px 10px 10px !important;
    width:100%;
}



/* TITLE */

.heading{

    font-size:22px !important;
    padding:15px !important;

}



/* FORM BOX */

.form-box{

    padding:15px !important;
    margin-top:15px !important;

}



/* INPUTS */

input,
select,
textarea,
button{

    width:100% !important;
    box-sizing:border-box;

}



/* TABLE SCROLL */

.table-container{

    overflow-x:auto;
    width:100%;

}


table{

    width:900px !important;

}


/* ICON */

.fa-edit{

    font-size:22px;

}



}

</style>


</head>


<body>

<button type="button" class="menu-btn" onclick="toggleSidebar()">☰</button>

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
🚨 Complaint Management System
</h2>





<div class="form-box">


<form method="GET">


<input 
type="text"
name="search"
placeholder="Search Citizen / Complaint ID">


<select name="status">

<option value="">
All Status
</option>


<option>
Pending
</option>


<option>
In Progress
</option>


<option>
Resolved
</option>


</select>



<button>
Search
</button>


</form>



</div>







<?php if($edit){ ?>


<div class="form-box">


<h3>
Update Complaint
</h3>


<form method="POST">


<input type="hidden"
name="id"
value="<?php echo $edit['id']; ?>">



<label>
Complaint ID
</label>

<input readonly
value="<?php echo $edit['id']; ?>">





<label>
Citizen
</label>

<input readonly
value="<?php echo $edit['citizen']; ?>">





<label>
Category
</label>

<input readonly
value="<?php echo $edit['category']; ?>">





<label>
Description
</label>


<textarea readonly>
<?php echo $edit['description']; ?>
</textarea>






<label>
Status
</label>


<select name="status">


<option 
<?php if($edit['status']=="Pending") echo "selected"; ?>>
Pending
</option>


<option
<?php if($edit['status']=="In Progress") echo "selected"; ?>>
In Progress
</option>


<option
<?php if($edit['status']=="Resolved") echo "selected"; ?>>
Resolved
</option>


</select>





<label>
Assign Officer
</label>


<input 
name="officer"
value="<?php echo $edit['officer']; ?>">





<label>
Zone
</label>


<select name="zone">


<option>North</option>

<option>South</option>

<option>East</option>

<option>West</option>


</select>



<br><br>


<button name="update">

Update Complaint

</button>



</form>


</div>


<?php } ?>





<div class="table-container">
<table>


<tr>

<th>ID</th>
<th>Citizen</th>
<th>Category</th>
<th>Description</th>
<th>Date</th>
<th>Status</th>
<th>Officer</th>
<th>Action</th>


</tr>



<?php while($row=$result->fetch_assoc()){ ?>


<tr>


<td>
<?php echo $row['id']; ?>
</td>



<td>
<?php echo $row['citizen']; ?>
</td>



<td>
<?php echo $row['category']; ?>
</td>



<td>
<?php echo $row['description']; ?>
</td>



<td>
<?php echo $row['date']; ?>
</td>




<td>
<?php echo $row['status']; ?>
</td>




<td>
<?php echo $row['officer']; ?>
</td>




<td>


<a href="?edit=<?php echo $row['id']; ?>">

<i class="fas fa-edit"></i>

</a>


</td>



</tr>



<?php } ?>



</table>

</div>


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