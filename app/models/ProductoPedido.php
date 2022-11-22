<?php

require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/Comanda.php';

class ProductoPedido
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
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos 
        (legajoEmpleado,idComanda,idPlato,idMesa,estado,minutosDemora) 
        VALUES (:legajoEmpleado, :idComanda,:idPlato,:idMesa,:estado,:minutosDemora)");
        $consulta->bindValue(':legajoEmpleado', $this->legajoEmpleado,PDO::PARAM_INT);
        $consulta->bindValue(':idComanda', $this->idComanda,PDO::PARAM_STR);
        $consulta->bindValue(':idPlato', $this->idPlato, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "En preparacion");
        $consulta->bindValue(':minutosDemora', $this->minutosDemora, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function obtenerPendientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado = 'En preparacion' ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function obtenerPendientePorEmpleado($legajoEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE legajoEmpleado = :legajoEmpleado");
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function ModificarEstadoPedido($legajoEmpleado,$idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET estado = 'Listo para servir', 
        horaFinalizacion = :horaFinalizacion 
        WHERE legajoEmpleado = :legajoEmpleado AND idPendiente = :idPedido AND estado = 'En preparacion'");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':horaFinalizacion', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    
    public static function obtenerPendientePorComanda($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idComanda = :idComanda");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }

    public static function obtenerPendientePorMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }


    public static function cerrarPendiente($idPendiente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
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
        return $legajosEmpleados[rand(0,count($legajosEmpleados)-1)];
    }

}