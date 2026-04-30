<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_RegistroAutenticacion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php 
        //Iniciar la sesion 
        session_start();

        //Incluir las funciones de la app
        include("../functions/funciones.php");

        //Abrir la conexion a la base de datos
        $conexion_db=abrirConexionDB();

        //Consultar todos los tipos de documento
        $resultadoConsultarTiposDocumento=consultarTiposDocumentos();

        //Si el usuario oprime el boton de registrarse
        if(isset($_POST["botonRegistrarse"])){
            //Incluye el archivo php donde se procesan los datos
            include("../php/procesadorRegistro.php"); 
        }
    ?>
    <div class="container">
        <div class="container-form">
            <form action="../forms/formRegistro.php" class="sign-up" method="POST">
                <h2>Registrarse</h2>
                <a href="../index.php" class="button-regresar"><i class="bi bi-arrow-left-circle"></i> Regresar al inicio</a>
                <span>Ingrese sus datos personales</span>
                <div class="container-input">
                    <i class="bi bi-person"></i>
                    <input type="text" placeholder="Nombre Completo" name="nombreCompleto" required>
                </div>
                <div class="container-input">
                    <i class="bi bi-card-checklist"></i>
                    <select name="tipoDocumento" required>
                        <option name="opcion">Seleccionar su Tipo de Documento</option>
                        <?php 
                            //Mostrar todos los tipos de documentos registrados en la base de datos en el select del formulario de registro
                            while($datosTipoDocumento=mysqli_fetch_array($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <div class="container-input">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" placeholder="Número de Documento" name="numDocumento" autocomplete="off" required>
                </div>
                <div class="container-input">
                    <i class="bi bi-geo-alt"></i>
                    <input type="text" placeholder="Barrio o Localidad" name="barrio" required>
                </div>
                <div class="container-input">
                    <i class="bi bi-envelope"></i>
                    <input type="email" placeholder="Correo Electrónico" name="correoElectronico" required>
                </div>
                <div class="container-input">
                    <i class="bi bi-lock"></i>
                    <input type="password" placeholder="Contraseña" name="contrasena" autocomplete="off" required>
                </div>
                <div class="container-input-checkbox">
                    <label><input type="checkbox" name="confirmarUsoDatos" required> Acepto el uso y tratamiento de mis datos personales</label>
                </div>
                <button class="button" name="botonRegistrarse" id="botonRegistrarse">Registrarse</button>
            </form>
        </div>
        <div class="container-form">
            <div class="container-welcome-sign-in">
                <img src="../images/img_logotipo.png" class="imagen-fondo" alt="Logotipo de la empresa BioUrbis">
                <div class="welcome-sign-in welcome">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Ingrese sus datos personales para acceder a su cuenta en BioUrbis</p>
                    <button class="button"><a  href="../forms/formAcceso.php">Iniciar Sesión</a></button>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>