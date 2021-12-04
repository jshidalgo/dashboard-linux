<?php
    $command = "sudo docker images | awk '{print \\$1 \\\"::$::\\\" \\$2 \\\"::$::\\\" \\$3 \\\"::$::\\\" \\$4, \\$5, \\$6 \\\"::$::\\\" \\$7 }'";
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
    <h1 align="center"> Listado de imagenes </h1>
    <br>
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">Image ID</th>
        <th scope="col">Repositorio</th>
        <th scope="col">Tag</th>
        <th scope="col">Creado hace</th>
        <th scope="col">Tamaño</th>
        </tr>
    </thead>
    <tbody>
        <?php

            for ($i=1; $i < count($salida); $i++) { 

                $values = explode("::$::",$salida[$i]);
               
                echo "
                <tr>
                <th scope=\"row\">{$values[2]}</th>
                <td>{$values[0]}</td>
                <td>{$values[1]}</td>
                <td>{$values[3]}</td>
                <td>{$values[4]}</td>
                </tr>
                ";

            }
        ?>
    </tbody>
    </table>
</body>