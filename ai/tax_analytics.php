<?php
include("../db.php");

/* =========================
   TAX ANALYTICS SUMMARY
========================= */

$totalCollection = 0;
$pendingTax = 0;
$paidCitizens = 0;
$defaulters = 0;
$pendingCount = 0;
$totalRecords = 0;
$collectionEfficiency = 0;
$averageTax = 0;


/* TOTAL COLLECTION */

$result = $conn->query("
SELECT SUM(total) AS total_collection
FROM taxes
WHERE status='Paid'
");

if($result && $row = $result->fetch_assoc())
{
    $totalCollection = $row['total_collection'] ?? 0;
}


/* PENDING TAX */

$result = $conn->query("
SELECT SUM(total) AS pending_amount
FROM taxes
WHERE status='Pending'
");

if($result && $row = $result->fetch_assoc())
{
    $pendingTax = $row['pending_amount'] ?? 0;
}


/* PAID CITIZENS */

$result = $conn->query("
SELECT COUNT(DISTINCT citizen_id) AS paid_count
FROM taxes
WHERE status='Paid'
");

if($result && $row = $result->fetch_assoc())
{
    $paidCitizens = $row['paid_count'];
}


/* OVERDUE TAXPAYERS */

$result = $conn->query("
SELECT COUNT(*) AS overdue_count
FROM taxes
WHERE status='Pending'
AND due_date < CURDATE()
");

if($result && $row = $result->fetch_assoc())
{
    $defaulters = $row['overdue_count'];
}


/* PENDING RECORDS */

$result = $conn->query("
SELECT COUNT(*) AS pending_count
FROM taxes
WHERE status='Pending'
");

if($result && $row = $result->fetch_assoc())
{
    $pendingCount = $row['pending_count'];
}


/* TOTAL TAX RECORDS */

$result = $conn->query("
SELECT COUNT(*) AS total_records
FROM taxes
");

if($result && $row = $result->fetch_assoc())
{
    $totalRecords = $row['total_records'];
}


/* AVERAGE TAX */

$result = $conn->query("
SELECT AVG(total) AS avg_tax
FROM taxes
WHERE status='Paid'
");

if($result && $row = $result->fetch_assoc())
{
    $averageTax = round($row['avg_tax'] ?? 0);
}


/* COLLECTION EFFICIENCY */

if(($totalCollection + $pendingTax) > 0)
{
    $collectionEfficiency =
    round(
        ($totalCollection /
        ($totalCollection + $pendingTax)) * 100,
        2
    );
}


// MONTHLY TAX COLLECTION

/* =========================
   MONTHLY COLLECTION TREND
========================= */

$months = [];
$amounts = [];

$result = $conn->query("
SELECT
MONTHNAME(payment_date) AS month,
SUM(total) AS amount

FROM taxes

WHERE status='Paid'
AND payment_date IS NOT NULL

GROUP BY MONTH(payment_date)

ORDER BY MONTH(payment_date)
");


while($row = $result->fetch_assoc())
{
    $months[] = $row['month'];
    $amounts[] = $row['amount'];
}
/* =========================
   TOP 5 DEFAULTERS
========================= */

$topDefaulters = $conn->query("

SELECT
citizen_id,
property_name,
total,
due_date

FROM taxes

WHERE status='Pending'
AND due_date < CURDATE()

ORDER BY total DESC

LIMIT 5

");


// STATUS DATA

$statusLabels=[
"Paid",
"Pending",
"Overdue"
];


$statusValues=[
$paidCitizens,
$pendingCount,
$defaulters
];



$monthsJSON=json_encode($months);
$amountsJSON=json_encode($amounts);

$statusLabelsJSON=json_encode($statusLabels);
$statusValuesJSON=json_encode($statusValues);



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tax Analytics</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html,body{
    height:100%;
    overflow-x:hidden;
    font-family:'Playfair Display', serif;
}

/* SIDEBAR (EXACT SAME AS DASHBOARD) */

.sidebar{
    width:250px;
    height:100vh;
    background:indigo;
    color:white;
    padding:20px;

    position:fixed;
    top:0;
    left:0;

    overflow-y:auto;
    overflow-x:hidden;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    font-size:larger;
    padding:10px;
    margin:8px 0;
    border-radius:8px;
    cursor:pointer;
    
    transition:0.3s;
}

.sidebar ul li:hover{
    background:#382865;
    transform:translateX(1px);
}

/* MAIN */

.main{
    margin-left:250px;
    width:calc(100% - 250px);
    min-height:100vh;
}

/* NAVBAR */

.navbar{
min-height:80px;
height:auto;
    background:#EFE9FF;
    padding:15px 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;

    box-shadow:0 2px 5px rgba(0,0,0,0.1);

   
    top:0;
    z-index:1000;
}

.search{
    width:250px;
    padding:8px;
    border:none;
    border-radius:5px;
}

/* TITLE */

.title{
    padding:20px;
    color:indigo;
}

/* CARDS */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
padding:0 25px;
}


.card{
background:#EFE9FF;
padding:20px;
border-radius:12px;
text-align:center;
box-shadow:0 2px 10px rgba(0,0,0,0.08);
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
}
.card p{
    font-size:22px;
    font-weight:bold;
}

/* CHARTS */

.charts{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    padding:25px;
}

.chart-box{
    height:250px;
    background:white;
    border-radius:10px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);

    display:flex;
    justify-content:center;
    align-items:center;
    color:indigo;
    font-size:20px;
}

/* STATUS */

.status{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    padding:25px;
}

.status-card{
    padding:20px;
    border-radius:10px;
    text-align:center;
    color:white;
}

.paid{ background:green; }
.pending{ background:orange; }
.overdue{ background:red; }

/* ZONES */

.zones{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
    padding:25px;
}

.zone-card{
background:#EFE9FF;
padding:20px;
border-radius:12px;
box-shadow:0 2px 10px rgba(0,0,0,0.08);
transition:.3s;
}

.zone-card:hover{
transform:translateY(-4px);
}
.box{
background:white;
margin:25px;
padding:20px;
border-radius:12px;
box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.box table{
width:100%;
border-collapse:collapse;
}

.box th{
background:indigo;
color:white;
padding:12px;
}

.box td{
padding:12px;
border:1px solid #ddd;
text-align:center;
}
/* RESPONSIVE */

/* ================= MOBILE VIEW FIX ================= */
.back-btn{
display:none;
background:indigo;
color:white;
text-decoration:none;
padding:8px 12px;
border-radius:8px;
font-size:14px;
font-weight:bold;
}

.nav-left{
display:flex;
align-items:center;
gap:12px;
}
@media(max-width:900px){

    body{
        display:block;
        overflow-x:hidden;
    }

    /* hide sidebar */
    .sidebar{
        display:none;
    }

    .main{
        margin-left:0 !important;
        width:100% !important;
        padding:10px;
    }

    .back-btn{
display:block;
}

.nav-left{
width:100%;
display:flex;
align-items:center;
gap:10px;
}

.nav-left h1{
font-size:20px;
margin:0;
}

.navbar{
align-items:flex-start;
}
    /* top header */
    .navbar,
    .header{
        height:auto;
        padding:15px;
        flex-direction:column;
        gap:15px;
        text-align:center;
    }


    .navbar h1,
    .header h1{
        font-size:22px;
    }


    .search{
        width:100%;
    }


    /* cards */
    .cards{
        grid-template-columns:1fr !important;
        padding:10px 0;
    }


    .card{
        width:100%;
    }


    /* charts */
    .charts{
        grid-template-columns:1fr !important;
        padding:10px 0;
    }


    .chart-box{
        height:250px;
        width:100%;
    }


    /* status */
    .status,
    .status-grid,
    .zone{
        grid-template-columns:1fr !important;
        padding:10px 0;
    }


    .zone-card{
        width:100%;
    }


    /* AI dashboard circle */
    .ai-score{
        flex-direction:column;
        gap:25px;
        padding:20px;
    }


    .circle{
        width:130px;
        height:130px;
    }


    .circle div{
        width:90px;
        height:90px;
        font-size:22px;
    }



    /* tables */
    .box{
        margin:10px 0;
        overflow-x:auto;
    }


    table{
        min-width:600px;
    }



    /* chatbot */
    .chat-section{
        height:calc(100vh - 130px);
    }


    .chat-box{
        height:100%;
    }


    .bot,
    .user{
        max-width:90%;
        font-size:13px;
    }


    .input-area{
        flex-wrap:wrap;
    }


    .input-area input{
        width:100%;
    }


    .input-area button{
        flex:1;
    }

}
.active a{
    color:whitesmoke;
    text-decoration:none;
}
</style>

</head>

<body>

<!-- SIDEBAR (EXACT SAME FOR ALL PAGES) -->

<div class="sidebar">

<ul>
        <li class="active"><a href="ai_dashboard.php">AI Dashboard</a></li>

        <li class="active"><a href="village_map.php"> Village Map</a></li>

        <li class="active"><a href="complaint_analytics.php">Complaint Analytics</a></li>

        <li class="active"><a href="tax_analytics.php"> Tax Analytics</a></li>

        <li class="active"><a href="chatbox.php"> AI Chatbot</a></li>

    
        <li class="active"><a href="predictions.php">AI Insights</a></li>
        <li class="active">
        <a href="../index.html">
        Logout
        </a>
</li>

</ul>

</div>

<!-- MAIN -->

<div class="main">

<div class="navbar">

<div class="nav-left">

<a href="javascript:history.back()" class="back-btn">
Back
</a>

<h1>Tax Analytics</h1>

</div>

<input type="text"
id="searchInput"
class="search"
placeholder="Search tax data...">

</div>

<div class="title">
<h2>Village Tax Overview</h2>
</div>

<!-- CARDS -->

<div class="cards">

<div class="card">
<h3>Total Collection</h3>
<p>₹<?php echo number_format($totalCollection); ?></p>
</div>

<div class="card">
<h3>Pending Tax</h3>
<p>₹<?php echo number_format($pendingTax); ?></p>
</div>

<div class="card">
<h3>Paid Citizens</h3>
<p><?php echo $paidCitizens; ?></p>
</div>

<div class="card">
<h3>Defaulters</h3>
<p><?php echo $defaulters; ?></p>
</div>

<div class="card">
<h3>Total Records</h3>
<p><?php echo $totalRecords; ?></p>
</div>

<div class="card">
<h3>Average Tax</h3>
<p>₹<?php echo number_format($averageTax); ?></p>
</div>

<div class="card">
<h3>Collection Efficiency</h3>
<p><?php echo $collectionEfficiency; ?>%</p>
</div>

</div>
<!-- CHARTS -->

<div class="charts">


<div class="chart-box">

<canvas id="taxTrend"></canvas>

</div>


<div class="chart-box">

<canvas id="taxStatus"></canvas>

</div>


</div>

<!-- STATUS -->

<div class="status">

<div class="status-card paid">
Paid<br><b><?php echo $paidCitizens; ?></b>
</div>

<div class="status-card pending">
Pending<br><b><?php echo $pendingCount; ?></b>
</div>

<div class="status-card overdue">
Overdue<br><b><?php echo $defaulters; ?></b>
</div>

</div>
<!-- TOP DEFAULTERS -->

<div class="box">

<h2 style="color:indigo; margin-bottom:15px;">
🚨 Top 5 Tax Defaulters
</h2>

<div style="overflow-x:auto;">

<table style="width:100%; border-collapse:collapse;">

<tr>

<th>Citizen ID</th>
<th>Property Name</th>
<th>Amount Due</th>
<th>Due Date</th>

</tr>

<?php

while($row = $topDefaulters->fetch_assoc())
{

?>

<tr>

<td>
<?php echo $row['citizen_id']; ?>
</td>

<td>
<?php echo $row['property_name']; ?>
</td>

<td>
₹<?php echo number_format($row['total']); ?>
</td>

<td>
<?php echo $row['due_date']; ?>
</td>

</tr>

<?php

}

?>

</table>

</div>

</div>
<!-- ZONES -->

<div class="zones">

<div class="zone-card">

<center>
<h3>🤖 AI Collection Insight</h3>

<?php

if($collectionEfficiency >= 80)
{
    echo "<p>Excellent tax collection performance. Most citizens are paying taxes on time.</p>";
}
elseif($collectionEfficiency >= 60)
{
    echo "<p>Moderate collection performance. Revenue collection is healthy but can improve.</p>";
}
else
{
    echo "<p>Low collection efficiency detected. Immediate follow-up with pending taxpayers is recommended.</p>";
}

?>

</center>

</div>



<div class="zone-card">

<center>
<h3>💰 Pending Revenue Analysis</h3>

<p>

₹<?php echo number_format($pendingTax); ?>

is still awaiting collection from taxpayers.

</p>

</center>

</div>



<div class="zone-card">

<center>

<h3>⚠ Defaulter Risk</h3>

<p>

<?php echo $defaulters; ?>

tax records are overdue and require attention.

</p>

</center>

</div>



<div class="zone-card">

<center>

<h3>👥 Citizen Participation</h3>

<p>

<?php echo $paidCitizens; ?>

citizens have successfully paid their taxes.

</p>

</center>

</div>

</div></div>
<script>


// MONTHLY TAX COLLECTION GRAPH

new Chart(
document.getElementById("taxTrend"),
{

type:"line",

data:{

labels:<?php echo $monthsJSON;?>,

datasets:[{

label:"Tax Collection",

data:<?php echo $amountsJSON;?>,

borderWidth:2

}]

},

options:{

responsive:true

}

});




// STATUS GRAPH


new Chart(
document.getElementById("taxStatus"),
{

type:"doughnut",

data:{

labels:<?php echo $statusLabelsJSON;?>,

datasets:[{

label:"Tax Status",

data:<?php echo $statusValuesJSON;?>,

borderWidth:1

}]

},

options:{

responsive:true

}

});


</script>
<script>

document.addEventListener("DOMContentLoaded", function(){

    const search = document.getElementById("searchInput");

    search.addEventListener("keyup", function(){

        let value = this.value.toLowerCase();

        let items = document.querySelectorAll(
            ".card, .status-card, .zone-card"
        );

        items.forEach(function(item){

            let text = item.textContent.toLowerCase();

            if(text.includes(value))
            {
                item.style.display = "";
            }
            else
            {
                item.style.display = "none";
            }

        });

    });

});

</script>
</body>
</html>