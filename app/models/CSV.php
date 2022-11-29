<?php

    class CSV
    {

        public static function ExportarTabla($tabla, $clase, $ruta)
        {
            $listaProductos = Producto::obtenerTodos($tabla, $clase);
            $file = fopen($ruta, "w+");
            foreach($listaProductos as $item)
            {
                $separadoPorComa = implode(",", (array)$item);  
                if($file)
                {
                    fwrite($file, $separadoPorComa.",\r\n"); 
                }                           
            }
            fclose($file);  
            return $ruta;     
        }

        public static function LeerCsv($archivo)
        {
            $auxArchivo = fopen($archivo, "r");
            $array = [];

            if(isset($auxArchivo))
            {
                try
                {
                    while(!feof($auxArchivo))
                    {
                        $datos = fgets($auxArchivo);                        
                        if(!empty($datos))
                        {          
                            array_push($array, $datos);                                                
                        }
                    }
                }
                catch(\Throwable $e)
                {
                    echo "No se pudo leer el archivo<br>";
                    printf($e);
                }
                finally
                {
                    fclose($auxArchivo);
                    return $array;
                }
            }
        }
    }
?>