<?php

include("../db.php");


/* =========================
 COMPLAINT ANALYTICS
========================= */


$totalC =
$conn->query("SELECT COUNT(*) c FROM complaints")
->fetch_assoc()['c'] ?? 0;


$pen =
$conn->query("
SELECT COUNT(*) c FROM complaints 
WHERE status='Pending'
")
->fetch_assoc()['c'] ?? 0;



$prog =
$conn->query("
SELECT COUNT(*) c FROM complaints 
WHERE status='In Progress'
")
->fetch_assoc()['c'] ?? 0;



$res =
$conn->query("
SELECT COUNT(*) c FROM complaints 
WHERE status='Resolved'
")
->fetch_assoc()['c'] ?? 0;




/* Resolution */

$resolutionRate=0;

if($totalC>0)
{
$resolutionRate =
round(($res/$totalC)*100,2);
}



/* Overdue */

$overdue =
$conn->query("
SELECT COUNT(*) c
FROM complaints
WHERE status!='Resolved'
AND date < DATE_SUB(CURDATE(),INTERVAL 7 DAY)
")
->fetch_assoc()['c'] ?? 0;



/* Zones */

$zones=[
"North"=>0,
"South"=>0,
"East"=>0,
"West"=>0
];


$zr=$conn->query("
SELECT zone,COUNT(*) total
FROM complaints
GROUP BY zone
");


while($z=$zr->fetch_assoc())
{
    $zones[$z['zone']]=$z['total'];
}



$north=$zones['North'];
$south=$zones['South'];
$east=$zones['East'];
$west=$zones['West'];



/* Category */

$catLabels=[];
$catValues=[];


$cr=$conn->query("
SELECT category,COUNT(*) total
FROM complaints
GROUP BY category
");


while($c=$cr->fetch_assoc())
{
$catLabels[]=$c['category'];
$catValues[]=$c['total'];
}



/* AI INSIGHTS */


$insights=[];


if($resolutionRate>=80)
{
$insights[]="Excellent complaint resolution performance.";
}
else
{
$insights[]="Resolution rate needs improvement.";
}



if($overdue>0)
{
$insights[]=
$overdue." complaints are older than 7 days and need attention.";
}
else
{
$insights[]="No overdue complaints detected.";
}



if($pen>$res)
{
$insights[]=
"Pending complaints are higher than resolved complaints.";
}
else
{
$insights[]=
"Resolved complaints are under control.";
}



$catLabels=json_encode($catLabels);
$catValues=json_encode($catValues);

?>

<!DOCTYPE html>

<html>

<head>

<title>Complaint Analytics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<style>


*{
margin:0;
padding:0;
box-sizing:border-box;
}


body{

font-family:'Playfair Display',serif;
background:white;

}


/* SIDEBAR */
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
padding:20px;

}



.navbar{

background:#EFE9FF;
padding:25px;

display:flex;
justify-content:space-between;
align-items:center;

border-radius:12px;

}



.search{

padding:10px;
border-radius:8px;
border:1px solid #ccc;

}




.title{

color:indigo;
margin:20px 0;

}



/* CARDS */


.cards{

display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;

}



.card{

background:#EFE9FF;
padding:25px;
border-radius:15px;
text-align:center;
box-shadow:0 3px 10px #ddd;

}


.card p{

font-size:28px;
font-weight:bold;
color:indigo;

}



/* CHARTS */


.charts{

display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
margin-top:25px;

}


.chart-box{

background:white;
height:300px;
padding:20px;
border-radius:15px;
box-shadow:0 3px 10px #ddd;

}



/* STATUS */


.status-section{

margin-top:25px;
padding:20px;
background:white;
border-radius:15px;
box-shadow:0 3px 10px #ddd;

}


.status-grid{

display:grid;
grid-template-columns:repeat(3,1fr);
gap:15px;

}



.status-card{

padding:20px;
color:white;
border-radius:12px;
text-align:center;

}


.pending{
background:orange;
}

.progress{
background:blue;
}

.resolved{
background:green;
}



/* ZONE */


.zone{

display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-top:25px;

}


.zone-card{

background:#EFE9FF;
padding:20px;
border-radius:15px;

}



/* AI */


.ai-box{

margin-top:25px;
background:#EFE9FF;
padding:25px;
border-radius:15px;

}
.active a{
    color:whitesmoke;
    text-decoration:none;
}

.ai-box li{

margin:10px;

}
.back-btn{
display:none;
background-color:indigo;
text-decoration:none;
color:white;
padding:8px 12px;
border-radius:8px;
font-size:14px;
}

.nav-left{
display:flex;
align-items:center;
gap:10px;
}


/* MOBILE FIX */

@media(max-width:800px){

.back-btn{
display:block;
}

.navbar{
flex-direction:column;
align-items:flex-start;
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
.sidebar{
display:none;
}



.main{

margin-left:0;
padding:10px;
width:100%;

}




.navbar h1{

font-size:22px;
text-align:center;

}



.search{

width:100%;

}



.cards{

grid-template-columns:1fr;

}



.card{

padding:18px;

}



.charts{

grid-template-columns:1fr;

}



.chart-box{

height:260px;
padding:10px;

}



.status-grid{

grid-template-columns:1fr;

}



.zone{

grid-template-columns:1fr;

}



.ai-box{

padding:15px;

}



.title{

font-size:22px;

}



}



</style>

</head>
<body>


<!-- SIDEBAR -->

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


<a href="ai_dashboard.php" class="back-btn">
Back
</a>
<br>
<h1 style="color:indigo;">
Complaint Analytics
</h1>

</div>
<br>
<input
id="searchInput"
class="search"
placeholder="Search analytics...">

</div>




<h2 class="title">

Village Complaint Overview

</h2>





<!-- ANALYTICS CARDS -->


<div class="cards">



<div class="card">

<h3>Total Complaints</h3>

<p>
<?php echo $totalC; ?>
</p>

</div>





<div class="card">

<h3>Pending</h3>

<p>
<?php echo $pen; ?>
</p>

</div>





<div class="card">

<h3>In Progress</h3>

<p>
<?php echo $prog; ?>
</p>

</div>





<div class="card">

<h3>Resolved</h3>

<p>
<?php echo $res; ?>
</p>

</div>





<div class="card">

<h3>Resolution Rate</h3>

<p>
<?php echo $resolutionRate; ?>%
</p>

</div>





<div class="card">

<h3>Overdue</h3>

<p>
<?php echo $overdue; ?>
</p>

</div>



</div>

<!-- CHART SECTION -->

<div class="charts">


<div class="chart-box">

<canvas id="statusChart"></canvas>

</div>



<div class="chart-box">

<canvas id="categoryChart"></canvas>

</div>


</div>





<!-- STATUS -->


<div class="status-section">


<h2 style="color:indigo;">
Complaint Status Analysis
</h2>



<div class="status-grid">


<div class="status-card pending">

Pending

<br>

<b>
<?php echo $pen; ?>
</b>

</div>



<div class="status-card progress">

In Progress

<br>

<b>
<?php echo $prog; ?>
</b>

</div>




<div class="status-card resolved">

Resolved

<br>

<b>
<?php echo $res; ?>
</b>

</div>



</div>


</div>






<!-- ZONE ANALYSIS -->


<div class="zone">



<div class="zone-card">

<h3>North Zone</h3>

<p>
<?php echo $north; ?>  
Complaints
</p>

</div>




<div class="zone-card">

<h3>South Zone</h3>

<p>
<?php echo $south; ?>  
Complaints
</p>

</div>





<div class="zone-card">

<h3>East Zone</h3>

<p>
<?php echo $east; ?>   
Complaints
</p>

</div>





<div class="zone-card">

<h3>West Zone</h3>

<p>
<?php echo $west; ?>    
Complaints
</p>

</div>



</div>





<!-- AI INSIGHTS -->


<div class="ai-box">


<h2 style="color:indigo;">
🤖 AI Complaint Insights
</h2>


<ul>


<?php

foreach($insights as $i)
{

echo "<li>".$i."</li>";

}

?>


</ul>


</div>





</div>   <!-- MAIN END -->





<script>


/* STATUS CHART */


new Chart(
document.getElementById("statusChart"),
{


type:"doughnut",


data:{


labels:[

"Pending",
"In Progress",
"Resolved"

],


datasets:[{


label:"Complaints",


data:[

<?php echo $pen; ?>,

<?php echo $prog; ?>,

<?php echo $res; ?>

],


borderWidth:1


}]


},


options:{

responsive:true

}


});






/* CATEGORY CHART */


new Chart(
document.getElementById("categoryChart"),
{


type:"bar",


data:{


labels:
<?php echo $catLabels; ?>,


datasets:[{


label:"Complaint Categories",


data:
<?php echo $catValues; ?>,


borderWidth:1


}]


},


options:{


responsive:true


}


});





/* SEARCH */


document
.getElementById("searchInput")
.addEventListener("keyup",function(){


let value=this.value.toLowerCase();


let items=document.querySelectorAll(
".card,.zone-card,.ai-box,.status-card"
);



items.forEach(function(item){


let text=item.innerText.toLowerCase();


if(text.includes(value))
{

item.style.display="block";

}

else
{

item.style.display="none";

}


});


});



</script>



</body>

</html>
