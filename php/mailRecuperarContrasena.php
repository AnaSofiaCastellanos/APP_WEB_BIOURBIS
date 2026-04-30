<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Verificar Recuperación Contraseña | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php
        //Import PHPMailer classes into the global namespace
        //These must be at the top of your script, not inside a function
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        
        //Evaluar si ya se creó un código de verificación antes
        if (isset($_POST["codigoVerificacionC"])){ 
            //Ejecutar mensaje de que ya se envio un codigo de vericacion
            $_SESSION["alerta"]="codigoExistente";

        }else{
            //Si la función random_int existe
            if(function_exists("random_int")){
                $codVerificacionC=random_int(1000,9999);
            }else{
                $codVerificacionC=mt_rand(1000,9999);
            }
            
            //Asignarle el codigo de verificacion aleatorio a la sesión activa
            $_SESSION["codigoVerificacionC"]=$codVerificacionC;

            //Recuperar la identificación del usuario
            $numeroDocumento=$_SESSION["numeroDocumento"];

            //Llamar a la función para consultar los datos de usuario registrado
            $datosUsuario=consultarDatosUsuario($numeroDocumento);
            
            $nombreUsuario=$datosUsuario["usuNombre"];
            $correoUsuario=$datosUsuario["usuCorreo"];
        
            require '../lib/phpMailer/Exception.php';
            require '../lib/phpMailer/PHPMailer.php';
            require '../lib/phpMailer/SMTP.php';
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'biourbiscompany@gmail.com';                     //SMTP username
                $mail->Password   = 'pbnlknscqsyihtse';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('biourbiscompany@gmail.com', 'BioUrbis');//Persona que envia el correo
                $mail->addAddress($correoUsuario, $nombreUsuario); //Persona que recibe el correo
                //$mail->addAddress('ellen@example.com');               //Más correos

                //Content-Contenido correo
                $mail->isHTML(true);   
                $mail->CharSet='UTF-8';                               //Set email format to HTML
                $mail->Subject = 'BioUrbis-Recuperar Contraseña';
                $mail->Body    = 
                "<table style= 'max-width: 600px; padding: 10px; margin: 0 auto; border-collapse: collapse; font-family: 'Montserrat';border-radius:5px;'>
                    <tr>
                        <td style='background-color: rgb(42, 59, 30); text-align: center;padding: 0; color: white; width:100%'>
                            <div>
                                <h4>
                                    BioUrbis
                                    <span  style='display: block;margin: 5px 0;'>Gestión de Huertos Urbanos</span>
                                </h4>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style=' color:#34495e; margin: 4% 10% 2%; text-align: justify; font-family: 'Montserrat';'>
                                <h2 style='color:rgb(184, 98, 12); margin: 0 0 7px;'>Hola $nombreUsuario</h2>
                                <p style='margin: 2px; font-size: 20px;'>
                                    <h3 style='color:black;'><b >Notificación para recuperar su contraseña</b></h3>
                                    <div style='color:black;'>Parece que olvidó su contraseña. No se preocupes, le ayudamos a 
                                    recuperarla.Ingrese el siguiente código de verificación para restablecer 
                                    su contraseña:</div>
                                    <div style='text-align: center; font-size: 30px; margin-top: 5px;'> <b>$codVerificacionC</b></div>
                                </p>
                                <h5 style='color: rgb(184, 98, 12); margin-bottom: 50px; font-size:20px;'>Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos</h5>
                                <hr>
                                <p style='color:#808080; font-size: 12px; text-align: center; margin: 30px 0 0;'>
                                    BioUrbis
                                    <span style='display: block; margin: 5px;'>Gestión de Huertos Urbanos</span>
                                    Correo Electrónico: biourbiscompany@gmai.com
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>";
            
                $mail->send();?>
                <?php               
            } catch (Exception $e) {
                $_SESSION["alerta"]="errorAlEnviarCorreo";
                // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

                //Ejecutar mensajes emergentes
                if(isset($_SESSION["alerta"])){
                    switch ($_SESSION["alerta"]) {
                        case 'errorAlEnviarCorreo': ?>
                            <script>
                                //Mensaje cuando surge un error a la hora de enviar el correo electronico al usuario
                                mostrarMensaje({
                                    title:"¡Error a la hora de enviar el correo electrónico!",
                                    text:"Recarge la página y vuelva a intentarlo",
                                    icon:"error",
                                                                            
                                    //Si el usuario acepta volver a enviar el correo elctronico
                                    rutaTrue:"../forms/formRecuperarContrasena.php",

                                    //Si el usuario no acepta volver a enviar el correo elctronico
                                    rutaFalse:"../forms/formRecuperarContrasena.php"
                                })
                            </script>
                            <?php
                        break;
                    }
                    unset($_SESSION["alerta"]);
                }
            }
        }  
    ?>
</body>
</html>