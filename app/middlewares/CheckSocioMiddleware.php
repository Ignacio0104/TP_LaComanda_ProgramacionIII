<?php
use Dotenv\Loader\Resolver;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CheckSocioMiddleware{
    public function __invoke(Request $request,RequestHandler $handler) : Response
    {
       $header = $request->getHeaderLine(("Authorization"));
       $token = trim(explode("Bearer",$header)[1]);
       $response= new Response();
       try {
        $data = AutentificadorJWT::ObtenerData($token);

        if($data->perfil_usuario=="socio")
        {
          echo "** El usuario es socio **";
          $response= $handler->handle($request);
        }else{
          $response->getBody()->write(json_encode(array('mensaje' => "Esta tarea solo puede realizarla un socio")));
        }     
      } catch (Exception $e) {
        $response->getBody()->write(json_encode(array('mensaje' => $e->getMessage())));
        $response = $response->withStatus(401);
      }
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
?>