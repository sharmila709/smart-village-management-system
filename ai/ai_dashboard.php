<?php

include("../db.php");


// CARDS

$complaints = $conn->query(
"SELECT COUNT(*) total FROM complaints"
)->fetch_assoc()['total'] ?? 0;


$users = $conn->query(
"SELECT COUNT(*) total FROM users"
)->fetch_assoc()['total'] ?? 0;


$properties = $conn->query(
"SELECT COUNT(*) total FROM properties"
)->fetch_assoc()['total'] ?? 0;


$tax = $conn->query(
"SELECT IFNULL(SUM(amount),0) total FROM taxes"
)->fetch_assoc()['total'] ?? 0;





// INSIGHTS

$insights=[];

$q=$conn->query(
"SELECT insight FROM insights"
);

while($row=$q->fetch_assoc())
{
    $insights[]=$row['insight'];
}





// PREDICTIONS

$predictions=[];

$q=$conn->query(
"SELECT type,value FROM predictions LIMIT 3"
);


while($row=$q->fetch_assoc())
{
    $predictions[]=$row;
}






// COMPLAINT GRAPH

$complaintName=[];
$complaintCount=[];


$q=$conn->query(
"SELECT category,COUNT(*) total 
FROM complaints 
GROUP BY category"
);


while($row=$q->fetch_assoc())
{
    $complaintName[]=$row['category'];
    $complaintCount[]=$row['total'];
}







// TAX GRAPH


$taxDate=[];
$taxAmount=[];


$q=$conn->query(
"SELECT payment_date,amount FROM taxes"
);


while($row=$q->fetch_assoc())
{
    $taxDate[]=$row['payment_date'];
    $taxAmount[]=$row['amount'];
}




// =============================
// 1. FUTURE COMPLAINT PREDICTION
// =============================

// total complaints
$c1 = $conn->query(
"SELECT COUNT(*) total FROM complaints"
)->fetch_assoc()['total'];


// last month complaints (growth)
$c2 = $conn->query(
"SELECT COUNT(*) total 
FROM complaints
WHERE MONTH(date)=MONTH(CURDATE())"
)->fetch_assoc()['total'];


// prediction formula
if($c1 > 0)
{
    $futureComplaints = round($c1 + ($c2 * 0.20));
}
else
{
    $futureComplaints = 0;
}




// =============================
// 2. REVENUE FORECAST
// =============================


// total collected tax

$totalTax = $conn->query(
"SELECT SUM(amount) total 
FROM taxes"
)->fetch_assoc()['total'];



// total expenses

$totalExpense = $conn->query(
"SELECT SUM(amount) total
FROM expenses"
)->fetch_assoc()['total'];



// remaining growth prediction

$balance = $totalTax - $totalExpense;


$revenueForecast = $balance + ($balance * 0.15);






// =============================
// 3. DEVELOPMENT FOCUS
// =============================


// find highest complaint category

$result = $conn->query(
"SELECT category,COUNT(*) total
FROM complaints
GROUP BY category
ORDER BY total DESC
LIMIT 1"
);



if($result->num_rows > 0)
{

$row=$result->fetch_assoc();

$developmentFocus = 
$row['category']." Improvement";

}
else
{

$developmentFocus="No Data";

}





?>


<!DOCTYPE html>
<html lang="en">

<head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AI Dashboard</title>


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


/* Sidebar */

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




.navbar{

height:15%;
background:indigo;
color:white;
padding:15px 50px;
display:flex;
justify-content:space-between;
align-items:center;

}



.search{
    width:300px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
}



.welcome{

padding:25px;

}


.welcome h2{

color:indigo;

}




.card-container{


display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
padding:25px;


}



.card{


background:#EFE9FF;
padding:20px;
border-radius:10px;
box-shadow:0 2px 5px #aaa;
text-align:center;


}



.card p{


font-size:28px;
font-weight:bold;


}






.insights{


margin:25px;
padding:20px;
background:white;
box-shadow:0 2px 5px #aaa;
border-radius:10px;


}


.insights h2{

color:indigo;

}





.analytics{


display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
padding:25px;


}



.chart-box{


height:250px;
background:white;
padding:20px;
box-shadow:0 2px 5px #aaa;
border-radius:10px;


}





.predictions{


display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
padding:25px;


}



.prediction-card{


background:#EFE9FF;
padding:20px;
border-radius:10px;
text-align:center;


}



.prediction-card p{


font-size:24px;
font-weight:bold;

}

/* ==========================
   MOBILE RESPONSIVE
========================== */
/* ==========================
   MOBILE DRAWER
========================== */
.menu-btn{
    top:10px;
    left:10px;
    width:45px;
    height:45px;
}


@media(max-width:768px){

body{
    display:block;
}

/* Menu button */
.menu-btn{
    display:block;
    position:relative;
    top:44px;
    left:15px;
    z-index:1001;
    background:indigo;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    font-size:22px;
    cursor:pointer;
}

/* Drawer sidebar */
.sidebar{
    position:fixed;
    top:0;
    left:-260px;
    width:250px;
    height:100vh;
    background:indigo;
    transition:0.3s;
    z-index:1000;
    overflow-y:auto;
}

/* Open drawer */
.sidebar.active{
    left:0;
}

/* Restore vertical menu */
.sidebar ul{
    display:block;
}

.sidebar ul li{
    margin:10px 0;
    font-size:16px;
    text-align:center;
}

/* Main content full width */
.main{
    margin-left:0;
    width:100%;
}

/* Navbar */
.navbar{
    padding-top:50px;
    height:auto;
    flex-direction:column;
    gap:15px;
    text-align:center;
}

.search{
    width:100%;
}

/* Cards */
.card-container{
    grid-template-columns:1fr;
    padding:15px;
}

/* Charts */
.analytics{
    grid-template-columns:1fr;
    padding:15px;
}

/* Predictions */
.predictions{
    grid-template-columns:1fr;
    padding:15px;
}

}


</style>


</head>
<body style="font-family:'Playfair Display',serif;">
<button class="menu-btn" onclick="toggleSidebar()">☰</button>

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
<a href="logout.php">
Logout
</a>
</li>
</ul>

</div>





<div class="main">



<div class="navbar">

<h1>
Smart Village AI Dashboard
</h1>


<input type="text" id="searchInput" placeholder="Search..." class="search">

</div>





<div class="welcome">


<h2>
<center>
Welcome to Smart Digital Village AI Center
</center>
</h2>


<br>


<p>

Monitor village growth, public services, and development activities through intelligent analytics.

Access AI-powered insights, predictive reports, and real-time data to support better decision-making.

</p>


</div>







<!-- CARDS -->

<div class="card-container">



<div class="card">

<h3>
Village Growth Index
</h3>

<p>

<?php echo $properties; ?>

</p>

</div>




<div class="card">

<h3>
Development Score
</h3>

<p>

<?php echo $users; ?>

</p>

</div>




<div class="card">

<h3>
Predicted Tax Collection
</h3>

<p>

₹<?php echo $tax; ?>

</p>

</div>





<div class="card">

<h3>
Priority Complaints
</h3>

<p>

<?php echo $complaints; ?>

</p>

</div>



</div>







<!-- INSIGHTS -->


<div class="insights">


<h2>
Latest AI Generated Insights
</h2>

<br>

<ul>


<?php

foreach($insights as $i)
{

echo "

<li>
$i
</li>
<br>

";

}


?>



</ul>


</div>










<!-- CHARTS -->


<div class="analytics">



<div class="chart-box">


<canvas id="complaintChart"></canvas>


</div>





<div class="chart-box">


<canvas id="taxChart"></canvas>


</div>



</div>










<!-- PREDICTIONS -->


<div class="predictions">

<div class="prediction-card">

<h3>Complaints</h3>

<p>
<?php echo $futureComplaints; ?>
</p>

</div>



<div class="prediction-card">

<h3>Revenue Forecast</h3>

<p>
₹<?php echo round($revenueForecast); ?>
</p>

</div>



<div class="prediction-card">

<h3>Development Focus</h3>


<?php echo $developmentFocus; ?>


</div>


</div>




</div>







<script>



new Chart(

document.getElementById("complaintChart"),

{


type:"bar",


data:{


labels:

<?php

echo json_encode($complaintName);

?>

,


datasets:[{


label:"Complaint Count",


data:

<?php

echo json_encode($complaintCount);

?>


}]


}


}

);










new Chart(

document.getElementById("taxChart"),

{


type:"line",


data:{


labels:


<?php

echo json_encode($taxDate);

?>


,


datasets:[{


label:"Tax Collection",


data:


<?php

echo json_encode($taxAmount);

?>


}]


}


}

);



</script>



<script>
document.getElementById("searchInput").addEventListener("keyup", function() {

    let value = this.value.toLowerCase();

    let items = document.querySelectorAll("table tr, .card, .prediction-card, li");

    items.forEach(function(item) {

        let text = item.innerText.toLowerCase();

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
</script>
<script>

function toggleSidebar()
{
    document.querySelector(".sidebar")
    .classList.toggle("active");
}

</script>

</body>

</html>