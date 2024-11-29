<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE id='$user_id'";
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Usuario eliminado correctamente.');</script>";
    } else {
        echo "<script>alert('Error al eliminar el usuario: " . $conexion->error . "');</script>";
    }
}

// Obtener todos los usuarios
$usuarios_sql = "SELECT id, nombre, rol FROM usuarios";
$usuarios_result = $conexion->query($usuarios_sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
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

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #c82333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Eliminar Perfil de Usuario</h1>
        <form action="eliminar_usuario.php" method="POST">
            <label for="user_id">Selecciona el Usuario a Eliminar:</label>
            <select id="user_id" name="user_id" required>
                <option value="">Selecciona un usuario</option>
                <?php while ($usuario = $usuarios_result->fetch_assoc()): ?>
                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre'] . " - " . $usuario['rol']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Eliminar Usuario</button>
            <a href="administrador.php">Atrás</a>
        </form>
    </div>
</body>

</html>