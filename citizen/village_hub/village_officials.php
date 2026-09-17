<?php

include("../../db.php");


$result = $conn->query("SELECT * FROM village_officials ORDER BY id DESC");


?>


<!DOCTYPE html>
<html>


<head>


<title>Village Officials</title>

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
padding:25px;
border-radius:20px;
box-shadow:0 5px 15px #ccc;
text-align:center;
transition:.3s;

}



.card:hover{

transform:translateY(-8px);

}




.card img{


width:130px;
height:130px;
border-radius:50%;
object-fit:cover;

}



.icon{

font-size:60px;

}




h2{

color:indigo;

}





.designation{


background:#eee8ff;
padding:10px;
border-radius:10px;

}



.info{


margin-top:15px;

}



.phone{


color:green;
font-size:20px;
font-weight:bold;

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
        padding:20px;
        border-radius:15px;
    }


    .card img{
        width:110px;
        height:110px;
    }


    .icon{
        font-size:45px;
    }


    h2{
        font-size:20px;
        word-break:break-word;
    }


    .designation{
        font-size:14px;
    }


    .info{
        font-size:14px;
    }


    .phone{
        font-size:18px;
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


<h1>👥 Village Officials</h1>

<p>Contact village administration</p>


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

if(!empty($row['photo']))
{


?>


<img src="../../assets/images/<?php echo $row['photo']; ?>">



<?php

}

else

{


?>


<div class="icon">

👤

</div>


<?php

}

?>





<h2>

<?php echo $row['name']; ?>

</h2>




<div class="designation">


<?php echo $row['designation']; ?>


</div>






<div class="info">


<p>


📞

<span class="phone">

<?php echo $row['phone']; ?>

</span>


</p>





<p>

📧

<?php echo $row['email']; ?>

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


<h2>No Officials Added</h2>


<p>
Admin has not added village officials yet.
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