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

        //Si el usuario oprime el boton de registrarse
        if(isset($_POST["botonRegistrarse"])){
            //Incluye el archivo php donde se procesan los datos
            include("../php/procesadorRegistro.php"); 
        }
    ?>
    <div class="container">
        <div class="container-form">
            <form action="../forms/formRegistro.php" class="sign-up" id="formularioRegistro" method="POST">
                <h2>Registrarse</h2>
                <a href="../index.php" class="button-regresar"><i class="bi bi-arrow-left-circle"></i> Regresar al inicio</a>
                <span>Ingrese sus datos personales</span>
                <div class="container-input">
                    <i class="bi bi-person"></i>
                    <input type="text" placeholder="Nombre Completo" name="nombreCompleto" id="nombreCompletoRegistro" autocomplete="on">
                </div>
                <p id="errorNombreCompletoRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-card-checklist"></i>
                    <select name="tipoDocumento" id ="tipoDocumentoRegistro">
                        <option name="opcion" value="">Seleccionar su Tipo de Documento</option>
                        <?php 
                            //Mostrar todos los tipos de documentos registrados en la base de datos en el select del formulario de registro
                            $resultadoConsultarTiposDocumento=consultarTiposDocumentosActivos();
                            while($datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoDocumentoRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" placeholder="Número de Documento" name="numDocumento"  id="numeroDocumentoRegistro" autocomplete="off">
                </div>
                <p id="errorNumDocumentoRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-geo-alt"></i>
                    <input type="text" placeholder="Barrio o Localidad" name="barrio" id="barrioRegistro" autocomplete="on">
                </div>
                <p id="errorBarrioRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-envelope"></i>
                    <input type="text" placeholder="Correo Electrónico" name="correoElectronico" id="correoElectronicoRegistro" autocomplete="on">
                </div>
                <p id="errorCorreoElectronicoRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-lock"></i>
                    <input type="password" placeholder="Contraseña" name="contrasena" id="contrasenaRegistro" autocomplete="off">
                </div>
                <p id="errorContrasenaRegistro" class="error-message"></p>

                <div class="container-input">
                    <i class="bi bi-shield-check"></i>
                    <input type="password"
                        placeholder="Confirmar Contraseña"
                        name="confirmarContrasena"
                        id="confirmarContrasenaRegistro"
                        autocomplete="new-password">
                </div>
                <p id="errorConfirmarContrasenaRegistro" class="error-message"></p>

                <div class="container-input-checkbox">
                    <label><input type="checkbox" name="confirmarUsoDatos" id="confirmarUsoDatos"> Acepto el uso y tratamiento de mis datos personales</label>
                </div>
                <p id="errorConfirmarUsoDatos" class="error-message"></p>
                
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
    
    <script src="../js/script_ValidacionFormularios.js" defer></script>
</body>
</html>