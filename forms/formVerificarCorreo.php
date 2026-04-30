<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Cuenta | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_RegistroAutenticacion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        //Activar la sesion para recuperar el código de verificación generado por el sistema y el número de documento del usuario registrado
        session_start();
        //Incluir las funciones del aplicativo
        include("../functions/funciones.php");
        
        //Recuperar código de verificación generado por el sistema
        $codVerificacion=$_SESSION["codigoVerificacion"];

        //Recuperar número de documento del usuario registrado
        $numeroDocumento=$_SESSION["numeroDocumento"];
        
        //Si el usuario oprimer el botón de verificar el código recibido
        if(isset($_POST["botonVerificar"])){
            //Recuperar el código de verificación ingresado por el usuario
            $codVerificacionUsuario=$_POST["codVerificacion"];

            //Si el codigo de verificacion es igual al generado por el sistema
            if($codVerificacionUsuario==$codVerificacion){ 

                //Abrir la conexión a la base de datos     
                $conexion_db=abrirConexionDB();

                //Consulta para actualizar el estado del correo a Verificado
                $queryVerificarCorreo="UPDATE usuario SET usuEstadoCorreo='Verificado' WHERE usuNumeroDocumento='$numeroDocumento'";
                $resultadoVerificarCorreo=mysqli_query($conexion_db, $queryVerificarCorreo);

                if($resultadoVerificarCorreo==True){
                    //Ejecutar mensaje cuando se actualizó el estado del correo
                    $_SESSION["alerta"]="verificacionExitosa";

                    //Destruir la sesión o existencia del código generado por el sistema
                    session_destroy(); 
                }            
            }else{   
                //Ejecutar mensaje cuando el código de verificación no es correcto
                $_SESSION["alerta"]="codigoIncorrecto";                
            }
        }
    ?>
    <div class="container-Verificar-Correo">
        <div class="container-form">
            <form action="../forms/formVerificarCorreo.php" class="sign-in" method="POST">
                <h2>Verificar Cuenta</h2>
                <span>Revise e ingrese el código de verificación enviado al correo electrónico registrado</span>
                <div class="container-input">
                    <i class="bi bi-send-check"></i>
                    <input type="text" placeholder="Código de Verificación" name="codVerificacion" required>
                </div>
                <button class="button" name="botonVerificar" id="botonVerificar">Verificar código</button>
            </form>
        </div>
    </div>
    <?php 
        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) { 
                case 'verificacionExitosa': ?>
                    <script>
                        //Mensaje cuando el correo del usuario fue verificado correctamente
                        mostrarMensaje({
                            title:"¡Verificación exitosa!",
                            text:"Queremos informarte que su registro en BioUrbis ha sido completado con éxito",
                            html: "<p>Lo invitamos a acceder a su cuenta y disfrutar de nuestros servicios</p>",
                            icon:"success",
                                                            
                            //Si el usuario acepta acceder a su cuenta
                            rutaTrue:"../forms/formAcceso.php",

                            //Si el usuario no acepta acceder a su cuenta
                            rutaFalse:"../index.php"
                        })
                    </script>
                    <?php
                break;

                case 'codigoIncorrecto': ?>
                    <script>
                        //Mensaje cuando el código de verificación no corresponde a la hora de verificar su correo
                        mostrarMensaje({
                            title:"¡Código de verificación incorrecto!",
                            text:"Verifique que el código sea el correcto y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario aceptar ingresar otro código de verificación
                            rutaTrue:"../forms/formVerificarCorreo.php",

                            //Si el usuario no acepta ingresar otra vez un código de verificación
                            rutaFalse:"../forms/formAcceso.php"
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