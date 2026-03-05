<?php
    //Elimina cualquier dato de sesión almacenado anteriormente
    session_start();
    session_destroy(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_RegistroAutenticacion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="container-form">
            <div class="container-welcome-sign-up">
                <img src="../images/img_logotipo.png" class="imagen-fondo2" alt="Logo de la empresa BioUrbis">
                <div class="welcome-sign-up welcome">
                    <h3>¡Bienvenido!</h3>
                    <p>Registre sus datos personales para usar todas las funciones que ofrece BioUrbis</p>
                    <button class="button-welcome-sign-up"><a  href="../forms/formRegistro.php">Registrarse</a></button>

                </div>
            </div>
        </div>
        <div class="container-form">
            <form action="../forms/formAcceso.php" class="sign-in" method="POST">
                <h2>Iniciar Sesión</h2>
                <div class="social-networks">
                    <i class="bi bi-google"></i>
                    <i class="bi bi-instagram"></i>
                </div>
                <span>Use su número de documento y contraseña</span>
                <div class="container-input">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" placeholder="Número de documento" name="numDocumento" required>
                </div>
                <div class="container-input">
                    <i class="bi bi-lock"></i>
                    <input type="password" placeholder="Contraseña" name="contrasena" required>
                </div>
                <a href="formRecuperarContrasena.php" class='olvidasteContrasena'>¿Olvidaste tu contraseña?</a>
                <button class="button" name="botonIniciarSesion" id="botonIniciarSesion">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <?php 
        //Incluye el archivo php donde se procesan los datos
        if(isset($_POST["botonIniciarSesion"])){
            include("../php/procesadorAcceso.php");
        } 
    ?>
</body>
</html>