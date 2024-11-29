<?php
session_start();
include('db_config.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'administrador') {
    header("Location: index.php");
    exit();
}

// Obtener todos los productos vendidos para el gráfico desde reporte_ventas
$sql_todos = "SELECT nombre, COUNT(nombre) as cantidad_vendida 
              FROM reporte_ventas 
              GROUP BY nombre";
$result_todos = $conexion->query($sql_todos);

// Obtener datos para el gráfico
$ventas_grafico = [];
$colores = [];
while ($row = $result_todos->fetch_assoc()) {
    $ventas_grafico['nombres'][] = $row['nombre'];
    $ventas_grafico['totales'][] = $row['cantidad_vendida'];
    // Generar un color aleatorio para cada producto
    $colores[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            width: 80%;
            max-width: 400px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-top: 0;
        }

        .btn-back {
            display: inline-block;
            margin: 10px 5px;
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
    </style>
</head>

<body>

    <div class="container">
        <h2>Gráfico de Ventas</h2>
        
        <!-- Botones de navegación -->
        <a href="administrador.php" class="btn-back">Atrás</a>
        <a href="tabla.php" class="btn-back">Ver tabla de Ventas</a>

        <!-- Gráfico de ventas -->
        <div class="chart-container">
            <canvas id="ventasChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const ventasChart = new Chart(ctx, {
            type: 'pie', // Gráfico de tipo pie
            data: {
                labels: <?php echo json_encode($ventas_grafico['nombres']); ?>,
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: <?php echo json_encode($ventas_grafico['totales']); ?>,
                    backgroundColor: <?php echo json_encode($colores); ?>, // Colores diferentes para cada producto
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Platillos Vendidos'
                    }
                }
            }
        });
    </script>

</body>

</html>
