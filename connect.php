<?php
    $servername = "localhost"; //server
    $username = "root"; //username
    $password = ""; //password
    $dbname = "lgu_dms";  //database

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, 3306);    // connecting 
    
    // Check connection
    if ($conn->connect_error) {      	
        die("Connection Failed: ".$conn->connect_error);
    }
    
?>