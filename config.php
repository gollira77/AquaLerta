<?php
// Datos de conexión a la base de datos
$servername = "localhost"; 
$username = "root";        
$password = "";           
$dbname = "aqualerta_bd";  
// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>