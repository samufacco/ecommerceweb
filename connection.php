<?php
$connection = new mysqli('localhost', 'samuel', 'alternanza2019', 'ecommerce'); 
if($connection->connect_error) die("$mysqli->connect_errno: $mysqli->connection_error");