<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}

/* ADD */
if(isset($_POST['add'])){
    $id = "WRK" . rand(1000,9999);
    $name = $_POST['name'];
    $role = $_POST['role'];
    $dept = $_POST['dept'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $complaint = $_POST['complaint'];
    $date = $_POST['date'];

    mysqli_query($conn,"INSERT INTO workers 
    VALUES('$id','$name','$role','$dept','$phone','$status','$complaint','$date')");
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM workers WHERE id='$id'");
}

/* EDIT LOAD */
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM workers WHERE id='$id'");
    $edit = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $dept = $_POST['dept'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $complaint = $_POST['complaint'];
    $date = $_POST['date'];

    mysqli_query($conn,"UPDATE workers SET 
    name='$name',
    role='$role',
    dept='$dept',
    phone='$phone',
    status='$status',
    complaint='$complaint',
    date='$date'
    WHERE id='$id'");
}

/* SEARCH */
$query = "SELECT * FROM workers";

if(isset($_GET['search'])){
    $s = $_GET['search'];
    $query = "SELECT * FROM workers 
    WHERE name LIKE '%$s%' OR id LIKE '%$s%'";
}

/* FILTER */
if(isset($_GET['status']) && $_GET['status']!=""){
    $st = $_GET['status'];
    $query = "SELECT * FROM workers WHERE status='$st'";
}

/* FILTER ROLE */
if(isset($_GET['role']) && $_GET['role']!=""){
    $r = $_GET['role'];
    $query = "SELECT * FROM workers WHERE role='$r'";
}

/* SORT */
if(isset($_GET['sort'])){
    if($_GET['sort']=="name"){
        $query = "SELECT * FROM workers ORDER BY name ASC";
    }
    if($_GET['sort']=="date"){
        $query = "SELECT * FROM workers ORDER BY date ASC";
    }
}

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Workers Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f6fb;
    font-family:'Playfair Display',serif;
    overflow-x:hidden;
}

/* ======================
   DESKTOP SIDEBAR
====================== */

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:indigo;
    color:white;
    overflow-y:auto;
    padding-top:20px;
    box-shadow:4px 0 15px rgba(0,0,0,.15);
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:8px 12px;
}

.sidebar ul li a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px 16px;
    border-radius:10px;
    font-size:larger;
}

.sidebar ul li a:hover{
    
}

/* ======================
   MAIN CONTENT
====================== */

.main{
    margin-left:240px;
    padding:25px;
}

/* ======================
   HEADING
====================== */

.heading{
    background:indigo;
    color:white;
    text-align:center;
    padding:18px;
    border-radius:12px;
    font-size:30px;
    margin-bottom:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.12);
}

/* ======================
   BOXES
====================== */

.form-box{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

/* ======================
   FORMS
====================== */

form{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:center;
}

input,
select{
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:10px;
    min-width:180px;
    font-size:15px;
    outline:none;
}

input:focus,
select:focus{
    border-color:indigo;
}

/* ======================
   BUTTONS
====================== */

button{
    background:indigo;
    color:white;
    border:none;
    border-radius:10px;
    padding:12px 20px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
    transition:.3s;
}

button:hover{
    transform:translateY(-2px);
}

/* ======================
   TABLE
====================== */

.table-box{
    background:white;
    border-radius:15px;
    overflow-x:auto;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

table{
    width:100%;
    min-width:1100px;
    border-collapse:collapse;
}

th{
    background:indigo;
    color:white;
    padding:15px;
    text-align:center;
}

td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #e5e7eb;
}

tr:hover{
    background:#f8fafc;
}

/* ======================
   STATUS BADGES
====================== */

.available{
    background:#16a34a;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    display:inline-block;
}

.busy{
    background:#f59e0b;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    display:inline-block;
}

.leave{
    background:#dc2626;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    display:inline-block;
}

/* ======================
   ACTION ICONS
====================== */

.fa-edit{
    color:#16a34a;
    font-size:20px;
}

.fa-trash{
    color:#dc2626;
    font-size:20px;
}

/* ======================
   SCROLLBAR
====================== */

.table-box::-webkit-scrollbar{
    height:10px;
}

.table-box::-webkit-scrollbar-thumb{
    background:indigo;
    border-radius:10px;
}

/* ======================
   MOBILE
====================== */

.menu-btn{
    display:none;
}

.overlay{
    display:none;
}

@media(max-width:768px){

.menu-btn{
    display:block;
    position:relative;
    top:10px;
    left:10px;
    width:35px;
    height:35px;
    background:indigo;
    color:white;
    border:none;
    border-radius:6px;
    z-index:3000;
    padding:0;
    font-size:18px;
}

/* Overlay */

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

/* Mobile Sidebar */

.sidebar{
    width:240px;
    left:-260px;
    top:0;
    height:100vh;
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
    font-size:16px;
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
}

/* Forms */

form{
    flex-direction:column;
    align-items:stretch;
}

input,
select,
button{
    width:100%;
}

/* Table */

.table-box{
    overflow-x:auto;
}

table{
    min-width:900px;
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
    <li><a href="reports.php">Report</a></li>
    <li><a href="village_hub/village_dashboard.php">Information Hub</a></li>
    <li><a href="login_history.php">Login History</a></li>
    <li>
<a href="php/logout.php">Logout</a>
</li>
   

    </ul>
</div>

<div class="main">

<h2 class="heading">👷 Workers Management System</h2>

<!-- SEARCH + FILTER + SORT -->
<div class="form-box">
<form method="GET">

<input type="text" name="search"
placeholder="Search Worker Name / ID"
style="width:250px;">

<button type="submit">Search</button>

<select name="status">
<option value="">All Status</option>
<option>Available</option>
<option>Busy</option>
<option>On Leave</option>
</select>

<select name="role">
<option value="">All Roles</option>
<option>Electrician</option>
<option>Plumber</option>
<option>Cleaner</option>
<option>Supervisor</option>
</select>

<button type="submit">Filter</button>

<button type="submit" name="sort" value="name">Sort Name</button>
<button type="submit" name="sort" value="date">Sort Date</button>

</form>
</div>

<!-- FORM -->
<div class="form-box">
<form method="POST">

<?php if($edit){ ?>
<input type="hidden" name="id" value="<?= $edit['id'] ?>">
<?php } ?>

<input type="text" name="name"
placeholder="Worker Name"
value="<?= $edit['name'] ?? '' ?>" required>

<select name="role">
<option>Electrician</option>
<option>Plumber</option>
<option>Cleaner</option>
<option>Supervisor</option>
</select>

<input type="text" name="dept"
placeholder="Department"
value="<?= $edit['dept'] ?? '' ?>">

<input type="text" name="phone"
placeholder="Phone Number"
value="<?= $edit['phone'] ?? '' ?>">

<select name="status">
<option>Available</option>
<option>Busy</option>
<option>On Leave</option>
</select>

<input type="text" name="complaint"
placeholder="Complaint ID"
value="<?= $edit['complaint'] ?? '' ?>">

<input type="date" name="date"
value="<?= $edit['date'] ?? '' ?>" required>

<?php if($edit){ ?>
<button name="update">Update Worker</button>
<?php } else { ?>
<button name="add">Add Worker</button>
<?php } ?>

</form>
</div>

<!-- TABLE -->
<div class="table-box">
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Role</th>
<th>Dept</th>
<th>Phone</th>
<th>Status</th>
<th>Complaint</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['role'] ?></td>
<td><?= $row['dept'] ?></td>
<td><?= $row['phone'] ?></td>
<td class="<?=
$row['status']=='Available'?'available':
($row['status']=='Busy'?'busy':'leave')
?>"><?= $row['status'] ?></td>
<td><?= $row['complaint'] ?></td>
<td><?= $row['date'] ?></td>

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
</div>
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