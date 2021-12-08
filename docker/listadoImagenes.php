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
        <th scope="col">Opciones</th>
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
                <td>{$values[4]}</td>".
                '<td class="opt">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16" title="Ver detalle imagen" data-toggle="modal" data-target="#modal-detalle" onclick="ver_detalle(\''.$values[2].'\')">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16" onclick="eliminar_imagen(\''.$values[2].'\')">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </td>'.
                "</tr>
                ";

            }
        ?>
    </tbody>
    </table>
</body>

<!-- Detalle imagen -->
<div class="modal fade" id="modal-detalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle de imagen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" >
            <pre id="detalle">
                Cargando...
            </pre>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<script>
 function ver_detalle(id){
    $.ajax({
    url: 'docker/consultas/detalleImagen.php',
    data: {
        "id" : id
    },
    success: function(data){
        data = JSON.parse(data)[0]
        console.log(data);
        const elem = document.getElementById('detalle');
        elem.innerHTML = JSON.stringify(data, undefined, 2);  
    
    },
    });
 }

 function eliminar_imagen(id){
       
    Swal.fire({
    title: 'Eliminar imagen',
    text: "¿Esta seguro que desea eliminar es imagen?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
    }).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
        url: 'docker/consultas/eliminarImagen.php',
        data: {
            "id" : id
        },
        success: function(data){
            data = JSON.parse(data)
            console.log(data);
            if (data.status) {
                $("#docker-images").click()
                Swal.fire(
                'Eliminado!',
                'La imagen ha sido eliminadda',
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
</script>

<style>
    .opt{
        display: flex;
        gap: 15px;
    }
    .opt svg:hover{
        cursor: pointer;
    }
</style>