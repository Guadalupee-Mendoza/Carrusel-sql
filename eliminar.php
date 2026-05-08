<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT ruta FROM imagenes WHERE id = $id";
    $res = mysqli_query($conexion, $query);
    $datos = mysqli_fetch_assoc($res);

    if ($datos) {
        if (file_exists($datos['ruta'])) {
            unlink($datos['ruta']);
        }
    }

    mysqli_query($conexion, "DELETE FROM imagenes WHERE id = $id");
}

header("Location: admin.php");
exit();
?>
