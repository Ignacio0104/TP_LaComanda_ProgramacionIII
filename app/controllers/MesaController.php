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
                  $payload = json_encode(array("Exito" => "Mesa habilitada con exito"));
                }else{
                  $payload = json_encode(array("Error" => "Favor revise la informacion de la mesa"));
                }
            }else{
                $payload = json_encode(array("Error!" => "Favor revise que el empleado sea un mozo"));
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
        $payload = json_encode(array("Los minutos que tiene de demora su mesa son: " => $minutos));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarCuenta($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idMesa = $parametros['idMesa'];

      if(Mesa::modificarEstadoConIdMesa($idMesa)>0){
        $payload = json_encode(array("Exito!  " => "Estado de la mesa modificado"));
      }else{
        $payload = json_encode(array("Error! " => "Favor verifique la información ingresada"));
      }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idMesa = $parametros['idMesa'];

      if(Mesa::cerrarMesaSQL($idMesa)>0){
        $payload = json_encode(array("Exito!  " => "EsStado de la mesa modificado"));
      }else{
        $payload = json_encode(array("Error! " => "Favor verifique la información ingresada"));
      }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
