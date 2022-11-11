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
        estado = 'cliente esperado pedido'
        WHERE idMesa = :idMesa");
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
        SET estado = :estado, 
        WHERE idMesa = :idMesa");
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $mesa->idMesa, PDO::PARAM_INT);
        $consulta->execute();
    }



        /*VERIFICAR POR QUE NO ANDA ESTO
        $consulta = $objAccesoDatos->prepararConsulta ("UPDATE mesas 
        SET legajoMozo = IF((SELECT perfilEmpleado FROM trabajadores WHERE legajo = :legajoMozo) ='mozo', :legajoMozo, 0), 
        estado = IF(legajoMozo = 0,'cerrado','cliente esperado pedido') 
        WHERE idMesa = :idMesa");*/
}