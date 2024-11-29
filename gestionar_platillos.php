<?php
session_start();
include('db_config.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

// Obtener todos los platillos de la base de datos
$sql = "SELECT * FROM platillos";
$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Platillos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .btn {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin: 20px auto;
        }

        .btn-back:hover {
            background-color: #0853a3;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Gestionar Platillos</h1>
        <a href="administrador.php" class="btn-back">Atrás</a>
        <a href="agregar_platillo.php" class="btn">Agregar Platillo</a>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio (₡)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <!-- precio en colones pero se puede poner el sinbolo que se desee -->
                        <td>₡<?php echo number_format($row['precio'], 2); ?></td>
                        <td>
                            <a href="editar_platillo.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Editar</a>
                            <a href="eliminar_platillo.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</body>

</html>
