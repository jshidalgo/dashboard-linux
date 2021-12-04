<!-- Script que permite mostrar la grafica de uso de RAM -->
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Uso RAM'],
        ['Memoria disponible', obtenerDatos(1)],
        ['Memoria usada', obtenerDatos(2)]
    ]);

    var options = {
        title: 'Uso RAM',
        pieHole: 0.4,
    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);
    }
</script>
<!-- Seccion donde se muestra la grafica -->
<body>
  <div id="donutchart" style="width: 900px; height: 500px;"></div>
</body>
<!-- script que permite obtener el uso de ram -->
<script>

    /**
     * Funcion que obtiene el uso de ram
     */
    function obtenerDatos(dato) {
    <?php
          
      $command = "free -m| awk '{print \\$2, \\$7}' | tail -2 | head -n 1";

      $command = "echo \"{$command}\" > /fifo/my_fifo";
     
      shell_exec($command );
      sleep(1.5);
      $fichero = '/fifo/output.txt';

        if (file_exists($fichero)) {
         
            $gestor = fopen($fichero, "r");
            $contenido = fread($gestor, filesize($fichero));
            fclose($gestor);

            $salida = explode(" ",$contenido);
        }
    ?>
    // memoria total del dispositivo
    totalMemoria = <?php echo $salida[0];?>;
    // memoria disponible 
    memoriaDisponible = <?php echo $salida[1];?>;
    // memoria usada
    memoriaUsada = totalMemoria - memoriaDisponible;
    //condicional para obtener la cantidad de memoria usada o disponible
    if (dato === 1) {
      return memoriaDisponible;
    }
    else if(dato === 2){
      return memoriaUsada;
    }
  }
  </script>

