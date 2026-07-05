<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        //Recuperar la fecha y hora actual del sistema
        $fechaActual=recuperarFechaActual();

        //Si el usuario oprime el botón de registrarse
        if(isset($_POST["botonRegistrarse"])){
            //Recuperar el número de documento ingresado
            $numDocumento=trim($_POST["numDocumento"]);

            //Consulta para verificar si el número de documento ya se encuentra registrado
            $resultadoVerificarExistenciaUsuario=consultarUsuarioExistente($numDocumento);

            //Si encuentra algún registro en la tabla de usuarios
            if(mysqli_num_rows($resultadoVerificarExistenciaUsuario)>0){
                //Ejecutar mensaje cuando el usuario ya se encuentra registrado
                $_SESSION["alerta"]="usuarioRegistrado";
            }else{
                //Recuperar los datos del usuario registrado en el formulario
                $nombreCompleto=ucwords(strtolower($_POST["nombreCompleto"]));

                $tipoDocumento=trim($_POST["tipoDocumento"]);
                $correo=trim($_POST["correoElectronico"]);
                $contrasena=trim($_POST["contrasena"]);

                $barrio=ucwords(strtolower($_POST["barrio"]));

                //Encriptar la contraseña del usuario
                $contrasena= password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
                
                $resultadoRegistrarUsuario=registrarUsuario($nombreCompleto,$tipoDocumento, $numDocumento, $correo, $contrasena, $barrio, $fechaActual);

                //Si el registro de los datos del usuario fue exitoso en la base de datos
                if($resultadoRegistrarUsuario==true){
                    registrarActividadUsuario("Usuario","Registrar","Usuario nuevo registrado en el sistema", $numDocumento);

                    //Almacenar los datos del usuario en sesiones temporales para procesos de verificación posteriores
                    $_SESSION["nombreUsuario"]=$nombreCompleto;
                    $_SESSION["correoUsuario"]=$correo;
                    $_SESSION["numeroDocumento"]=$numDocumento; 

                    //Ejecutar mensaje de registro exitoso en la base de datos
                    $_SESSION["alerta"]="registroExitoso";
                }else{
                    //Ejecutar mensaje de registro fallido en la base datos
                    $_SESSION["alerta"]="registroFallido";
                }
            } 
        }
        
        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){ 
            switch ($_SESSION["alerta"]){
                case "usuarioRegistrado": ?>
                    <script>
                        //Mensaje cuando la información del usuario se registra en la base de datos
                        mostrarMensaje({
                            title:"¡El número de identificación es inválido!",
                            text:"Queremos informarle que ya tiene una cuenta registrada en BioUrbis con el número de identificación ingresado",
                            footer:"Ingrese un número de identificación válido o <a href='../forms/formAcceso.php' class='redireccionScript'>Intente recuperar su contraseña</a>",
                            icon:"error",
                                                            
                            //Si el usuario acepta registrarse en el aplicativo
                            rutaTrue:"../forms/formRegistro.php",

                            //Si el usuario acepta registrarse en el aplicativo
                            rutaFalse:"../forms/formRegistro.php"
                        })
                    </script>
                    <?php
                break;

                case "registroExitoso": ?>
                    <script>
                        //Mensaje cuando la información del usuario se registra en la base de datos
                        mostrarMensaje({
                            title:"¡Registro exitoso!",
                            text:"Para completar el proceso de registro, lo invitamos a verificar su correo electrónico ingresado",
                            icon:"success", //Agregar iconos de acuerdo al mensaje (warning, info, question, error)
                                 
                            //Si el usuario acepta verificar el correo
                            rutaTrue:"../php/mailVerificarCorreo.php",

                            //Si el usuario no acepta verificar el correo
                            rutaFalse:"../forms/formAcceso.php"
                        });
                    </script>
                    <?php
                break;
                
                case "registroFallido": ?>
                    <script>
                        //Mensaje cuando se produce un error a la hora de registrar al usuario
                        mostrarMensaje({
                            title:"¡Registro fallido!",
                            text:"Ha ocurrido un error al momento de registrar su información",
                            icon:"error", //Agregar iconos de acuerdo al mensaje (warning, info, question, error)
                            footer:"Le solicitamos volver a ingresar sus datos",
                                 
                            //Si surge algún error, se redirreciona al usuario para volver a intentarlo
                            rutaTrue:"../forms/formRegistro.php",

                            //Si surge algún error, se redirreciona al usuario para volver a intentarlo
                            rutaFalse:"../forms/formRegistro.php"
                        });
                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }
    ?> 
</body>
</html>

