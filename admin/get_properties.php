<?php

include("php/db.php");


$citizen=$_GET['citizen'];

$type=$_GET['type'];


$result=mysqli_query($conn,"
SELECT *
FROM properties
WHERE citizen_id='$citizen'
AND property_type='$type'
");


echo "<option value=''>Select Property</option>";


while($row=mysqli_fetch_assoc($result)){


echo "

<option value='".$row['id']."'>

".$row['name'].
" - ₹".$row['price']."

</option>";

}

?>