<?php
    $command = "sudo docker stats -a --no-stream --format '{{ .ID }}::$::{{.Name}}::$::{{.CPUPerc}}::$::{{.MemUsage}}'";
    $command = "echo \"{$command}\" > /fifo/my_fifo";
    
    shell_exec($command);
    sleep(3);
    $fichero = '/fifo/output.txt';

    if (file_exists($fichero)) {
        
        $gestor = fopen($fichero, "r");
        $salida = [];
        while (($line = fgets($gestor)) !== false) {
            $salida[] = trim($line);
        }
    
        fclose($gestor);

    }
    
    
    
?>

<body>
    <h1 align="center"> Monitorización de contendores </h1>
    <br>
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">Contendor ID</th>
        <th scope="col">Nombre</th>
        <th scope="col">Percentaje de uso de CPU</th>
        <th scope="col">Memoria usada</th>
        </tr>
    </thead>
    <tbody>
        <?php

            for ($i=0; $i < count($salida); $i++) { 

                $values = explode("::$::",$salida[$i]);
               
                echo "
                <tr>
                <th scope=\"row\">{$values[0]}</th>
                <td>{$values[1]}</td>
                <td>{$values[2]}</td>
                <td>{$values[3]}</td>
                </tr>
                ";

            }
        ?>
    </tbody>
    </table>
</body>