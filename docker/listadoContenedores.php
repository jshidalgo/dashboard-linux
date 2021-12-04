<?php
    $command = "sudo docker ps -a --format '{{ .ID }}::$::{{.Names}}::$::{{ .Status }}::$::{{.Ports}}::$::{{.RunningFor}}::$::{{.Image}}::$::{{.Mounts}}'";
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
    
    
    
?>

<body>
    <h1 align="center"> Listado de contendores </h1>
    <br>
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">Contendor ID</th>
        <th scope="col">Nombre</th>
        <th scope="col">Estado</th>
        <th scope="col">Puerto</th>
        <th scope="col">Creado hace</th>
        <th scope="col">Imagen</th>
        <th scope="col">Volumenes</th>
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
                <td>{$values[4]}</td>
                <td>{$values[5]}</td>
                <td>{$values[6]}</td>
                </tr>
                ";

            }
        ?>
    </tbody>
    </table>
</body>