<?php

class Comanda
{
    public $idComanda;
    public $idMesa;
    public $URLimagen;
    public $estado;
    public $fechaAlta;
    public $horaAlta;
    public $horaBaja;

    public function crearComanda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comandas (idComanda,URLimagen,estado,fechaAlta,horaAlta,idMesa) 
        VALUES (:idComanda, :URLimagen, :estado, :fechaAlta, :horaAlta, :idMesa)");
        $fecha = new DateTime(date("d-m-Y"));
        $hora = new DateTime(date("h:i:sa"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':horaAlta', date_format($hora, 'H:i:sa'));
        $consulta->bindValue(':idComanda', $this->idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':URLimagen', $this->URLimagen, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', "En preparacion", PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }


    public static function obtenerComandaPorIdentificador($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas WHERE idComanda = :idComanda");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function modificarEstadoComanda($comanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas 
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

    
    function crearCodigoComanda() { 

        $letras = "abcdefghijkmnopqrstuvwxyz023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $codigo = '' ; 
    
        while ($i < 5) { 
            $num = rand() % 33; 
            $tmp = substr($letras, $num, 1); 
            $codigo = $codigo . $tmp; 
            $i++; 
        } 
        return $codigo;  
    } 

    public static function verificarMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(1) FROM mesas WHERE mesas.idMesa = :idMesa AND mesas.estado = 'cerrado'");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

}