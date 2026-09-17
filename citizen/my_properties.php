<?php

include("../db.php");

session_start();


if(!isset($_SESSION['user_id'])){
header("Location: loginpage.php");
exit();
}


$user_id=$_SESSION['user_id'];



/*
find citizen table id
*/

$citizen=$conn->query("

SELECT *

FROM citizens

WHERE name = (

SELECT name FROM citizen_login 
WHERE id='$user_id'

)

")->fetch_assoc();



if($citizen){

$cid=$citizen['id'];

}
else{

$cid=0;

}



$result=$conn->query("

SELECT *

FROM properties

WHERE citizen_id='$cid'

ORDER BY id DESC

");


?>


<!DOCTYPE html>

<html>

<head>

<title>My Properties</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>


body{

background:linear-gradient(135deg,indigo);
padding:20px;
font-family:Arial;

}



.container{

background:white;
padding:25px;
border-radius:12px;
max-width:1000px;
margin:auto;

}



table{

width:100%;
border-collapse:collapse;

}



th{

background:indigo;
color:white;
padding:12px;

}


td{

padding:12px;
text-align:center;
border:1px solid #ddd;

}



a{

background:indigo;
color:white;
padding:10px;
text-decoration:none;

}
/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .container{
        width:100%;
        padding:15px;
        border-radius:10px;
        overflow:hidden;
    }


    h2{
        font-size:22px;
        margin:15px 0;
    }


    a{
        display:block;
        width:100%;
        text-align:center;
        border-radius:6px;
        margin-bottom:15px;
    }


    /* MOBILE TABLE SCROLL */
    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
        width:100%;
    }


    th,td{
        font-size:13px;
        padding:9px;
    }


    td{
        text-align:center;
    }

}

</style>


</head>


<body>


<div class="container">


<a href="dashboard.php">
← Back
</a>


<h2 align="center">
🏠 My Properties
</h2>



<table>


<tr>

<th>ID</th>
<th>Property ID</th>
<th>Name</th>
<th>Type</th>
<th>Value</th>
<th>Village</th>
<th>Status</th>
<th>Date</th>


</tr>



<?php


if($result->num_rows>0){


while($row=$result->fetch_assoc()){


?>


<tr>


<td><?= $row['id'] ?></td>


<td>
<b><?= $row['property_code'] ?></b>
</td>


<td><?= $row['name'] ?></td>


<td><?= $row['property_type'] ?></td>


<td>
₹<?= number_format($row['price']) ?>
</td>


<td><?= $row['village'] ?></td>


<td><?= $row['status'] ?></td>


<td><?= $row['date'] ?></td>


</tr>


<?php

}

}

else{


echo "
<tr>
<td colspan='8'>
No Property Assigned
</td>
</tr>";

}

?>


</table>



</div>


</body>

</html>