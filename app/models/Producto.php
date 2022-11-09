<?php

class Producto
{
    public $idProcto;
    public $nombre;
    public $precio;


    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO menu (nombre,precio) 
        VALUES (:nombre, :precio)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimolegajo();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductoPorNombre($nombreProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu WHERE nombre = :nombreProducto");
        $consulta->bindValue(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductoPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu WHERE idProducto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarEmpleado($empleado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleados SET nombre = :nombre, 
        clave = :clave, fechaBaja=:fechaBaja , 
        perfilEmpleado= :perfilEmpleado WHERE legajo = :legajo");
        $claveHash = password_hash($empleado->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $empleado->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':legajo', $empleado->legajo, PDO::PARAM_INT);
        if($empleado->fechaBaja != null)
        {
            $consulta->bindValue(':fechaBaja', $empleado->fechaBaja, PDO::PARAM_INT);
        }else{
            $consulta->bindValue(':fechaBaja', null, PDO::PARAM_INT);
        }
        if($empleado->perfilEmpleado!=null)
        {
            $consulta->bindValue(':perfilEmpleado', $empleado->perfilEmpleado, PDO::PARAM_INT);
        }else{
            $consulta->bindValue(':perfilEmpleado', null, PDO::PARAM_INT);
        }
        $consulta->execute();
    }

    public static function borrarEmpleado($legajo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE legajo = :legajo");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public function __toString()
    {
        return "legajo: $this->legajo | Usuario: $this->usuario" ;
    }
}