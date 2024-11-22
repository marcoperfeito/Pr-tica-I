<?php
$servername = "localhost"; 
$username = "root";        
$password = "root";            
$dbname = "pratica1";      


$conn = new mysqli("localhost", "root", "root", "pratica1");


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>


