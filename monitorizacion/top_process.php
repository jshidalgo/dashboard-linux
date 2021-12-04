
<?php
    //Comando que muestra los 3 proceso que mas consumen CPU
    $command_1 = "top -b -n 1 | head -10 | tail -3 | awk '{print \\$1, \\$9, \\$12}'";

    $command_1 = "echo \"{$command_1}\" > /fifo/my_fifo";

    shell_exec($command_1);
    sleep(1.5);
    $fichero = '/fifo/output.txt';

    if (file_exists($fichero)) {
    
        $gestor = fopen($fichero, "r");
        $salida_1 = [];
        while (($line = fgets($gestor)) !== false) {
            $salida_1[] = trim($line);
        }
    
        fclose($gestor);

    }
              
    for ($i=0 ; $i < count($salida_1) ; ++$i){
        $salida_1[$i] = explode(" ", $salida_1[$i]);
    }
    echo('<style>#contenido {display:flex; margin-top: 50px;} .table-top{width:50%; padding:0 20px;} .title-table {font-size:25px}</style>');
    echo('<div class="table-top"><h1 class="title-table">Top 3 de los procesos que más usan la CPU</h1>');
    echo("<table class='table'>");
    echo('<tr><th scope="col">PID</th><th scope="col">%CPU</th><th scope="col">CMD</th></tr>');
        for($i=0 ; $i < count($salida_1) ; ++$i){
            echo("<tr>");

            foreach($salida_1[$i] as $aux){
                echo("<td>".$aux."</td>");
            }
            echo("</tr>");
        }
    echo("</table></div>");


    //Comando que muestra los 3 proceso que mas consumen RAM
    $command_2 = "ps c aux | awk '{print \\$2, \\$4, \\$11}' | sort -k2r | head -n 4 | tail -3";

    $command_2 = "echo \"{$command_2}\" > /fifo/my_fifo";

    shell_exec($command_2);
    sleep(1.5);

    if (file_exists($fichero)) {
    
        $gestor = fopen($fichero, "r");
        $salida_2 = [];
        while (($line = fgets($gestor)) !== false) {
            $salida_2[] = trim($line);
        }
    
        fclose($gestor);

    }
    for($i=0; $i < count($salida_2); ++$i){
        $salida_2[$i] = explode(" ", $salida_2[$i]);
    }

    echo('<div class="table-top"><h1 class="title-table">Top 3 de los procesos que más usan la RAM</h1>');
    echo("<table class='table'>");
    echo('<tr><th scope="col">PID</th><th scope="col">%RAM</th><th scope="col">CMD</th></tr>');
    foreach($salida_2 as $aux){
        echo('<tr>');
        foreach($aux as $proc){
            echo('<td>'.$proc.'</td>');
        }
        echo('</tr>');
        
    }
    echo("</table></div>");

?>