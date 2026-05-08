<?php
include 'db.php';

if (ob_get_length()) ob_clean();

$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;

// Primero obtenemos el total
$total_result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM imagenes");
$total_row = mysqli_fetch_assoc($total_result);
$total = (int)$total_row['total'];

if ($total === 0) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['total' => 0, 'imagen' => null]);
    exit();
}

// Normalizamos el índice (por si viene negativo o mayor al total)
$index = (($index % $total) + $total) % $total;

$query = "SELECT nombre, ruta FROM imagenes ORDER BY id DESC LIMIT 1 OFFSET $index";
$resultado = mysqli_query($conexion, $query);
$imagen = mysqli_fetch_assoc($resultado);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'total' => $total,
    'index' => $index,
    'imagen' => $imagen
]);
exit();
?>