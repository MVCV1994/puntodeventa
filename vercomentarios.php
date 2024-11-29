<?php
session_start();
include('db_config.php');

// Verificar si se envió un nuevo comentario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre']) && isset($_POST['comentario'])) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $comentario = $conexion->real_escape_string($_POST['comentario']);

    // Insertar el comentario en la base de datos
    $sql = "INSERT INTO comentarios (nombre, comentario) VALUES ('$nombre', '$comentario')";
    if ($conexion->query($sql) === TRUE) {
        $_SESSION['success'] = "Comentario añadido correctamente.";
    } else {
        $_SESSION['error'] = "Error al añadir el comentario: " . $conexion->error;
    }
}

// Verificar si se envió una solicitud para eliminar un comentario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $id_comentario = $conexion->real_escape_string($_POST['id_comentario']);
    
    // Eliminar el comentario de la base de datos
    $sql_eliminar = "DELETE FROM comentarios WHERE id = '$id_comentario'";
    if ($conexion->query($sql_eliminar) === TRUE) {
        $_SESSION['success'] = "Comentario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el comentario: " . $conexion->error;
    }
}

// Obtener los comentarios de la base de datos
$sql_comentarios = "SELECT * FROM comentarios ORDER BY fecha DESC";
$result_comentarios = $conexion->query($sql_comentarios);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: block;
            width: 100px;
            margin-bottom: 20px;
        }
        .btn-back:hover {
            background-color: #0853a3;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .comentarios {
            margin-top: 30px;
        }
        .comentario {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            position: relative;
        }
        .comentario h4 {
            color: #007bff;
        }
        .comentario p {
            margin: 10px 0;
        }
        .comentario small {
            display: block;
            margin-bottom: 10px;
        }
        .comentario form {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .comentario button {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .comentario button:hover {
            background-color: #c82333;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Botón de atrás -->
        <a href="administrador.php" class="btn-back">Atrás</a>

        <h1>Comentarios</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <!-- Lista de comentarios -->
        <div class="comentarios">
            <?php if ($result_comentarios->num_rows > 0): ?>
                <?php while ($comentario = $result_comentarios->fetch_assoc()): ?>
                    <div class="comentario">
                        <h4><?php echo htmlspecialchars($comentario['nombre']); ?></h4>
                        <p><?php echo htmlspecialchars($comentario['comentario']); ?></p>
                        <small><?php echo date('d/m/Y H:i:s', strtotime($comentario['fecha'])); ?></small>
                        <form method="POST" action="">
                            <input type="hidden" name="id_comentario" value="<?php echo $comentario['id']; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay comentarios aún.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>