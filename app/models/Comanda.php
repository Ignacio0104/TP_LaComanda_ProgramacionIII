<?php

class Comanda
{
    public $idComanda;
    public $URLimagen;
    public $estado;
    public $fechaAlta;
    public $horaAlta;
    public $horaBaja;

    public function crearComanda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idComanda,URLimagen,estado,fechaAlta,horaAlta) 
        VALUES (:idComanda, :URLimagen, :estado, :fechaAlta, :horaAlta)");
        $fecha = new DateTime(date("d-m-Y"));
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':horaAlta', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':idComanda', $this->idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':URLimagen', $this->URLimagen, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimolegajo();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }


    public static function obtenerComandaPorIdentificador($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idComanda = :idComanda");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function modificarEstadoComanda($comanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
        SET estado = :estado, 
        WHERE idComanda = :idComanda");
        $consulta->bindValue(':estado', $comanda->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idComanda', $comanda->idComanda, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function cerrarComanda($idComanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedido SET horaBaja = :horaBaja 
        WHERE idComanda = :idComanda");
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_INT);
        $consulta->bindValue(':horaBaja', date_format($hora, 'H:i:sa'));
        $consulta->execute();
    }

}