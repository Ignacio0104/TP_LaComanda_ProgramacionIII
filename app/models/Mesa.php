<?php

require_once './models/Empleado.php';
class Mesa
{
    public $idMesa;
    public $estado;
    public $legajoMozo;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta ("UPDATE mesas 
        SET legajoMozo = :legajoMozo,
        estado = 'cliente esperando mozo'
        WHERE idMesa = :idMesa AND estado = 'cerrado'");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':legajoMozo', $this->legajoMozo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function verificarMozo($mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta ("SELECT * FROM trabajadores WHERE legajo = :legajoMozo");
        $consulta->bindValue(':legajoMozo', $mesa->legajoMozo, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Empleado');
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerTiempoEspera($idMesa,$idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('SELECT MAX(minutosDemora) 
        FROM pedidos WHERE idMesa=:idMesa AND idComanda = :idComanda AND estado = "En preparacion"');
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }


    public static function obtenerMesaPorIdentificador($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }


    public static function modificarEstadoMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas 
        SET estado = :estado 
        WHERE idMesa = :idMesa");
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $mesa->idMesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarEstadoConIdMesa($idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas 
        SET estado = 
            CASE WHEN (SELECT estado FROM mesas WHERE idMesa = :idMesaDos) = 'cliente comiendo' 
            THEN 'cliente pagando' ELSE (SELECT estado FROM mesas WHERE idMesa = :idMesaTres)
        END
        WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaDos', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaTres', $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function traerCosto($idComanda,$idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT menu.nombre, menu.precio 
        FROM menu INNER JOIN pedidos ON menu.idProducto = pedidos.idPlato 
        WHERE pedidos.idComanda=:idComanda AND pedidos.idMesa=:idMesa");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public static function cargarFactura($idComanda,$idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO facturas(idComanda, idMesa,fecha,monto)
        VALUES (:idComanda, (SELECT idMesa FROM comandas where idComanda=:idComandaDos),:fecha, 
        (SELECT SUM(precio) FROM menu INNER JOIN pedidos ON menu.idProducto = pedidos.idPlato 
        WHERE pedidos.idComanda=:idComandaTres AND pedidos.idMesa = :idMesa))");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':idComandaDos', $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':idComandaTres', $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }


    public static function cerrarMesaSQL($idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas 
        SET estado = CASE WHEN (SELECT estado FROM mesas WHERE idMesa = :idMesaDos) = 'cliente pagando' 
            THEN 'cerrado' ELSE (SELECT estado FROM mesas WHERE idMesa = :idMesaTres)
        END,
        legajoMozo = 
            CASE WHEN (SELECT estado FROM mesas WHERE idMesa = :idMesaCuatro) = 'cliente pagando' 
            THEN 0 ELSE (SELECT legajoMozo FROM mesas WHERE idMesa = :idMesaCinco)
        END
        WHERE idMesa = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaDos', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaTres', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaCuatro', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMesaCinco', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        
        return $consulta->rowCount();
    }

}