<?php
session_start();
include("php/db.php");

// Citizens count
$citizen_query = "SELECT COUNT(*) AS total FROM citizens";
$citizen_result = $conn->query($citizen_query);
$citizen_data = $citizen_result->fetch_assoc();
$total_citizens = $citizen_data['total'];

// Properties count
$property_query = "SELECT COUNT(*) AS total FROM properties";
$property_result = $conn->query($property_query);
$property_data = $property_result->fetch_assoc();
$total_properties = $property_data['total'];

// Complaints count
$complaint_query = "SELECT COUNT(*) AS total FROM complaints";
$complaint_result = $conn->query($complaint_query);
$complaint_data = $complaint_result->fetch_assoc();
$total_complaints = $complaint_data['total'];

// Total Tax
$tax_query = "SELECT SUM(total) AS total FROM taxes";
$tax_result = $conn->query($tax_query);
$tax_data = $tax_result->fetch_assoc();
$total_tax = $tax_data['total'];

if(!isset($_SESSION['admin'])){
    header("Location: login.html");
    exit();
}

/* =========================
   TAX CHART (FIXED PART ONLY)
========================= */

$tax_chart = array_fill(0, 12, 0);

$tax_sql = "
SELECT 
    MONTH(due_date) AS month,
    SUM(total) AS total
FROM taxes
WHERE due_date IS NOT NULL
GROUP BY MONTH(due_date)
ORDER BY month
";

$result = $conn->query($tax_sql);

while($row = $result->fetch_assoc()){
    $month = (int)$row['month'];

    if($month >= 1 && $month <= 12){
        $tax_chart[$month - 1] = (float)$row['total'];
    }
}

/* Complaint Categories */
$complaint_chart = [];
$complaint_labels = [];

$comp_sql = "SELECT category, COUNT(*) as count 
             FROM complaints 
             GROUP BY category";

$result2 = $conn->query($comp_sql);

while($row = $result2->fetch_assoc()){
    $complaint_labels[] = $row['category'];
    $complaint_chart[] = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Playfair' serif;
            display: flex;
            font-size: larger;
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

        .sidebar ul { list-style: none; padding: 0; }

        .sidebar ul li { margin: 12px 0; }

        .sidebar ul li a {
            padding: 10px 20px;
            display: block;
            color: white;
            text-decoration: none;
        }

        .main {
            margin-left: 220px;
            flex: 1;
            background: #f5f7ff;
            padding: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: lavender;
            padding: 20px;
            border-radius: 10px;
        }

        .charts {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .chart-box {
            background: #f4f6fb;
            padding: 20px;
            border-radius: 10px;
        }
        /* =====================
   MOBILE RESPONSIVE
===================== */
.menu-btn{
    display:none;
}

.overlay{
    display:none;
}
.mobile-title{
    display:none;
}
@media(max-width:768px){

.mobile-title{
    display:block;
    text-align:center;
    color:indigo;
    margin:-35px 0 20px 0;
    font-size:large;
    font-weight:bold;
}
body{
    display:block !important;
    overflow-x:hidden;
}


/* Sidebar becomes top menu */

.menu-btn{
    display:block;
    position:relative;
    top:15px;
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


/* drawer sidebar */

.sidebar{

    width:240px ;
    height:100vh ;
    position:fixed ;
    top:0;
    left:-260px;
    z-index:2500;
    background:indigo;
    padding-top:20px ;
    transition:.3s;

}


.sidebar.active{
    left:0;
}



.sidebar ul{
    display:block;
}



.sidebar ul li{
    margin:10px 0 ;
    text-align:center;
}


.sidebar ul li a{
    padding:12px 20px ;
    font-size:16px;
}



/* Main */

.main{

    margin-left:0 !important;
    padding:15px !important;
    width:100% !important;
    box-sizing:border-box;

}



/* Cards */

.cards{

    grid-template-columns:1fr !important;
    gap:15px;

}


.card{

    width:100%;
    box-sizing:border-box;

}



/* Charts */

.charts{

    grid-template-columns:1fr !important;

}



.chart-box{

    width:100%;
    overflow:hidden;

}


canvas{

    max-width:100% !important;
    height:auto !important;

}


}
    </style>
</head>

<body>

<button class="menu-btn" onclick="toggleSidebar()">☰</button>
<h1 class="mobile-title">Admin Dashboard</h1>
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
        <li><a href="php/logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">

<div class="cards">
    <div class="card"><h3>Citizens</h3><p><?= $total_citizens ?></p></div>
    <div class="card"><h3>Properties</h3><p><?= $total_properties ?></p></div>
    <div class="card"><h3>Taxes</h3><p>₹<?= $total_tax ?></p></div>
    <div class="card"><h3>Complaints</h3><p><?= $total_complaints ?></p></div>
</div>

<div class="charts">
    <div class="chart-box">
        <h3>Tax Collection</h3>
        <canvas id="barChart"></canvas>
    </div>

    <div class="chart-box">
        <h3>Complaint Types</h3>
        <canvas id="pieChart"></canvas>
    </div>
</div>

</div>

<script>
let taxData = <?= json_encode(array_values($tax_chart), JSON_NUMERIC_CHECK); ?>;

new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Tax Collection',
            data: taxData
        }]
    }
});

let complaintData = <?= json_encode($complaint_chart); ?>;
let complaintLabels = <?= json_encode($complaint_labels); ?>;

new Chart(document.getElementById("pieChart"), {
    type: 'pie',
    data: {
        labels: complaintLabels,
        datasets: [{
            data: complaintData
        }]
    }
});
</script>
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