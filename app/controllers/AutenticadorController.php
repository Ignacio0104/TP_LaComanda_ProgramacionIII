<?php
require_once './models/AutentificadorJWT.php';
require_once './interfaces/IApiUsable.php';

class AutentificadorController extends AutentificadorJWT
{
    public function CrearTokenLogin ($request, $response,$args)
    {
        $parametros = $request->getParsedBody();
        $usuarioBaseDeDatos=Empleado::obtenerEmpleadoPorLegajo($parametros["legajo"]);
        if($usuarioBaseDeDatos !=null)
        {
            if(password_verify($parametros["clave"],$usuarioBaseDeDatos->clave) 
            && ($usuarioBaseDeDatos->fechaBaja <1))
            {
                $datos = array('legajo'=> $usuarioBaseDeDatos->legajo,'usuario' => $usuarioBaseDeDatos->nombre, 'clave' => $usuarioBaseDeDatos->clave
                ,"perfil_usuario"=> $usuarioBaseDeDatos->perfilEmpleado);
                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('mensaje' => $token));
                $response->getBody()->write($payload);

            }else{
                $response->getBody()->write(json_encode(array("mensaje" => "Error, verifique la información")));
            }
        }else{
            $response->getBody()->write(json_encode(array("mensaje" => "Error, verifique la información")));
        }

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}


?>


