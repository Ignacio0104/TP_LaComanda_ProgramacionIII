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
            if($mesa->verificarMozo($mesa)->perfilEmpleado == "mozo"){   
                if($mesa->crearMesa()>0){
                  $payload = json_encode(array("mensaje" => "Mesa habilitada con exito"));
                }else{
                  $payload = json_encode(array("mensaje" => "Favor revise la informacion de la mesa"));
                }
            }else{
                $payload = json_encode(array("mensaje" => "Favor revise que el empleado sea un mozo"));
            }    
        }catch(\Throwable $ex)
        {
            $payload=json_encode(array("Error!" => $ex->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaDeMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEsperaMesa($request, $response, $args)
    {
        $minutos = Mesa::obtenerTiempoEspera($_GET["idMesa"],$_GET["idComanda"]);
        if($minutos==null)
        {
          $payload = json_encode(array("mensaje" => "Aún no se puede determinar el tiempo de espera"));
        }else{
          $payload = json_encode(array("mensaje" => $minutos));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarCuenta($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idMesa = $parametros['idMesa'];
      $idComanda = $parametros['idComanda'];
      $lista=Mesa::traerCosto($idComanda);
      if(Mesa::modificarEstadoConIdMesa($idMesa)>0 && count($lista)>=1){
        $mensaje = " Cuenta: ";
        $montoFinal=0;
        for ($i=0; $i < count($lista); $i++) { 
          $mensaje.=" | ".$lista[$i]["nombre"]."- $".$lista[$i]["precio"];
          $montoFinal+=$lista[$i]["precio"];
        }
          $payload = json_encode(array("mensaje" => "Cuenta cerrada con exito".$mensaje." | Precio FINAL $".$montoFinal));     
      }else{
        $payload = json_encode(array("mensaje" => "Favor verifique la información ingresada"));
      }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idMesa = $parametros['idMesa'];
      $idComanda = $parametros['idComanda'];

      if(Mesa::cerrarMesaSQL($idMesa)>0){
        if(Mesa::cargarFactura($idComanda)==1)
        {
          $payload = json_encode(array("mensaje" => "Mesa cerrada y factura cargada"));
        }else{
          $payload = json_encode(array("mensaje" => "Mesa cerrada pero no se pudo cargar la factura"));
        }
      }else{
        $payload = json_encode(array("mensaje" => "Favor verifique la información ingresada"));
      }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
