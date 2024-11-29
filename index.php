<?php
include('db_config.php');

session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Punto de Venta</title>
    <style>
        .footer {
            background-color: #003366;
            /* Color de fondo */
            color: white;
            /* Color del texto */
            text-align: center;
            /* Centrar el texto */
            padding: 20px;
            /* Espaciado interno */
            position: relative;
            /* Posición relativa para el contenedor */
        }

        .footer-content {
            display: flex;
            /* Usar flexbox para alinear elementos */
            flex-direction: column;
            /* Colocar elementos en columna */
            align-items: center;
            /* Centrar elementos */
        }

        .footer-logo {
            width: 100px;
            /* Ancho de la imagen */
            height: auto;
            /* Mantener proporciones */
            margin-bottom: 10px;
            /* Espacio inferior */
            position: relative;

        }

        .footer-text {
            font-size: 16px;
            /* Tamaño del texto */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 35px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 0px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="procesar_login.php" method="POST">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <h3></h3>
        <img src="Mi_pdf.png" alt="Logo del Restaurante" class="logo">
        <p><a href="menu_cliente.php">Escanea el codigo o click para ver el Menu</a></p>
        <p><a href="comentarios.php">Agrega tu comentario</a></p>
    </div>
    <script>
        document.getElementById("current-year").textContent = new Date().getFullYear();
    </script>
</body>
<footer class="footer">
    <div class="footer-content">
        <p class="footer-text">Creado por Marco Cruz Vargas - Restaurante 'puntodeventa' &copy; <span id="current-year"></span></p>
    </div>
</footer>

</html>