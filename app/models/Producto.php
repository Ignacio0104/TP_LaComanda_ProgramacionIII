<?php

class Producto
{
    public $idProducto;
    public $nombre;
    public $precio;
    public $tipo;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO menu (idProducto,nombre,precio,tipo) 
        VALUES (:idProducto,:nombre, :precio, :tipo) ON DUPLICATE KEY UPDATE nombre = :nombreDos, precio=:precioDos,tipo=:tipoDos");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(":idProducto", $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':nombreDos', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precioDos', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDos', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductoPorNombre($nombreProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu WHERE nombre = :nombreProducto");
        $consulta->bindValue(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductoPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM menu WHERE idProducto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($producto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE menu SET nombre = :nombre, 
        precio = :precio WHERE idProducto = :id");
        $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':id', $producto->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE * FROM menu WHERE idProducto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CargarCSV($archivo)
    {
        $array = CSV::LeerCsv($archivo);
        for($i = 0; $i < sizeof($array); $i++)
        {
            $campos = explode(",", $array[$i]); 
            $producto = new Producto();
            $producto->idProducto = $campos[0];
            $producto->nombre = $campos[1];
            $producto->precio = $campos[2];
            $producto->tipo = $campos[3];
            $producto->crearProducto();
        }
    }

}