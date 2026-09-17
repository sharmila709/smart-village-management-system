<?php

include("../../db.php");


$result = $conn->query("SELECT * FROM emergency_contacts ORDER BY id DESC");


?>

<!DOCTYPE html>
<html>

<head>

<title>Emergency Contacts</title>
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
border-radius:12px;

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
transition:.3s;

}



.card:hover{

transform:translateY(-8px);

}



.icon{

font-size:45px;
text-align:center;

}



h2{

color:indigo;
text-align:center;

}



.info{

margin-top:20px;

}



.label{

font-weight:bold;
color:#555;

}



.phone{

font-size:22px;
color:green;
font-weight:bold;

}




.back{

display:inline-block;
margin-top:30px;
padding:10px 20px;
background:indigo;
color:white;
text-decoration:none;
border-radius:10px;

}



.empty{

background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px #ccc;

}


/* ================= MOBILE VIEW ================= */

@media(max-width:600px){

    body{
        padding:10px;
    }


    .header{
        padding:18px;
        text-align:center;
    }


    .header h1{
        font-size:24px;
    }


    .header p{
        font-size:14px;
    }


    .container{
        grid-template-columns:1fr;
        gap:15px;
        margin-top:20px;
    }


    .card{
        padding:20px;
        border-radius:15px;
    }


    .icon{
        font-size:40px;
    }


    h2{
        font-size:20px;
    }


    .phone{
        font-size:20px;
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

<h1>🚑 Emergency Contacts</h1>

<p>Important village emergency numbers</p>

</div>




<div class="container">



<?php


if($result && $result->num_rows>0)
{


while($row=$result->fetch_assoc())
{


?>



<div class="card">



<div class="icon">

🚨

</div>



<h2>

<?php echo $row['department']; ?>

</h2>




<div class="info">


<p>

<span class="label">
Contact Person:
</span>

<br>

<?php echo $row['contact_person']; ?>

</p>



<p>

<span class="label">
Phone:
</span>

<br>

<span class="phone">

📞 <?php echo $row['phone']; ?>

</span>

</p>



<p>

<span class="label">
Address:
</span>

<br>

<?php echo $row['address']; ?>

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


<h2>No Emergency Contacts Available</h2>

<p>
Admin has not added emergency details yet.
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