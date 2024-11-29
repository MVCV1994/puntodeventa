<?php
session_start();
include('db_config.php');

// Verificar si el carrito existe en la sesión y crear uno si no es así
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = 1;  // Esto puede ser dinámico si tienes un sistema de autenticación de usuario
}

// Agregar producto al carrito
if (isset($_POST['agregar_carrito'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $id_usuario = $_SESSION['usuario_id'];  // Obtener ID del usuario actual

    // Verificar si el producto ya está en el carrito del usuario
    $sql_check = "SELECT * FROM carrito WHERE id_producto = ? AND id_usuario = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("ii", $id, $id_usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si ya está en el carrito, actualizar la cantidad
        $sql_update = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_producto = ? AND id_usuario = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ii", $id, $id_usuario);
        $stmt_update->execute();
    } else {
        // Si no está en el carrito, agregarlo
        $sql_insert = "INSERT INTO carrito (id_producto, nombre, precio, cantidad, id_usuario) 
                       VALUES (?, ?, ?, 1, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->bind_param("isdi", $id, $nombre, $precio, $id_usuario);
        $stmt_insert->execute();
    }

    // Establecer mensaje en la sesión para mostrarlo en la vista
    $_SESSION['mensaje_carrito'] = "Producto agregado al carrito";

    // Redirigir al menú
    header("Location: ver_menu.php");
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 32px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .menu-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
        }

        .menu-item img {
            max-width: 100%;
            max-height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .menu-item h3 {
            color: #007bff;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .menu-item .descripcion {
            max-height: 60px;
            overflow-y: auto;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            padding: 5px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .menu-item p {
            color: #555;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .menu-item .price {
            font-weight: bold;
            font-size: 16px;
            color: #4287f5;
        }

        .btn-order {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
        }

        .btn-order:hover {
            background-color: #0056b3;
        }

        .btn-nav {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-right: 10px;
        }

        .btn-nav:hover {
            background-color: #0056b3;
        }

        /* Estilo para el mensaje */
        .mensaje {
            display: none;
            background-color: #28a745;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 80%;
            max-width: 400px;
        }
    </style>
</head>
<body>

    <!-- Mostrar mensaje si está disponible -->
    <?php if (isset($_SESSION['mensaje_carrito'])): ?>
        <div class="mensaje" id="mensaje_carrito">
            <?php echo $_SESSION['mensaje_carrito']; ?>
        </div>
        <?php unset($_SESSION['mensaje_carrito']); ?>
    <?php endif; ?>

    <div class="container">
        <a href="ver_carrito.php" class="btn-nav">Ver Carrito</a>

        <?php
        // Botón que redirige según el rol del usuario
        if (isset($_SESSION['usuario_rol'])) {
            switch ($_SESSION['usuario_rol']) {
                case 'administrador':
                    echo '<a href="administrador.php" class="btn-nav">Ir a Menú de Administrador</a>';
                    break;
                case 'cajero':
                    echo '<a href="cajero.php" class="btn-nav">Ir a Menú de Cajero</a>';
                    break;
                case 'mesero':
                    echo '<a href="mesero.php" class="btn-nav">Ir a Menú de Mesero</a>';
                    break;
            }
        }
        ?>

        <h1>Menú de Platillos</h1>

        <div class="menu-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="menu-item">
                    <img src="<?php echo $row['imagen']; ?>" alt="Imagen de <?php echo $row['nombre']; ?>">
                    <h3><?php echo $row['nombre']; ?></h3>
                    <div class="descripcion">
                        <p><?php echo $row['descripcion']; ?></p>
                    </div>
                    <p class="price">₡<?php echo number_format($row['precio'], 2); ?></p>

                    <form method="POST" action="ver_menu.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
                        <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                        <button type="submit" name="agregar_carrito" class="btn-order">Ordenar Ahora</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // Mostrar el mensaje cuando sea necesario
        if (document.getElementById("mensaje_carrito")) {
            document.getElementById("mensaje_carrito").style.display = "block"; // Mostrar el mensaje
            setTimeout(function() {
                document.getElementById("mensaje_carrito").style.display = "none"; // Ocultar el mensaje después de 1 segundo
            }, 2000);  // 1000ms = 1 segundo
        }
    </script>

</body>
</html>
