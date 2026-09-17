<?php

include("../db.php");

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];


/* FIND ACTUAL CITIZEN ID FROM LOGIN NAME */

$citizen = $conn->query("

SELECT *

FROM citizens

WHERE name=(

    SELECT name
    FROM citizen_login
    WHERE id='$user_id'

)

")->fetch_assoc();


if($citizen){

    $cid = $citizen['id'];

}else{

    $cid = 0;

}


/* GET TAXES */

$result = $conn->query("

SELECT
t.*,
p.name AS property_name

FROM taxes t

LEFT JOIN properties p
ON t.property_id = p.id

WHERE t.citizen_id = '$cid'

ORDER BY t.id DESC

");

?>
<!DOCTYPE html>

<html>


<head>

<title>My Taxes</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>


*{
box-sizing:border-box;
}


body{

font-family:'Playfair Display',serif;
background:linear-gradient(135deg,indigo);
padding:25px;

}



.container{

background:white;
padding:25px;
border-radius:15px;
max-width:1100px;
margin:auto;

}



h1{

text-align:center;
color:indigo;

}



.back{

display:inline-block;
background:indigo;
color:white;
padding:10px;
text-decoration:none;
border-radius:8px;

}





.table-box{

overflow-x:auto;
margin-top:20px;

}



table{

width:1100px;
border-collapse:collapse;

}



th{

background:indigo;
color:white;
padding:14px;

}



td{

padding:12px;
text-align:center;
border-bottom:1px solid #ddd;
white-space:nowrap;

}



.table-box::-webkit-scrollbar{

height:8px;

}



.table-box::-webkit-scrollbar-thumb{

background:lightgray;
border-radius:20px;

}



.paid{

color:green;
font-weight:bold;

}



.pending{

color:orange;
font-weight:bold;

}



.overdue{

color:red;
font-weight:bold;

}

/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .container{
        width:100%;
        padding:15px;
        border-radius:12px;
    }


    h1{
        font-size:22px;
        margin-top:15px;
    }


    .back{
        display:block;
        width:100%;
        text-align:center;
        padding:10px;
        margin-bottom:15px;
    }


    .table-box{
        width:100%;
        overflow-x:auto;
    }


    table{
        width:900px;
    }


    th,td{
        font-size:13px;
        padding:9px;
    }


    .paid,
    .pending,
    .overdue{
        font-size:13px;
    }

}

</style>


</head>



<body>


<div class="container">



<a class="back"
href="dashboard.php">
← Back
</a>



<h1>
💰 My Tax Details
</h1>



<div class="table-box">


<table>


<tr>

<th>ID</th>

<th>Property</th>

<th>Type</th>

<th>Amount</th>

<th>Fine</th>

<th>Total</th>

<th>Due Date</th>

<th>Status</th>


</tr>



<?php

if($result->num_rows>0){


while($row=$result->fetch_assoc()){


?>


<tr>


<td>
<?= $row['id'] ?>
</td>



<td>
<?= $row['property_name'] ?? "N/A" ?>
</td>



<td>
<?= $row['type'] ?>
</td>



<td>
₹<?= $row['amount'] ?>
</td>



<td>
₹<?= $row['fine'] ?>
</td>



<td>
<b>
₹<?= $row['total'] ?>
</b>
</td>



<td>
<?= $row['due_date'] ?>
</td>




<td>


<?php


if($row['status']=="Paid"){

echo "<span class='paid'>Paid</span>";

}

else{


if(date("Y-m-d")>$row['due_date']){

echo "<span class='overdue'>Overdue</span>";

}

else{

echo "<span class='pending'>Pending</span>";

}


}


?>


</td>



</tr>



<?php

}

}

else{

echo "
<tr>
<td colspan='8'>
No Tax Records Found
</td>
</tr>";

}

?>


</table>


</div>


</div>



</body>

</html>