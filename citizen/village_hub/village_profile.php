<?php

include("../../db.php");


$result = $conn->query("SELECT * FROM village_profile LIMIT 1");

$data = null;

if($result && $result->num_rows > 0)
{
    $data = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Village Profile</title>

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
display:flex;
justify-content:center;

}



.profile{

width:70%;
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 5px 15px #ccc;

}



.profile img{

width:100%;
height:300px;
object-fit:cover;
border-radius:15px;

}



.row{

display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
margin-top:25px;

}



.box{

background:#f1edff;
padding:20px;
border-radius:15px;

}


h2{

color:indigo;

}



.back{

display:inline-block;
margin-top:20px;
padding:10px 20px;
background:indigo;
color:white;
border-radius:10px;
text-decoration:none;

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
        width:100%;
        margin-top:20px;
    }


    .profile{
        width:100%;
        padding:18px;
        border-radius:15px;
    }


    .profile img{
        height:200px;
    }


    .profile h2{
        font-size:22px;
        margin-top:15px;
        word-break:break-word;
    }


    .row{
        grid-template-columns:1fr;
        gap:15px;
        margin-top:20px;
    }


    .box{
        padding:15px;
        font-size:14px;
    }


    h3{
        font-size:18px;
    }


    .profile p{
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

<h1>🏡 Village Profile</h1>

<p>Complete village information</p>

</div>



<div class="container">


<div class="profile">


<?php if($data){ ?>


<?php

if(!empty($data['office_image']))
{

echo "<img src='../../assets/images/".$data['office_image']."'>";

}

?>



<h2>
<?php echo $data['village_name']; ?>
</h2>



<div class="row">


<div class="box">

<b>Panchayat</b>

<p>
<?php echo $data['panchayat_name']; ?>
</p>

</div>



<div class="box">

<b>District</b>

<p>
<?php echo $data['district']; ?>
</p>

</div>



<div class="box">

<b>State</b>

<p>
<?php echo $data['state']; ?>
</p>

</div>




<div class="box">

<b>Population</b>

<p>
<?php echo $data['population']; ?>
</p>

</div>




<div class="box">

<b>Total Families</b>

<p>
<?php echo $data['total_families']; ?>
</p>

</div>



<div class="box">

<b>Main Occupation</b>

<p>
<?php echo $data['occupation']; ?>
</p>

</div>


</div>



<br>


<h3>Description</h3>

<p>

<?php echo $data['description']; ?>

</p>



<?php
}
else
{
?>

<h2>No Village Profile Added</h2>

<p>
Admin has not uploaded village details yet.
</p>


<?php
}
?>



<a class="back" href="village_hub_dashboard.php">
⬅ Back
</a>



</div>


</div>


</body>

</html>