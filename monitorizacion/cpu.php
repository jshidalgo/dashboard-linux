<!--Script que muestra la grafica del procesador-->
<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['%CPU 1 MIN', (obtenerDatos())[0]],
          ['%CPU 5 MIN', (obtenerDatos())[1]],
          ['%CPU 15 MIN', (obtenerDatos())[2]]
        ]);

        var options = {
          width: 1200, height: 400,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

      }
    </script>
<!-- Seccion donde se muestra la grafica -->
<body>
    <style>#chart_div table {margin:auto !important;}</style>
    <div id="chart_div" style="width: 100%; height: 100%;"></div>
</body>


<script>
    function obtenerDatos() {
        <?php

            //Comando que muestra la cantidad del load average
            //Los valores de load average son para cada minuto, cada 5 minutos y cada 15 minutos respectivamente
            $command_1 = "top -b -n 1 | head -1 ";
            $command_1 = "echo \"{$command_1}\" > /fifo/my_fifo";
            shell_exec($command_1);
            sleep(1.5);
            $fichero = '/fifo/output.txt';

            if (file_exists($fichero)) {
             
                $gestor = fopen($fichero, "r");
                $contenido = fread($gestor, filesize($fichero));
                fclose($gestor);
    
                $linea = explode(" ",$contenido);
            }

            // Comando que muestra la cantidad de nucleos
            $command_2 = "nproc"; 
            $command_2 = "echo \"{$command_2}\" > /fifo/my_fifo";
            shell_exec($command_2);
            sleep(1.5);

            if (file_exists($fichero)) {
             
                $gestor = fopen($fichero, "r");
                $contenido = fread($gestor, filesize($fichero));
                fclose($gestor);
                
                //Numero de nucleos de procesador
                $cores = explode(" ",$contenido);
            }
            
            //Arreglo que contendara los valores del load average
            $load_average = Array();
            
            array_push( $load_average, substr($linea[count($linea)-3], 0 , strlen($linea[count($linea)-3])-1) ); // Cada 15 minutos
            array_push( $load_average, substr($linea[count($linea)-2], 0 , strlen($linea[count($linea)-2])-1) ); //Cada 5 minutos
            array_push( $load_average, $linea[count($linea)-1] ); //Cada 1 minuto

            //print_r($load_average);

            //Arreglo con los porcentajes de uso de CPU
            $porcentajes = Array();
            
            array_push($porcentajes, ( doubleval(str_replace(",",".",$load_average[0])) * 100)/ doubleval($cores) );
            array_push($porcentajes, ( doubleval(str_replace(",",".",$load_average[1])) * 100)/ doubleval($cores) );
            array_push($porcentajes, ( doubleval(str_replace(",",".",$load_average[2])) * 100)/ doubleval($cores) );
        
        ?>
        var resultado = <?php echo json_encode($porcentajes); ?>;
        return resultado;
    }
</script>


