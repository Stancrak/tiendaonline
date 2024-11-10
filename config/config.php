<?php


$dsn = 'mysql:host=localhost;dbname=tiendaonline';
$usuario = 'root';
$contraseña = '';

try {
    $conexion = new PDO($dsn, $usuario, $contraseña);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
