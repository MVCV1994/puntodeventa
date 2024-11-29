<?php
session_start();
include('db_config.php');

// Obtener todos los platillos de la base de datos
$sql = "SELECT * FROM platillos";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
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
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            /* Color azulado para el título */
            margin-bottom: 30px;
        }

        .menu-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .menu-item img {
            max-width: 120px;
            /* Ajusta el tamaño de la imagen */
            border-radius: 5px;
            margin-right: 20px;
        }

        .menu-item h3 {
            margin: 0;
            color: #007bff;
            font-size: 1.5em;
        }

        .menu-item p {
            margin: 5px 0;
            color: #555;
        }

        .price {
            font-weight: bold;
            font-size: 1.2em;
            color: #28a745;
            /* Color verde para el precio */
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            /* Botón azul */
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .menu-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu-item img {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="back-button">Inicio</a>

        <h1>Menú del Restaurante</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="menu-item">
                    <img src="<?php echo $row['imagen']; ?>" alt="Imagen de <?php echo $row['nombre']; ?>">
                    <div>
                        <h3><?php echo $row['nombre']; ?></h3>
                        <p><?php echo $row['descripcion']; ?></p>
                        <p class="price">$<?php echo number_format($row['precio'], 2); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay platillos disponibles en el menú.</p>
        <?php endif; ?>

    </div>
</body>

</html>