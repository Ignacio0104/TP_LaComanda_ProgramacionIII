<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado
{
    public $tipoPerfiles = ["bartender","cervecero","cocinero","mozo","socio"];
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $perfilEmpleado = $parametros["perfilEmpleado"];
        $clave = $parametros["clave"];
        $legajo = $parametros["legajo"];

        // Creamos el usuario
        try{
            if(in_array($perfilEmpleado,$this->tipoPerfiles))
            {
                $empleado = new Empleado();
                $empleado->legajo=$legajo;
                $empleado->nombre = $nombre;
                $empleado->clave = $clave;
                $empleado->perfilEmpleado= $perfilEmpleado;
                $empleado->crearEmpleado();
                $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        
            }else{
                $payload=json_encode(array("Error!" => "Perfil de empleado ingresado invalido"));
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
*/
    public function BorrarUno($request, $response, $args)
    {

        $datos = json_decode(file_get_contents("php://input"), true);
        $usuarioId = $datos['id'];
        if(Empleado::borrarEmpleado($usuarioId)==1){
          $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "Error, verifique la información"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
