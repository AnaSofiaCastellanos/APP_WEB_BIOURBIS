<?php 
    session_start();
    //Sesion donde se almacenan las alertas de los mensajes emergentes
    $_SESSION["alerta"]="";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Nuestras Semillas | BioUrbis</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Gestion de jardineras" name="keywords">
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

    <!-- Ejecución de Pantallas Emergentes-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php
        //Incluir las funciones de la app
        include ("../functions/funciones.php");

        //Llamar la función que realiza la consulta de todas las semillas
        $resultadoConsultarSemillas=consultarSemillasActivasConFichaYEtapa(false);

        //Si el usuario oprime el boton para enviar la solicitud
        if(isset($_POST["enviarSolicitud"])){
            //Recuperar los datos del formulario
            $nombre = ucwords(strtolower(trim($_POST["username"])));
            $correo=trim($_POST["email"]);
            $semilla = ucwords(strtolower(trim($_POST["seed"])));
            $mensaje=ucfirst(strtolower(trim($_POST["message"])));

            //Recuperar el tipo de solicitud enviada
            $tipoSolicitud=$_POST["enviarSolicitud"];

            //Consulta para verificar si la semilla ya se encuentra registrada en el sistema
            $resultadoConsultarExistenciaSemilla=consultarExistenciaSemillaPorNombre($semilla);

            //Evalua si existe algun registro con ese nombre
            if(mysqli_num_rows($resultadoConsultarExistenciaSemilla)>0){
                //Alerta para informar que la semilla ya se encuentra registrada en el sistema
                $_SESSION["alerta"]="semillaExistente";

            }else{
                if(registrarSolicitud($tipoSolicitud, $mensaje)){
                    $_SESSION["alerta"]="solicitudRegistrada";

                    //Enviar correo electrónico para confirmar su solicitud 
                    include("mailConfirmacionSolicitud.php");

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Solicitud","Crear", "Registró una nueva solicitud", null); 
                }else{
                    $_SESSION["alerta"]="errorConsulta";
                }
            }
        }
    ?>
    <div class="container-fluid bg-white p-0">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="../index.php" class="logo"><image src="../images/img_logotipo.png" width="80px" height="80px"></image></a>
            <a href="../index.php" class="logo"><span class="nombre-logo">BioUrbis</span></a>
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
        <!-- Navbar End -->

        <!-- Page Header End -->
        <div class="container-xxl py-5 page-header position-relative mb-5" style=" background-color:rgb(38,41,22);">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Nuestras Semillas</h1>
                
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Semillas Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Aquí encontrará...</h1>
                    <p>
                        Un catálogo de semillas cuidadosamente seleccionadas, ideales para jardineras caseras. 
                    </p>
                </div>
                <div class="row g-4">
                    <?php 
                        //Mostrar todas las semillas registradas en la base de datos
                        while ($datosSemillas=mysqli_fetch_assoc($resultadoConsultarSemillas)){
                            ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="classes-item" >
                                    <div class="rounded-circle w-75 mx-auto p-3" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
                                            <img class="img-fluid2 rounded-circle" src="../<?php echo $datosSemillas["semImagen"]?>" alt="Imagen de referencia de la semilla <?php echo $datosSemillas["semNombre"]?>">
                                        </a>
                                    </div>
                                    <div class="rounded p-4 pt-5 mt-n5" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
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

         <!-- Solicitudes Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="rounded" style=" background-color:rgb(234,213,166);">
                    <div class="row g-0">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="h-100 d-flex flex-column justify-content-center p-5">
                                <h1 class="mb-4">Solicite su propia semilla</h1>
                                <form action="catalogoSemillas.php" method="POST" id="formularioSolicitarSemilla">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="gname" name="username" placeholder="Gurdian Name">
                                                <label for="gname">Nombre completo</label>
                                            </div>
                                            <p id="errorNombreCompletoSolicitarSemilla" class="error-message"></p>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control border-0" id="gmail" name="email" placeholder="Gurdian Email">
                                                <label for="gmail">Correo Electrónico</label>
                                            </div>
                                            <p id="errorCorreoSolicitarSemilla" class="error-message"></p>
                                        </div>    

                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="seed" name="seed" placeholder="Gurdian Seed">
                                                <label for="seed">Semilla</label>
                                            </div>
                                            <p id="errorSemillaSolicitarSemilla" class="error-message"></p>
                                        </div> 

                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control border-0" placeholder="Leave a message here" id="message"  name="message" style="height: 100px"></textarea>
                                                <label for="message">Mensaje</label>
                                            </div>
                                            <p id="errorMensajeSolicitarSemilla" class="error-message"></p>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100 py-3" type="submit" name="enviarSolicitud" value="Admisión Nueva Semilla">Enviar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100 rounded" src="../images/img_solicitarSemilla.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Solicitudes End -->

        <!-- Footer Start -->
        <div class="container-fluid text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style=" background-color:rgb(38,41,22);">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Contacto</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Bogotá, DC</p>
                        <p class="mb-2 text-nowrap"><i class="fa fa-envelope me-3"></i>biourbiscompany@gmail.com</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Más información</h3>
                        <a class="btn btn-link text-white-50" href="../sobreNosotros.html">Sobre Nosotros</a>
                        <a class="btn btn-link text-white-50" href="mailto:biourbiscompany@gmail.com">Contáctanos</a>
                    </div> 
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
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

    <?php      
        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) {
                case 'errorConsulta': ?>
                    <script>
                        //Mensaje cuando surge un error a la hora de registrar la reseña del usuario
                        mostrarMensaje({
                            title:"¡Error a la hora de enviar su solicitud!",
                            text:"Recarge la página y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario acepta volver a enviar la solicitud
                            rutaTrue:"catalogoSemillas.php",

                            //Si el usuario no acepta volver a enviar la solicitud
                            rutaFalse:"catalogoSemillas.php"
                        })
                    </script>
                    <?php
                break;

                case 'semillaExistente': ?>
                    <script>
                        //Mensaje cuando surge el usuario solicita una semilla que ya existe
                        mostrarMensaje({
                            title:"¡Semilla ya registrada!",
                            text:"La semilla que intenta solicitar ya se encuentra en nuestro sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar una nueva solicitud
                            rutaTrue:"catalogoSemillas.php",

                            //Si el usuario no acepta volver a enviar una nueva solicitud
                            rutaFalse:"catalogoSemillas.php"
                        })
                    </script>
                    <?php
                break;

                case 'solicitudRegistrada': ?>
                    <script>
                        mostrarMensaje({
                            title: "¡Solicitud registrada!",
                            text: "La solicitud de la nueva semilla fue enviada correctamente",
                            icon: "success",
                            footer:"Una vez sea revisada y aprobada por el administrador, estará disponible en el sistema",

                            showCancelButton: false,

                            rutaTrue: "catalogoSemillas.php",
                            rutaFalse: "catalogoSemillas.php"
                        });
                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }
    ?>
    <script src="../js/script_ValidacionFormularios.js" defer></script>
</body>
</html>