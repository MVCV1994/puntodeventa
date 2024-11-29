<?php
session_start();
include('db_config.php');

// Verificar si se envió un nuevo comentario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        form {
            margin-top: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
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
        }

        .comentario h4 {
            color: #007bff;
        }

        .comentario p {
            margin: 10px 0;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }

        .b {
            background-color: #007bff;
            color: white;
            padding: 6px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            text-align: right;
            position: relative;
            top: 0px;
            left: 900px;

        }

        .bb:hover {
            background-color: #0056b3;
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
        <h1>Comentarios</h1>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo $_SESSION['success'];
                                unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar un comentario -->
        <form action="comentarios.php" method="POST">
            <input type="text" name="nombre" placeholder="Tu nombre" required>
            <textarea name="comentario" placeholder="Escribe tu comentario" required></textarea>
            <button type="submit">Agregar comentario</button>
            <h4></h4>
            <a href="index.php" class="b bb">Atrás</a>

        </form>


</html>