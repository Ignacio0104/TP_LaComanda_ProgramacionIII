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
              $payload=json_encode(array("mensaje" => $ex->getMessage()));
          }
        }else{
          $payload=json_encode(array("mensaje" => "Numero de mesa no existe o la mesa ya está ocupada"));
        }
       
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
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
        $payload = json_encode(array("pedidosTerminados" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerComandasTiempo($request, $response, $args)
    {
        $lista = Comanda::obtenerComandasTiempo();
        $payload = json_encode(array("pedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
    
        $datos = json_decode(file_get_contents("php://input"), true);
        $idComanda = $datos['idComanda'];
        
        if(Comanda::cambiarEstados($idComanda)>0){
          $payload = json_encode(array("mensaje" => "Comanda cerrada"));
        }else{
          $payload = json_encode(array("mensaje" => "Error al cambiar estados"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

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
