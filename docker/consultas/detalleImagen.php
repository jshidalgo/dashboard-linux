<?php
$id = $_REQUEST['id'];

$command = "sudo docker image inspect $id";
$command = "echo \"{$command}\" > /fifo/my_fifo";

shell_exec($command);
sleep(1.5);
$fichero = '/fifo/output.txt';

if (file_exists($fichero)) {
             
    $gestor = fopen($fichero, "r");
    $contenido = fread($gestor, filesize($fichero));
    fclose($gestor);

    echo $contenido;
}



