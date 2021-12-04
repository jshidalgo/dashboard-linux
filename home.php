<body>
  <h2 align="center">Información del sistema anfitrion (host)</h2>

  <h3 style="margin-left: 10px;">Datos de la versión</h3>
  <div style="margin:15px;">
    <?php
        $command = "lsb_release -a";
        $command = "echo \"{$command}\" > /fifo/my_fifo";
    
        shell_exec($command);
        sleep(1.5);
        $fichero = '/fifo/output.txt';

        if (file_exists($fichero)) {
        
            $gestor = fopen($fichero, "r");
            $salida = [];
            while (($line = fgets($gestor)) !== false) {
                $salida[] = explode(':',trim($line));
            }
        
            fclose($gestor);

        }

        foreach($salida as $value){
            echo "<b>{$value[0]}</b>:{$value[1]}<br>";
        }

    ?>
  </div>
  
<br>

<h3 style="margin-left: 10px;">Información del docker</h3>
<div style="margin:15px;">
    <?php
        $command = "docker version";
        $command = "echo \"{$command}\" > /fifo/my_fifo";
    
        shell_exec($command);
        sleep(1.5);
        $fichero = '/fifo/output.txt';

        if (file_exists($fichero)) {
        
            $gestor = fopen($fichero, "r");
            $salida = [];
            while (($line = fgets($gestor)) !== false) {
                $salida[] = explode(':',trim($line));
            }
        
            fclose($gestor);

        }

        foreach($salida as $value){
            echo "<b>{$value[0]}</b>:{$value[1]}<br>";
        }

    ?>
</div>
  

</body>