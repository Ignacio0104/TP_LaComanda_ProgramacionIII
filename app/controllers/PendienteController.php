<?php
require_once './models/Pendiente.php';

class PendienteCotroller 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $pendiente = new Pendiente();
        $pendiente->idComanda = $parametros["idComanda"];
        $pendiente->idPlato = $parametros["idPlato"];
        $pendiente->idMesa = $parametros["idMesa"];
        $pendiente->estado = "En preparacion";
        $pendiente->minutosDemora= $parametros["minutos"];
        $pendiente->legajoEmpleado = Pendiente::elegirTrabajor($parametros["idPlato"]);
        if($comanda->verificarMesa($parametros["mesa"])>0)
        {
          try{     
            if(isset($parametros['URLImagen'])){
              $comanda->URLimagen = $parametros['URLImagen'];
            };
            $comanda->idMesa=$parametros["mesa"];
            $comanda->idComanda = $comanda->crearCodigoComanda();
            $comanda->crearComanda();
            $payload = json_encode(array("mensaje" => "Comanda creada con exito. El codigo de comanda es $comanda->idComanda"));           
          }catch(\Throwable $ex)
          {
              $payload=json_encode(array("Error!" => $ex->getMessage()));
          }
        }else{
          $payload=json_encode(array("Error!" => "Numero de mesa no existe"));
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
        $lista = Comanda::obtenerTodos();
        $payload = json_encode(array("listaDePedidos" => $lista));

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
