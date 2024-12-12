<?php

namespace tarea25;

require_once 'CocheBBDD.php';
require_once 'Coche.php';

// Parametros de la conexión a la base de datos
$cocheBBDD = new CocheBBDD('localhost', 'root', 'Ciclo2gs2025', 'prueba');

// Llamada a la función para obtener los coches
$coches = $cocheBBDD->obtenerCoches();

// Comprobación si se ha presionado el botón "Eliminar"
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    if ($cocheBBDD->eliminarCoche($id)) {
        echo "Coche eliminado correctamente.";
        header("Location: listado_coches.php"); // Redireccionamos para refrescar la lista
        exit();
    } else {
        echo "Error al eliminar el coche.";
    }
}

// Comprobación si se ha presionado el botón "Guardar actualización"
if (isset($_POST['guardar_actualizacion'])) {
    // Crear el objeto Coche con los datos del formulario
    $coche = new Coche(
        $_POST['id'],
        $_POST['modelo'],
        $_POST['marca'],
        $_POST['matricula'],
        $_POST['precio'],
        $_POST['fecha']
    );

    // Actualizar el coche en la base de datos
    if ($cocheBBDD->actualizarCoche($coche)) {
        echo "Coche actualizado correctamente.";
        header("Location: listado_coches.php"); // Redirigir para mostrar los cambios
        exit();
    } else {
        echo "Error al actualizar el coche.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Coches</title>
    <link rel="stylesheet" href="Css/listado.css">
</head>
<body>
<table border="1">
    <h2>Listado de coches</h2>
    <tr>
        <th>ID</th>
        <th>Modelo</th>
        <th>Marca</th>
        <th>Matrícula</th>
        <th>Precio</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($coches as $coche): ?>
        <tr>
            <td><?php echo $coche->getId(); ?></td>
            <td><?php echo $coche->getModelo(); ?></td>
            <td><?php echo $coche->getMarca(); ?></td>
            <td><?php echo $coche->getMatricula(); ?></td>
            <td><?php echo $coche->getPrecio(); ?></td>
            <td><?php echo $coche->getFecha(); ?></td>
            <td>
                <!-- Para eliminar el coche -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $coche->getId(); ?>">
                    <input type="submit" name="eliminar" value="Eliminar">
                </form>

                <!-- Para actualizar el coche -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $coche->getId(); ?>">
                    <input type="submit" name="actualizar" value="Actualizar">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="index.php">Volver al inicio</a>

<?php
// Mostrar el formulario de actualización si se seleccionó un coche para actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    foreach ($coches as $coche) {
        if ($coche->getId() == $id) {
            break; // Salir del bucle una vez encontrado
        }
    }

    // Formulario para cambiar los datos de un coche
    ?>
    <h3>Actualizar Coche</h3>
    <form method="POST" action="listado_coches.php">
        <input type="hidden" name="id" value="<?php echo $coche->getId(); ?>">
        <label>Modelo:</label><input type="text" name="modelo" value="<?php echo $coche->getModelo(); ?>" required><br>
        <label>Marca:</label><input type="text" name="marca" value="<?php echo $coche->getMarca(); ?>" required><br>
        <label>Matrícula:</label><input type="text" name="matricula" value="<?php echo $coche->getMatricula(); ?>" required><br>
        <label>Precio:</label><input type="text" name="precio" value="<?php echo $coche->getPrecio(); ?>" required><br>
        <label>Fecha:</label><input type="date" name="fecha" value="<?php echo $coche->getFecha(); ?>" required><br>
        <input type="submit" name="guardar_actualizacion" value="Guardar Cambios">
    </form>

    <?php
}
?>

</body>
</html>
