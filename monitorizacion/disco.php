<!-- scrip que permite mostrar la grafica de uso de volumen -->
<script>
      google.charts.load('current', {'packages':['treemap']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Volume', 'Parent', 'Volume (size)','Market increase/decrease (color)'],
          ['Volumenes',    null,                 0,                               0],
          //seccion que permite obtener el uso de volumen del dispositivo
            <?php
                $command = " df | awk '{print \\$1, \\$5, \\$6}'";

                $command = "echo \"{$command}\" > /fifo/my_fifo";
          
                shell_exec($command);
                sleep(1.5);
                $fichero = '/fifo/output.txt';

                if (file_exists($fichero)) {
                
                    $gestor = fopen($fichero, "r");
                    $salida = [];
                    while (($line = fgets($gestor)) !== false) {
                        $salida[] = trim($line);
                    }
                
                    fclose($gestor);

                }
              
                for ($i=1; $i < count($salida); $i++) { 

                    $palabras = explode(" ",$salida[$i]);
                    $uso= trim($palabras[1],"%");
                    $color = rand(-30,30);
                    echo "['$palabras[2]','Volumenes',$uso, 0],";
       
                }

            ?>
        ]);

        tree = new google.visualization.TreeMap(document.getElementById('chart_div'));

        tree.draw(data, {
            title: "Ocupación de volumen",
            minColor: '#f00',
            midColor: '#ddd',
            maxColor: '#0d0',
            headerHeight: 15,
            fontColor: 'black',
            generateTooltip: showFullTooltip
        });

        function showFullTooltip(row, size, value) {
            return '<div style="background:#fd9; padding:10px; border-style:solid">' +
            '<span style="font-family:Courier"><b>' + data.getValue(row, 0) +
            '</b>, ' + data.getValue(row, 1) + ', ' + data.getValue(row, 2) +
            ', ' + data.getValue(row, 3) + '</span><br>'+
            '<b>Montado en: </b>' + data.getValue(row, 0) + '<br>' + 
            '<b>% de uso: </b>' + data.getValue(row, 2) + '%<br>' +
                ' </div>';
        }
      }
    </script>
<!-- Seccion donde se muestra la grafica -->
<body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
</body>