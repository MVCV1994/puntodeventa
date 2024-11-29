<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php"); // Redirige a la página de inicio si no tiene permiso
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff; 
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
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
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">'.$_SESSION['error'].'</p>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<p class="success">'.$_SESSION['success'].'</p>';
                unset($_SESSION['success']);
            }
        ?>
        <form action="procesar_registro.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="administrador">Administrador</option>
                <option value="cajero">Cajero</option>
                <option value="mesero">Mesero</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="index.php">Inicia Sesión</a></p>
        <a href="administrador.php">Atrás</a>
    </div>
</body>
</html>
