<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Semillas</title>
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
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
       <!--  <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.html" class="logo"><image src="../images/img_logotipo.png" width="80px" height="80px" alt="Logotipo de la empresa BioUrbis"></image></a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="../index.php" class="nav-item nav-link">Home</a>
                    <a href="../sobreNosotros.html" class="nav-item nav-link">Sobre Nosotros</a>
                    <a href="catalogoSemillas.php" class="nav-item nav-link active">Semillas</a>
                   
                </div>
                <a href="../forms/formRegistro.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">¡Únete a Nosotros!<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header End -->
        <div class="container-xxl py-5 page-header position-relative mb-5" style=" background-color:rgb(38,41,22);">
            <div class="container py-5">
                <h1 class="display-2 text-white animated slideInDown mb-4">Semillas</h1>
                
            </div>
        </div>
        <!-- Page Header End -->

        <?php
            include ("../functions/funciones.php");//Funciones de la app
            include("../db/conexion.php");
            $resultadoConsultarSemillas=consultarSemillas($conexion_db);//Llamar la función que realiza la consulta de todas las semillas
        ?>

        <!-- Semillas Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nuestras Semillas</h1>
                    <p>Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed rebum vero dolor duo.</p>
                </div>
                <div class="row g-4">
                    <?php 
                        while ($datosSemillas=mysqli_fetch_array($resultadoConsultarSemillas)){
                            ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="classes-item" >
                                    <div class="rounded-circle w-75 mx-auto p-3" style=" background-color:rgb(234,213,166);">
                                        <a class="d-block text-center h3 mt-3 mb-4" href="fichaTecnicaSemillas.php?idSemilla=<?php echo $datosSemillas["idSemilla"]?>" target="_blank"><input type="submit" value="<?php $datosSemillas["idSemilla"]?>" name="idSemilla" id="idSemilla" >
                                            <img class="img-fluid rounded-circle" src="../images/semillas/img_<?php echo $datosSemillas["semImagen"]?>" alt="Imagen de referencia de la semilla <?php echo $datosSemillas["semNombre"]?>">
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
                                <h1 class="mb-4">Solicita tu propia Semilla</h1>
                                <form>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="gname" placeholder="Gurdian Name">
                                                <label for="gname">Nombre completo</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="email" class="form-control border-0" id="gmail" placeholder="Gurdian Email">
                                                <label for="gmail">Correo Electrónico</label>
                                            </div>
                                        </div>    
                                        <div class="col-sm-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control border-0" id="seed" placeholder="Gurdian Seed">
                                                <label for="seed">Semilla</label>
                                            </div>
                                        </div>                                    
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control border-0" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                                <label for="message">Mensaje</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100 py-3" type="submit">Enviar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="position-absolute w-100 h-100 rounded" src="../images/contact.jpg" style="object-fit: cover;">
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
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Santa Lucía</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+57 1233</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>biourbiscompany@gmail.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/groups/freewebsitecode"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/groups/freewebsitecode"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/freewebsitecode"><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/freewebsitecode"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Quick Links</h3>
                        <a class="btn btn-link text-white-50" href="../sobreNosotros.html">Sobre Nosotros</a>
                        <a class="btn btn-link text-white-50" href="">Contacto</a>
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
</body>
</html>