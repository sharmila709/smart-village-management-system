<?php
include("php/db.php");



// Complaints
$complaints = mysqli_query($conn, "SELECT * FROM complaints");
$complaintData = [];
while($row = mysqli_fetch_assoc($complaints)){
    $complaintData[] = $row;
}

// Workers
$workers = mysqli_query($conn, "SELECT * FROM workers");
$workerData = [];
while($row = mysqli_fetch_assoc($workers)){
    $workerData[] = $row;
}

// Expenses
$expenses = mysqli_query($conn, "SELECT * FROM expenses");
$expenseData = [];
while($row = mysqli_fetch_assoc($expenses)){
    $expenseData[] = $row;
}

// Announcements
$ann = mysqli_query($conn, "SELECT * FROM announcements");
$announcementData = [];
while($row = mysqli_fetch_assoc($ann)){
    $announcementData[] = $row;
}


/* ---------------------------
   TAX CHART (AUTO MONTH FIX)
----------------------------*/

$tax_chart = array_fill(0, 12, 0);

$sql = "
SELECT 
MONTH(payment_date) AS month,
SUM(total) AS total

FROM taxes

WHERE status='Paid'
AND payment_date IS NOT NULL

GROUP BY MONTH(payment_date)
";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)){
    $month = (int)$row['month'];

    if($month >= 1 && $month <= 12){
        $tax_chart[$month - 1] = (float)$row['total'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Report Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
/* hide mobile button on desktop */
.menu-btn{
    display:none;
}

.overlay{
    display:none;
}

@media(max-width:768px){

/* hamburger */
.menu-btn{
    display:block;
    position:relative;
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
    background:rgba(0,0,0,0.4);
    z-index:2000;
    display:none;
}


.overlay.active{
    display:block;
}

/* drawer sidebar */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    top:0;
    left:-260px;
    background:indigo;
    z-index:2500;
    padding-top:20px;
    transition:0.3s ease;
}


.sidebar.active{
    left:0;
}


/* menu vertical */
.sidebar ul{
    display:block;
    padding:0;
}


.sidebar ul li{
    margin:10px 0;
    text-align:center;
}


.sidebar ul li a{
    display:block;
    padding:12px 20px;
    font-size:16px;
}



/* main area */
.main{
    margin-left:0 !important;
    width:100% !important;
    padding:70px 15px 15px;
}



/* heading */
.heading{
    font-size:22px;
}


/* tables */
table{
    min-width:700px;
}


.card{
    width:100%;
    overflow-x:auto;
}


canvas{
    max-width:100%;
}


body{
    overflow-x:hidden;
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
<h1 class="heading">📊 Admin Reports</h1>

<!-- TAX CHART -->
<div class="card">
    <h2>Tax Collection</h2>
    <div style="width:80%; height:300px; margin:auto;">
    <canvas id="barChart"></canvas>
</div>
</div>

<!-- COMPLAINTS -->
<div class="card">
<h2>Complaints</h2>
<table>
<tr><th>ID</th><th>Title</th><th>Category</th></tr>

<?php foreach($complaintData as $c){ ?>
<tr>
<td><?php echo $c['id']; ?></td>
<td><?php echo $c['description']; ?></td>
<td><?php echo $c['category']; ?></td>
</tr>
<?php } ?>

</table>
</div>

<!-- WORKERS -->
<div class="card">
<h2>Workers</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Work</th></tr>

<?php foreach($workerData as $w){ ?>
<tr>
<td><?php echo $w['id']; ?></td>
<td><?php echo $w['name']; ?></td>
<td><?php echo $w['role']; ?></td>
</tr>
<?php } ?>

</table>
</div>

<!-- EXPENSES -->
<div class="card">
<h2>Expenses</h2>
<table>
<tr><th>ID</th><th>Title</th><th>Amount</th></tr>

<?php foreach($expenseData as $e){ ?>
<tr>
<td><?php echo $e['id']; ?></td>
<td><?php echo $e['title']; ?></td>
<td><?php echo $e['amount']; ?></td>
</tr>
<?php } ?>

</table>
</div>

<!-- ANNOUNCEMENTS -->
<div class="card">
<h2>Announcements</h2>
<table>
<tr><th>ID</th><th>Message</th></tr>

<?php foreach($announcementData as $a){ ?>
<tr>
<td><?php echo $a['id']; ?></td>
<td><?php echo $a['title']; ?></td>
</tr>
<?php } ?>

</table>
</div>


<!-- CHART SCRIPT -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let taxData = <?php echo json_encode($tax_chart); ?>;

new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Tax Collection',
            data: taxData,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>
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