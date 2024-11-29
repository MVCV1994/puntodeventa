<?php
session_start();
include('db_config.php');

// Definir cuántos productos por página
$productos_por_pagina = 20;

// Calcular la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Obtener los productos para el menú desplegable de filtros
$productos_sql = "SELECT DISTINCT nombre FROM reporte_ventas";
$productos_result = $conexion->query($productos_sql);

// Construir la cláusula WHERE para los filtros
$whereClause = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filtrar'])) {
    $producto = $conexion->real_escape_string($_POST['producto']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    if (!empty($producto)) {
        $whereClause .= " AND nombre = '$producto'";
    }
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $whereClause .= " AND DATE(fecha_venta) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }
}

// Consultar los productos vendidos aplicando filtros y mostrar solo los primeros 20 con OFFSET
$sql_filtrado = "SELECT nombre, (cantidad * precio) AS total, fecha_venta 
                 FROM reporte_ventas 
                 WHERE 1=1 $whereClause 
                 ORDER BY fecha_venta DESC
                 LIMIT $productos_por_pagina OFFSET $offset";  // Paginación para productos visibles
$result_filtrado = $conexion->query($sql_filtrado);

// Calcular el total generado para los productos visibles en la página actual
$total_generado = 0;
while ($row = $result_filtrado->fetch_assoc()) {
    $total_generado += $row['total'];
    $filas[] = $row; // Guardar cada fila para usar en el renderizado de la tabla
}

// Obtener el total de productos vendidos para calcular las páginas
$sql_total = "SELECT COUNT(*) as total FROM reporte_ventas WHERE 1=1 $whereClause";
$result_total = $conexion->query($sql_total);
$total_filas = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_filas / $productos_por_pagina);

// Obtener el total general de dinero generado sin paginación (para mostrar todo)
$sql_total_generado = "SELECT SUM(cantidad * precio) AS total_generado 
                       FROM reporte_ventas 
                       WHERE 1=1 $whereClause";
$result_total_generado = $conexion->query($sql_total_generado);
$row_total_generado = $result_total_generado->fetch_assoc();
$total_generado_completo = $row_total_generado['total_generado'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos Vendidos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form input,
        .filter-form select {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .filter-form button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .page-number {
            display: inline-block;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Reporte de Productos Vendidos</h1>
        <a href="reportes.php" class="btn-back">Atrás</a>

        <!-- Formulario de filtro -->
        <form method="POST" action="" class="filter-form">
            <label for="producto">Filtrar por Producto:</label>
            <select name="producto" id="producto">
                <option value="">Seleccionar Producto</option>
                <?php while ($row = $productos_result->fetch_assoc()) : ?>
                    <option value="<?php echo $row['nombre']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio">
            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin">
            <button type="submit" name="filtrar">Filtrar</button>
        </form>

        <!-- Tabla de productos vendidos -->
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($filas)) : ?>
                    <?php foreach ($filas as $row) : ?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td>₡<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo $row['fecha_venta']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No se encontraron registros para los filtros seleccionados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Mostrar el total generado (sin paginación) -->
        <h2>Total Generado: ₡<?php echo number_format($total_generado_completo, 2); ?></h2>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($pagina_actual > 1) : ?>
                <a href="?pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a>
            <?php endif; ?>
            <span class="page-number">Página <?php echo $pagina_actual; ?> de <?php echo $total_paginas; ?></span>
            <?php if ($pagina_actual < $total_paginas) : ?>
                <a href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
