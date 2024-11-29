<?php
session_start();
include('db_config.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

// Obtener los datos del platillo a editar
$id = $_GET['id'];
$sql = "SELECT * FROM platillos WHERE id = '$id'";
$result = $conexion->query($sql);
$platillo = $result->fetch_assoc();

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $precio = $conexion->real_escape_string($_POST['precio']);

    $sql = "UPDATE platillos SET nombre='$nombre', descripcion='$descripcion', precio='$precio' WHERE id='$id'";
    if ($conexion->query($sql) === TRUE) {
        header("Location: gestionar_platillos.php");
        exit();
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Platillo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            color: #010445;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input,
        textarea {
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

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Editar Platillo</h1>
        <form action="editar_platillo.php?id=<?php echo $id; ?>" method="POST">
            <label for="nombre">Nombre del Platillo</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $platillo['nombre']; ?>" required>

            <label for="descripcion">Descripción del Platillo</label>
            <textarea id="descripcion" name="descripcion" required><?php echo $platillo['descripcion']; ?></textarea>

            <label for="precio">Precio del Platillo</label>
            <input type="text" id="precio" name="precio" value="<?php echo $platillo['precio']; ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
        <div class="link">
            <a href="gestionar_platillos.php">Cancelar</a>
        </div>
        
        <h3>La descripcion debe ser menor a 50 caracteres</h3>

    </div>
</body>

</html>