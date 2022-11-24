<?php
require_once './models/ProductoPedido.php';
require_once './models/Mesa.php';
require_once './models/Comanda.php';
require_once './models/Producto.php';

class ProductoPedidoController 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        try{
          $pendiente = new ProductoPedido();
          
          $comandaAuxiliar = Comanda::obtenerComandaPorIdentificador($parametros["idComanda"]);
         
          $productoAuxiliar = Producto::obtenerProductoPorId($parametros["idPlato"]);
          if($comandaAuxiliar!= null && $productoAuxiliar != null)
          {
            $pendiente->idComanda = $parametros["idComanda"];
            $pendiente->idMesa = $comandaAuxiliar->idMesa;
            $pendiente->legajoEmpleado = 0;
            $pendiente->idPlato= $parametros["idPlato"];
            $pendiente->minutosDemora= 0;
            $pendiente->estado = "Pendiente";
            $pendiente->crearPendiente();
            $payload=json_encode(array("Exito!" => "Se creó el pendiente correctamente"));
          }else{
            $payload=json_encode(array("Error!" => "Revisar comanda y producto"));
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
        $lista = ProductoPedido::obtenerTodos();
        $payload = json_encode(array("listaDePedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPendientes($request, $response, $args)
    {
        $lista = ProductoPedido::obtenerPendientes();
        $payload = json_encode(array("listaDePedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerPendientesPersonales($request, $response, $args)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer",$header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $lista = ProductoPedido::obtenerPendientePorEmpleado($data->legajo);
        if(!$lista)
        {
          $payload = json_encode(array("listaDePedidos" => "Este usuario no tiene pendientes"));
        }else{
          $payload = json_encode(array("listaDePedidos" => $lista));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesSector($request, $response, $args)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer",$header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $lista = ProductoPedido::obtenerPendientePorSector($data->perfil_usuario);
        if(!$lista)
        {
          $payload = json_encode(array("listaDePedidos" => "Este sector no tiene pendientes"));
        }else{
          $payload = json_encode(array("listaDePedidos" => $lista));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function AsignarPendientesEmpleado($request, $response, $args)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer",$header)[1]);
        $parametros = $request->getParsedBody();
        $data = AutentificadorJWT::ObtenerData($token);
        
        $lista = ProductoPedido::ValidarPedidoPendiente($parametros["idPedido"],$data->perfil_usuario,$parametros["idProducto"]);
        $payload = json_encode(array("Lista" => $lista));
       /* if(ProductoPedido::AsignarPedido($data->perfil_usuario,$data->legajo,
        $parametros["idPedido"],$parametros["idProducto"],$parametros["minutosDemora"])==1)
        {
          $payload = json_encode(array("mensaje" => "Pedido asignado con exito!"));
        }else{
          $payload = json_encode(array("mensaje" => "Error"));
        }*/
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }



    public function CompletarPedido($request, $response, $args)
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer",$header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $parametros = $request->getParsedBody();

        $idPendiente = $parametros["idPendiente"];
        $idComanda = $parametros["idComanda"];
        $retorno = ProductoPedido::ModificarEstadoPedido($data->legajo,$idPendiente);
        if($retorno === 1)
        {
           if(ProductoPedido::verificarPedido($idComanda)==0){
              ProductoPedido::cerrarPendiente($idComanda);
              $payload = json_encode(array("Exito!" => "La comanda ya está completa"));
           }else{
            $payload = json_encode(array("Exito!" => "Se actualiazó el estado del pedido"));
           }
         
        }else{
          $payload = json_encode(array("Error!" => "No se encontró el pendiente. Verificar trabajador y id pedido"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

  
}
