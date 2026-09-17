<?php

include("../../db.php");


$result = $conn->query("SELECT * FROM important_places ORDER BY id DESC");


?>

<!DOCTYPE html>
<html>

<head>

<title>Important Places</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>


body{

font-family:'Playfair Display',serif;
background:#f7f7f7;

}


.header{
    background:indigo;
    color:white;
    padding:25px;
    border-radius:10px;
    position:relative;
}

.back-btn{
    position:absolute;
    top:30px;
    right:20px;
    background:white;
    color:indigo;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}

.back-btn:hover{
    background:#f0f0f0;
}


.container{

margin-top:30px;

display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;

}



.card{

background:white;
padding:20px;
border-radius:20px;
box-shadow:0 5px 15px #ccc;
transition:.3s;

}



.card:hover{

transform:translateY(-8px);

}




.card img{

width:100%;
height:220px;
object-fit:cover;
border-radius:15px;

}




.icon{

font-size:50px;
text-align:center;

}




h2{

color:indigo;
text-align:center;

}



.info{

margin-top:15px;

}



.label{

font-weight:bold;
color:#555;

}



.back{

display:inline-block;
margin-top:30px;
padding:10px 20px;
background:indigo;
color:white;
border-radius:10px;
text-decoration:none;

}



.empty{

background:white;
padding:30px;
border-radius:15px;

}

/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .header{
        padding:20px;
        text-align:center;
    }


    .header h1{
        font-size:24px;
        line-height:30px;
    }


    .header p{
        font-size:14px;
    }


    .back-btn{
        position:static;
        display:block;
        width:100%;
        margin-top:15px;
        text-align:center;
    }


    .container{
        grid-template-columns:1fr;
        gap:15px;
        margin-top:20px;
    }


    .card{
        width:100%;
        padding:18px;
        border-radius:15px;
    }


    .card img{
        height:180px;
    }


    .icon{
        font-size:40px;
    }


    h2{
        font-size:20px;
        word-break:break-word;
    }


    .info{
        font-size:14px;
        line-height:22px;
    }


    .back{
        width:100%;
        text-align:center;
        margin-top:20px;
    }

}

</style>


</head>



<body>



<div class="header">


<h1>📍 Important Places</h1>

<p>Village locations and landmarks</p>


</div>






<div class="container">



<?php


if($result && $result->num_rows>0)

{


while($row=$result->fetch_assoc())

{


?>



<div class="card">



<?php

if(!empty($row['image']))
{

?>


<img src="../../assets/images/<?php echo $row['image']; ?>">


<?php

}

else

{


?>


<div class="icon">

📍

</div>


<?php

}

?>





<h2>

<?php echo $row['place_name']; ?>

</h2>





<div class="info">



<p>

<span class="label">
📌 Location
</span>

<br>

<?php echo $row['location']; ?>

</p>





<p>

<span class="label">
Description
</span>

<br>

<?php echo $row['description']; ?>

</p>



</div>



</div>




<?php

}

}

else

{


?>


<div class="empty">


<h2>No Important Places Added</h2>

<p>
Admin has not added village places yet.
</p>


</div>


<?php

}


?>



</div>





<a class="back" href="village_hub_dashboard.php">

⬅ Back to Hub

</a>





</body>


</html>