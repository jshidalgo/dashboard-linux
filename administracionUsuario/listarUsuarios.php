<!-- contendor de lista de usuarios del sistema -->
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">UID</th>
            <th scope="col">Usuario</th>
            <th scope="col">Grupo</th>
            <th scope="col">Directorio home</th>
            <th scope="col">Shell</th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <!-- tabla -->
        <?php
            // comando que obtiene los usuarios 1000 primeros usuarios del sistema
            exec('/bin/bash -c "getent passwd {1000..2000}"', $salida);

            foreach ($salida as $linea) {
                //separar la salida por :
                $salida = explode(":", $linea);
                // obtener el grupo al que el usuario pertenece
                exec('cat /etc/group | grep '.$salida[3], $grupo);
                $grupo = explode(":", $grupo[0])[0];
                echo '<tr>
                <th scope="row">'.$salida[2].'</th>
                <td>'.$salida[0].'</td>
                <td>'.$grupo.'</td>
                <td>'.$salida[5].'</td>
                <td>'.$salida[6].'</td>
                <td><svg style="cursor:pointer;" onclick="eliminar(\''.$salida[0].'\')" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                </svg></td>
                <td><svg style="cursor:pointer;" data-toggle="modal" data-target="#exampleModal" onclick="obtenerDatos(\''.$salida[0].'\')" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg></td>
                </tr> '; 
            }
        ?>
        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- div contenedor del formulario -->
        <div class="container">
            <form id="formulario" method="post">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre de usuario</label>
                    <input name="user" type="text" class="form-control" id="nombreUsuario" placeholder="Nombre de usuario">
                    <span style="display: none; color: red;" id="msg-error-user">Ingrese el un nombre de usuario</span>
                </div>
                <div class="form-group">
                    <label for="clave">Clave de usuario anterior</label>
                    <input name="password-old" type="password" class="form-control" id="clave-anteior" placeholder="Clave de usuario anterior">
                    <span style="display: none; color: red;" id="msg-error-passwd-old" >Ingrese una contraseña anterior</span>
                    <span style="display: none; color: red;" id="msg-error-passwd-no" >La contraseña no coincide con la anterior</span>
                </div>
                <div class="form-group">
                    <label for="clave">Nueva clave de usuario</label>
                    <input name="password" type="password" class="form-control" id="clave" placeholder="Nueva clave de usuario">
                    <span style="display: none; color: red;" id="msg-error-passwd" >Ingrese una nueva contraseña</span>
                </div>
                <div class="form-group">
                <label for="shell">Seleccione el shell</label>
                <select class="form-control" id="shell" name="shell">
                    <option value=''>Seleccione</option>
                    <option value='bash'>bin/bash</option>
                    <option value='sh'>bin/sh</option>
                </select>
                <span style="display: none; color: red;" id="msg-error-shell" >Seleccione un shell</span>
            </div>
                <input type="hidden" name="accion" value="Editar">
            </form>
        
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn-enviar">Actualizar</button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- scripts -->
<script>
    /**
    *funcion que permite enviar peticion post para eliminar a un usuario
     */
    function eliminar(user) {
        // accion y usuario
        var parametros = {
                "accion" : "Eliminar",
                "user" : user //usuario a eliminar
        };
        $.ajax({
            method: "POST",
            url: "administracionUsuario/administracion.php",
            data: parametros
        }).done(function(data) {
            alert(data); // imprimimos la respuesta
            location.reload();
        }).fail(function() {
            alert("Algo salió mal");
        });       
    }

    //variables que permite conocer los valores antes de editarlos
    var old_password = "";
    var usuarioEditar = "";
    var old_shell = "";
    /**
    * Funcion que permite obtener los daots de un usuario
     */
    function obtenerDatos(user) {
        // accion y usuario
        var parametros = {
                "accion" : "Obtener",
                "user" : user
        };
        $.ajax({
            method: "POST",
            url: "administracionUsuario/administracion.php",
            data: parametros
        }).done(function(data) {
            //actualziar campos de el modal de editar
            respuesta = data.split(" ");
            usuarioEditar = respuesta[0];
            $('#nombreUsuario').val(usuarioEditar);
            old_password = respuesta[2];
            old_shell = respuesta[1].split("/")[2];
            $('#shell').val(old_shell);
            
        }).fail(function() {
            alert("Algo salió mal");
        });     
    }

    // permite lanzar peticion post para editar un usuario
    $('#btn-enviar').click(function(){
                        
            if (validarCampos()) {
                var parametros = {
                    "dat" : $("#formulario").serialize(),
                    "accion" : "Editar",
                    "old_hash" : old_password,
                    "old" : old_pswd,
                    "user" : usuarioEditar,
                    "shell" : old_shell,
                }
                var url = "administracionUsuario/administracion.php";                                      
                $.ajax({                        
                type: "POST",                 
                url: url,                    
                data: parametros,
                success: function(data)            
                {
                    if (data == "La clave anterior no coincide con la actual") {
                        $('#msg-error-passwd-no').css('display','block');
                    }else{
                        $('#msg-error-passwd-no').css('display','none');
                        alert(data);
                        limpiarCampos(); //limpar campos
                        location.reload();
                    }
                }
                });
            }
        
      });

    
    /**
    * Funcion que permite validar que los campos se encuentren completos
     */
    function validarCampos() {
        user = $('#nombreUsuario').val();
        old_pswd = $('#clave-anteior').val();
        passwd = $('#clave').val();
        shell = $('#shell').val();
        
        if (user !== "" && passwd !== "" && shell !== "" && old_pswd !== "") {
            return true;          
        }

        if (user === "") {
            $('#msg-error-user').css('display','block');
        }else{
            $('#msg-error-user').css('display','none');
        }
        if (passwd === "") {
            $('#msg-error-passwd').css('display','block');
        }else{
            $('#msg-error-passwd').css('display','none');
            $('#msg-error-passwd-no').css('display','none');
        }
        if (old_pswd === "") {
            $('#msg-error-passwd-old').css('display','block');
        }else{
            $('#msg-error-passwd-old').css('display','none');
        }
        if (shell === "") {
            $('#msg-error-shell').css('display','block');
        }else{
            $('#msg-error-shell').css('display','none');
        }
        return false;
    }
    /**
    * Funcion que permite limpiar los campos
     */
    function limpiarCampos() {
        user = $('#nombreUsuario').val("");
        passwd = $('#clave').val("");
        shell = $('#shell').val("");
    }
</script>