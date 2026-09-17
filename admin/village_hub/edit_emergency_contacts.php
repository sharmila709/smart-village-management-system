<?php

include("../../db.php");


// GET ID

$id=$_GET['id'];



$result=$conn->query(
"SELECT * FROM emergency_contacts WHERE id=$id"
);


$row=$result->fetch_assoc();





// UPDATE

if(isset($_POST['update']))
{


$department=$_POST['department'];
$person=$_POST['person'];
$phone=$_POST['phone'];
$address=$_POST['address'];



$conn->query("

UPDATE emergency_contacts SET

department='$department',
contact_person='$person',
phone='$phone',
address='$address'


WHERE id=$id

");



header("location:manage_emergency_contacts.php");


}


?>



<!DOCTYPE html>
<html>


<head>


<title>Edit Emergency Contact</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

body{
    font-family:'Playfair Display',serif;
    background:#f7f7f7;
    margin:0;
    padding:0;
}


.box{

    background:white;

    padding:25px;

    width:70%;

    margin:40px auto;

    border-radius:20px;

    box-shadow:0 5px 15px #ccc;

    box-sizing:border-box;

}



input,textarea{

    width:100%;

    padding:10px;

    margin:10px 0;

    box-sizing:border-box;

    font-size:16px;

}



textarea{

    min-height:100px;

}



button{

    background:indigo;

    color:white;

    padding:12px 25px;

    border:0;

    border-radius:10px;

    cursor:pointer;

    font-size:16px;

}



@media(max-width:768px){


    .box{

        width:95%;

        margin:20px auto;

        padding:20px;

        border-radius:15px;

    }


    h1{

        font-size:24px;

        text-align:center;

    }


    button{

        width:100%;

    }


}



</style>

</head>



<body>




<div class="box">



<h1>✏ Edit Emergency Contact</h1>




<form method="post">



<input

name="department"

value="<?php echo $row['department']; ?>"

placeholder="Department">






<input

name="person"

value="<?php echo $row['contact_person']; ?>"

placeholder="Contact Person">





<input

name="phone"

value="<?php echo $row['phone']; ?>"

placeholder="Phone">





<textarea

name="address"

placeholder="Address">

<?php echo $row['address']; ?>

</textarea>





<button name="update">

Update

</button>



</form>



</div>




</body>


</html>