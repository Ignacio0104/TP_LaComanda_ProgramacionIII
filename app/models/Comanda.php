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

    public static function obtenerComandasPorIdPendientes($idComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas 
        WHERE idComanda = :idComanda AND estado = 'En preparacion'");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function validarComandaEncuesta($idComanda,$idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas 
        WHERE idComanda = :idComanda AND idMesa = :idMesa");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }


    public static function obtenerComandasTerminadas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM comandas WHERE estado = 'Pedido terminado'");
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function obtenerComandasTiempo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('SELECT 
        DISTINCT comandas.idComanda, comandas.estado, comandas.horaAlta, comandas.idMesa, 
        (SELECT MAX(minutosDemora) FROM pedidos 
        WHERE pedidos.idComanda=comandas.idComanda AND estado = "En preparacion") as "Demora" 
        FROM comandas INNER JOIN pedidos ON comandas.idComanda = pedidos.idComanda 
        WHERE comandas.estado = "En preparacion"');
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
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
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas SET estado = 'Entregado' 
        WHERE idComanda = :idComanda AND estado = 'Pedido terminado' ");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function cambiarEstados($idComanda)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas 
        INNER JOIN mesas ON comandas.idMesa = mesas.idMesa 
        SET comandas.estado = 'Entregado', mesas.estado = 'Cliente comiendo' 
        WHERE comandas.idMesa = (SELECT idMesa FROM comandas where idComanda=:idComanda
        AND estado = 'Pedido terminado')");
        $consulta->bindValue(':idComanda', $idComanda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM mesas WHERE mesas.idMesa = :idMesa AND mesas.estado = 'cliente esperando mozo'");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

  
}