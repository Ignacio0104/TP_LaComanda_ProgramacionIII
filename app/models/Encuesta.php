<?php

class Encuesta
{
    public $idComanda;
    public $puntuacionMesa;
    public $puntuacionRestaurante;
    public $puntuacionMozo;
    public $punutacionCocinero;
    public $fecha;
    public $comentarios;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (idComanda,puntuacionMesa,
        puntuacionRestaurante,puntuacionMozo,puntuacionCocinero,fecha,comentarios) 
        VALUES (:idComanda,:puntuacionMesa,
        :puntuacionRestaurante,:puntuacionMozo,:puntuacionCocinero,:fecha,:comentarios)");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':idComanda', $this->idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacionMesa', $this->puntuacionMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionRestaurante', $this->puntuacionRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionMozo', $this->puntuacionMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionCocinero', $this->puntuacionCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }


}