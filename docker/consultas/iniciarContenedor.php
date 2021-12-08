<?php
$id = $_REQUEST['id'];

$command = "sudo docker start $id";
$command = "echo \"{$command}\" > /fifo/my_fifo";

shell_exec($command);
sleep(5);
$fichero = '/fifo/output.txt';

$contenido = "";
if (file_exists($fichero)) {
             
    $gestor = fopen($fichero, "r");
    //si no hay datos en el fichero de salidas exitosas
    if(filesize($fichero) > 0){
        $contenido = fread($gestor, filesize($fichero));
    }
    fclose($gestor);    
}
//verificar si hay contenido
if (!$contenido) {
    $fichero = '/fifo/error.txt';

    $contenido = "";
    if (file_exists($fichero)) {
                
        $gestor = fopen($fichero, "r");
        //si no hay datos en el fichero de salidas exitosas
        if(filesize($fichero) > 0){
            $contenido = fread($gestor, filesize($fichero));
        }
        fclose($gestor);    

        die(json_encode(['status'=> false, 'message' => $contenido])) ;
    }
    
}

die(json_encode(['status'=> true, 'message' => $contenido])) ;