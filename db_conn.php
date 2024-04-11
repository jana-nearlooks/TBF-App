<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "users data";

$conn = mysqli_connect($servername, $Username, $Password, $dbname);


if(!$conn)

{
    die("Connection failed: ".mysqli_connect_error());
}

?>