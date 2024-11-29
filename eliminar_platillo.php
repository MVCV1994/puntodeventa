<?php
session_start();
include('db_config.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM platillos WHERE id='$id'";
if ($conexion->query($sql) === TRUE) {
    header("Location: gestionar_platillos.php");
    exit();
} else {
    echo "Error al eliminar el platillo: " . $conexion->error;
}
?>