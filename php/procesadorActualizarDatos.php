<?php 
    session_start();
    //Recuperar el número de documento del usuario activo
    $usuarioActivo=$_SESSION["numeroDocumento"];
    //Recuperar el tipo de usuario que va a actualizar su perfil
    $origenArchivo=$_SESSION["origenActualizacion"];
    $_SESSION["alerta"]="";

    //Evaluar el usuario y crear su respectiva ruta
    if($origenArchivo=="admin"){
        $ruta="../php/homeAdmin.php";
    }else if($origenArchivo=="usuario"){
        $ruta="../php/homeUsuario.php";
    }

    //Incluir las funciones de la app
    include("../functions/funciones.php");

    //Abrir la conexion a la base de datos
    $conexion_db=abrirConexionDB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Datos | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_RegistroAutenticacion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
<?php
    //Si el usuario o el administrador oprime el botón de actualizar datos
    if(isset($_POST["guardarCambiosBtn"]) || isset($_POST["actualizarPerfilAdminBtn"])){
        //Consultar los datos del usuario
        $datosUsuario=consultarDatosUsuario($usuarioActivo);

        //Verificar si el correo del usuario se encuentra verificado
        if($datosUsuario["usuEstadoCorreo"]==="Verificado"){
            if(isset($_POST["guardarCambiosBtn"])){
                //Recuperar los datos del usuario registrados en el formulario
                $nombreCompleto = ucwords(strtolower(trim($_POST["name"])));
                $tipoDocumento=trim($_POST["editTypeIdProfile"]);
                $correo=trim($_POST["email"]);
                $barrio = ucwords(strtolower(trim($_POST["location"])));
                $contrasena=trim($_POST["password"]);

            }else if(isset($_POST["actualizarPerfilAdminBtn"])){
                //Recuperar los datos del administrador registrados en el formulario
                $nombreCompleto = ucwords(strtolower(trim($_POST["editNameAdmin"])));
                $tipoDocumento=trim($_POST["editTypeIdProfileAdmin"]);
                $correo=trim($_POST["editEmailAdmin"]);
                $barrio = ucwords(strtolower(trim($_POST["editLocationAdmin"])));
                $contrasena=trim($_POST["editPasswordAdmin"]);
            }
            //Actualizar el nombre del usuario
            $_SESSION["nuevoNombreCompleto"] = ($nombreCompleto!="") ? $nombreCompleto : $datosUsuario["usuNombre"];

            //Actualizar el tipo de documento del usuario
            $descripcionTipoDoc=$datosUsuario["idTipoDocumento"];

            $queryConsultarIdTipoDoc="SELECT * FROM tipo_documento WHERE tipoDocDescripcion='$descripcionTipoDoc'";
            $resultadoQuery=mysqli_query($conexion_db, $queryConsultarIdTipoDoc);

            if(mysqli_num_rows($resultadoQuery)>0){
                $datosTipoDoc=mysqli_fetch_assoc($resultadoQuery);
                $_SESSION["nuevoTipoDocumento"]=$datosTipoDoc["idTipoDocumento"];
            };

            //Actualizar el correo del usuario
            if($correo!=""){
                $_SESSION["nuevoCorreo"]=$correo;
                $_SESSION["nuevoEstadoCorreo"]="No verificado";
            }else{
                $_SESSION["nuevoCorreo"]=$datosUsuario["usuCorreo"];
                $_SESSION["nuevoEstadoCorreo"]=$datosUsuario["usuEstadoCorreo"];
            }

            //Actualizar el barrio o localidad del usuario
            $_SESSION["nuevoBarrio"] = ($barrio!="") ? $barrio : $datosUsuario["usuBarrio"];
            
            //Actualizar la contraseña del usuario
            $_SESSION["nuevaContrasena"]  = ($contrasena!="") ? password_hash($contrasena, PASSWORD_DEFAULT) : $datosUsuario["usuContrasena"];
            
            //Enviar correo electrónico para verificar la identidad del usuario
            include("../php/mailActualizarDatos.php");
        }else{ 
            //Ejecutar mensaje de que la cuenta del usuario no se encuentra verificada
            $_SESSION["alerta"]="cuentaNoVerificada";
        }    
    }
    if(
        isset(
            $_SESSION["codigoVerificacion"],
            $_SESSION["nuevoNombreCompleto"],
            $_SESSION["nuevoTipoDocumento"],
            $_SESSION["nuevoCorreo"],
            $_SESSION["nuevoEstadoCorreo"],
            $_SESSION["nuevoBarrio"],
            $_SESSION["nuevaContrasena"]
        )
    ){
        //Recuperar código de verificación generado por el sistema
        $codVerificacion=$_SESSION["codigoVerificacion"] ?? "";

        //Recuperar el valor de las sesiones creadas con los datos actualizados
        $nuevoNombreCompleto=$_SESSION["nuevoNombreCompleto"] ?? "";
        $nuevoTipoDocumento=$_SESSION["nuevoTipoDocumento"] ?? "";
        $nuevoCorreo=$_SESSION["nuevoCorreo"] ?? "";
        $nuevoEstadoCorreo=$_SESSION["nuevoEstadoCorreo"] ?? "";
        $nuevoBarrio=$_SESSION["nuevoBarrio"] ?? "";
        $nuevaContrasena=$_SESSION["nuevaContrasena"] ?? "";
        
    }

    //Si el usuario oprime el botón de verificar código
    if(isset($_POST["botonVerificar"])){
        //Recuperar el código de verificación ingresado por el usuario
        $codVerificacionUsuario=$_POST["codVerificacion"]; 

        if($codVerificacionUsuario==$codVerificacion){
            //Actualizar la información en la base de datos 
            $queryActualizarDatos="UPDATE usuario SET usuNombre='$nuevoNombreCompleto', idTipoDocumento='$nuevoTipoDocumento',
            usuCorreo='$nuevoCorreo', usuEstadoCorreo='$nuevoEstadoCorreo', usuContrasena='$nuevaContrasena', usuBarrio='$nuevoBarrio' WHERE usuNumeroDocumento='$usuarioActivo'";
            $resultadoActualizarDatos=mysqli_query($conexion_db, $queryActualizarDatos); 

            if($resultadoActualizarDatos==true){ 
                //Ejecutar mensaje informativo solo si el usuario actualizó su correo
                if($nuevoEstadoCorreo!="Verificado"){
                    $_SESSION["alerta"]="correoNoVerificado";
                }
                //Ejecutar mensaje de que los datos se actualizaron correctamente
                $_SESSION["alerta"]="datosActualizadosCorrectamente";
                
                //Registrar la actividad del usuario
                registrarActividadUsuario("Perfil","Actualizar", "Actualizó su información personal", $usuarioActivo); 
            }else{ 
                //Ejecutar mensaje de que hubo un error durante la consulta
                $_SESSION["alerta"]="errorConsulta";
                session_destroy(); //Destruir la sesión o existencia del código generado por el sistema      
            } 
        }else{ 
            //Ejecutar mensaje cuando el código de verificación no coincide
            $_SESSION["alerta"]="codigoIncorrecto";       
        } 
    }
    ?>
    <!--Formulario para verificar si el código de verificación ingresado es correcto-->
    <div class="container-Verificar-Correo">
        <div class="container-form">
            <form action="../php/procesadorActualizarDatos.php" class="sign-in" method="POST" >
                <h2 style='margin-left:50px'>Confirmar actualización de datos</h2>
                <span>Revise e ingrese el código de verificación enviado al correo electrónico registrado</span>
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
                        id="codVerificacion">
                </div>
                <button class="button" name="botonVerificar" id="botonVerificar">Verificar código</button>
            </form>
        </div>
    </div>
    <?php 
        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) {
                case 'correoNoVerificado': ?>
                    <script>
                        //Mensaje cuando el código de verificación no corresponde a la hora de validar la actualización de los datos del usuario
                        mostrarMensaje({
                            title:"¡Su correo electrónico ha sido actualizado correctamente !",
                            text:"Debido a esta modificación, le solicitamos verificar su nuevo correo para velar por la seguridad de su cuenta",
                            icon:"sucess",
                                                            
                            //Si el usuario acepta verificar su nuevo correo electrónico
                            rutaTrue:"../php/mailVerificarCorreo.php",

                           //Si el usuario no acepta verificar su nuevo correo electrónico
                            rutaFalse:"<?php echo $ruta; ?>"
                        })
                    </script>
                    <?php
                break;
                
                case 'cuentaNoVerificada': ?>
                    <script>
                        //Mensaje cuando la cuenta del usuario no se encuentra verificada desde el perfil
                        mostrarMensaje({
                            title:"¡La cuenta no se encuentra verificada!",
                            text:"Para garantizar la seguridad de su cuenta, necesitamos verificar su dirección de correo electrónico.",
                            html: "<a href='../php/mailVerificarCorreo.php' class='redireccionScript'>Haga clic aquí para completar el proceso</a>",
                            icon:"info",     

                            //Si el usuario acepta verificar su correo electrónico
                            rutaTrue:"../php/mailVerificarCorreo.php",

                            //Si el usuario no acepta verificar su correo electrónico, vuelva su perfil
                            rutaFalse:"<?php echo $ruta; ?>"
                        })
                    </script>
                    <?php
                break;

                case 'datosActualizadosCorrectamente': ?>
                    <script>
                        //Mensaje cuando los datos del usuario han sido actualizados correctamente
                        mostrarMensaje({
                            title:"¡Actualización exitosa!",
                            text:"Sus datos han sido actualizados correctamente", 
                            icon:"success",
                            showCancelButton: false,
                            showCloseButton: false,

                            //Si el usuario acepta el mensaje de éxito
                            rutaTrue:"<?php echo $ruta; ?>",

                            //Si el usuario no acepta el mensaje de éxito
                            rutaFalse:"<?php echo $ruta; ?>"
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

                            //Si el usuario acepta volver a actualizar sus datos
                            rutaTrue:"../php/procesadorActualizarDatos.php",

                            //Si el usuario no acepta volver a actualizar sus datos
                            rutaFalse:"<?php echo $ruta; ?>"
                        })
                    </script>
                    <?php
                break;

                case 'codigoIncorrecto': ?>
                    <script>
                        //Mensaje cuando el código de verificación no corresponde a la hora de validar la actualización de los datos del usuario
                        mostrarMensaje({
                            title:"¡Código de verificación incorrecto!",
                            text:"Verifique que el código sea el correcto y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario acepta ingresar otra vez un código de verificación
                            rutaTrue:"../php/procesadorActualizarDatos.php",

                            //Si el usuario no acepta ingresar otra vez un código de verificación
                            rutaFalse:"<?php echo $ruta; ?>"
                        })
                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }     
    ?>
    <script src="../js/script_ValidacionCodigos.js" defer></script>
</body>
</html>