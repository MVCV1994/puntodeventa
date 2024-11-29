<?php
session_start();
include 'db_config.php'; // Conexión a la base de datos

$usuario_id = $_SESSION['usuario_id'];
$producto_id = $_POST['producto_id'];

// Eliminar el producto del carrito para el usuario actual
$query = $conn->prepare("DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?");
$query->bind_param("ii", $usuario_id, $producto_id);
$query->execute();
$query->close();

header("Location: ver_carrito.php");
exit();
?>
