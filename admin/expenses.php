<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}

/* ADD */
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $dept = $_POST['dept'];
  

    mysqli_query($conn,"INSERT INTO expenses 
    (title,category,amount,expense_date,status,dept)
    VALUES
    ('$title','$category','$amount','$date','$status','$dept')");
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM expenses WHERE id=$id");
}

/* EDIT */
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM expenses WHERE id=$id");
    $edit = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $dept = $_POST['dept'];
    

    mysqli_query($conn,"UPDATE expenses SET
    title='$title',
    category='$category',
    amount='$amount',
    expense_date='$date',
    status='$status',
    dept='$dept'
    WHERE id=$id");
}

/* SEARCH */
$query = "SELECT * FROM expenses";

if(isset($_GET['search'])){
    $s = $_GET['search'];
    $query = "SELECT * FROM expenses 
    WHERE title LIKE '%$s%' OR id LIKE '%$s%'";
}

/* FILTER */
if(isset($_GET['category']) && $_GET['category']!=""){
    $c = $_GET['category'];
    $query = "SELECT * FROM expenses WHERE category='$c'";
}

if(isset($_GET['status']) && $_GET['status']!=""){
    $st = $_GET['status'];
    $query = "SELECT * FROM expenses WHERE status='$st'";
}

/* SORT */
if(isset($_GET['sort'])){
    if($_GET['sort']=="amount"){
        $query = "SELECT * FROM expenses ORDER BY amount ASC";
    }
    if($_GET['sort']=="date"){
        $query = "SELECT * FROM expenses ORDER BY expense_date ASC";
    }
}

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Expense Management</title>
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
font-size:larger; }

.sidebar ul li a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
}
.menu-btn{
    display:none;
}

.overlay{
    display:none;
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
/* FORM STYLING */
form{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    align-items:center;
    margin-bottom:20px;
}

input,
select{
    padding:12px;
    border:1px solid #d1d5db;
    border-radius:8px;
    font-size:15px;
    min-width:180px;
    outline:none;
}

input:focus,
select:focus{
    border-color:indigo;
}

/* BUTTONS */
button{
    background:indigo;
    color:white;
    border:none;
    border-radius:8px;
    padding:12px 20px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    opacity:0.95;
    transform:translateY(-2px);
}

/* FORM BOX */
.form-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    margin-bottom:20px;
}

/* SAVE BUTTON */
button[name="add"],
button[name="update"]{
    min-width:140px;
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
.paid{
    color:green;
    font-weight:bold;
}

.pending{
    color:red;
    font-weight:bold;
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
    display:none;
    position:fixed;
    top:0;
    left:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,.4);

    z-index:2000;
}


.overlay.active{
    display:block;
}



/* SIDEBAR DRAWER */

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


.sidebar li{

margin:10px 0;

text-align:center;

}



.sidebar a{

padding:12px 20px;

font-size:16px;

}



/* MAIN */

.main{

margin-left:0 !important;

width:100% !important;

padding:60px 10px 10px !important;

}




.heading{

font-size:22px;

}




form{

flex-direction:column;

}




input,
select{

width:100% !important;

box-sizing:border-box;

}



/* Only normal buttons */

.form-box button{

width:100% !important;

}



/* Keep menu button small */

.menu-btn{

width:35px !important;

height:35px !important;

}



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
        <li><a href="reports.php">Reports</a></li>
        <li><a href="village_hub/village_dashboard.php">Information Hub</a></li>
        <li><a href="login_history.php">Login History</a></li>
        <li><a href="php/logout.php">Logout</a></li>
    </ul>
</div>
<div class="main">
<h2 class="heading">💰 Expense Management System</h2>

<!-- FILTER + SEARCH + SORT -->
<div class="form-box">
<form method="GET">

<input type="text"
name="search"
placeholder="Search Expense ID / Title"
style="width:250px;">

<button type="submit">Search</button>

<select name="category">
<option value="">All Category</option>
<option>Maintenance</option>
<option>Salary</option>
<option>Electricity</option>
<option>Water</option>
</select>

<select name="status">
<option value="">All Status</option>
<option>Paid</option>
<option>Pending</option>
</select>

<button type="submit">Filter</button>

<button type="submit" name="sort" value="amount">Sort Amount</button>

<button type="submit" name="sort" value="date">Sort Date</button>

</form>
</div>
<!-- FORM -->
<div class="form-box">
<form method="POST">

<?php if($edit){ ?>
<input type="hidden" name="id" value="<?= $edit['id'] ?>">
<?php } ?>

<input type="text"
name="title"
placeholder="Expense Title"
value="<?= $edit['title'] ?? '' ?>"
required>

<select name="category">
<option>Maintenance</option>
<option>Salary</option>
<option>Electricity</option>
<option>Water</option>
</select>

<input type="number"
name="amount"
placeholder="Amount"
value="<?= $edit['amount'] ?? '' ?>"
required>

<input type="date"
name="date"
value="<?= $edit['expense_date'] ?? '' ?>"
required>

<select name="status">
<option>Paid</option>
<option>Pending</option>
</select>

<input type="text"
name="dept"
placeholder="Department"
value="<?= $edit['dept'] ?? '' ?>">

<?php if($edit){ ?>
<button name="update">Update Expense</button>
<?php } else { ?>
<button name="add">Add Expense</button>
<?php } ?>

</form>
</div>

<!-- TABLE -->
<table>
<tr>
<th>ID</th>
<th>Title</th>
<th>Category</th>
<th>Amount</th>
<th>Date</th>
<th>Status</th>
<th>Dept</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['category'] ?></td>
<td><?= $row['amount'] ?></td>
<td><?= $row['expense_date'] ?></td>

<td class="<?= $row['status']=='Paid'?'paid':'pending' ?>">
<?= $row['status'] ?>
</td>

<td><?= $row['dept'] ?></td>


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

document.querySelector(".sidebar")
.classList.toggle("active");


document.querySelector(".overlay")
.classList.toggle("active");


}

</script>
</body>
</html>