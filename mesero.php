<?php
session_start();

// Asegurarse de que el usuario esté autenticado como mesero
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'mesero') {
    echo "Debe iniciar sesión como mesero para acceder a esta página.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Mesero</title>
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
        }

        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }

        .btn-menu {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            text-align: center;
            font-size: 18px;
        }

        .btn-menu:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: transparent;
            /* Sin fondo */
            color: #cc1421;
            /* Color del texto */
            padding: 12px 20px;
            margin-top: 20px;
            border: 2px solid #cc1421;
            /* Borde del botón */
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            /* Efecto de transición */
        }

        .btn-back:hover {
            background-color: #910a14;
            /* Fondo azul al pasar el cursor */
            color: white;
            /* Color blanco del texto */
            font-weight: bold;
            /* Poner el texto en negrita */
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Menú de Mesero</h1>

        <form action="ver_menu.php" method="get">
            <button type="submit" class="btn-menu">Ver Menú</button>
        </form>

        <form action="ver_carrito.php" method="get">
            <button type="submit" class="btn-menu">Ver Carrito</button>
        </form>


        <form action="logout.php" method="get">
            <button type="submit" class="btn-back">Cerrar Sesión</button>
        </form>
    </div>

</body>

</html>