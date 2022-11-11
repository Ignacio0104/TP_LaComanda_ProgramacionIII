<?php
require_once './models/Producto.php';

class ProductoController extends Producto
{
    public $tipos = ["comida","trago","cerveza"];
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio =$parametros["precio"];
        $tipo = $parametros["tipo"];
        try{
          if(in_array($tipo,$this->tipos))
          {
              $producto = new Producto();
              $producto->nombre=$nombre;
              $producto->precio = $precio;
              $producto->tipo = $tipo;
              $producto->crearProducto();
              $payload = json_encode(array("mensaje" => "Producto creado con exito"));
      
          }else{
              $payload=json_encode(array("Error!" => "Tipo de producto equivocado"));
          }
      }catch(\Throwable $ex)
      {
          $payload=json_encode(array("Error!" => $ex->getMessage()));
      }
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
/*
    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Empleado::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
*/
    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
 /*   
    public function ModificarUno($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        $datos = json_decode(file_get_contents("php://input"), true);
        $usuarioAModificar = new Empleado();
        $usuarioAModificar->id=$datos["id"]; 
        $usuarioAModificar->usuario=$datos["usuario"]; 
        $usuarioAModificar->clave=$datos["clave"]; 
        if(array_key_exists("fechaBaja",$datos))
        {
          $usuarioAModificar->fechaBaja=$datos["fechaBaja"]; 
        }
        if(array_key_exists("perfil_usuario",$datos))
        {
          $usuarioAModificar->perfil_usuario=$datos["perfil_usuario"]; 
        }
        Empleado::modificarUsuario($usuarioAModificar);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();

        $datos = json_decode(file_get_contents("php://input"), true);
        $usuarioId = $datos['id'];
        Empleado::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }*/
}
