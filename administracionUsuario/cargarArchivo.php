<?php
    //ruta temporal del archivo seleccionado
    $archivo = $_FILES["media"]["tmp_name"];

    //ejecucion y lectura de archivo
    if (($gestor = fopen($archivo, "r")) !== FALSE) {
        while (($linea = fgetcsv($gestor, ",")) !== FALSE) {
            //usuario,clave,shell
            agregarUsuario($linea[0],$linea[1],$linea[2]);
        }
        //cierre de archivo
        fclose($gestor);
    }

    /**
     * funcion que permite agregar un usuario
     */
    function agregarUsuario($user,$password,$shell)
    {
        switch ($shell) {
            case "bash":
                $dir_shell = "/bin/bash";
                break;
            case "sh":
                $dir_shell = "/bin/sh";
                break;
            default:
                $dir_shell = "/bin/bash";
        }
        // se ejecuta el comadno para agregar al usuario y su directorio home
        $command = "sudo useradd -m -s".$dir_shell." ".$user;
        exec( $command, $salida,$res );
        
        //respuestas de ejecucion del comando
        if ($res == 0) {
            echo "Usuario '".$user."' creado con exito\n";
            //si el usuario es creado se le crea la clave enviada
            $command = "echo '".$user.":".$password."'| sudo chpasswd";
            exec( $command, $salida,$res );
            
        }elseif ($res == 9) {
            echo "El usuario '".$user."' ya se encuentra registrado\n";
        }elseif ($res == 12) {
            echo "No se puede crear el directorio home del usuario '".$user."'\n";
        }
        
    }
?>