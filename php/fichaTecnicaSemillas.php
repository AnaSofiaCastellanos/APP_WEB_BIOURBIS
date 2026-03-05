<?php
  session_start();

  //Incluir las funciones de la app
  include ("../functions/funciones.php");
  //Incluir la conexión a la base de datos
  include("../db/conexion.php");

  //Recuperar el id de la semilla en la url
  $idSemilla=$_GET["idSemilla"];
  //Consultar si la semilla existe
  $existeSemilla=consultarExistenciaSemilla($idSemilla, $conexion_db);

  //Evalúa el resultado de la consulta, si existe la semilla
  if($existeSemilla){
    //Arreglo con los datos basicos de la semilla
    $datosSemilla=consultarDatosSemilla($idSemilla, $conexion_db);
    $idEtapaCrecimiento=$datosSemilla["idEtapaCrecimiento"];

    //Arreglo con los datos de la ficha técnica de la semilla
    $datosFichaTecnica=consultarFichaTecnicaSemilla($idSemilla, $conexion_db);

    //Arreglo con los datos de la etapa de crecimiento de la semilla
    $datosEtapaCrecimiento=consultarEtapaCrecimientoSemilla($idEtapaCrecimiento, $conexion_db);

  }else{ 
    $_SESSION["alerta"]="errorConsulta";
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ficha Técnica de <?php echo $datosSemilla["semNombre"]?></title>
  <!--Logotipo pestaña-->
  <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/style_FichaTecnicaSemillas.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
  <div class="container">
    <header>
      <h1>Ficha Técnica de <?php echo $datosSemilla["semNombre"]?></h1>
    </header>

    <main class="content">
      <div class="left-panel">
        <div class="image-box">
          <img src="../images/semillas/img_<?php echo $datosSemilla["semImagen"]?>" alt="Imagen de referencia de la semilla <?php echo $datosSemilla["semNombre"]?>">
        </div>
      </div>

      <div class="right-panel">
        <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Nombre</strong></label>
            <div><?php echo $datosSemilla["semNombre"]?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Tipo de Semilla</strong></label>
            <div><?php echo $datosSemilla["idTipoSemilla"]?></div>
          </div>
        </div>

        <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Tipo de Tierra</strong></label>
            <div><?php echo $datosFichaTecnica["idTipoTierra"]?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Cantidad de Tierra (kg)</strong></label>
            <div><?php echo $datosFichaTecnica["fichaCantidadTierraMin"],"-",$datosFichaTecnica["fichaCantidadTierraMax"],"kg"?></div>
          </div>
          <div class="circle"></div>
          <div class="field">
            <label><strong>Espacio aprox (m2)</strong></label>
            <div><?php echo $datosFichaTecnica["fichaEspacio"], "m2"?></div>
          </div>
        </div>

        <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Clima</strong></label>
            <div><?php echo $datosFichaTecnica["idTipoClima"]?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Temperatura (ºC)</strong></label>
            <div><?php echo $datosFichaTecnica["fichaTemperaturaMin"],"-",$datosFichaTecnica["fichaTemperaturaMax"],"ºC" ?></div>
          </div> 
        </div>

        <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Humedad (%)</strong></label>
            <div><?php echo $datosFichaTecnica["fichaHumedadMin"],"%","-",$datosFichaTecnica["fichaHumedadMax"],"%"?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Cantidad de Agua (ml)</strong></label>
            <div><?php echo $datosFichaTecnica["fichaCantidadAguaMin"],"-",$datosFichaTecnica["fichaCantidadAguaMax"],"ml"?></div>
          </div>
        </div>

        <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Germinación (días)</strong></label>
            <div><?php echo $datosEtapaCrecimiento["etapaCreDiasGerminacionMin"],"-",$datosEtapaCrecimiento["etapaCreDiasGerminacionMax"]," días"?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Desarrollo Vegetativo (días)</strong></label>
            <div><?php echo $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"],"-",$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"]," días"?></div>
          </div>
        </div>

        <div class="row">
          
          <div class="circle"></div>
          <div class="field">
            <label><strong>Floración (días)</strong></label>
            <div><?php echo $datosEtapaCrecimiento["etapaCreDiasFloracionMin"],"-",$datosEtapaCrecimiento["etapaCreDiasFloracionMax"]," días" ?></div>
          </div>

          <div class="circle"></div>
          <div class="field">
            <label><strong>Llenado de granos (días)</strong></label>
            <div><?php echo $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"],"-",$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"]," días" ?></div>
          </div>
        </div>

         <div class="row">

          <div class="circle"></div>
          <div class="field">
            <label><strong>Cosecha (días)</strong></label>
            <div><?php echo $datosEtapaCrecimiento["etapaCreDiasCosechaMin"],"-",$datosEtapaCrecimiento["etapaCreDiasCosechaMax"]," días" ?></div>
          </div>
        </div>
      </div>
    </main>

    <div class="notes">
      <textarea placeholder="Escribe observaciones aquí..."></textarea>
    </div>
  </div>
  <?php 
    //Ejecutar mensajes emergentes
    if(isset($_SESSION["alerta"])){
      switch ($_SESSION["alerta"]) {
        case 'errorConsulta': ?>
          <script>
            //Mensaje cuando surge un error en alguna consulta
            mostrarMensaje({
              title:"¡Ha ocurrido un error inesperado!",
              text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
              icon:"error",

              showCancelButton: false, 

              //Si el usuario acepta volver a cargar la página
              rutaTrue:"../php/fichaTecnicaSemillas.php",

              //Si el usuario no acepta volver a cargar la página
              rutaFalse:"../php/fichaTecnicaSemillas.php"
            })
          </script>
          <?php
        break;
      }
      unset($_SESSION["alerta"]);
    }
  ?>
</body>
</html>