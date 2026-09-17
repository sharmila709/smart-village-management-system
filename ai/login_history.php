<?php

include("../db.php");

$result=$conn->query(
"SELECT * FROM login_history 
ORDER BY id DESC"
);

?>


<h2>AI Login History</h2>


<table border="1" cellpadding="10">

<tr>
<th>Email</th>
<th>User Type</th>
<th>Login Time</th>
<th>Logout Time</th>
</tr>


<?php while($row=$result->fetch_assoc()){ ?>

<tr>

<td><?=$row['email']?></td>

<td><?=$row['user_type']?></td>

<td><?=$row['login_time']?></td>

<td><?=$row['logout_time']?></td>

</tr>

<?php } ?>

</table>