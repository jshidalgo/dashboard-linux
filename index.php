<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- script de graficas -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <!-- estilos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <!-- scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  
  <title>Control</title>
</head>
<body>
<!-- barra de navegacion -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" id="home" style="cursor: pointer;">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
        <div class="navbar-nav">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Monitorización
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" id='usoRam'>Uso RAM</a>
            <!-- <a class="dropdown-item" href="#" id='disco'>Capacidad disco</a> -->
            <a class="dropdown-item" href="#" id='cpu'>Uso CPU</a>
            <a class="dropdown-item" href="#" id='top-procesos'>Top-procesos</a>
          </div>
          
        </div>
      </li>
      <li class="nav-item dropdown">
        <div class="navbar-nav">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Dockers
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" id='docker-images'>Listado de imagenes</a>
            <a class="dropdown-item" href="#" id='docker-containers'>Listado de contenedores</a>
            <a class="dropdown-item" href="#" id='docker-stats'>Monitoreo de contenedores</a>
          </div>         
        </div>
      </li>
      </ul>
    </div>
  </nav>

  <div id="contenido">

  </div>
</body>
<!-- escript para cargar las graficas -->
<script>
  //variable de refresh
  var refreshId = null;
  var refresh = 30000;//30 segundos

  $('#contenido').load('/home.php');

  //permite cargar el home
  $(document).ready(function() {
    $("#home").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/home.php');
    });
  });

  //permite obtener y actualizar cada x tiempo en contenido del uso de la ram
  $(document).ready(function() {
    $("#usoRam").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/monitorizacion/ram.php');
      refreshId =  setInterval( function(){
        $('#contenido').load('/monitorizacion/ram.php');
      }, refresh )
    });
  });
  //permite obtener y actualizar cada x tiempo en contenido
  $(document).ready(function() {
        $("#cpu").click(function(event) {
            clearInterval(refreshId);
            $('#contenido').load('/monitorizacion/cpu.php');
            refreshId =  setInterval( function(){
                $('#contenido').load('/monitorizacion/cpu.php');
            }, refresh )
        });
    });
    $(document).ready(function() {
        $("#top-procesos").click(function(event) {
            clearInterval(refreshId);
            $('#contenido').load('/monitorizacion/top_process.php');
            refreshId =  setInterval( function(){
                $('#contenido').load('/monitorizacion/top_process.php');
            }, refresh )
        });
    });
  
  //permite cargar en contenido del volumen del disco
  $(document).ready(function() {
    $("#disco").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/monitorizacion/disco.php');
    });
  });

  //permite ver el listado de imagenes docker en el sistema
  $(document).ready(function() {
    $("#docker-images").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/docker/listadoImagenes.php');
    });
  });

  //listado de contenedores
  $(document).ready(function() {
    $("#docker-containers").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/docker/listadoContenedores.php');
    });
  });

  //permite monitorizar el consumo de los contenedores
  $(document).ready(function() {
    $("#docker-stats").click(function(event) {
      clearInterval(refreshId);
      $('#contenido').load('/docker/MonitoreoContenedores.php');
      refreshId =  setInterval( function(){
        $('#contenido').load('/docker/MonitoreoContenedores.php');
      }, refresh )
    });
  });
</script>
</html>