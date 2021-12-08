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
    <button type="button" class="btn btn-primary" style="margin:10px;" data-toggle="modal" data-target="#exampleModal">Crear contenedor</button>
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
        <th scope="col">Opciones</th>
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
                <td>{$values[6]}</td>".
                '<td class="opt">
                <svg xmlns="http://www.w3.org/2000/svg" width="22"  fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16" onclick="iniciar(\''.$values[0].'\')">
                <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="22"  fill="currentColor" class="bi bi-stop-circle-fill" viewBox="0 0 16 16" onclick="detener(\''.$values[0].'\')">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5h-3z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="22"  fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16" onclick="eliminar(\''.$values[0].'\')">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                </svg>
                </td>'.
                "</tr>";

            }
        ?>
    </tbody>
    </table>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Crear contenedor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="crear-contenedor">
            <div class="form-group">
                <label for="exampleInputEmail1">Nombre contenedor</label>
                <input type="text" class="form-control" name="nombre" >
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Asignación de puertos</label>
                <div style="display:flex;gap:10px;align-items: center;">
                    <div class="form-group">
                        <label for="">Puerto host</label>
                        <input type="text" class="form-control" name="p_host" > 
                    </div>
                    :
                    <div class="form-group">
                        <label for="">Puerto contenedor</label>
                        <input type="text" class="form-control" name="p_contenedor" > 
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Imagen</label>
                <input type="text" class="form-control" name="imagen" >
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Opciones adicionales</label>
                <textarea class="form-control" name="opts" rows="3"></textarea>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="crear()">Crear</button>
        </div>
        </div>
    </div>
    </div>

</body>

<script>
    function iniciar(id){
       Swal.fire({
       title: 'Iniciar contenedor',
       text: "¿Desea iniciar esta contenedor?",
       icon: 'question',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Sí, iniciar',
       cancelButtonText: 'Cancelar'
       }).then((result) => {
       if (result.isConfirmed) {
           $.ajax({
           url: 'docker/consultas/iniciarContenedor.php',
           data: {
               "id" : id
           },
           timeout: 0,
           success: function(data){
               data = JSON.parse(data)
               console.log(data);
               if (data.status) {
                   $("#docker-containers").click()
                   Swal.fire(
                   'Iniciado!',
                   'El contenedor se ha iniciado',
                   'success'
                   )
               }else{
                   Swal.fire(
                   'Error!',
                   data.message,
                   'error'
                   )
               }
           },
           beforeSend: function(data){
               $('#spinner_loader').addClass('show')
           },
           complete: function(data){
            $('#spinner_loader').removeClass('show')
           }
           });
       
       }
       })
    }
   

    function detener(id){
        Swal.fire({
       title: 'Detener contenedor',
       text: "¿Desea detener esta contenedor?",
       icon: 'question',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Sí, detener',
       cancelButtonText: 'Cancelar'
       }).then((result) => {
       if (result.isConfirmed) {
           $.ajax({
           url: 'docker/consultas/detenerContenedor.php',
           data: {
               "id" : id
           },
           timeout: 0,
           success: function(data){
               data = JSON.parse(data)
               console.log("deterner ...",data);
               if (data.status) {
                   $("#docker-containers").click()
                   Swal.fire(
                   'Detenido!',
                   'El contenedor se ha detenio',
                   'success'
                   )
               }else{
                   Swal.fire(
                   'Error!',
                   data.message,
                   'error'
                   )
               }
           },
           beforeSend: function(data){
               $('#spinner_loader').addClass('show')
           },
           complete: function(data){
            $("#docker-containers").click()
            $('#spinner_loader').removeClass('show')
           },
           error: function(jqXHR, textStatus, errorThrown){
               console.log(jqXHR,textStatus,errorThrown);
           }
           });
       
       }
       })
    }
    function eliminar(id){
          
       Swal.fire({
       title: 'Eliminar contenedor',
       text: "¿Esta seguro que desea eliminar este contenedor?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Sí, eliminar',
       cancelButtonText: 'Cancelar'
       }).then((result) => {
       if (result.isConfirmed) {
           $.ajax({
           url: 'docker/consultas/eliminarContenedor.php',
           data: {
               "id" : id
           },
           timeout: 0,
           success: function(data){
               data = JSON.parse(data)
               console.log(data);
               if (data.status) {
                $("#docker-containers").click()
                   Swal.fire(
                   'Eliminado!',
                   'El contenedor ha sido eliminadda',
                   'success'
                   )
               }else{
                   Swal.fire(
                   'Error!',
                   data.message,
                   'error'
                   )
               }
           },
           beforeSend: function(data){
               $('#spinner_loader').addClass('show')
           },
           complete: function(data){
            $('#spinner_loader').removeClass('show')
           },
           });
       
       }
       })
    }

    function crear(){

        $.ajax({
            type: "POST",
              url: 'docker/consultas/crearContenedor.php',
              data: $('#crear-contenedor').serialize(),
              timeout: 0,
              success: function(data){
                  data = JSON.parse(data)
                  console.log(data);
                  if (data.status) {
                   $("#docker-containers").click()
                   Swal.fire(
                   'Creado!',
                   'El contenedor ha sido creado',
                   'success'
                   )
               }else{
                   Swal.fire(
                   'Error!',
                   data.message,
                   'error'
                   )
               }
              },
              beforeSend: function(data){
                  $('#spinner_loader').addClass('show')
              },
              complete: function(data){
                $("#docker-containers").click()
               $('#spinner_loader').removeClass('show')
              },
              });
       }
    //crear-contenedor

   </script>
   

<style>
    .opt{
        display: flex;
        gap: 26px;
        flex-wrap: wrap;
    }
    .opt svg:hover{
        cursor: pointer;
    }
</style>