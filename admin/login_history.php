<?php

session_start();

include("../db.php");


$result = $conn->query(
"SELECT * FROM login_history ORDER BY id DESC"
);

?>


<!DOCTYPE html>
<html>

<head>

<title>Login History</title>

<style>

*{
    box-sizing:border-box;
}


body{
    margin:0;
    padding:15px;
    font-family:Arial;
}


table{
    width:90%;
    margin:auto;
    border-collapse:collapse;
}


.aa{
    margin-right:20px;
    text-decoration:none;
    color:indigo;
    font-size:28px;
}


th,td{
    padding:12px;
    border:1px solid #aaa;
    text-align:center;
}


h2{
    text-align:center;
    color:indigo;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:20px;
}



/* Mobile View */

@media(max-width:600px)
{


h2{
    font-size:22px;
    gap:10px;
}


.aa{
    font-size:24px;
    margin-right:0;
}


table{

    width:100%;
    display:block;
    overflow-x:auto;
    white-space:nowrap;

}


th,td{

    padding:8px;
    font-size:13px;

}


body{

    padding:10px;

}


}


</style>
</head>


<body>
<h2>
<a href="dashboard.php" class="aa">←</a>
User Login History
</h2>


<table>

<tr>

<th>Email</th>
<th>User Type</th>
<th>Login Time</th>
<th>Logout Time</th>

</tr>


<?php while($row=$result->fetch_assoc()){ ?>


<tr>

<td><?= $row['email']; ?></td>

<td><?= $row['user_type']; ?></td>

<td><?= $row['login_time']; ?></td>

<td><?= $row['logout_time']; ?></td>


</tr>


<?php } ?>


</table>


</body>

</html>