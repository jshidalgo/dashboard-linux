<?php

$nombre = trim($_POST['nombre']) == '' ? '' : '--name '.trim($_POST['nombre']);
$p_host = trim($_POST['p_host']) == '' ? '' : '-p '.trim($_POST['p_host']);
$p_contenedor = trim($_POST['p_contenedor']) == '' ? '' : ':'.trim($_POST['p_contenedor']);
$publish = $p_host == '' ? null : $p_host.$p_contenedor;
$imagen = trim($_POST['imagen']);
$opts = trim($_POST['opts']) == '' ? '' : trim($_POST['opts']);


//valiar que la imagen este presente
if(!$imagen){
    die(json_encode(['status'=> false, 'message' => 'La imagen es necesaria para crear el contenedor'])) ;
}

$command = "sudo docker run -d $publish $opts $nombre $imagen";
$command = "echo \"{$command}\" > /fifo/my_fifo";

shell_exec($command);
sleep(20.5);
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