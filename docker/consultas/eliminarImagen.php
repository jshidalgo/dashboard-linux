<?php
$id = $_REQUEST['id'];

$command = "sudo docker image rm $id";
$command = "echo \"{$command}\" > /fifo/my_fifo";

shell_exec($command);
sleep(1.5);
$fichero = '/fifo/output.txt';

$contenido = null;
if (file_exists($fichero)) {
             
    $gestor = fopen($fichero, "r");
    //si no hay datos en el fichero de salidas exitosas
    if(filesize($fichero) > 0){
        $contenido = fread($gestor, filesize($fichero));
    }
    fclose($gestor);    
}
//verificar si hay contenido
if ($contenido) {
    echo json_encode(['status'=> true, 'message' => $contenido]);
}else{
    $fichero = '/fifo/error.txt';

    $contenido = null;
    if (file_exists($fichero)) {
                
        $gestor = fopen($fichero, "r");
        //si no hay datos en el fichero de salidas exitosas
        if(filesize($fichero) > 0){
            $contenido = fread($gestor, filesize($fichero));
        }
        fclose($gestor);    

        echo json_encode(['status'=> false, 'message' => $contenido]);
    }
}


