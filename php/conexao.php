<?php
$servername = "localhost";  
$username   = "root";       
$password   = "root";           
$dbname     = "sigest";     

// conexão
$conn = new mysqli("localhost:3306", "root", "root", "sigest");

// Verificando a conexão
if ($conn->connect_error) {
    die(" Falha na conexão: " . $conn->connect_error);
}

?>