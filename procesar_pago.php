<?php
session_start();
include('db_config.php');

// Solo el cajero o el administrador pueden acceder a esta página
if (!isset($_SESSION['usuario_rol']) || ($_SESSION['usuario_rol'] != 'cajero' && $_SESSION['usuario_rol'] != 'administrador')) {
    header("Location: index.php"); // Redirige a la página de inicio si no tiene permiso
    exit();
}

// Obtener los productos del carrito desde la base de datos
$sql_carrito = "SELECT * FROM carrito";
$result_carrito = $conexion->query($sql_carrito);

$carrito = [];
$total = 0;

// Calcular el total y guardar los productos en un array para mostrarlos en la vista
while ($item = $result_carrito->fetch_assoc()) {
    $carrito[] = $item;
    $total += $item['precio'] * $item['cantidad'];
}

// Procesar el pago cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el carrito no está vacío
    if (empty($carrito)) {
        $_SESSION['error'] = "El carrito está vacío. No se puede procesar el pago.";
        header("Location: procesar_pago.php");
        exit();
    }

    // Guardar la venta en la base de datos (solo en reporte_ventas)
    foreach ($carrito as $item) {
        // Insertar en la tabla reporte_ventas
        $sql_reporte_ventas = "INSERT INTO reporte_ventas (nombre, cantidad, precio, fecha_venta) VALUES (?, ?, ?, NOW())";
        $stmt_reporte_ventas = $conexion->prepare($sql_reporte_ventas);
        $stmt_reporte_ventas->bind_param("sid", $item['nombre'], $item['cantidad'], $item['precio']);
        $stmt_reporte_ventas->execute();
    }

    // Vaciar el carrito
    $sql_clear_carrito = "DELETE FROM carrito";
    $conexion->query($sql_clear_carrito);

    // Mensaje de éxito
    $_SESSION['success'] = "Pago procesado correctamente. Total: ₡" . number_format($total, 2);
    header("Location: procesar_pago.php"); // Redirigir para mostrar el mensaje de éxito
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff; 
        }

        .total {
            font-size: 20px;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
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
            font-weight: bold;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
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
    <div class="form-container">
        <h2>Procesar Pago</h2>

        <?php
        // Mostrar mensajes de error o éxito
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p class="success">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <?php if ($result_carrito->num_rows > 0): ?>
            <p class="total">Total a pagar: ₡<?php echo number_format($total, 2); ?></p>
            <form action="procesar_pago.php" method="POST">
                <button type="submit">Procesar Pago</button>
            </form>
        <?php else: ?>
            <p class="error">No hay productos en el carrito.</p>
        <?php endif; ?>

        <div class="link">
            <p><a href="ver_menu.php">Ver Menú</a></p>
        </div>
    </div>
</body>

</html>
