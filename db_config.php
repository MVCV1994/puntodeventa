<?php
$conexion = new mysqli("localhost", "root", "", "puntodeventa");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
