<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
    $nombre_mostrar = mysqli_real_escape_string($conexion, $_POST['nombre_personalizado']);
    $nombre_archivo = $_FILES['imagen']['name'];
    $ruta_temporal  = $_FILES['imagen']['tmp_name'];
    $carpeta_destino = "img_carrusel/";

    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }

    $ruta_final = $carpeta_destino . time() . "_" . $nombre_archivo;

    if (move_uploaded_file($ruta_temporal, $ruta_final)) {
        $sql = "INSERT INTO imagenes (nombre, ruta) VALUES ('$nombre_mostrar', '$ruta_final')";
        mysqli_query($conexion, $sql);
    }
}

header("Location: admin.php");
exit();
?>
