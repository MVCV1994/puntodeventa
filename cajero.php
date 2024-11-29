<?php
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'cajero') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cajero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff; 
        }
        p {
            text-align: center;
            color: #666;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            text-align: center; 
        }
        a {
            text-decoration: none;
            color: #007bff; 
            font-weight: bold;
            display: inline-block;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s; 
        }
        a:hover {
            background-color: #007bff; 
            color: white; 
        }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: #dc3545; /* Color rojo para el enlace de cerrar sesión */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, Cajero <?php echo $_SESSION['usuario_nombre']; ?></h1>
        <p>Aquí puedes registrar venta y Ver el Menu.</p>
        <ul>
            <li><a href="ver_carrito.php">Registrar Venta</a></li>
            <li><a href="ver_menu.php">Ver Menú</a></li>
        </ul>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>