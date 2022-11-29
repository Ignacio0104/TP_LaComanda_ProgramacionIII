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
        $consulta->bindValue(':estado', $this->estado);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos 
        WHERE legajoEmpleado = :legajoEmpleado AND estado = 'En preparacion'");
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function obtenerPendientePorSector($pefilEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos 
        INNER JOIN menu ON pedidos.idPlato = menu.idProducto WHERE menu.tipo = :perfilEmpleado 
        AND pedidos.estado = 'Pendiente'");
        $consulta->bindValue(':perfilEmpleado', $pefilEmpleado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function AsignarPedido($legajoEmpleado, $idPedido,$minutosDemora)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos 
        SET estado = 'En preparacion',
        legajoEmpleado = :legajoEmpleado,
        minutosDemora = :minutosDemora
        WHERE idPendiente = :idPedido");
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':minutosDemora', $minutosDemora, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function ValidarPedidoPendiente($idPedido, $perfilEmpleado,$idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos 
        WHERE idPendiente = :idPedido AND estado = 'Pendiente' AND legajoEmpleado = 0 
        AND (SELECT tipo FROM menu INNER JOIN pedidos ON pedidos.idPlato = menu.idProducto 
        WHERE menu.idProducto = :idProducto LIMIT 1)=:perfilEmpleado");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':perfilEmpleado', $perfilEmpleado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
    public static function ModificarEstadoPedido($legajoEmpleado,$idPedido,$idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET estado = 'Listo para servir', 
        horaFinalizacion = :horaFinalizacion 
        WHERE legajoEmpleado = :legajoEmpleado AND idPendiente = :idPedido 
        AND estado = 'En preparacion'
        AND idComanda=:idComanda");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':horaFinalizacion', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':legajoEmpleado', $legajoEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
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


    /*public static function cerrarPendiente($idComanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas 
        SET estado = CASE WHEN (SELECT COUNT(*) FROM PEDIDOS 
        WHERE idComanda = :idComandaTres AND estado = 'En preparacion')=0 
        THEN 'Pedido terminado' ELSE 'En preparacion' END, 
        horaBaja = CASE WHEN (SELECT COUNT(*) FROM PEDIDOS 
        WHERE idComanda = :idComandaDos AND estado = 'En preparacion')=0 
        THEN :horaBaja ELSE '0' END 
        WHERE idComanda = :idComanda");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':horaBaja', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(":idComanda", $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(":idComandaDos", $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(":idComandaTres", $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(":idComandaCuatro", $idComanda, PDO::PARAM_STR);
        $consulta->execute();
    }*/

     public static function cerrarPendiente($idComanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas 
        SET estado = 'Pedido terminado', 
        horaBaja = :horaBaja 
        WHERE idComanda = :idComanda");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':horaBaja', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(":idComanda", $idComanda, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function verificarPedido($idComanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT COUNT(*) FROM PEDIDOS 
        WHERE idComanda = :idComanda AND estado = 'En preparacion'");
        $consulta->bindValue(":idComanda", $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return$consulta->fetchColumn();
    }

    public static function elegirTrabajor($idPlato)
    {
        $legajosEmpleados= Empleado::obtenerEmpleadosPorTipo($idPlato);
        return $legajosEmpleados[rand(0,count($legajosEmpleados)-1)];
    }

}