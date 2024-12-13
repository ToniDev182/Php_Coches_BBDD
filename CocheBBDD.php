<?php

namespace tarea25;

use mysqli;

require_once('Coche.php');

class CocheBBDD
{

    private $conexion;  // Definimos la conexion

    public function __construct($host, $user, $pass, $bd)
    {
        $this->conexion = new mysqli($host, $user, $pass, $bd);
        if ($this->conexion->connect_error) {
            echo "Error en la conexion";
            $this->conexion = null;
        }
    }


    // funcion para insertar un coche
    public function insertarCoche(Coche $coche)
    {
        if (!$this->conexion) {
            echo "No puedes agregar un vehiculo, primero necesitas conectarte a la base de datos";
            return false;
        }
        /*La funcion prepare es un metodo de la clase mysqli que se utiliza para preparar una sentencia SQL antes de ejercutarla.*/
        $consulta = $this->conexion->prepare(     // query se utiliza con parametros dinamicos "?"
            "INSERT INTO coches (modelo, marca, matricula, precio, fecha) VALUES (?, ?, ?, ?, ?)"
        );
    // guardo el valor en variables ya que solo las variables pueden pasarse como referenia
        $modelo = $coche->getModelo();
        $marca = $coche->getMarca();
        $matricula = $coche->getMatricula();
        $precio = $coche->getPrecio();
        $fecha = $coche->getFecha();

        /* Al usar bind_param, se garantiza que los valores se interpretan correctamente según su tipo, lo que evita problemas con los tipos de datos.*/
        $consulta->bind_param(
            'sssds', // Indicamos el tipo de dato.
            $matricula,
            $marca,
            $modelo,
            $precio,
            $fecha
        );
        return $consulta->execute(); // ejecuta consulta SQL
    }

    // Fucion para obtenerCoches
    public function obtenerCoches()
    {
        if (!$this->conexion) {
            echo "No se pueden obtener la coche en la base de datos, primero necesitas conectarte.";
            return [];
        }

        $resultados = [];
        $consulta = $this->conexion->query("SELECT * FROM coches"); // para consultas que incluyan parametros
        while ($fila = $consulta->fetch_assoc()) { //Se utiliza para recuperar una fila de resultados de una consulta SQL como un array asociativo.
            $resultados[] = new Coche(
                $fila['id'],
                $fila['modelo'],    // El orden es importante
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
        // query se utiliza con parametros dinamicos "?"
        $consulta = $this->conexion->prepare("DELETE FROM coches WHERE id=?");
        $consulta->bind_param('i', $id);
        return $consulta->execute(); // Ejecuta la consulta
    }

    public function actualizarCoche(Coche $coche)
    {
        if (!$this->conexion) {
            echo "No se pueden actualizar la coche en la base de datos, primero necesitas conectarte.";
            return false;
        }

        $matricula = $coche->getMatricula();
        $marca = $coche->getMarca();
        $modelo = $coche->getModelo();
        $precio = $coche->getPrecio();
        $fecha = $coche->getFecha();
        $id = $coche->getId();

        $consulta = $this->conexion->prepare(
            "UPDATE coches SET matricula = ?, marca = ?, modelo = ?, precio = ?, fecha = ? WHERE id = ?"
        );
        $consulta->bind_param(
            'sssdsd',
            $matricula,
            $marca,
            $modelo,
            $precio,
            $fecha,
            $id
        );
        return $consulta->execute();
    }

    public function cerrarConexion()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
