<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}

/* ADD */
if(isset($_POST['add'])){
    $id = "ANN" . rand(1000,9999);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $event_date = $_POST['event_date'];
    $status = $_POST['status'];

    mysqli_query($conn,"INSERT INTO announcements 
    (id,title,description,category,date,event_date,status)
    VALUES
    ('$id','$title','$description','$category','$date','$event_date','$status')");
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM announcements WHERE id='$id'");
}

/* EDIT */
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM announcements WHERE id='$id'");
    $edit = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $event_date = $_POST['event_date'];
    $status = $_POST['status'];

    mysqli_query($conn,"UPDATE announcements SET
    title='$title',
    description='$description',
    category='$category',
    date='$date',
    event_date='$event_date',
    status='$status'
    WHERE id='$id'");
}

/* SEARCH */
$query = "SELECT * FROM announcements WHERE 1=1";
if(isset($_GET['search'])){
    $s = $_GET['search'];
    $query .= " AND title LIKE '%$s%'";
}

$result = mysqli_query($conn,$query);

/* DASHBOARD */
$total = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM announcements"));
$active = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM announcements WHERE status='Active'"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Announcement Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{
    background:#f4f6fb;
    font-family:'Playfair Display',serif;
    margin:0;
    padding:0;              /* removed padding to avoid sidebar overlap */
    font-size: larger;
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

.sidebar ul li { margin: 12px 0; }

.sidebar ul li a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
}

/* MAIN CONTENT SHIFT (IMPORTANT) */
.container{
    max-width:1400px;
    margin-left:240px;     /* pushes content away from sidebar */
    padding:30px;
}

/* HEADING */
.heading{
    background:indigo;
    color:white;
    text-align:center;
    padding:20px;
    border-radius:15px;
    font-size:32px;
    font-weight:bold;
    margin-bottom:25px;
}

/* STATS */
.stats{
    display:flex;
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:lavender;
    flex:1;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.card h3{
    margin:0;
    color:#666;
}

.card p{
    font-size:30px;
    color:#4f46e5;
    font-weight:bold;
}

/* SECTION */
.section{
    background:white;
    padding:25px;
    width:100%;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

/* SEARCH BAR */
.search-bar{
    display:flex;
    gap:15px;
}

.search-bar input{
    flex:1;
    padding:15px;
    border:1px solid #ddd;
    border-radius:10px;
}

.search-bar button{
    background:indigo;
    color:white;
    border:none;
    padding:15px 25px;
    border-radius:10px;
    cursor:pointer;
}

/* INPUT GROUP */
.input-group{
    flex:1;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

input,
textarea,
select{
    width:100%;
    padding:13px;
    border:1px solid #ddd;
    border-radius:10px;
    box-sizing:border-box;
}

textarea{
    height:100px;
}

/* ROW */
.row{
    display:flex;
    gap:15px;
    margin-top:20px;
}

/* BUTTON */
.btn-area{
    margin-top:25px;
}

.main-btn{
    background:indigo;
    color:white;
    border:none;
    padding:14px 30px;
    border-radius:10px;
    cursor:pointer;
}

/* TABLE */
table{
    width:90%;
    margin-left:0%;
    border-collapse:collapse;
    background:white;
}

th{
    background:indigo;
    color:white;
    padding:13px;
}

td{
    padding:15px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#fafafa;
}

/* ACTION BUTTONS */
.edit-btn{
    color:green;
    font-size:18px;
    margin-right:10px;
}

.delete-btn{
    color:red;
    font-size:18px;
}

.status{
    background:#dcfce7;
    color:#15803d;
    padding:6px 14px;
    border-radius:20px;
}
 
/* MOBILE RESPONSIVE */
/* desktop hide mobile button */
.menu-btn{
    display:none;
}

.overlay{
    display:none;
}
@media(max-width:768px){


/* sidebar */

.menu-btn{
    display:block;
    position:relative;
    top:60px;
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


/* overlay */

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


/* drawer */

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
    margin:10px 0;
    text-align:center;
}


.sidebar ul li a{
    padding:12px 20px;
    font-size:16px;
}



/* content */

.container{

margin-left:0;

padding:15px;

}




.heading{

font-size:22px;

padding:15px;

}




/* cards */

.stats{

flex-direction:column;

}



.card{

width:100%;

}




/* search */

.search-bar{

flex-direction:column;

}



.search-bar button{

width:100%;

}




/* form rows */

.row{

flex-direction:column;

}




.input-group{

width:100%;

}




/* table */

.section{

overflow-x:auto;

}



table{

min-width:900px;

}



}


/* tablet */

@media(max-width:1000px) and (min-width:769px){


.container{

margin-left:220px;

}


.stats{

flex-wrap:wrap;

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
    <li><a href="php/logout.php"> Logout </a><li>
   

    </ul>
</div>

<div class="main">

<div class="container">

    <!-- Header -->
    <div class="heading">
        📢 Announcement Management System
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="card">
            <h3>Total Announcements</h3>
            <p><?= $total ?></p>
        </div>

        <div class="card">
            <h3>Active Announcements</h3>
            <p><?= $active ?></p>
        </div>
    </div>

    <!-- Search -->
    <div class="section">
        <form method="GET" class="search-bar">
            <input type="text" name="search"
                   placeholder="🔍 Search announcement title...">
            <button type="submit">
                Search
            </button>
        </form>
    </div>

    <!-- Form -->
    <div class="section form-box">

        <form method="POST">

            <?php if($edit){ ?>
                <input type="hidden" name="id"
                value="<?= $edit['id'] ?>">
            <?php } ?>

            <div class="input-group">
                <label>Announcement Title</label>
                <input type="text"
                       name="title"
                       value="<?= $edit['title'] ?? '' ?>"
                       required>
            </div>

            <div class="input-group">
                <label>Description</label>
                <textarea name="description"><?= $edit['description'] ?? '' ?></textarea>
            </div>

            <div class="row">

                <div class="input-group">
                    <label>Category</label>
                    <select name="category">
                        <option>General</option>
                        <option>Emergency</option>
                        <option>Event</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Created Date</label>
                    <input type="date"
                           name="date"
                           value="<?= $edit['date'] ?? '' ?>"
                           required>
                </div>

                <div class="input-group">
                    <label>Event Date</label>
                    <input type="date"
                           name="event_date"
                           value="<?= $edit['event_date'] ?? '' ?>"
                           required>
                </div>

                <div class="input-group">
                    <label>Status</label>
                    <select name="status">
                        <option>Active</option>
                        <option>Expired</option>
                    </select>
                </div>

            </div>

            <div class="btn-area">
                <?php if($edit){ ?>
                    <button class="main-btn" name="update">
                        Update Announcement
                    </button>
                <?php } else { ?>
                    <button class="main-btn" name="add">
                        Add Announcement
                    </button>
                <?php } ?>
            </div>

        </form>

    </div>


    <!-- Table -->
    <div class="section">

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Announcement</th>
                <th>Description</th>
                <th>Category</th>
                <th>Created Date</th>
                <th>Event Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>

            <?php while($row=mysqli_fetch_assoc($result)){ ?>
            <tr>

                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['category'] ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['event_date'] ?></td>
                <td>
                    <span class="status">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td>

                    <a href="?edit=<?= $row['id'] ?>"
                       class="edit-btn">
                        <i class="fas fa-edit"></i>
                    </a>

                    <a href="?delete=<?= $row['id'] ?>"
                       class="delete-btn"
                       onclick="return confirm('Delete?');">
                        <i class="fas fa-trash"></i>
                    </a>

                </td>

            </tr>
            <?php } ?>

            </tbody>

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