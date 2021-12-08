<!-- div contenedor del formulario -->
<div class="container">
    <form id="formulario" method="post">
        <div class="form-group">
            <label for="nombreUsuario">Nombre de usuario</label>
            <input name="user" type="text" class="form-control" id="nombreUsuario" placeholder="Nombre de usuario">
            <span style="display: none; color: red;" id="msg-error-user">Ingrese el un nombre de usuario</span>
        </div>
        <div class="form-group">
            <label for="clave">Clave de usuario</label>
            <input name="password" type="password" class="form-control" id="clave" placeholder="Clave de usuario">
            <span style="display: none; color: red;" id="msg-error-passwd" >Ingrese una contraseña</span>
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
        <input type="hidden" name="accion" value="Agregar">
        <input type="button" class="btn btn-primary" id="btn-enviar" value="Agregar">
    </form>
    <!-- cargar svg -->
<br>
   
    <form enctype="multipart/form-data" id="formulario-csv" method="post">
        <div>
            <label for="file">Agregar usuarios a partir de un .csv:</label>
            <input type="file" id="file" name="file" accept=".csv">
        </div>
        <div>
            <input type="button" class="btn btn-primary" id="btn-enviar-csv" value="Crear">
        </div>
    </form>
</div>
<!-- scripts -->
<script>
    // permite lanzar peticion post para agergar un usuario
      $('#btn-enviar').click(function(){
          if (validarCampos()) {
            var url = "administracionUsuario/administracion.php";                                      
            $.ajax({                        
            type: "POST",                 
            url: url,                    
            data: $("#formulario").serialize(),
            success: function(data)            
            {
                alert(data); //alerta de resultado 
                limpiarCampos(); //limpar campos
            }
            });
          }
        
      });

      // permite enviar peticion post para agregar los usuarios que se encuentren dentro de un archivo
      $('#btn-enviar-csv').click(function(){
        extension = $("#file").val().split(".")[1];
          if (extension === "csv") {
            var file = document.getElementById('file').files[0];
            var form = new FormData();
            form.append('media', file);
            $.ajax({
                url : "administracionUsuario/cargarArchivo.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data : form,
                success: function(response){
                    alert(response);
                }
            });
          }else{
            alert("Seleccione un archivo .csv");
          }
        
      });

    
    /**
    * Funcion que permite validar que los campos se encuentren completos
     */
    function validarCampos() {
        user = $('#nombreUsuario').val();
        passwd = $('#clave').val();
        shell = $('#shell').val();
        
        if (user !== "" && passwd !== "" && shell !== "") {
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