<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrador</title>
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

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px 0;
        }

        .card {
            flex: 1 1 30%;
            margin: 10px;
            padding: 20px;
            background-color: #e3f2fd;
            border: 1px solid #007bff;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: block;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout {
            text-align: center;
            margin-top: 20px;
        }

        .logout a {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Bienvenido, Administrador <?php echo $_SESSION['usuario_nombre']; ?></h1>
        <p>Aquí puedes gestionar el sistema.</p>
        <div class="card-container">
            <div class="card">
                <a href="gestionar_platillos.php">Gestionar Platillos</a>
            </div>
            <div class="card">
                <a href="reportes.php">Ver Reporte de Ventas</a>
            </div>
            <div class="card">
                <a href="ver_menu.php">Ver Menú y Solicitar</a>
            </div>
            <div class="card">
                <a href="eliminar_usuario.php">Eliminar Perfil de Usuario</a>
            </div>
            <div class="card">
                <a href="registro.php">Regístrar Nuevo Usuairo</a></p>
            </div>

            <div class="card">
                <a href="vercomentarios.php">Comentarios</a></p>
            </div>
        </div>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>

</html>