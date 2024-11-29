<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $precio = $conexion->real_escape_string($_POST['precio']);
    
    // Procesar la imagen
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagen);
    
    // Subir la imagen al servidor
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
        // Si la imagen se subió correctamente, inserta el platillo con la ruta de la imagen
        $sql = "INSERT INTO platillos (nombre, descripcion, precio, imagen) VALUES ('$nombre', '$descripcion', '$precio', '$target_file')";
        if ($conexion->query($sql) === TRUE) {
            header("Location: gestionar_platillos.php");
            exit();
        } else {
            echo "Error: " . $conexion->error;
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Platillo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff; 
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #007bff; 
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%; 
        }
        button:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #007bff; 
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: block; 
            text-align: left;
            margin: 20px auto; 
            width: fit-content; 
        }

        .btn-back:hover {
            background-color: #0853a3; 
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Agregar Nuevo Platillo</h1>
    <form action="agregar_platillo.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del Platillo</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="descripcion">Descripción del Platillo</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="precio">Precio del Platillo</label>
        <input type="text" id="precio" name="precio" required>

        <label for="imagen">Imagen del Platillo</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>

        <button type="submit">Agregar Platillo</button>
    </form>
    <a href="gestionar_platillos.php" class="btn-back">Atrás</a> 
</div>

</body>
</html>