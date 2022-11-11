<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa
{
    public $estados = ["cliente esperado pedido","cliente comiendo","cliente pagando","cerrado"];
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idMesa = $parametros['idMesa'];
        $legajo = $parametros["legajo"];
        // Creamos el usuario
        try{      
            $mesa = new Mesa();
            $mesa->idMesa=$idMesa;
            $mesa->legajoMozo=$legajo;
            if($mesa->crearMesa()>0){
                $payload = json_encode(array("Exito" => "Mesa habilitada con exito"));
            }else{
                $payload = json_encode(array("Error!" => "Favor revise la informacion ingresada"));
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
        $lista = Empleado::obtenerTodos();
        $payload = json_encode(array("listaDeEmpleados" => $lista));

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
