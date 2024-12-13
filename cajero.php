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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            box-sizing: border-box;
            /* Incluye padding en el cálculo del ancho */
        }

        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: calc(100% - 40px);
            /* Ajusta el ancho para respetar los márgenes */
            margin: 10px auto;
            /* Centra el botón horizontalmente */
            text-align: center;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
        }

        a:hover {
            background-color: #0056b3;
        }

        .logout a {
            background-color: transparent;
            color: #cc1421;
            padding: 12px 20px;
            margin-top: 20px;
            border: 2px solid #cc1421;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
            display: inline-block;
            transition: all 0.3s ease;
            width: calc(100% - 40px);
            margin: 20px auto;
            /* Asegura la separación vertical */
        }

        .logout a:hover {
            background-color: #910a14;
            color: white;
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