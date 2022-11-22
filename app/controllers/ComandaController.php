<?php
require_once './models/Comanda.php';

class ComandaController 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $comanda = new Comanda();
        if($comanda->verificarMesa($parametros["mesa"])>0)
        {
          try{ 
            if(file_exists($_FILES["URLImagen"]["tmp_name"])){
              $comanda->URLimagen = $this->moverImagen($parametros["mesa"]);
            }
            $comanda->idMesa=$parametros["mesa"];
            $comanda->idComanda = $comanda->crearCodigoComanda();
            $comanda->crearComanda();
            
            $mesaAux= Mesa::obtenerMesaPorIdentificador($parametros["mesa"]);
            $mesaAux->estado="Cliente esperando pedido";
  
            Mesa::modificarEstadoMesa($mesaAux);
            $payload = json_encode(array("mensaje" => "Comanda creada con exito. El codigo de comanda es $comanda->idComanda"));           
          }catch(\Throwable $ex)
          {
              $payload=json_encode(array("Error!" => $ex->getMessage()));
          }
        }else{
          $payload=json_encode(array("Error!" => "Numero de mesa no existe o la mesa ya está ocupada"));
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

    public function TraerTodasTerminadas($request, $response, $args)
    {
        $lista = Comanda::obtenerComandasTerminadas();
        $payload = json_encode(array("Pedidos listos para servir" => $lista));

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

    private function moverImagen($idMesa)
    {
      $carpetaFotos = ".".DIRECTORY_SEPARATOR."fotoComandas".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
      if(!file_exists($carpetaFotos))
      {
          mkdir($carpetaFotos, 0777, true);
      }
      $nuevoNombre = $carpetaFotos."fotoMesa".$idMesa.".jpg";

      if(file_exists($nuevoNombre))
      {
        $carpetaBackUp= ".".DIRECTORY_SEPARATOR."fotoComandas".DIRECTORY_SEPARATOR."Backup2022".DIRECTORY_SEPARATOR;

        if(!file_exists($carpetaBackUp))
        {
            mkdir($carpetaBackUp, 0777, true);
        }
        rename($nuevoNombre, $carpetaBackUp."fotoMesa".$idMesa.".jpg");
      }
      rename($_FILES["URLImagen"]["tmp_name"], $nuevoNombre);
      return $nuevoNombre;
    }
  
}
