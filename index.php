<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Inicio | BioUrbis</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="images/img_logotipo.png" type="image/x-icon">
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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style_Index.css" rel="stylesheet">

    <!-- Ejecución de Pantallas Emergentes-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        session_start();
        $_SESSION["alerta"]="";
        //Incluir las funciones de la app
        include("functions/funciones.php");
        
        //Abrir la conexion a la base de datos
        $conexion_db=abrirConexionDB();

        //Recuperar la fecha y hora actual del sistema
        $fechaActual=recuperarFechaActual();

        //Si el usuario oprime el botón de enviar la reseña
        if(isset($_POST["enviarResena"])){
            //Recuperar la reseña del usuario
            $mensajeUsuario = ucfirst(strtolower(trim($_POST["message"])));

            //Si el usuario inicio sesión en el sistema
            if(isset($_SESSION["numeroDocumento"])){
                $usuarioActivo=$_SESSION["numeroDocumento"];

                //Consultar los datos del usuario registrado
                $datosUsuario=consultarDatosUsuario($usuarioActivo);
                $nombreUsuario=$datosUsuario["usuNombre"];
                $correoUsuario=$datosUsuario["usuCorreo"];

                $resultadoRegistrarResena=registrarResena($fechaActual, $nombreUsuario, $correoUsuario, $mensajeUsuario, $usuarioActivo, true);

                if($resultadoRegistrarResena){
                    registrarActividadUsuario("Reseña","Crear", "Registró una nueva reseña", $usuarioActivo); 
                }
            }else{
                //Si el usuario no inicio sesión en el sistema
                $nombreUsuario=$_POST["name"];
                if($nombreUsuario!=""){
                    $nombreUsuario=$nombreUsuario;
                }else{
                    $nombreUsuario="Invitado";
                }
                $correoUsuario=trim($_POST["email"]);

               $resultadoRegistrarResena=registrarResena($fechaActual, $nombreUsuario, $correoUsuario, $mensajeUsuario, null, false);
                if($resultadoRegistrarResena){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Reseña","Crear", "Registró una nueva reseña", null); 
                }   
            }

            if($resultadoRegistrarResena){  
                $_SESSION["alerta"]='resenaRegistrada';

                require_once "functions/enviarCorreos.php";

                $enviado = enviarCorreo( 
                    $correoUsuario,
                    $nombreUsuario,
                    "BioUrbis - Reseña publicada con éxito",
                    correoResenaPublicada(
                        $nombreUsuario,
                        $mensajeUsuario
                    )
                );

                if(!$enviado){
                    $_SESSION["alerta"]="errorAlEnviarCorreoResena";
                } ?>
                <!--Redirección a la pagina inicial del proyecto -->
                <script> window.location.replace("index.php");</script>
                <?php 
            }else{
                $_SESSION["alerta"]="errorConsulta";
            }
        }  
    ?>
<div class="container-fluid bg-white p-0">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.php" class="logo"><image src="images/img_logotipo.png" width="80px" height="80px"></image></a>
            <a href="../index.php" class="logo"><span class="nombre-logo">BioUrbis</span></a>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link" style="font-family: 'Montserrat', sans-serif;">Inicio</a>
                    <a href="sobreNosotros.html" class="nav-item nav-link" style="font-family: 'Montserrat', sans-serif;">Sobre Nosotros</a>
                    <a href="php/catalogoSemillas.php" class="nav-item nav-link" style="font-family: 'Montserrat', sans-serif;">Nuestras Semillas</a>
                </div>
                <a href="forms/formRegistro.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block" style="font-family: 'Montserrat', sans-serif;">¡Únete a Nosotros!<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5">
            <div class="owl-carousel header-carousel position-relative">

                <!-- SLIDE 1 -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images/img_fondo1.jpg" alt="BioUrbis plataforma">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .5);">
                        <div class="container">
                            <div class="col-lg-8">
                                <span class="badge bg-success mb-3 fs-6">BioUrbis</span>
                                <h1 class="display-2 text-white mb-4">
                                    ¿Qué es BioUrbis?
                                </h1>
                                <p class="text-white mb-3">
                                    Es una plataforma web que facilita la gestión de huertos urbanos, 
                                    ayudando a cultivar de forma organizada, sostenible e inteligente.
                                </p>
                                <div class="mb-4">
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Gestión de cultivos</p>
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Recomendaciones útiles</p>
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Uso educativo y comunitario</p>
                                </div>
                                <a href="sobreNosotros.html" class="btn btn-primary rounded-pill px-4 py-2 me-2">
                                    Saber más
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 2 -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images/img_fondo2.jpg" alt="Huerto urbano">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .5);">
                        <div class="container">
                            <div class="col-lg-8">
                                <span class="badge bg-success mb-3 fs-6">Agricultura Urbana</span>
                                <h1 class="display-2 text-white mb-4">
                                    Cultive su propio espacio verde
                                </h1>
                                <p class="text-white fs-5 mb-4">
                                    Gestione sus jardineras de forma fácil e inteligente con BioUrbis.
                                </p>
                                <div class="mb-4">
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Control de cultivos</p>
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Alertas de riego</p>
                                    <p class="text-white"><i class="fa fa-check-circle text-success me-2"></i>Fichas técnicas</p>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>


                <!-- SLIDE 3 -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images/img_fondo3.jpg" alt="Cultivos en casa">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .5);">
                        <div class="container">
                            <div class="col-lg-8">
                                <span class="badge bg-warning mb-3 fs-6">Funcionalidades</span>
                                <h1 class="display-2 text-white mb-4">
                                    Todo lo que puede hacer
                                </h1>
                                <div class="mb-4">
                                    <p class="text-white"><i class="fa fa-leaf text-success me-2"></i>Registrar y gestionar cultivos</p>
                                    <p class="text-white"><i class="fa fa-seedling text-success me-2"></i>Explorar catálogo de semillas</p>
                                    <p class="text-white"><i class="fa fa-bell text-success me-2"></i>Alertas de riego y cuidado</p>
                                    <p class="text-white"><i class="fa fa-book text-success me-2"></i>Fichas técnicas detalladas</p>
                                    <p class="text-white"><i class="fa fa-users text-success me-2"></i>Uso educativo y comunitario</p>
                                    <p class="text-white"><i class="fa fa-globe text-success me-2"></i>Promoción de sostenibilidad</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 4 -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images/img_fondo4.jpg" alt="Aprendizaje">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .5);">
                        <div class="container">
                            <div class="col-lg-8">
                                <span class="badge bg-info mb-3 fs-6">Aprendizaje</span>
                                <h1 class="display-2 text-white mb-4">
                                    Aprenda sobre cada cultivo
                                </h1>

                                <p class="text-white fs-5 mb-4">
                                    Consulte fichas técnicas detalladas y mejore sus resultados.
                                </p>

                                <a href="php/catalogoSemillas.php" class="btn btn-primary rounded-pill px-4 py-2 me-2">Ver semillas</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 5 -->
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images/img_fondo5.jpg" alt="Comunidad">

                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center"
                        style="background: rgba(0, 0, 0, .5);">

                        <div class="container">
                            <div class="col-lg-8">
                                <span class="badge bg-primary mb-3 fs-6">Comunidad</span>

                                <h1 class="display-2 text-white mb-4">
                                    Conecte con otros cultivadores
                                </h1>

                                <p class="text-white fs-5 mb-4">
                                    Comparta experiencias y mejore su huerto con ayuda de la comunidad.
                                </p>

                                <a href="forms/formRegistro.php" class="btn btn-primary rounded-pill px-4 py-2 me-2">Únete</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Carousel End -->

        <!-- Semillas Start --> 
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Algunas de Nuestras Semillas</h1>
                    <p style="font-family: 'Montserrat', sans-serif;">
                        Aquí encontrará algunas semillas que se encuentran en la plataforma 
                        y podrá ver toda su información 
                    </p>
                </div>
                <div class="row g-4">
                    <?php 
                        //Consultar las semillas con su informacion completa
                        $resultadoConsultarSemillas=consultarSemillasActivasConFichaYEtapa(true);
                        while ($datosSemillas=mysqli_fetch_assoc($resultadoConsultarSemillas)){
                            ?>
                            <div class="col-lg-3 col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="classes-item" >
                                    <div class="rounded-circle w-75 mx-auto p-3" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="../php/fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
                                            <img class="img-fluid rounded-circle" src="<?php echo $datosSemillas["semImagen"]?>" alt="Imagen Semilla">
                                        </a>
                                    </div>
                                    <div class="rounded p-4 pt-5 mt-n5" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="../php/fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
                                        <?php echo $datosSemillas["semNombre"];?></a>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="d-flex align-items-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                </div>      
            </div>
        </div>
        <!-- Semillas End -->

        <!-- Reseñas Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="rounded" style="background-color: rgb(234,213,166);">
                    <div class="row g-0">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="h-100 d-flex flex-column justify-content-center p-5">
                                <h1 class="mb-4">¿Tiene algún comentario?</h1>
                                <form action="index.php" method="POST" id="formularioEnviarResena" autocomplete="on">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="nameEnviarResena" placeholder="Gurdian Name" name="name">
                                                <label for="gname">Nombre </label>
                                            </div>
                                            <p id="errorNombreCompletoEnviarResena" class="error-message"></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="gmailEnviarResena" placeholder="Gurdian Email" name="email">
                                                <label for="gmail">Correo Electrónico</label>
                                            </div>
                                            <p id="errorCorreoEnviarResena" class="error-message"></p>
                                        </div>    
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control border-0" placeholder="Escriba su mensaje aquí" id="messageEnviarResena" style="height: 100px" name="message"></textarea>
                                                <label for="message">Mensaje</label>
                                            </div>
                                            <p id="errorMensajeEnviarResena" class="error-message"></p>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100 py-3" type="submit" name="enviarResena" id="enviarResena">Enviar Comentario</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100 rounded" src="images\img_resena.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="contenedor-resenas">
            <h2 class="titulo-resenas">Reseñas</h2>

            <div class="lista-resenas">
                <?php   
                    $resultadoConsultarResenas=consultarResenasIndex();
                    while($resenas=mysqli_fetch_assoc($resultadoConsultarResenas)){
                ?>
                    <div class="card-resena">
                        <div class="resena-header">
                            <span class="nombre"><?php echo $resenas["resenaNombreUsuario"]; ?></span>
                            <span class="fecha"><?php echo $resenas["resenaFecha"]; ?></span>
                        </div>
                        <?php if(!empty($resenas["resenaCorreo"])) { ?>
                            <span class="email"><?php echo $resenas["resenaCorreo"]; ?></span>
                        <?php } ?>
                        <p class="mensaje">
                            <?php echo $resenas["resenaDescripcion"]; ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Reseñas End -->

        <!-- Footer Start -->
        <div class="container-fluid text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Contacto</h3>
                        <p class="mb-2" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-map-marker-alt me-3"></i>Santa Lucía</p>
                        <p class="mb-2" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-phone-alt me-3"></i>+57 123</p>
                        <p class="mb-2 text-nowrap" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-envelope me-3"></i>biourbiscompany@gmail.com</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4" >Información rápida</h3>
                        <a class="btn btn-link text-white-50" href="sobreNosotros.html" style="font-family: 'Montserrat', sans-serif;">Sobre Nosotros</a>
                        <a class="btn btn-link text-white-50" href="" style="font-family: 'Montserrat', sans-serif;">Contacto</a>
                    </div>      
                </div>
            </div>
        </div>
        <!-- Footer End -->
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <?php 
        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) {
                case 'resenaRegistrada': ?>
                    <script>
                        mostrarMensaje({
                            title: "¡Reseña registrada!",
                            text: "Su comentario de la plataforma BioUrbis fue publicado correctamente",
                            icon: "success",
                            footer:"Gracias por compartir su opinión",

                            showCancelButton: false,

                            rutaTrue: "catalogoSemillas.php",
                            rutaFalse: "catalogoSemillas.php"
                        });
                    </script>
                    <?php
                break;

                case 'errorConsulta': ?>
                    <script>
                        //Mensaje cuando surge un error a la hora de registrar la reseña del usuario
                        mostrarMensaje({
                            title:"¡Error a la hora de publicar su reseña!",
                            text:"Recarge la página y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario acepta volver a ingresar sus credenciales
                            rutaTrue:"index.php",

                            //Si el usuario no acepta volver a ingresar sus credenciales
                            rutaFalse:"index.php"
                        })
                    </script>
                    <?php
                break;

                case "correoInvalido": ?>
                    <script>
                        //Mensaje cuando el correo no es valido para registrar la reseña del usuario
                        mostrarMensaje({
                            title:"¡Correo electrónico inválido!",
                            text:"Ingrese una dirección de correo electrónico válida e intente de nuevo",
                            icon:"error",
                                                            
                            //Si el usuario acepta volver a registrar una reseña
                            rutaTrue:"index.php",

                            //Si el usuario no acepta volver a registrar una reseña
                            rutaFalse:"index.php"
                        })
                    </script>
                    <?php
                break;

                case "errorAlEnviarCorreoResena": ?>
                    <script>

                        mostrarMensaje({

                            title:"¡Error a la hora de enviar el correo electrónico!",

                            text:"Recargue la página y vuelva a intentarlo",

                            icon:"error",

                            rutaTrue:"../index.php",

                            rutaFalse:"../index.php"

                        });

                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }
    ?>
    <script src="../js/scripts_HomeUsuario.js"></script>
    <script src="../js/script_ValidacionFormularios.js" defer></script>
</body>
</html>