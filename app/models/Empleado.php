<?php

class Empleado
{
    public $legajo;
    public $nombre;
    public $perfilEmpleado;
    public $clave;
    public $fechaAlta;
    public $horaAlta;
    public $fechaBaja;

    public function crearEmpleado()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO trabajadores (legajo,nombre,perfilEmpleado,clave,fechaAlta,horaAlta) 
        VALUES (:legajo,:nombre, :perfilEmpleado, :clave, :fechaAlta, :horaAlta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $fecha = new DateTime(date("d-m-Y"));
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':horaAlta', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':legajo', $this->legajo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':perfilEmpleado', $this->perfilEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function obtenerEmpleadoPorNombre($nombreEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE nombre = :nombreEmpleado");
        $consulta->bindValue(':nombreEmpleado', $nombreEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public static function obtenerEmpleadoPorLegajo($legajo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores WHERE legajo = :legajo");
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Empleado');
    }

    public static function obtenerEmpleadosPorTipo($idProducto)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores 
        WHERE perfilEmpleado = (SELECT tipo from menu WHERE idProducto = :idProducto)");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
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
        $consulta = $objAccesoDato->prepararConsulta("UPDATE trabajadores SET fechaBaja = :fechaBaja WHERE legajo = :legajo");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':legajo', $legajo, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $consulta->rowCount();
    }

}