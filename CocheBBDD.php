<?php

namespace tarea25;

use PDO;
use PDOException;

require_once('Coche.php');

class CocheBBDD
{

    private $conexion;  // Definimos la conexion

    // Cambiamos la forma en la que se establece la conexion de base de datos
    public function __construct($host, $user, $pass, $bd)
    {
        try {  // el manejor de errores en PDO se hace con excepciones.
            $this->conexion = new PDO("mysql:host=$host;dbname=$bd", $user, $pass); // creamos una nueva conexion utilizando PD0
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // advertencias y warnings en caso de error
        } catch (PDOException $e) {  // si se produce una excepcion se muestra un mensaje de error.
            echo "Error en la conexion: " . $e->getMessage();
            $this->conexion = null; // evita intentar usar una conexion invalida
        }
    }


    // funcion para insertar un coche
    public function insertarCoche(Coche $coche)
    {
        if (!$this->conexion) { // si no hay conexion a la BBDD mandamos un mensaje.
            echo "No puedes agregar un vehiculo, primero necesitas conectarte a la base de datos";
            return false;
        }
        /*La funcion prepare es un metodo de la clase mysqli que se utiliza para preparar una sentencia SQL antes de ejercutarla.*/
        $consulta = $this->conexion->prepare(     // query se utiliza con parametros dinamicos
            "INSERT INTO coches (modelo, marca, matricula, precio, fecha) VALUES (:modelo, :marca, :matricula, :precio, :fecha)"
        // En PDO cuando se usa una consulta preparada puedes utilizar los nombres  de parametros en lugar de "?" para mayor claridad
        );

        //En PDO Usamos bindValue para pasar los valores directamente (sin necesidad de referencias)
        // bindValue asigna un valor de forma inmediata y no lo asocia a una variable por referencia.
        // Esto es más eficiente cuando no se necesita modificar el valor después de la vinculación.
        $consulta->bindValue(':modelo', $coche->getModelo());
        $consulta->bindValue(':marca', $coche->getMarca());
        $consulta->bindValue(':matricula', $coche->getMatricula());
        $consulta->bindValue(':precio', $coche->getPrecio());
        $consulta->bindValue(':fecha', $coche->getFecha());

        return $consulta->execute();
    }

    // Fucion para obtenerCoches
    public function obtenerCoches()
    {
        if (!$this->conexion) {
            echo "No se pueden obtener la coche en la base de datos, primero necesitas conectarte.";
            return []; // si no se puede obtener la lista se retorna un array vacio.
        }

        $resultados = []; // Creomos un array vacio para almacenar los coches
        $consulta = $this->conexion->query("SELECT * FROM coches"); // para consultas que incluyan parametros usamos query
        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) { //Se utiliza para recuperar una fila de resultados de una consulta SQL como un array asociativo.
            $resultados[] = new Coche(                              // se ejecuta mientra existan filas que devolver.
                $fila['id'],
                $fila['modelo'],
                $fila['marca'],
                $fila['matricula'],
                $fila['precio'],
                $fila['fecha']
            );
        }
        return $resultados;
    }

    public function eliminarCoche($id)
    {
        if (!$this->conexion) {
            echo "No se pueden eliminar la coche en la base de datos";
            return false;
        }
        // query se utiliza con parametros dinamicos
        $consulta = $this->conexion->prepare("DELETE FROM coches WHERE id= :id"); // usamos el nombre del parametro en lugar de "?"
        $consulta->bindparam(':id', $id, PDO::PARAM_INT); // bindparam vinvula la consulta SQUL con la variable; PHP PDO::PARAM_INT indica que el valor esperado es un entero
        return $consulta->execute(); // Ejecuta la consulta
    }

    public function actualizarCoche(Coche $coche)
    {
        if (!$this->conexion) {
            echo "No se pueden actualizar el coche en la base de datos, primero necesitas conectarte.";
            return false;
        }

        // Preparamos la consulta SQL para actualizar los datos de un coche
        $consulta = $this->conexion->prepare(
            "UPDATE coches SET matricula = :matricula, marca = :marca, modelo = :modelo, 
            precio = :precio, fecha = :fecha WHERE id = :id"
        );
        // hay que crear variables intermedias, solo las variables pueden ser pasadas como referencia
        $matricula = $coche->getMatricula();
        $marca = $coche->getMarca();
        $modelo = $coche->getModelo();
        $precio = $coche->getPrecio();
        $fecha = $coche->getFecha();
        $id = $coche->getId();

        // Vinculamos los parámetros con los valores
        $consulta->bindParam(':matricula', $matricula);
        $consulta->bindParam(':marca', $marca);
        $consulta->bindParam(':modelo', $modelo);
        $consulta->bindParam(':precio', $precio);
        $consulta->bindParam(':fecha', $fecha);
        $consulta->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutamos la consulta y retornamos el resultado (true si se actualizó correctamente)
        return $consulta->execute();
    }


}