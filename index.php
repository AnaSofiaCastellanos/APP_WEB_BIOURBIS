<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>BioUrbis-Gestión de Huertos</title>
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
</head>
<body>
    <?php 
        session_start();

        //Incluir la conexión a la base de datos
        include("db/conexion.php");
        //Incluir las funciones de la app
        include("functions/funciones.php");

        //Recuperar la fecha y hora actual del sistema
        date_default_timezone_set('America/Bogota');
        $fechaActual=date('Y-m-d');

        //Si el usuario oprime el botón de enviar la reseña
        if(isset($_POST["enviarResena"])){
            $mensajeUsuario=$_POST["message"];

            //Si el usuario inicio sesión en el sistema
            if(isset($_SESSION["numeroDocumento"])){
                $usuarioActivo=$_SESSION["numeroDocumento"];

                //Consultar los datos del usuario registrado
                $datosUsuario=consultarDatosUsuario($usuarioActivo, $conexion_db);
                $nombreUsuario=$datosUsuario["usuNombre"];
                $correoUsuario=$datosUsuario["usuCorreo"];

                //Registrar la reseña del usuario con su identificación
                $queryRegistrarResena="INSERT INTO resena (resenaFecha, resenaNombreUsuario, resenaCorreo, resenaDescripcion, usuNumeroDocumento) 
                VALUES('$fechaActual', '$nombreUsuario' ,'$correoUsuario', '$mensajeUsuario', $usuarioActivo)";
                $resultadoRegistrarResena=mysqli_query($conexion_db, $queryRegistrarResena);
            }else{
                //Si el usuario no inicio sesión en el sistema
                $nombreUsuario=$_POST["name"];
                if($nombreUsuario!=""){
                    $nombreUsuario=$nombreUsuario;
                }else{
                    $nombreUsuario="Invitado";
                }
                $correoUsuario=$_POST["email"];

                //Registrar la reseña del usuario sin su identificación
                $queryRegistrarResena="INSERT INTO resena (resenaFecha, resenaNombreUsuario, resenaCorreo, resenaDescripcion) 
                VALUES('$fechaActual','$nombreUsuario', '$correoUsuario', '$mensajeUsuario')";
                $resultadoRegistrarResena=mysqli_query($conexion_db, $queryRegistrarResena);
            }

            if($resultadoRegistrarResena){  
                //Enviar correo electrónico para confirmar su reseña 
                include("php/mailConfirmacionResena.php");
/*                 ?>
                <!--Redirección a la pagina inicial del proyecto -->
                <script> window.location.replace("index.php");</script>
                <?php */
            }else{
                $_SESSION["alerta"]="errorConsulta";
            }
        }  
    ?>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.html" class="logo"><image src="images/img_logotipo.png" width="80px" height="80px"></image></a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link active" style="font-family: 'Montserrat', sans-serif;">Home</a>
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
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images\tree2.jpg" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(0, 0, 0, .2);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-2 text-white animated slideInDown mb-4">¿Qué es BioUrbis?</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">
                                        BioUrbis es una plataforma web diseñada para facilitar la gestion de huertos urbanos. 
                                        Permite registrar cultivos, recibir recomendaciones y generar alertas. 
                                        Esta pensada para comunidades, familias, instituciones educativas y agricultores urbanos que deseen cultivar de forma sostenible y educativa</p>
                                    <a href="" class="btn btn-primary rounded-pill py-sm-3 px-sm-5 me-3 animated slideInLeft" style="font-family: 'Montserrat', sans-serif;">Saber más</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="images\tree.jpg" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(0, 0, 0, .2);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-2 text-white animated slideInDown mb-4">¿Qué puedes hacer en la plataforma?</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2" > <ul>
                                        <li class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">Registrar tus huertos y cultivos</li>
                                        <li class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">Elegir semillas de un catálago predefinido</li>
                                        <li class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">Recibir alertas sobre riego,cosecha y cuidados de cada cultivo</li>
                                        <li class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">Consultar fichas técnicas de cada semilla</li>
                                        <li class="fs-5 fw-medium text-white mb-4 pb-2" style="font-family: 'Montserrat', sans-serif;">Enviar reportes o solicitudes para mejorar su experiencia</li>
                                    </ul> </p>
                                    <a href="" class="btn btn-primary rounded-pill py-sm-3 px-sm-5 me-3 animated slideInLeft" style="font-family: 'Montserrat', sans-serif;">Saber más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->
        <?php
            $queryConsultarSemillas="SELECT * FROM semilla LIMIT 6";
            $resultadoConsultarSemillas=mysqli_query($conexion_db, $queryConsultarSemillas);
        ?>

        <!-- Semillas Start --> 
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Algunas de Nuestras Semillas</h1>
                    <p style="font-family: 'Montserrat', sans-serif;">
                        Aquí encontrarás algunas semillas que se encuentran en la plataforma 
                        y podrás ver toda su información 
                    </p>
                </div>
                <div class="row g-4">
                    <?php 
                        while ($datosSemillas=mysqli_fetch_array($resultadoConsultarSemillas)){
                            ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="classes-item" >
                                    <div class="rounded-circle w-75 mx-auto p-3" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="../php/fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
                                            <img class="img-fluid rounded-circle" src="../images/semillas/img_<?php echo $datosSemillas["semImagen"]?>" alt="">
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
                <div class="bg-light rounded">
                    <div class="row g-0">
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="h-100 d-flex flex-column justify-content-center p-5">
                                <h1 class="mb-4">¿Tienes algún comentario?</h1>
                                <form action="index.php" method="POST">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="name" placeholder="Gurdian Name" name="name">
                                                <label for="gname">Nombre </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control border-0" id="gmail" placeholder="Gurdian Email" name="email" required>
                                                <label for="gmail">Correo Electrónico</label>
                                            </div>
                                        </div>    
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control border-0" placeholder="Leave a message here" id="message" style="height: 100px" name="message" required></textarea>
                                                <label for="message">Mensaje</label>
                                            </div>
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
                                <img class="position-absolute w-100 h-100 rounded" src="images\contact.jpg" style="object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $queryConsultarResenas="SELECT * FROM resena LIMIT 5"; 
            $resultadoConsultarResenas=mysqli_query($conexion_db, $queryConsultarResenas);

            while($resenas=mysqli_fetch_array($resultadoConsultarResenas)){
                echo "Nombre: ", $resenas["resenaNombreUsuario"];
                echo "Fecha: ", $resenas["resenaFecha"];
                echo "Mensaje: ", $resenas["resenaDescripcion"];
            }
        ?>
        <!-- Reseñas End -->

        <!-- Footer Start -->
        <div class="container-fluid text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Contacto</h3>
                        <p class="mb-2" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-map-marker-alt me-3"></i>Santa Lucía</p>
                        <p class="mb-2" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-phone-alt me-3"></i>+57 123</p>
                        <p class="mb-2" style="font-family: 'Montserrat', sans-serif;"><i class="fa fa-envelope me-3"></i>biourbiscompany@gmail.com</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4" >Información rápida</h3>
                        <a class="btn btn-link text-white-50" href="about.html" style="font-family: 'Montserrat', sans-serif;">Sobre Nosotros</a>
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
            }
            unset($_SESSION["alerta"]);
        }
    ?>
</body>

</html>