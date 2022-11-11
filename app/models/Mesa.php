<?php

class Mesa
{
    public $idMesa;
    public $estado;
    public $legajoMozo;

    public function crearMesa()
    {
        echo $this->legajoMozo;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas 
        SET legajoMozo=IF((SELECT perfilEmpleado FROM trabajadores WHERE trabajadores.legajo=:legajoMozo)='mozo', :legajoMozo, NULL), 
        estado=IF(legajoMozo IS NULL,'cerrado','cliente esperado pedido') 
        WHERE mesas.idMesa=:idMesa");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':legajoMozo', $this->legajoMozo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->rowCount();
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

}