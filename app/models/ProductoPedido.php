<?php

require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Producto.php';
require_once './models/Comanda.php';

class ProductoPedido
{
    public $idProductoPedido;
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
        echo $this->legajoEmpleado;
        echo $this->idComanda;
        echo $this->idPlato;
        echo $this->idMesa;
        echo $this->minutosDemora;
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

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pendiente');
    }

    public static function obtenerPendientePorEmpleado($legajoEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE legajoEmpleado = :legajoEmpleado");
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    
    public static function obtenerPendientePorComanda($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idComanda = :idComanda");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    public static function obtenerPendientePorMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pendiente');
    }

    public static function modificarEstadoPendiente($pendiente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
        SET estado = :estado, 
        WHERE idProductoPedido = :idProductoPedido");
        $consulta->bindValue(':estado', $pendiente->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idProductoPedido', $pendiente->idProductoPedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function cerrarPendiente($idProductoPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
        SET horaFinalizacion = :horaFinalizacion, estado = :estado
         WHERE legajo = :legajo");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':idProductoPedido', $idProductoPedido, PDO::PARAM_INT);
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