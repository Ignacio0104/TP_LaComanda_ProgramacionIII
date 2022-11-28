<?php

use Psr7Middlewares\Transformers\Encoder;

require_once './models/Encuesta.php';


class EncuestaController 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idMesa = $parametros['idMesa'];
        $idComanda = $parametros['idComanda'];
        $puntuacionMesa = $parametros["puntuacionMesa"];
        $puntuacionRestaurante = $parametros["puntuacionRestaurante"];
        $puntuacionMozo = $parametros["puntuacionMozo"];
        $puntuacionCocinero = $parametros["puntuacionCocinero"];
        $comentarios = $parametros["comentarios"];

        $mesaAux = Mesa::obtenerMesaPorIdentificador($idMesa);

        try{
          if($mesaAux->estado == "cliente pagando" && Comanda::validarComandaEncuesta($idComanda,$idMesa)!=null)
          {
            if($puntuacionMesa>0 && $puntuacionMesa<11&&
            $puntuacionRestaurante>0 && $puntuacionRestaurante<11&&
            $puntuacionMozo>0 && $puntuacionMozo<11&&
            $puntuacionCocinero>0 && $puntuacionCocinero<11)
          {
            if(Comanda::obtenerComandaPorIdentificador($idComanda)!=null)
            {
              $encuesta = new Encuesta();
              $encuesta->idComanda = $idComanda;
              $encuesta->puntuacionMesa = $puntuacionMesa;
              $encuesta->puntuacionMozo = $puntuacionMozo;
              $encuesta->puntuacionRestaurante = $puntuacionRestaurante;
              $encuesta->puntuacionCocinero = $puntuacionCocinero;
              $encuesta->comentarios = $comentarios;
              $encuesta->crearEncuesta();
              $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));
            }else{
              $payload = json_encode(array("mensaje" => "No se encontro la comanda indicada"));
            }

          }else{
              $payload=json_encode(array("mensaje" => "Revisar puntuaciones (entre 1 y 10)"));
          }
          }else{
            $payload=json_encode(array("mensaje" => "Revise el estado de la mesa (encuesta valida para cliente pagando) y que la comanda coincida con la mesa"));
          }
           
        }catch(\Throwable $ex)
        {
            $payload=json_encode(array("mensaje" => $ex->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
