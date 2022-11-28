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
          $archivo = CSV::ExportarTabla('producto', 'Producto',"productos.csv");
            if(file_exists($archivo)&& filesize($archivo)>0)
            {
              $payload = json_encode(array("mensaje" => "http://localhost:666/".$archivo ));
            }else{
              $payload = json_encode(array("mensaje" => "Error, verifique la informacion ingresada"));
            }
            
            $response->getBody()->write($payload);
            
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al listar: <br> $mensaje .<br>");
        }
        finally
        {
          return $response->withHeader('Content-Type', 'text/csv');
        }    
    }

    public function ImportarTabla($request, $response, $args)
    {
        try
        {
            $archivo = ($_FILES["archivoCSV"]);
            Producto::CargarCSV($archivo["tmp_name"]);
            $payload = json_encode(array("mensaje" => "La base de datos fue actualizada correctamente"));
          
        }
        catch(Throwable $mensaje)
        {
          $payload = json_encode(array("mensaje" => $mensaje->getMessage()));
        }
        finally
        {
          $response->getBody()->write($payload);
          return $response->withHeader('Content-Type', 'text/csv');
        }    
       
    }
}
