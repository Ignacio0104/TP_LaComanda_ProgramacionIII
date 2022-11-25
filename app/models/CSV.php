<?php

    class CSV
    {

        public static function GrabarEnCsv($item, $ruta)
        {             
            $retorno = false;
            if($item)
            {
                $separadoPorComa = implode(",", (array)$item);
                $file = fopen($ruta, "a+");
                if($file)
                {
                    fwrite($file, $separadoPorComa.PHP_EOL); 
                }                 
                fclose($file);   
                $retorno = true;
            }
            return $retorno;                  
        }

        public static function ExportarTabla($tabla, $clase, $ruta)
        {
            $listaProductos = Producto::obtenerTodos($tabla, $clase);
            $ruta.="productos.csv";
            $file = fopen($ruta, "w+");
            foreach($listaProductos as $item)
            {
                $separadoPorComa = implode(",", (array)$item);  
                if($file)
                {
                    fwrite($file, $separadoPorComa.PHP_EOL); 
                }                           
            }
            fclose($file);  

            if(file_exists($ruta)&&filesize($ruta)>0)
            {
                return true;
            }else{
                return false;
            }
            
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
                        $registro = fgets($auxArchivo);                        
                        if(!empty($registro))
                        {          
                            array_push($array, $registro);                                                
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