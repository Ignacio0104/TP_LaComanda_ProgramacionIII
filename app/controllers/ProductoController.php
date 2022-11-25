<?php
require_once './models/Producto.php';
require_once './models/CSV.php';

class ProductoController extends Producto
{
    public $tipos = ["bartender","cervecero","cocinero"];
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio =$parametros["precio"];
        $tipo = $parametros["tipo"];
        try{
          if(in_array($tipo,$this->tipos))
          {
              $producto = new Producto();
              $producto->nombre=$nombre;
              $producto->precio = $precio;
              $producto->tipo = $tipo;
              $producto->crearProducto();
              $payload = json_encode(array("mensaje" => "Producto creado con exito"));
      
          }else{
              $payload=json_encode(array("mensaje" => "Tipo de producto equivocado"));
          }
      }catch(\Throwable $ex)
      {
          $payload=json_encode(array("mensaje" => $ex->getMessage()));
      }
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ExportarTabla($request, $response, $args)
    {
        try
        {
            CSV::ExportarTabla('producto', 'Producto',"productos.csv");
            $payload = json_encode(array("mensaje" => "Tabla exportada con exito"));
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al listar: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }    
    }

    public function ImportarTabla($request, $response, $args)
    {
        try
        {
            $archivo = ($_FILES["archivo"]);
            Producto::CargarCSV($archivo["tmp_name"]);
            $payload = json_encode("Carga exitosa.");
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al listar: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }    
    }
}
