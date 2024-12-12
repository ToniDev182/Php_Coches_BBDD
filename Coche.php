<?php

namespace tarea25;

class Coche
{
    // Atributos de la clase
    private $id;
    private $modelo;
    private $marca;
    private $matricula;
    private $precio;
    private $fecha;

    // Constructor
    public function __construct($id, $modelo, $marca, $matricula, $precio, $fecha)
    {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->marca = $marca;
        $this->matricula = $matricula;
        $this->precio = $precio;
        $this->fecha = $fecha;
    }

    // Métodos getter
    public function getId()
    {
        return $this->id;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    // Métodos setter
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    // Método toString()
    public function __toString()
    {
        return "Coche [ID: " . $this->id .
            ", Modelo: " . $this->modelo .
            ", Marca: " . $this->marca .
            ", Matrícula: " . $this->matricula .
            ", Precio: €" . number_format($this->precio, 2) .
            ", Fecha: " . $this->fecha . "]";
    }
}

?>
