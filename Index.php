<?php

namespace tarea25;

require_once 'CocheBBDD.php';
require_once 'Coche.php';

// Parametros de la conexión a la base de datos
$cocheBBDD = new CocheBBDD('localhost', 'root', 'Ciclo2gs2025', 'prueba');

if (isset($_POST['guardar'])) {
    // Validar y obtener los datos del formulario
    $matricula = $_POST['modelo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['matricula'];
    $precio = $_POST['precio'];
    $fecha = $_POST['fecha'];

    // Validación de campos vacíos
    if (empty($matricula) || empty($marca) || empty($modelo) || empty($precio) || empty($fecha)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Crear el objeto Coche
        $coche = new Coche(null, $matricula, $marca, $modelo, $precio, $fecha);

        // Insertar el coche en la base de datos
        if ($cocheBBDD->insertarCoche($coche)) {
            echo "Coche agregado correctamente.";
            header("Location: listado_coches.php"); // Redirigimos al listado de coches una vez añadamos uno.
            exit();
        } else {
            echo "Error al agregar el coche.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Agregar Coche</title>
    <link rel="stylesheet" href="Css/styles.css">
</head>
<body>
<div class="container">
    <h2>Agregar un Nuevo Coche</h2>
    <form method="POST" action="index.php">
        <label>Modelo:</label>
        <input type="text" name="modelo" required>
        <label>Marca:</label>
        <input type="text" name="marca" required>
        <label>Matrícula:</label>
        <input type="text" name="matricula" required>
        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>
        <label>Fecha:</label>
        <input type="date" name="fecha" required>
        <input type="submit" name="guardar" value="Agregar coche">
    </form>
    <a href="listado_coches.php">Ver listado de coches</a>
</div>
</body>
</html>

