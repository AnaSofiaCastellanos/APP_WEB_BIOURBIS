<?php 
    //Iniciar la sesion para almacenar el estado del formulario
    session_start();
    //Incluir las funciones del aplicativo
    include("../functions/funciones.php");

    //Si la variable global estadoForm no se encuentra inicializada, valor por defecto inicio
    if (!isset($_SESSION['estadoForm'])) {
        $_SESSION['estadoForm'] = 'inicio';
    }

    //Si el usuario oprime el botón para verificar su número de identificación y el estado del formulario es inicio
    if(isset($_POST["botonVerificarId"]) && $_SESSION['estadoForm']==="inicio"){
        //Recuperar la identidad del usuario
        $numeroDocumento=trim($_POST["numDocumento"]);
                        
        //Verificar si el usuario existe
        $resultadoVerificarExistencia=consultarUsuarioExistente($numeroDocumento);

        //Si existe algun usuario registrado
        if(mysqli_num_rows($resultadoVerificarExistencia)>0){
            $datosUsuario=arregloDatos($resultadoVerificarExistencia);

            //Sesión activa del usuario existente
            $_SESSION["numeroDocumento"]=$numeroDocumento;

            if($datosUsuario["usuEstado"]==='Activo'){  
                if($datosUsuario["usuEstadoCorreo"]==='Verificado'){
                    //Enviar correo con el código de verificación para recuperar la contraseña      
                    include("../php/mailRecuperarContrasena.php"); 

                    $_SESSION['estadoForm'] = 'identifacionOK';
                }else{ 
                    //Ejecutar mensaje de que la cuenta no esta verificada
                    $_SESSION["alerta"]="cuentaNoVerificada"; 
                }  
            }else{
                $_SESSION["alerta"]="cuentaInactiva";
            }
        }else{
            //Ejecutar mensaje de usuario no registrado
            $_SESSION["alerta"]="usuarioNoRegistrado"; 
        }       
    }

    //Si el usuario oprime el botón para verificar el código enviado y el estado del formulario es identicacionOK
    if(isset($_POST["botonVerificar"]) && $_SESSION['estadoForm'] ==="identifacionOK"){
        $codVerificacionUsuario=trim($_POST["codVerificacion"]); //Recuperar el código de verificación ingresado por el usuario
        $codVerificacion=$_SESSION["codigoVerificacionC"];//Recuperar el código de verificación generado

        if($codVerificacionUsuario==$codVerificacion){ //Si el código de verificación es correcto 
            $_SESSION["estadoForm"]="codigoOK";
            ?>         
            <?php
        }else{ 
            //Ejecutar mensaje cuando el código de verificación no es correcto
            $_SESSION["alerta"]="codigoIncorrecto";   
            session_destroy(); //Destruir la sesión o existencia del código generado por el sistema
        }
    }

    //Si el usuario oprime el botón para actualizar su contraseña
    if(isset($_POST["botonNuevaContrasena"]) && $_SESSION["estadoForm"]==="codigoOK"){
        $nuevaContrasena=password_hash(trim($_POST["nuevaContrasena"]),PASSWORD_BCRYPT); //Encriptar la contraseña del usuario
        $numeroDocumentoUsuario=$_SESSION["numeroDocumento"];//Recuperar la identificación del usuario

        if(actualizarContrasena($numeroDocumentoUsuario, $nuevaContrasena)){
            //Ejecutar mensaje cuando se actualizó correctamente la contraseña
            $_SESSION["alerta"]="actualizacionContrasenaExitosa"; 

            //Registrar la actividad del usuario
            registrarActividadUsuario("Usuario","Actualizar","Usuario ha recuperado y actualizado su contraseña", $numeroDocumentoUsuario);

            session_destroy(); //Destruir la sesión o existencia del código generado por el sistema
        }else{ 
            $_SESSION["alerta"]="errorConsulta"; 
            session_destroy(); //Destruir la sesión o existencia del código generado por el sistema
        }                                
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_RecuperarContrasena.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <div class="container">
        <table>
            <?php 
                //Mostrar el formulario para ingresar el numero de identificacion del usuario
                if($_SESSION['estadoForm']==="inicio"){ ?>
                    <th>
                        <!--Formulario para recuperar el número de identidad del usuario-->
                        <form action="../forms/formRecuperarContrasena.php" class="sign-in" method="POST" id="formularioRecuperarDocumento">
                            <h2>Recuperar Contraseña</h2>
                            <span style="font-weight:lighter;">Ingrese su número de identificación</span>
                            <div class="container-input">
                                <i class="bi bi-person-vcard"></i>
                                <input type="text" placeholder="Número de Identificación" name="numDocumento" id="numDocumentoRecuperarContrasena">
                            </div>
                            <p id="errorNumDocumentoRecuperarContrasena" class="error-message"></p>
                            
                            <button class="button" name="botonVerificarId" id="botonVerificarId">Verificar identificación</button>
                        </form>
                    </th>
                    <?php
                }
                //Mostrar el formulario para ingresar el codigo de verificacion enviado por el correo
                if($_SESSION['estadoForm'] ==="identifacionOK"){ ?>
                    <!--Formulario para ingresar el código de verifación del correo-->
                    <tr>
                        <td>
                            <form action="formRecuperarContrasena.php" class="sign-in" method="POST" style='background-color:none' id="formularioRecuperarCodigo">
                                <h2>Recuperar Contraseña</h2>
                                <span>Ingrese el código de verificación enviado al correo electrónico registrado</span>
                                <div class="verification-container">
                                    <div class="verification-code">

                                        <input type="text" maxlength="1" class="code-input">
                                        <input type="text" maxlength="1" class="code-input">
                                        <input type="text" maxlength="1" class="code-input">
                                        <input type="text" maxlength="1" class="code-input">

                                    </div>

                                    <input
                                        type="hidden"
                                        name="codVerificacion"
                                        class="verification-hidden"
                                        id="codVerificacionRecuperarContrasena">
                                </div>
                                <p id="errorCodigoVerificacionRecuperarContrasena" class="error-message"></p>
                                
                                <button class="button" name="botonVerificar" id="botonVerificar" >Verificar código</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                //Mostrar el formulario para ingresar la nueva contraseña del usuario
                if($_SESSION["estadoForm"]==="codigoOK"){ ?>
                    <!--Formulario para actualizar la contraseña-->
                    <tr>
                        <td>
                            <form action="formRecuperarContrasena.php" class="sign-in" method="POST" id="formularioNuevaContrasena" style='background-color:none'>
                                <h2>Recuperar Contraseña</h2>
                                <span>Ingrese su nueva contraseña</span>
                                <div class="container-input">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" placeholder="Nueva Contraseña" name="nuevaContrasena" id="nuevaContrasenaRecuperarContrasena">
                                </div>
                                <p id="errorNuevaContrasenaRecuperarContrasena" class="error-message"></p>

                                <button class="button" name="botonNuevaContrasena" id="botonNuevaContrasena">Actualizar Contraseña</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </table>             
    </div> 
    <?php 
        //Ejecutar cada uno de los scripts según la alerta que se encuentre almacenada en la sesión
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) {
                case 'cuentaNoVerificada': ?>
                    <script>
                        //Mensaje cuando la cuenta del usuario no se encuentra verificada e intenta recuperar la contraseña
                        mostrarMensaje({
                            title:"¡Su cuenta no se encuentra verificada!",
                            text:"Para garantizar la seguridad de su cuenta, necesitamos verificar su dirección de correo electrónico",
                            html: "<a href='../php/mailVerificarCorreo.php' class='redireccionScript'>Haz clic aquí para completar el proceso</a>",
                            icon:"info",
                                                            
                            //Si el usuario acepta verificar su cuenta
                            rutaTrue:"../php/mailVerificarCorreo.php",

                            //Si el usuario acepta registrarse en el aplicativo
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;

                case 'usuarioNoRegistrado': ?>
                    <script>
                        //Mensaje cuando el número de documento no se encuentra registrado en la base de datos
                        mostrarMensaje({
                            title:"¡Usuario no encontrado!",
                            text:"El número de identificación ingresado no se encuentra en nuestro sistema",
                            footer:"Ingrese un número de identificación válido o <a href='../forms/formRegistro.php' class='redireccionScript'>Lo invitamos a registrarse</a>",
                            icon:"error",
                                                            
                            //Si el usuario acepta ingresar un número de identificación válido
                            rutaTrue:"../forms/formRecuperarContrasena.php",

                            //Si el usuario acepta registrarse en el aplicativo
                            rutaFalse:"../forms/formRegistro.php"
                        })
                    </script>
                    <?php
                break;

                case 'codigoIncorrecto': ?>
                    <script>
                        //Mensaje cuando el código de verificación no corresponde a la hora de recuperar la contraseña
                        mostrarMensaje({
                            title:"¡Código de verificación incorrecto!",
                            text:"Verifique que el código sea el correcto y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario acepta ingresar otra vez un código de verificación
                            rutaTrue:"../forms/formRecuperarContrasena.php",

                            //Si el usuario no acepta ingresar otra vez un código de verificación
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;

                case 'actualizacionContrasenaExitosa': ?>
                    <script>
                        //Mensaje cuando la contraseña del usuario se actulizó correctamente
                        mostrarMensaje({
                            title:"¡Restablecimiento de su contraseña exitosa!",
                            text:"Queremos informarle que la actualización de sus datos en BioUrbis has sido completado con éxito",
                            html: "<p>Lo invitamos a acceder a su cuenta y disfrutar de nuestros servicios</p>",
                            icon:"success",

                            //Si el usuario acepta ingresar sus nuevas credenciales para acceder a su cuenta
                            rutaTrue:"../forms/formAcceso.php",

                            //Si el usuario no acepta ingresar sus nuevas credenciales para acceder a su cuenta
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;

                case 'errorConsulta': ?>
                    <script>
                        //Mensaje cuando surge un error en alguna consulta
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a recuperar su contraseña
                            rutaTrue:"../forms/formRecuperarContrasena.php",

                            //Si el usuario no acepta volver a recuperar su contraseña
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;

                case "errorAlEnviarCorreo": ?>
                    <script>
                        mostrarMensaje({

                            title:"¡Error a la hora de enviar el correo electrónico!",

                            text:"Recargue la página y vuelva a intentarlo",

                            icon:"error",

                            rutaTrue:"../forms/formRecuperarContrasena.php",

                            rutaFalse:"../forms/formRecuperarContrasena.php"

                        });
                    </script>
                    <?php
                break;

                case "cuentaInactiva": ?>
                    <script>
                        //Mensaje cuando la cuenta del usuario se encuentra inactiva
                        mostrarMensaje({
                            title:"¡Su cuenta se encuentra inactiva!",
                            text:"Lo invitamos a comunicarse con un administrador para comenzar su proceso de reactivación",
                            icon:"error",
                                                            
                            //Si el usuario acepta enviar la solicitud
                            rutaTrue:"../index.php",

                            //Si el usuario no acepta enviar la solicitud
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }
    ?>  
    <script src="../js/script_ValidacionFormularios.js" defer></script>
    <script src="../js/script_ValidacionCodigos.js" defer></script>
</body>
</html>

                       