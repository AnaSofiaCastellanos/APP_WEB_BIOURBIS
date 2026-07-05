<?php
  session_start();
  //Incluir las funciones de la app
  include ("../functions/funciones.php");

  //Recuperar el id de la semilla en la url
  $idSemilla=$_GET["idSemilla"];

  //Evalúa el resultado de la consulta, si existe la semilla
  if(consultarExistenciaSemilla($idSemilla)){
    //Arreglo con los datos basicos de la semilla
    $datosSemilla=consultarDatosSemilla($idSemilla);

    if($datosSemilla["idEtapaCrecimiento"]!="0"){
        //Arreglo con los datos de la etapa de crecimiento de la semilla
        $datosEtapaCrecimiento=consultarDatosEtapaCrecimiento($datosSemilla["idEtapaCrecimiento"]);
    }else{
        $_SESSION["alerta"]="semillaSinEtapaCrecimiento";
    }

    if($datosSemilla["idFicha"]!="0"){
        //Arreglo con los datos de la ficha técnica de la semilla
        $datosFichaTecnica=consultarFichaTecnicaSemilla($idSemilla);
    }else{
        $_SESSION["alerta"]="semillaSinFicha";
    }
  }else{ 
    $_SESSION["alerta"]="errorConsulta";
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Técnica <?php echo $datosSemilla["semNombre"]?> | BioUrbis</title>
    <link rel="stylesheet" href="../css/style_FichaTecnicaSemillas.css">
    <link href="css/style_Index.css" rel="stylesheet">
        <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style_Index.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light fixed-top px-4 px-lg-5 py-lg-0">
        <a href="../index.php" class="logo"><image src="../images/img_logotipo.png" width="80px" height="80px"></image></a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto">
                <a href="../index.php" class="nav-item nav-link">Inicio</a>
                <a href="../sobreNosotros.html" class="nav-item nav-link">Sobre Nosotros</a>
                <a href="catalogoSemillas.php" class="nav-item nav-link active">Nuestras Semillas</a> 
            </div>
            <a href="../forms/formRegistro.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">¡Únete a Nosotros!<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <div class="ficha-container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Ficha Técnica <?php echo $datosSemilla["semNombre"]?></h1>
        </div>

        <!-- Contenido con imagen en esquina -->
        <div class="content">
            <!-- Imagen en esquina superior derecha -->
              <div class="image-corner">
                    <img src="../<?php echo $datosSemilla["semImagen"]?>" 
                    alt="Imagen de referencia de la semilla <?php echo $datosSemilla["semNombre"]?>">
              </div>

            <!-- Datos en dos columnas -->
            <div class="data-section">
                <div class="data-column">
                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Nombre</div>
                            <div class="data-value"><?php echo $datosSemilla["semNombre"]?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Tipo de semilla</div>
                            <div class="data-value"> <?php echo $datosSemilla["tipoSemDescripcion"]?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Tipo de tierra</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["tipoTierraDescripcion"]?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Cantidad de tierra</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["fichaCantidadTierraMin"],"-",$datosFichaTecnica["fichaCantidadTierraMax"],"kg"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Espacio aproximado</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["fichaEspacio"], "m2"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Clima</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["tipoClimaDescripcion"]?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Temperatura</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["fichaTemperaturaMin"],"-",$datosFichaTecnica["fichaTemperaturaMax"],"ºC" ?></div>
                        </div>
                    </div>
                </div>

                <div class="data-column">
                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Húmedad</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["fichaHumedadMin"],"%","-",$datosFichaTecnica["fichaHumedadMax"],"%"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Cantidad de agua</div>
                            <div class="data-value"><?php echo $datosFichaTecnica["fichaCantidadAguaMin"],"-",$datosFichaTecnica["fichaCantidadAguaMax"],"ml"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Germinación</div>
                            <div class="data-value"><?php echo $datosEtapaCrecimiento["etapaCreDiasGerminacionMin"],"-",$datosEtapaCrecimiento["etapaCreDiasGerminacionMax"]," días"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Desarrollo vegetativo</div>
                            <div class="data-value"><?php echo $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"],"-",$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"]," días"?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Floración</div>
                            <div class="data-value"><?php echo $datosEtapaCrecimiento["etapaCreDiasFloracionMin"],"-",$datosEtapaCrecimiento["etapaCreDiasFloracionMax"]," días" ?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Llenado de granos</div>
                            <div class="data-value"><?php echo $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"],"-",$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"]," días" ?></div>
                        </div>
                    </div>

                    <div class="data-row">
                        <div class="icon"></div>
                        <div>
                            <div class="data-label">Cosecha</div>
                            <div class="data-value"><?php echo $datosEtapaCrecimiento["etapaCreDiasCosechaMin"],"-",$datosEtapaCrecimiento["etapaCreDiasCosechaMax"]," días" ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de observaciones -->
        <div class="observaciones-section">
            <div class="observaciones-box">
                <?php 
                    $texto = $datosSemilla['semObservaciones'];
                    /* Separar por saltos de línea */
                    $items = explode("\n", $texto);
                    echo "<ul>";
                    
                    foreach($items as $item){
                        // Evita líneas vacías
                        if(trim($item) != ""){
                            echo "<li>" . trim($item) . "</li>";
                        }
                    }
                    echo "</ul>";
                ?>
            </div>
        </div>
    </div>
  <?php 
    //Ejecutar mensajes emergentes
    if(isset($_SESSION["alerta"])){
        switch ($_SESSION["alerta"]) {
            //Mensaje emergente cuando existe algún error en la consulta
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
            //Mensaje emergente cuando una semilla no tiene registrada una etapa de crecimiento
            case "semillaSinEtapaCrecimiento": ?>
                <script>
                    mostrarMensaje({
                        title: "¡Etapa de crecimiento no encontrada!",
                        text: "La semilla seleccionada no tiene registrada una etapa de crecimiento todavía ",
                        icon: "warning",
                        footer: "Si el problema persiste, contacte a un administrador",

                        showCancelButton: false,

                        rutaTrue: "../php/fichaTecnicaSemillas.php",
                        rutaFalse: "../php/fichaTecnicaSemillas.php"
                    })
                </script>
                <?php
            break;

            //Mensaje emergente cuando una semilla no tiene registrada una ficha tecnica
            case "semillaSinFicha": ?>
                <script>
                    mostrarMensaje({
                        title: "¡Ficha técnica no encontrada!",
                        text: "La semilla seleccionada no tiene una ficha técnica registrada todavía|",
                        icon: "warning",
                        footer: "Si el problema persiste, contacte a un administrador",

                        showCancelButton: false,

                        rutaTrue: "../php/fichaTecnicaSemillas.php",
                        rutaFalse: "../php/fichaTecnicaSemillas.php"
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