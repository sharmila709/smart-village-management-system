<?php
include("../db.php");


/* ==========================
BASIC COUNTS
========================== */

$totalComplaints =
$conn->query("SELECT COUNT(*) c FROM complaints")
->fetch_assoc()['c'] ?? 0;

$pending =
$conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")
->fetch_assoc()['c'] ?? 0;

$resolved =
$conn->query("SELECT COUNT(*) c FROM complaints WHERE status='Resolved'")
->fetch_assoc()['c'] ?? 0;

$inprogress =
$conn->query("SELECT COUNT(*) c FROM complaints WHERE status='In Progress'")
->fetch_assoc()['c'] ?? 0;

$totalProperties =
$conn->query("SELECT COUNT(*) c FROM properties")
->fetch_assoc()['c'] ?? 0;

$totalUsers =
$conn->query("SELECT COUNT(*) c FROM citizens")
->fetch_assoc()['c'] ?? 0;


/* ==========================
TAX DATA
========================== */

$totalTax =
$conn->query("
SELECT SUM(total) c
FROM taxes
")
->fetch_assoc()['c'] ?? 0;


$paidTax =
$conn->query("
SELECT SUM(total) c
FROM taxes
WHERE status='Paid'
")
->fetch_assoc()['c'] ?? 0;


$pendingTax =
$conn->query("
SELECT SUM(total) c
FROM taxes
WHERE status='Pending'
")
->fetch_assoc()['c'] ?? 0;


$overdueTax =
$conn->query("
SELECT SUM(total) c
FROM taxes
WHERE status='Overdue'
")
->fetch_assoc()['c'] ?? 0;


/* ==========================
SERVICE SCORE
========================== */

$serviceScore = 0;

if($totalComplaints > 0)
{
    $serviceScore =
    round(($resolved/$totalComplaints)*100);
}


/* ==========================
TAX SCORE
========================== */

$taxScore = 0;

if($totalTax > 0)
{
    $taxScore =
    round(($paidTax/$totalTax)*100);
}


/* ==========================
ZONE ANALYSIS
========================== */

$zones=[
"North"=>0,
"South"=>0,
"East"=>0,
"West"=>0
];

$q=$conn->query("
SELECT zone,COUNT(*) c
FROM complaints
GROUP BY zone
");

while($r=$q->fetch_assoc())
{
    $zone=
    ucfirst(strtolower($r['zone']));

    if(isset($zones[$zone]))
    {
        $zones[$zone]=$r['c'];
    }
}

$worstZone =
array_keys($zones,max($zones))[0];

$bestZone =
array_keys($zones,min($zones))[0];


/* ==========================
AI PREDICTION
========================== */

$complaintHistory=[];

$cq=$conn->query("
SELECT MONTH(date) month,
COUNT(*) total
FROM complaints
GROUP BY MONTH(date)
ORDER BY MONTH(date) DESC
LIMIT 6
");

while($row=$cq->fetch_assoc())
{
    $complaintHistory[]=
    $row['total'];
}

if(count($complaintHistory)>0)
{
    $avgComplaint =
    round(
    array_sum($complaintHistory)
    /
    count($complaintHistory)
    );

    $predictedComplaints =
    round($avgComplaint*1.10);
}
else
{
    $predictedComplaints = 0;
}


/* ==========================
REVENUE FORECAST
========================== */

$revenueHistory=[];

$rq=$conn->query("
SELECT MONTH(payment_date) month,
SUM(total) total
FROM taxes
WHERE status='Paid'
GROUP BY MONTH(payment_date)
ORDER BY MONTH(payment_date) DESC
LIMIT 6
");

while($row=$rq->fetch_assoc())
{
    $revenueHistory[]=
    $row['total'];
}

if(count($revenueHistory)>0)
{
    $avgRevenue =
    round(
    array_sum($revenueHistory)
    /
    count($revenueHistory)
    );

    $forecastRevenue =
    round($avgRevenue*1.15);
}
else
{
    $forecastRevenue = 0;
}


/* ==========================
RISK SCORE
========================== */

$riskScore=0;

if($totalUsers>0)
{
    $riskScore=
    round(
    (($pendingTax+$overdueTax)
    /
    max($totalTax,1))
    *100
    );
}


/* ==========================
DEVELOPMENT SCORE
========================== */

$developmentScore =
round(
(
$serviceScore+
$taxScore+
(100-$riskScore)
)/3
);


/* ==========================
VILLAGE STATUS
========================== */

if($developmentScore>=80)
{
    $status="Excellent";
    $statusColor="green";
}
elseif($developmentScore>=60)
{
    $status="Growing";
    $statusColor="orange";
}
else
{
    $status="Attention Needed";
    $statusColor="red";
}


/* ==========================
AI RECOMMENDATIONS
========================== */

$recommendations=[];

if($pending>$resolved)
{
$recommendations[]=
"Complaint backlog is increasing. More workers should be assigned.";
}

if($taxScore<70)
{
$recommendations[]=
"Tax collection efficiency is below expected level.";
}

if($riskScore>25)
{
$recommendations[]=
"High risk of tax default detected.";
}

if($serviceScore>80)
{
$recommendations[]=
"Citizen service quality is performing well.";
}

if($developmentScore>85)
{
$recommendations[]=
"Village development trend is highly positive.";
}

if(empty($recommendations))
{
$recommendations[]=
"Village performance remains stable.";
}

?>

<!DOCTYPE html>

<html>

<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<title>AI Intelligence Center</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
}


body{
display:flex;
background:white;
}

.sidebar{

width:250px;
height:100vh;
background:indigo;
color:black;
padding:20px;
position:fixed;
top:0;
left:0;
overflow-y:auto;

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

}


.sidebar ul li:hover{

background:#382865;

}


.sidebar a{

color:white;
text-decoration:none;

}




.main{

margin-left:250px;
width:100%;

}




.header{

height:7%;
background:#E6E6FA;
color:black;
padding:15px 50px;
display:flex;
justify-content:space-between;
align-items:center;

}



.search{

width:250px;
padding:8px;

}


.header{

background:#EFE9FF;

padding:30px;


color:black;

display:flex;

justify-content:space-between;

}





.cards{

display:grid;

grid-template-columns:repeat(4,1fr);

gap:20px;

margin-top:25px;

}



.card{

background:#EFE9FF;

padding:25px;

border-radius:20px;


transition:.3s;

}


.card:hover{

transform:translateY(-8px);

}



.value{

font-size:35px;

font-weight:bold;

color:indigo;

margin-top:10px;

}



.zone h1{
font-size:40px;
margin-bottom:10px;
}

.zone p{
color:#666;
}

.ai-score{

margin-top:25px;

background:white;

padding:30px;

border-radius:20px;

display:flex;

justify-content:space-around;

align-items:center;

}

.progress h1{
font-size:40px;
margin-bottom:10px;
}

.progress p{
color:#666;
}

.progress h3{
color:indigo;
}


.circle{

width:170px;

height:170px;

border-radius:50%;
background:conic-gradient(
#6d28d9 <?php echo $developmentScore;?>%,
#ddd 0%
);

display:flex;

align-items:center;

justify-content:center;

}



.circle div{

width:120px;

height:120px;

background:white;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

font-size:30px;

font-weight:bold;

}





.progress{

background:white;

margin-top:25px;

padding:25px;

border-radius:20px;

}




.bar{

height:14px;

background:#ddd;

border-radius:20px;

margin:10px 0 20px;

}



.fill{

height:100%;

background:#6d28d9;

border-radius:20px;

}




.zone{

display:grid;

grid-template-columns:repeat(4,1fr);

gap:15px;

}



.zone div{

background:#ede9fe;

padding:20px;

border-radius:15px;

text-align:center;

font-size:20px;

}





.badge{

padding:10px 20px;
border-radius:10px;

color:white;

background:<?php echo $statusColor;?>;

}




.insight{

background:#f5f3ff;

padding:18px;

margin-top:15px;

border-left:6px solid #6d28d9;

border-radius:12px;

}


.back-btn{
display:none;
text-decoration:none;
background:indigo;
color:white;
padding:8px 14px;
border-radius:8px;
font-size:14px;
font-weight:bold;
}

.header-left{
display:flex;
align-items:center;
gap:15px;
}

/* ===========================
   MOBILE RESPONSIVE FIX
=========================== */

@media(max-width:900px){

.back-btn{
display:block;
}

.header-left{
width:100%;
display:flex;
align-items:center;
gap:10px;
}

.header-left h1{
font-size:18px;
margin:0;
}

.header-left p{
font-size:13px;
}
body{
    display:block;
    overflow-x:hidden;
}


/* hide desktop sidebar */
.sidebar{
    display:none;
}



/* main full width */

.main{

    margin-left:0;
    width:100%;
    padding:10px;

}



/* HEADER */

.header{

    height:auto;
    padding:15px;
    flex-direction:column;
    gap:15px;
    text-align:center;

}


.header h1{

    font-size:22px;

}


.header p{

    font-size:14px;

}


.search{

    width:100%;
}



/* STATUS BADGE */

.badge{

    display:inline-block;
    margin-top:5px;

}




/* CARDS */

.cards{

    grid-template-columns:1fr;
    gap:15px;
    margin-top:15px;

}


.card{

    padding:20px;
    border-radius:15px;

}


.value{

    font-size:30px;

}




/* SCORE AREA */

.ai-score{

    flex-direction:column;
    gap:30px;
    padding:20px;
    text-align:center;

}



.circle{

    width:140px;
    height:140px;

}


.circle div{

    width:100px;
    height:100px;
    font-size:24px;

}




/* PROGRESS BOX */

.progress{

    padding:18px;
    margin-top:15px;

}



.progress h2{

    font-size:20px;

}




/* ZONE GRID */

.zone{

    grid-template-columns:1fr;
    gap:15px;

}


.zone div{

    padding:18px;

}




/* AI INSIGHTS */

.insight{

    font-size:14px;
    padding:15px;

}



/* search hidden items fix */

.card,
.progress,
.insight{

    transition:0.2s;

}



}



/* SMALL PHONES */

@media(max-width:500px){


.header h1{

font-size:18px;

}



.circle{

width:120px;
height:120px;

}


.circle div{

width:85px;
height:85px;
font-size:20px;

}



.value{

font-size:25px;

}



.zone h1{

font-size:28px;

}



.progress h1{

font-size:30px;

}


}

</style>


</head>



<body style="font-family:'Playfair Display',serif;">



<div class="sidebar">

<ul>

<li>
<a href="ai_dashboard.php">
AI Dashboard
</a>
</li>


<li>
<a href="village_map.php">
Village Map
</a>
</li>


<li>
<a href="complaint_analytics.php">
Complaint Analytics
</a>
</li>


<li>
<a href="tax_analytics.php">
Tax Analytics
</a>
</li>


<li>
<a href="chatbox.php">
AI Chatbot
</a>
</li>




<li>
<a href="predictions.php">
AI Insights
</a>
</li>

<li>
<a href="../index.html">
Logout
</a>
</li>
</ul>

</div>






<div class="main">
<div class="header">

<div class="header-left">

<a href="javascript:history.back()" class="back-btn">
Back
</a>

<div>

<h1>
🧠 Smart Village Predictive Intelligence Center
</h1>

<p>
AI powered analysis from village database
</p>

</div>

</div>



<div>

<input 
class="search"
id="searchInput"
placeholder="Search AI insights...">


<br><br>


<span class="badge">

Village Status :
<?php echo $status;?>

</span>

</div>

</div>
<div class="cards">

<div class="card">
<h3>👥 Citizens</h3>
<div class="value">
<?php echo $totalUsers; ?>
</div>
</div>

<div class="card">
<h3>🏠 Properties</h3>
<div class="value">
<?php echo $totalProperties; ?>
</div>
</div>

<div class="card">
<h3>📈 Predicted Complaints</h3>
<div class="value">
<?php echo $predictedComplaints; ?>
</div>
</div>

<div class="card">
<h3>💰 Revenue Forecast</h3>
<div class="value">
₹<?php echo number_format($forecastRevenue); ?>
</div>
</div>

<div class="card">
<h3>⚠ Risk Score</h3>
<div class="value">
<?php echo $riskScore; ?>%
</div>
</div>

<div class="card">
<h3>📊 Service Score</h3>
<div class="value">
<?php echo $serviceScore; ?>%
</div>
</div>

<div class="card">
<h3>🧾 Tax Score</h3>
<div class="value">
<?php echo $taxScore; ?>%
</div>
</div>

<div class="card">
<h3>🚀 Development Score</h3>
<div class="value">
<?php echo $developmentScore; ?>%
</div>
</div>

</div>
<div class="ai-score">

<div>

<h2>🤖 Village Development Score</h2>

<br>

<div class="circle">

<div>
<?php echo $developmentScore; ?>%
</div>

</div>

<br>

<center>

<h3 style="color:<?php echo $statusColor; ?>">

<?php echo $status; ?>

</h3>

</center>

</div>


<div>

<h2>📊 AI Health Monitor</h2>

<br>

<p>Complaint Resolution Efficiency</p>

<div class="bar">
<div class="fill"
style="width:<?php echo $serviceScore; ?>%">
</div>
</div>

<p>
<?php echo $serviceScore; ?>%
</p>

<br>


<p>Tax Collection Efficiency</p>

<div class="bar">
<div class="fill"
style="width:<?php echo $taxScore; ?>%">
</div>
</div>

<p>
<?php echo $taxScore; ?>%
</p>

<br>


<p>Village Risk Index</p>

<div class="bar">
<div class="fill"
style="width:<?php echo (100-$riskScore); ?>%">
</div>
</div>

<p>
<?php echo $riskScore; ?>% Risk
</p>

<br>


<p>Predicted Growth Potential</p>

<div class="bar">
<div class="fill"
style="width:<?php echo $developmentScore; ?>%">
</div>
</div>

<p>
<?php echo $developmentScore; ?>%
</p>

</div>

</div>

<div class="progress">

<h2>🔮 Future Forecast Analysis</h2>

<br>

<div class="zone">

<div>

<h3>📈 Next Month Complaints</h3>

<br>

<h1 style="color:indigo;">
<?php echo $predictedComplaints; ?>
</h1>

<p>
Estimated from previous complaint trends
</p>

</div>



<div>

<h3>💰 Revenue Forecast</h3>

<br>

<h1 style="color:green;">
₹<?php echo number_format($forecastRevenue); ?>
</h1>

<p>
Expected tax collection next cycle
</p>

</div>



<div>

<h3>⚠ Risk Assessment</h3>

<br>

<h1 style="color:red;">
<?php echo $riskScore; ?>%
</h1>

<p>
Probability of tax default issues
</p>

</div>



<div>

<h3>🚀 Development Potential</h3>

<br>

<h1 style="color:purple;">
<?php echo $developmentScore; ?>%
</h1>

<p>
Expected village growth score
</p>

</div>

</div>

</div>
<div class="progress">

<h2>🛰 AI Zone Intelligence</h2>

<br>

<div class="zone">

<div>

<h3>North Zone</h3>

<br>

<h1 style="color:indigo;">
<?php echo $zones['North']; ?>
</h1>

<p>Total Complaints</p>

</div>



<div>

<h3>South Zone</h3>

<br>

<h1 style="color:indigo;">
<?php echo $zones['South']; ?>
</h1>

<p>Total Complaints</p>

</div>



<div>

<h3>East Zone</h3>

<br>

<h1 style="color:indigo;">
<?php echo $zones['East']; ?>
</h1>

<p>Total Complaints</p>

</div>



<div>

<h3>West Zone</h3>

<br>

<h1 style="color:indigo;">
<?php echo $zones['West']; ?>
</h1>

<p>Total Complaints</p>

</div>

</div>


<br><br>

<div class="insight">

⚠ Highest Risk Zone :
<b><?php echo $worstZone; ?></b>

<br><br>

This zone currently has the highest complaint volume and requires administrative attention.

</div>


<div class="insight">

⭐ Best Performing Zone :
<b><?php echo $bestZone; ?></b>

<br><br>

This zone has the lowest complaint volume and demonstrates better citizen satisfaction.

</div>


<?php

$zoneTotal=max($zones);

if($zoneTotal>10)
{

echo "

<div class='insight'>

🚨 AI Alert:
Complaint density is increasing in ".$worstZone." Zone.
Additional workers should be allocated.

</div>

";

}
?>


</div>
<div class="progress">


<h2>🧠 AI Decision Support Engine</h2>

<br>


<?php


$aiMessages=[];



if($pending > $resolved)
{

$aiMessages[] =
"🚨 Complaint prediction shows increasing workload. 
Recommendation: Increase field worker allocation.";

}



if($predictedComplaints > $totalComplaints)
{

$aiMessages[] =
"📈 AI Forecast:
Complaint volume may increase next month based on previous trends.";

}



if($taxScore < 70)
{

$aiMessages[] =
"💰 Revenue Warning:
Tax collection efficiency is below optimal level.
Start recovery campaigns.";

}



if($riskScore > 30)
{

$aiMessages[] =
"⚠ Village Risk Alert:
Financial risk indicators detected.
Administrative review recommended.";

}



if($developmentScore >= 80)
{

$aiMessages[] =
"⭐ Village Status:
Development indicators are strong.
Current management strategy is effective.";

}



if($worstZone)
{

$aiMessages[] =
"🛰 Zone Analysis:
".$worstZone." zone requires priority monitoring.";

}



foreach($aiMessages as $msg)
{

echo "

<div class='insight'>

".$msg."

</div>";

}



?>


</div>




</div>

<script>


document
.getElementById("searchInput")
.addEventListener("keyup",function(){


let value=this.value.toLowerCase();


let boxes=document.querySelectorAll(
".card,.progress,.insight"
);



boxes.forEach(function(box){


if(box.innerText.toLowerCase()
.includes(value))
{

box.style.display="block";

}

else
{

box.style.display="none";

}


});


});


</script>
</body>

</html>