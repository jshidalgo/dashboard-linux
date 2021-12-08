<?php
    //accion a realizar
    $accion = $_POST["accion"];
    
    if ($accion == "Agregar") {
        agregarUsuario();
    }elseif ($accion == "Eliminar") {
        eliminarUsuario($_POST["user"]);
    }elseif ($accion == "Obtener") {
        obtenerUsuario($_POST["user"]);
    }elseif ($accion == "Editar") {
        if (password_verify($_POST["old"],$_POST["old_hash"])) {
            $formulario = explode("&", $_POST["dat"]);
            //var_dump($_REQUEST);
            editarUsuario($formulario,$_POST["user"],$_POST["old"],$_POST["shell"]);
        }else{
            echo "La clave anterior no coincide con la actual";
        }
    
    }

    /**
     * Funcion que permite editar el nombre, clave y shell de un usuario
     */
    function editarUsuario($formulario,$usuario,$passwd,$shell)
    {
        // informacion por la cual se quiere editar
        $nuevoUsuario = explode("=",$formulario[0])[1];
        $nuevaClave = explode("=",$formulario[2])[1];
        $nuevoShell = explode("=",$formulario[3])[1];
        // mensaje de respuesta 
        $mensaje = "";
        // comprobar que haya informacion a editar
        if($nuevaClave != $passwd){
            //comando para edita la clave de usuario            
            exec( "echo '".$usuario.":".$nuevaClave."' | sudo chpasswd", $salida,$res);
            // respuesta de la ejecucion del comando
            if ($res == 0) {
                $mensaje = $mensaje."Se ha actualizado la clave correctamente\n";
            }else {
                $mensaje = $mensaje."La clave no se ha podido actualizar\n";
            }
        }
        if ($nuevoShell != $shell) {
            // comando para editar el shell del usuario            
            exec( "sudo usermod -s /bin/".$nuevoShell." ".$usuario, $salida,$res);
            // respuesta de la ejecucion del comando
            if ($res == 0) {
                $mensaje = $mensaje."Se ha actualizado el shell correctamente\n";
            }else {
                $mensaje = $mensaje."El shell no se ha podido actualizar\n";
            }
        }
        if ($nuevoUsuario != $usuario) {
            
            // comando para cambiar el usuario y el nombre del grupo de este 
        
            exec( "sudo usermod -l ".$nuevoUsuario." ".$usuario, $salida, $res);
            if ($res == 0) {
                //cambio de nombre de grupo
                exec( "sudo groupmod -n ".$nuevoUsuario." ".$usuario, $aux); 
                $mensaje = $mensaje."Se ha actualizado el nombre y grupo correctamente\n";
            }else {
                $mensaje = $mensaje."No se ha podido actualizar el nombre de usuario\n";
            }
               
        }
        echo $mensaje;
    }
    /**
     * funcion que permite obtener los datos de un usuario
     */
    function obtenerUsuario($usuario)
    {
        // obtener datos de usuario
        $command = "sudo cat /etc/passwd | grep ".$usuario;
        exec( $command, $salida);
        $linea = explode(":", $salida[0]);
        $nombre = $linea[0];
        $shell = $linea[6];

        exec( "sudo cat /etc/shadow | grep ".$usuario, $salida2);
        $linea = explode(":", $salida2[0]);
        $clave = $linea[1];

        echo $nombre." ".$shell." ".$clave;
    }

    /**
     * funcion que permite eliminar a un usuario registrado
     */
    function eliminarUsuario($user)
    {
        // elimina al usuario junto a al directorio home del usuario
        $command = "sudo userdel -r ".$user;
        exec( $command, $salida, $res);
        //respuesta de la ejecucuin del comando
        if ($res == 0) {
            echo "Usuario eliminado exitosamente";
        }elseif ($res == 6) {
            echo "El usuario especificado no existe";
        }elseif ($res == 8) {
            echo "El usuario se encuentra actualmete conectado";
        }elseif ($res == 8) {
            echo "No se puede remover el direcotorio home";
        }
    }

    /**
     * funcion que permite agregar un usuario
     */
    function agregarUsuario()
    {
        //datos de formulario
        $user = trim($_POST["user"]);
        $password = $_POST["password"];
        $shell = $_POST["shell"];

        switch ($shell) {
            case "bash":
                $shell = "/bin/bash";
                break;
            case "sh":
                $shell = "/bin/sh";
                break;
        }
        // se ejecuta el comadno para agregar al usuario y su directorio home
        $command = "sudo useradd -m -s".$shell." ".$user;
        exec( $command, $salida,$res );
        
        //respuestas de ejecucion del comando
        if ($res == 0) {
            echo "Usuario creado con exito";
            //si el usuario es creado se le crea la clave enviada
            $command = "echo '".$user.":".$password."'| sudo chpasswd";
            exec( $command, $salida,$res );
            
        }elseif ($res == 9) {
            echo "Este usuario ya se encuentra registrado";
        }elseif ($res == 12) {
            echo "No se puede crear el directorio home";
        }
        
    }
?>
