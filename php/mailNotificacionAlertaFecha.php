<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Notificación Alerta | BioUrbis</title>
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

        $_SESSION["numeroDocumento"]=$usuarioActivo;
            
        //Llamar a la función para consultar los datos de usuario registrado
        $datosUsuario=consultarDatosUsuario($usuarioActivo);
            
        //Recuperar correo y nombre completo del usuario
        $correo=$datosUsuario["usuCorreo"];
        $nombre=$datosUsuario["usuNombre"];

        //Recuperar los valores de la alerta de tipo fechas
        $nombreJardinera=$row['jarNombre'];
        $semilla= $row['semNombre'];
        $fechaAlerta=$row['alerFecha']; 
        $descripcionAlerta=$row['alerDescripcion']; 
        $recomendacionAlerta=$row['alerRecomendacion'];


        require_once '../lib/phpMailer/Exception.php';
        require_once '../lib/phpMailer/PHPMailer.php';
        require_once '../lib/phpMailer/SMTP.php';
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug =0 ;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'biourbiscompany@gmail.com';                     //SMTP username
            $mail->Password   = 'pbnlknscqsyihtse';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('biourbiscompany@gmail.com', 'BioUrbis');//Persona que envia el correo
            $mail->addAddress($correo, $nombre); //Persona que recibe el correo
            //$mail->addAddress('ellen@example.com');               //Más correos

            //Content-Contenido correo
            $mail->isHTML(true);
            $mail->CharSet='UTF-8';                                    //Set email format to HTML
            $mail->Subject = 'BioUrbis-Notificación de Alerta Jardinera';
            $mail->Body    = 
           "<table style='max-width: 600px; padding: 10px; margin: 0 auto; border-collapse: collapse; font-family: Montserrat; border-radius:5px;'>
                <tr>
                    <td style='background-color:rgb(42, 59, 30); text-align: center;padding: 0; color: white;'>
                        <div>
                            <h4>
                                BioUrbis
                                <span style='display: block;margin: 5px 0;'>Gestión de Huertos Urbanos</span>
                            </h4>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style='color:#34495e; margin: 4% 10% 2%; text-align: justify; font-family: Montserrat;'>
                            <h2 style='color: rgb(184, 98, 12); margin: 0 0 7px;'>Hola $nombre</h2>

                            <h3 style='color:black; margin-top:10px;'>
                                Se ha generado una alerta en su jardinera $nombreJardinera 
                            </h3>
                            <div style='color:black;'>
                                <p>
                                    Debido a la proximidad de fechas importantes e influyentes para el crecimiento de su planta.
                                </p>
                            </div>

                            <div style='color:black; font-size: 16px;'>
                                <p><strong>Jardinera:</strong> $nombreJardinera</p>
                                <p><strong>Semilla:</strong> $semilla</p>
                                <p><strong>Fecha:</strong> $fechaAlerta</p>
                                <p><strong>Alerta:</strong> $descripcionAlerta</p>
                                <p><strong>Recomendación:</strong> $recomendacionAlerta</p>

                                <p style='margin-top:15px;'>
                                    Le recomendamos registrar los cambios percibidos de su jardinera en este tiempo para identificar su nueva evolución
                                </p>
                            </div>

                            <h5 style='color: rgb(184, 98, 12); margin-bottom: 50px; font-size:20px;'>
                                Si tiene alguna duda o necesita ayuda, no dude en contactarnos
                            </h5>

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
            
            $mail->send();  
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
                                rutaTrue:"homeUsuario.php?page=profile",

                                //Si el usuario no acepta volver a enviar el correo elctronico
                                rutaFalse:"homeUsuario.php?page=profile"
                            })
                        </script>
                        <?php
                    break;
                }
                unset($_SESSION["alerta"]);
            }
        }
    ?> 
</body>
</html>
