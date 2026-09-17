<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}

/* ADD */
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $village = $_POST['village'];

    mysqli_query($conn, "INSERT INTO citizens(name, age, address, village)
    VALUES('$name','$age','$address','$village')");
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM citizens WHERE id=$id");
}

/* EDIT LOAD */
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM citizens WHERE id=$id");
    $edit = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $village = $_POST['village'];

    mysqli_query($conn, "UPDATE citizens SET 
    name='$name', age='$age', address='$address', village='$village'
    WHERE id=$id");
}

/* SEARCH */
$query = "SELECT * FROM citizens";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM citizens WHERE name LIKE '%$search%'";
}

/* SORT */
if(isset($_GET['sort'])){
    if($_GET['sort']=="name"){
        $query = "SELECT * FROM citizens ORDER BY name ASC";
    }
    if($_GET['sort']=="age"){
        $query = "SELECT * FROM citizens ORDER BY age ASC";
    }
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Citizen Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* BODY */
body{
    margin:0;
    background:#f3f4f6;
    font-family:'Playfair Display', serif;
    overflow-x:hidden;
}

/* SIDEBAR */
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
/* MAIN CONTENT */
.main{
    margin-left:240px;
    padding:20px;
    width:calc(100% - 240px);
    box-sizing:border-box;
}

/* HEADING */
.heading{
    background:indigo;
    color:white;
    text-align:center;
    font-size:32px;
    font-weight:bold;
    padding:18px;
    border-radius:10px;
    margin-bottom:20px;
}

/* FORMS */
form{
    margin-bottom:15px;
}

input{
    padding:10px;
    width:180px;
    border:1px solid #ccc;
    border-radius:4px;
    font-size:16px;
}

button{
    padding:10px 15px;
    background:indigo;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-size:15px;
}

button:hover{
    opacity:0.9;
}

/* TABLE */
table{
    width:100%;
    background:white;
    border-collapse:collapse;
    margin-top:20px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

th{
    background:indigo;
    color:white;
    padding:15px;
    text-align:center;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

tr:hover{
    background:#f9f9f9;
}

/* ICONS */
.fa-edit{
    color:green;
    font-size:20px;
    margin-right:10px;
}

.fa-trash{
    color:red;
    font-size:20px;
}

/* MOBILE RESPONSIVE */
.menu-btn{
    display:none;
}

.overlay{
    display:none;
}
@media(max-width:768px){


/* sidebar becomes top menu */

.menu-btn{
    display:block;
    position:fixed;
    top:35px;
    left:15px;
    z-index:3000;
    background:indigo;
    color:white;
    border:none;
    font-size:24px;
    width:45px;
    height:45px;
    border-radius:8px;
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

width:240px;
height:100vh;
position:fixed;
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


.sidebar ul li{
    text-align:center;
    margin:10px 0;
}   


.sidebar ul li a{
    padding:12px 20px;
    font-size:16px;
}

/* main area */

.main{

margin-left:0;

width:100%;

padding:15px;

}




.heading{

font-size:22px;

}




/* forms */

form{

display:flex;

flex-direction:column;

gap:10px;

}



input{

width:100%;

box-sizing:border-box;

}



button{

width:100%;

}




/* search/sort */

form a button{

width:100%;

}




/* table scroll */

table{

display:block;

overflow-x:auto;

white-space:nowrap;

min-width:850px;

}



}
</style>
</head>

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
        <li><a href="reports.php">Reports</a></li>
        <li><a href="village_hub/village_dashboard.php">Information Hub</a></li>
        <li><a href="login_history.php">Login History</a></li>
        <li><a href="php/logout.php">Logout</a></li>
    </ul>
</div>
<div class="main">

<h2 class="heading">🧑Citizen Management System</h2>

<!-- SEARCH + SORT -->
<form method="GET">
    <input type="text" name="search" placeholder="Search name">
    <button>Search</button>

    <a href="?sort=name"><button type="button">Sort A-Z</button></a>
    <a href="?sort=age"><button type="button">Sort Age</button></a>
</form>

<br>

<!-- ADD / UPDATE FORM -->
<form method="POST">

<?php if($edit){ ?>
<input type="hidden" name="id" value="<?= $edit['id'] ?>">
<?php } ?>

<input type="text" name="name" placeholder="Name" value="<?= $edit['name'] ?? '' ?>" required>
<input type="number" name="age" placeholder="Age" value="<?= $edit['age'] ?? '' ?>" required>
<input type="text" name="address" placeholder="Address" value="<?= $edit['address'] ?? '' ?>" required>
<input type="hidden" name="village" value="Reddiyarpatti">

<?php if($edit){ ?>
<button name="update">Update</button>
<?php } else { ?>
<button name="add">Save</button>
<?php } ?>

</form>

<!-- TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Age</th>
    <th>Address</th>
    <th>Village</th>
    <th>Total Properties</th>
    <th>Total Tax</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { 

    $cid = $row['id'];

    // COUNT PROPERTIES
    $p = mysqli_query($conn, "SELECT COUNT(*) as total FROM properties WHERE citizen_id='$cid'");
    $prop = mysqli_fetch_assoc($p);
    $total_properties = $prop['total'];

    // SUM TAX
    $t = mysqli_query($conn, "SELECT SUM(total) as tax FROM taxes WHERE citizen_id='$cid'");
    $tax = mysqli_fetch_assoc($t);
    $total_tax = $tax['tax'] ?? 0;
?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['age'] ?></td>
    <td><?= $row['address'] ?></td>
    <td><?= $row['village'] ?></td>
    <td><?= $total_properties ?></td>
    <td>₹<?= $total_tax ?></td>
    <td>

        <a href="?edit=<?= $row['id'] ?>" style="color:green; margin-right:10px;">
            <i class="fas fa-edit"></i>
        </a>

        <a href="?delete=<?= $row['id'] ?>" style="color:red;" 
           onclick="return confirm('Are you sure to delete?');">
            <i class="fas fa-trash"></i>
        </a>

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