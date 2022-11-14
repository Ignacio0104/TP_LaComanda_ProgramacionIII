<?php

require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/Comanda.php';

class Pendiente
{
    public $idPendiente;
    public $legajoEmpleado;
    public $idComanda;
    public $idPlato;
    public $idMesa;
    public $estado;
    public $minutosDemora;
    public $horaFinalizacion;

    public function crearPendiente()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pendientes 
        (legajoEmpleado,idComanda,idPlato,idMesa,estado,minutosDemora) 
        VALUES (:legajoEmpleado, :idComanda,:idPlato,:idMesa,:estado,:minutosDemora)");
        $consulta->bindValue(':legajoEmpleado', $this->legajoEmpleado,PDO::PARAM_STR);
        $consulta->bindValue(':idComanda', $this->idComanda,PDO::PARAM_STR);
        $consulta->bindValue(':idPlato', $this->idPlato, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', "En preparacion");
        $consulta->bindValue(':minutosDemora', $this->minutosDemora, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimolegajo();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pendientes");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pendiente');
    }

    public static function obtenerPendientePorEmpleado($legajoEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pendientes WHERE legajoEmpleado = :legajoEmpleado");
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    
    public static function obtenerPendientePorComanda($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pendientes WHERE idComanda = :idComanda");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    public static function obtenerPendientePorMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pendientes WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    public static function modificarEstadoPendiente($pendiente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pendientes 
        SET estado = :estado, 
        WHERE idPendiente = :idPendiente");
        $consulta->bindValue(':estado', $pendiente->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idPendiente', $pendiente->idPendiente, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function cerrarPendiente($idPendiente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pendientes 
        SET horaFinalizacion = :horaFinalizacion, estado = :estado
         WHERE legajo = :legajo");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':idPendiente', $idPendiente, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "listo para servir");
        $consulta->bindValue(':horaFinalizacion', date_format($hora, 'H:i:sa'));
        $consulta->execute();
    }

    public static function elegirTrabajor($idPlato)
    {
        $legajosEmpleados= Empleado::obtenerEmpleadosPorTipo($idPlato);
        return $legajosEmpleados[rand(0,count($legajosEmpleados))];
    }

}