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
            if(password_verify($parametros["clave"],$usuarioBaseDeDatos->clave))
            {
                $datos = array('usuario' => $usuarioBaseDeDatos->nombre, 'clave' => $usuarioBaseDeDatos->clave
                ,"perfil_usuario"=> $usuarioBaseDeDatos->perfilEmpleado);
                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('Usuario logueado con éxito!' => $token));
                $response->getBody()->write($payload);

            }else{
                $response->getBody()->write("Error en los datos ingresados");
            }
        }else{
            $response->getBody()->write("El usuario no existe");
        }

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}



    /*public function VerificarClave ($request, $response,$args)
    {
        //$datos = json_decode(file_get_contents("php://input"), true);
        //$datos= $request->getParsedBody(); 
        $parametros = $request->getParsedBody();
        $usuarioBaseDeDatos=Usuario::obtenerUsuario($parametros["usuario"]);

       if(password_verify($parametros["clave"],$usuarioBaseDeDatos->clave))
        {
            $payload = json_encode(array("mensaje" => "Usuario logueado"));
        }else{
            $payload = json_encode(array("mensaje" => "Error!"));
        }
        
       if(password_verify($parametros["clave"],$usuarioBaseDeDatos->clave))
        {
            $payload = "Retorno desde el controller $usuarioBaseDeDatos" ;
        }else{
            $payload = "Retorno desde el controller Error";
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }*/
?>


