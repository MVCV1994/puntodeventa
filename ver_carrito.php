<?php
session_start();
include('db_config.php');

// Asegurarse de que el usuario esté autenticado y tenga permisos para ver el carrito
if (!isset($_SESSION['usuario_rol']) || ($_SESSION['usuario_rol'] != 'cajero' && $_SESSION['usuario_rol'] != 'administrador' && $_SESSION['usuario_rol'] != 'mesero')) {
    echo "Debe iniciar sesión y tener permisos para ver el carrito.";
    exit();
}

// Verificar si se ha enviado una solicitud para agregar o eliminar cantidad
if (isset($_POST['id_producto'], $_POST['accion'])) {
    $id_producto = $_POST['id_producto'];
    $accion = $_POST['accion'];

    // Consultar el carrito para obtener la cantidad actual del producto
    $sql = "SELECT cantidad FROM carrito WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($producto) {
        $cantidad_actual = $producto['cantidad'];

        // Si la acción es agregar, aumentamos la cantidad
        if ($accion == 'agregar') {
            $nueva_cantidad = $cantidad_actual + 1;
        }
        // Si la acción es eliminar, reducimos la cantidad
        elseif ($accion == 'eliminar') {
            if ($cantidad_actual > 1) {
                $nueva_cantidad = $cantidad_actual - 1;
            } else {
                // Si la cantidad es 1, eliminamos el producto
                $sql_delete = "DELETE FROM carrito WHERE id = ?";
                $stmt_delete = $conexion->prepare($sql_delete);
                $stmt_delete->bind_param("i", $id_producto);
                $stmt_delete->execute();
                header("Location: ver_carrito.php");
                exit();
            }
        }

        // Actualizamos la cantidad en el carrito
        if (isset($nueva_cantidad)) {
            $sql_update = "UPDATE carrito SET cantidad = ? WHERE id = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param("ii", $nueva_cantidad, $id_producto);
            $stmt_update->execute();
        }
    }
}

// Consultar todos los productos en el carrito sin filtrar por usuario
$sql = "SELECT id, nombre, precio, cantidad FROM carrito";
$result = $conexion->query($sql);

// Calcular el total
$total = 0;
$productos = array();

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
    $total += $row['precio'] * $row['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        /* Agrega aquí tus estilos CSS */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-right: 20px;
        }

        .btn-order,
        .btn-pay {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 10px auto;
        }

        .btn-order:hover,
        .btn-pay:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Carrito de Compras</h1>

        <?php if (empty($productos)): ?>
            <p>No hay productos en el carrito.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td>₡<?php echo number_format($item['precio'], 2); ?></td>
                            <td>
                                <?php echo $item['cantidad']; ?>
                                <!-- Formulario para actualizar la cantidad -->
                                <form method="POST" action="ver_carrito.php" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="accion" value="agregar">+</button>
                                    <button type="submit" name="accion" value="eliminar">-</button>
                                </form>
                            </td>
                            <td>₡<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <p class="total">Total a Pagar: ₡<?php echo number_format($total, 2); ?></p>

            <!-- Botón de pago visible solo para Cajero y Administrador -->
            <?php if ($_SESSION['usuario_rol'] == 'cajero' || $_SESSION['usuario_rol'] == 'administrador'): ?>
                <form method="POST" action="procesar_pago.php">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <button type="submit" class="btn-pay">Pagar</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <button onclick="window.location.href='ver_menu.php';" class="btn-order">Seguir Comprando</button>
    </div>
</body>

</html>