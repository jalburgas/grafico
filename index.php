<?php
// 1. Incluir la conexión y módulos de JPGraph
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/jpgraph/src/jpgraph.php';
require_once __DIR__ . '/jpgraph/src/jpgraph_bar.php';
require_once __DIR__ . '/jpgraph/src/jpgraph_pie.php';

// 2. Obtener los datos desde la base de datos (MySQLi POO)
$db = new Conexion();
$mysqli = $db->conexion;

$datos = [];
$leyendas_x = [];

$sql = "SELECT mes, monto FROM ventas ORDER BY id ASC";
$resultado = $mysqli->query($sql);

if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $leyendas_x[] = $row['mes'];
        $datos[] = (float)$row['monto'];
    }
    $resultado->free();
} else {
    die("Error en la consulta: " . $mysqli->error);
}

if (empty($datos)) {
    die("No se encontraron datos en la base de datos.");
}

// Carpeta temporal para guardar las imágenes generadas
$dir_temp = __DIR__ . '/temp/';
if (!file_exists($dir_temp)) {
    mkdir($dir_temp, 0777, true);
}

$img_barra = 'temp/grafico_barra_' . time() . '.png';
$img_torta = 'temp/grafico_torta_' . time() . '.png';

// ==========================================
// 3. GENERAR GRÁFICO DE BARRAS
// ==========================================
$graphBar = new Graph(800, 450);
$graphBar->SetScale("textlin");
$graphBar->SetMargin(60, 40, 50, 60);

$graphBar->title->Set("Ventas Mensuales (Barras)");
$graphBar->xaxis->title->Set("Meses");
$graphBar->yaxis->title->Set("Monto ($)");
$graphBar->xaxis->SetTickLabels($leyendas_x);

$barplot = new BarPlot($datos);
$barplot->value->Show();
$barplot->SetFillColor(array('orange', 'green', 'blue', 'red', 'purple', 'yellow'));
$barplot->SetColor('darkgray');

$graphBar->Add($barplot);
$graphBar->Stroke($dir_temp . basename($img_barra)); // Guarda la imagen en lugar de enviarla directo

// ==========================================
// 4. GENERAR GRÁFICO DE TORTA
// ==========================================
$graphPie = new PieGraph(600, 400);
$graphPie->title->Set("Distribución de Ventas (Torta)");
$graphPie->SetShadow();

$pieplot = new PiePlot($datos);
$pieplot->SetLegends($leyendas_x);
$pieplot->SetLabelType(PIE_VALUE_PER);
$graphPie->legend->Pos(0.05, 0.5, "right", "center");

$graphPie->Add($pieplot);
$graphPie->Stroke($dir_temp . basename($img_torta)); // Guarda la imagen
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
            margin: 20px;
        }

        .contenedor {
            background: white;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <h1>Reporte Gráfico de Ventas</h1>

    <div class="contenedor">
        <h3>Gráfico de Barras</h3>
        <img src="<?php echo $img_barra; ?>" alt="Gráfico de Barras">
    </div>

    <br>

    <div class="contenedor">
        <h3>Gráfico de Torta</h3>
        <img src="<?php echo $img_torta; ?>" alt="Gráfico de Torta">
    </div>
</body>

</html>