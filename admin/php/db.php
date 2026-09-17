<?php

$conn = mysqli_connect(
    "localhost",   
    "root",
    "",
    "smart_village_system"
);

// Check connection
if(!$conn){
    die("❌ Connection failed: " . mysqli_connect_error());
}

?>