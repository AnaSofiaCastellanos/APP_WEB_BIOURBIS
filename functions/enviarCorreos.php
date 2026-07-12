<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/phpMailer/Exception.php';
require_once __DIR__ . '/../lib/phpMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/phpMailer/SMTP.php';

function enviarCorreo($destino, $nombre, $asunto, $body){

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'biourbiscompany@gmail.com';
        $mail->Password = 'pbnlknscqsyihtse';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('biourbiscompany@gmail.com','BioUrbis');
        $mail->addAddress($destino,$nombre);

        $mail->isHTML(true);
        $mail->CharSet='UTF-8';

        $mail->Subject = $asunto;
        $mail->Body = plantillaBase($body);

        $mail->send();

        return true;

    }catch(Exception $e){

        error_log($mail->ErrorInfo);

        return false;
    }
}

function plantillaBase($contenido){
    return "
    <table style='max-width: 600px;
    padding: 10px;
    margin: auto;
    border-collapse: collapse;
    font-family: Montserrat;'>

        <tr>
            <td style='background-color:rgb(42,59,30);
            text-align:center;
            color:white;
            padding:15px;'>

                <h2>BioUrbis</h2>

                <span>
                    Gestión de Huertos Urbanos
                </span>

            </td>
        </tr>

        <tr>
            <td style='padding:20px;'>
                $contenido
                <hr>

                <p style='text-align:center;
                color:gray;
                font-size:12px;'>
                    BioUrbis <br>
                    Gestión de Huertos Urbanos <br>
                    biourbiscompany@gmail.com
                </p>
            </td>
        </tr>
    </table>
    ";
}

function correoVerificacionCuenta($nombreUsuario, $codigo){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Notificación de verificación de cuenta
        </h3>

        <p style='color:black;'>
            Le damos la bienvenida a <strong>BioUrbis</strong>.
            Gracias por registrarse en nuestra plataforma.
        </p>

        <p style='color:black;'>
            Para verificar su cuenta ingrese el siguiente código de verificación:
        </p>

        <div style='
            text-align:center;
            font-size:34px;
            font-weight:bold;
            color:rgb(184,98,12);
            margin:25px 0;
            letter-spacing:5px;
        '>
            $codigo
        </div>

        <p style='color:black;'>
            Si usted no realizó este registro puede ignorar este correo.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoResenaPublicada($nombreUsuario, $mensajeUsuario){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Su reseña fue publicada con éxito
        </h3>

        <p style='color:black;'>
            Su reseña ya fue publicada en nuestra plataforma.
        </p>

        <p style='color:black;'>
            <strong>Mensaje enviado:</strong><br>
            \"$mensajeUsuario\"
        </p>

        <p style='color:black;'>
            Gracias por compartir su opinión. Sus comentarios nos ayudan a
            mejorar nuestros servicios y permiten que más personas conozcan
            la experiencia que ofrece BioUrbis.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";

}

function correoRecuperarContrasena($nombreUsuario, $codigo){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Notificación para recuperar su contraseña
        </h3>

        <p style='color:black;'>
            Hemos recibido una solicitud para restablecer la contraseña de su cuenta en
            <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            Para continuar con el proceso, ingrese el siguiente código de verificación:
        </p>

        <div style='
            text-align:center;
            font-size:34px;
            font-weight:bold;
            color:rgb(184,98,12);
            margin:25px 0;
            letter-spacing:5px;
        '>
            $codigo
        </div>

        <p style='color:black;'>
            Si usted no solicitó este cambio, puede ignorar este correo de forma segura.
            Su contraseña permanecerá sin modificaciones.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoSolicitudEnviada($nombre, $tipoSolicitud, $mensaje){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombre
        </h2>

        <h3 style='color:black;'>
            Su solicitud ha sido enviada correctamente
        </h3>

        <p style='color:black;'>
            Hemos recibido su solicitud en la plataforma
            <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            <strong>Tipo de solicitud:</strong> $tipoSolicitud
        </p>

        <p style='color:black;'>
            <strong>Mensaje enviado:</strong><br>
            $mensaje
        </p>

        <p style='color:black;'>
            Su solicitud ya fue enviada a un administrador y actualmente
            se encuentra en proceso de revisión.
        </p>

        <p style='color:black;'>
            Durante el transcurso de la semana nos comunicaremos con usted
            para informarle el estado de la solicitud y los siguientes pasos.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoSolicitudUsuario($nombre, $tipoSolicitud, $mensaje){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombre
        </h2>

        <h3 style='color:black;'>
            Su solicitud ha sido enviada correctamente
        </h3>

        <p style='color:black;'>
            Hemos recibido su solicitud en la plataforma
            <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            <strong>Tipo de solicitud:</strong> $tipoSolicitud
        </p>

        <p style='color:black;'>
            <strong>Mensaje enviado:</strong><br>
            $mensaje
        </p>

        <p style='color:black;'>
            Su solicitud fue enviada a uno de nuestros administradores y
            actualmente se encuentra en proceso de revisión.
        </p>

        <p style='color:black;'>
            En el transcurso de la semana nos comunicaremos con usted para
            informarle el estado de la solicitud y los siguientes pasos.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoAlertaJardinera(
    $tipo,
    $nombre,
    $nombreJardinera,
    $semilla,
    $fechaAlerta,
    $descripcionAlerta,
    $recomendacionAlerta,
    $valorRegistrado = null,
    $rangoRecomendado = null
){
    if($tipo == "factores"){

        $introduccion = "
            <p>
                Debido a que se han registrado factores externos inadecuados para el crecimiento de su planta.
            </p>
        ";

        $informacionExtra = "
            <p><strong>Valor obtenido:</strong> $valorRegistrado</p>
            <p><strong>Rango recomendado:</strong> $rangoRecomendado</p>
        ";

        $mensajeFinal = "
            Le recomendamos revisar su jardinera lo antes posible para evitar afectaciones en su crecimiento.
        ";

    }else{

        $introduccion = "
            <p>
                Debido a la proximidad de fechas importantes e influyentes para el crecimiento de su planta.
            </p>
        ";

        $informacionExtra = "";

        $mensajeFinal = "
            Le recomendamos registrar los cambios percibidos en su jardinera para identificar su nueva evolución.
        ";

    }

    return "

        <h2 style='color:rgb(184,98,12);'>
            Hola $nombre
        </h2>

        <h3 style='color:black;'>
            Se ha generado una alerta en su jardinera \"<b>$nombreJardinera</b>\"
        </h3>

        $introduccion

        <br>

        <p><strong>Jardinera:</strong> $nombreJardinera</p>

        <p><strong>Semilla:</strong> $semilla</p>

        <p><strong>Fecha:</strong> $fechaAlerta</p>

        <p><strong>Alerta:</strong> $descripcionAlerta</p>

        <p><strong>Recomendación:</strong> $recomendacionAlerta</p>

        $informacionExtra

        <br>

        <p>
            $mensajeFinal
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna duda o necesita ayuda, no dude en contactarnos.
        </h4>

    ";   
}

function correoModificacionEstadoCuenta(
    $nombre,
    $nombreAdmin,
    $fechaActual,
    $documento
){
    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombre
        </h2>

        <h3 style='color:black;'>
            Un administrador ha modificado el estado de su cuenta registrada en la plataforma.
        </h3>

        <p style='color:black;'>
            Le informamos que un administrador de la plataforma
            <strong>BioUrbis</strong> modificó el estado de su cuenta registrada.
        </p>

        <br>

        <p><strong>Administrador:</strong> $nombreAdmin</p>

        <p><strong>Fecha de actualización:</strong> $fechaActual</p>

        <p><strong>Número de identificación:</strong> $documento</p>

        <br>

        <p style='color:black;'>
            Si reconoce esta modificación no es necesario realizar ninguna acción.
            En caso de que no esté de acuerdo con los cambios realizados o considere
            que existe algún error, comuníquese con el administrador o con el equipo
            de soporte.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna duda o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoActualizacionCuenta(
    $nombre,
    $nombreAdmin,
    $fechaActual,
    $documento
){
    return "

        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombre
        </h2>

        <h3 style='color:black;'>
            Un administrador ha actualizado la información de su cuenta.
        </h3>

        <p style='color:black;'>
            Le informamos que un administrador de la plataforma
            <strong>BioUrbis</strong> realizó modificaciones en los datos
            asociados a su cuenta.
        </p>

        <br>

        <p><strong>Administrador:</strong> $nombreAdmin</p>

        <p><strong>Fecha de actualización:</strong> $fechaActual</p>

        <p><strong>Número de identificación:</strong> $documento</p>

        <br>

        <p style='color:black;'>
            Si reconoce esta actualización no es necesario realizar ninguna acción.
            En caso de que no esté de acuerdo con los cambios realizados o considere
            que existe algún error, comuníquese con el administrador o con el equipo
            de soporte.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna duda o necesita ayuda, no dude en contactarnos.
        </h4>

    ";
}

function correoVerificacionActualizacionDatos($nombreUsuario, $codigo){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Confirmación para actualización de su información
        </h3>

        <p style='color:black;'>
            Hemos recibido su solicitud para actualizar los datos personales asociados a su cuenta activa en <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            Para confirmar este cambio necesitamos verificar que usted es el propietario de la cuenta y autoriza estas modificaciones.
        </p>

        <p style='color:black;'>
            Ingrese el siguiente código de verificación:
        </p>

        <div style='
            text-align:center;
            font-size:34px;
            font-weight:bold;
            color:rgb(184,98,12);
            margin:25px 0;
            letter-spacing:5px;
        '>
            $codigo
        </div>

        <p style='color:black;'>
            Si usted no solicitó esta actualización, puede ignorar este correo. No se realizará ningún cambio sin la verificación correspondiente.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoSolicitudConfirmada($nombreUsuario, $tipoSolicitud){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Su solicitud ha sido confirmada
        </h3>

        <p style='color:black;'>
            Nos complace informarle que su solicitud relacionada con
            <strong>$tipoSolicitud</strong> ha sido revisada y confirmada por un administrador de
            <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            La solicitud fue procesada correctamente y los cambios correspondientes ya han sido aplicados en la plataforma.
        </p>

        <p style='color:black;'>
            Agradecemos su paciencia durante el proceso de revisión. Si requiere información adicional o presenta alguna inquietud, puede comunicarse con nuestro equipo de soporte.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por confiar en BioUrbis.
        </h4>

        <h4 style='color:rgb(184,98,12); margin-top:20px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoSolicitudRechazada($nombreUsuario, $tipoSolicitud){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Su solicitud no ha sido aprobada
        </h3>

        <p style='color:black;'>
            Le informamos que su solicitud relacionada con
            <strong>$tipoSolicitud</strong> ha sido revisada por un administrador de
            <strong>BioUrbis</strong>.
        </p>

        <p style='color:black;'>
            Después de realizar la respectiva validación, la solicitud no pudo ser aprobada en esta ocasión.
        </p>

        <p style='color:black;'>
            Si considera que se trata de un error o desea obtener más información sobre esta decisión, puede comunicarse con nuestro equipo de soporte o enviar una nueva solicitud con la información requerida.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Agradecemos su comprensión.
        </h4>

        <h4 style='color:rgb(184,98,12); margin-top:20px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoResenaBloqueada($nombreUsuario){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Su reseña ha sido desactivada
        </h3>

        <p style='color:black;'>
            Le informamos que un administrador de <strong>BioUrbis</strong> ha revisado una de sus reseñas y ha decidido desactivarla, ya que su contenido no cumple con las normas de convivencia y publicación establecidas en nuestra plataforma.
        </p>

        <p style='color:black;'>
            La reseña contenía información o expresiones que fueron consideradas inapropiadas, ofensivas o no relacionadas con el propósito de la comunidad, por lo que dejó de estar visible para los demás usuarios.
        </p>

        <p style='color:black;'>
            Nuestro objetivo es mantener un espacio respetuoso, seguro y útil para todos los usuarios. Le invitamos a publicar futuras reseñas con un lenguaje adecuado, respetuoso y relacionado con la experiencia en la plataforma.
        </p>

        <p style='color:black;'>
            Si considera que esta decisión fue tomada por error o desea obtener más información, puede comunicarse con nuestro equipo de soporte.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por ayudarnos a mantener una comunidad respetuosa y de calidad.
        </h4>

        <h4 style='color:rgb(184,98,12); margin-top:20px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoActualizacionJardinera($nombreUsuario, $nombreJardinera){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Su jardinera ha sido actualizada
        </h3>

        <p style='color:black;'>
            Le informamos que un administrador de <strong>BioUrbis</strong> ha realizado una actualización en la información de su jardinera
            <strong>$nombreJardinera</strong>.
        </p>

        <p style='color:black;'>
            Los cambios ya fueron aplicados correctamente en la plataforma y se encuentran disponibles para su consulta.
        </p>

        <p style='color:black;'>
            Le recomendamos ingresar a su cuenta para revisar la información actualizada y verificar que todos los datos sean correctos.
        </p>

        <p style='color:black;'>
            Si considera que existe algún error o tiene alguna inquietud sobre esta actualización, puede comunicarse con nuestro equipo de soporte.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por confiar en BioUrbis.
        </h4>

        <h4 style='color:rgb(184,98,12); margin-top:20px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoJardineraInactiva($nombreUsuario, $nombreJardinera){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            El estado de su jardinera ha sido modificado
        </h3>

        <p style='color:black;'>
            Le informamos que un administrador de <strong>BioUrbis</strong> ha modificado el estado de la jardinera
            <strong>$nombreJardinera</strong>.
        </p>

        <p style='color:black;'>
            Esta acción se realizó como parte del proceso de administración y control de la plataforma, por lo que la jardinera ya no se encuentra disponible para realizar registros o seguimientos.
        </p>

        <p style='color:black;'>
            Si considera que esta acción fue realizada por error o desea conocer el motivo de la modificación, puede comunicarse con nuestro equipo de soporte o con un administrador de la plataforma.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por utilizar BioUrbis.
        </h4>

        <h4 style='color:rgb(184,98,12); margin-top:20px;'>
            Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.
        </h4>
    ";
}

function correoActualizacionFactorExterno(
    $nombreUsuario,
    $nombreJardinera,
    $idFactorExterno,
){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Actualización de información de factor externo
        </h3>

        <p style='color:black;'>
            Le informamos que la información de uno de los factores externos registrados en su jardinera ha sido actualizada correctamente.
        </p>

        <div style='
            background:#f8f9fa;
            border-left:5px solid rgb(184,98,12);
            padding:18px;
            margin:25px 0;
        '>
            <p><strong>Jardinera:</strong> $nombreJardinera</p>
            <p><strong>Factor externo Nº:</strong> $idFactorExterno</p>
        </div>

        <p style='color:black;'>
            Esta actualización permitirá que el historial de seguimiento de su jardinera permanezca organizado y refleje la información más reciente registrada en la plataforma.
        </p>

        <p style='color:black;'>
            Si usted no reconoce esta modificación o considera que existe algún error en la información registrada, comuníquese con el administrador o con el equipo de soporte de BioUrbis.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por utilizar BioUrbis.
        </h4>
    ";
}

function correoInactivacionFactorExterno(
    $nombreUsuario,
    $nombreJardinera,
    $idFactorExterno,
){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            El estado de un factor externo de su jardinera ha sido modificado
        </h3>

        <p style='color:black;'>
            Le informamos que el estado de uno de los factores externos registrados en su jardinera ha sido modificado en la plataforma BioUrbis.
        </p>

        <div style='
            background:#f8f9fa;
            border-left:5px solid rgb(184,98,12);
            padding:18px;
            margin:25px 0;
        '>
            <p><strong>Jardinera:</strong> $nombreJardinera</p>
            <p><strong>Factor externo Nº:</strong> $idFactorExterno</p>
        </div>

        <p style='color:black;'>
            Si usted no reconoce esta modificación o considera que existe algún error, comuníquese con el administrador o con el equipo de soporte de BioUrbis.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por utilizar BioUrbis.
        </h4>
    ";
}

function correoActualizacionMonitoreo(
    $nombreUsuario,
    $nombreJardinera,
    $idSeguimiento, 
    $fechaSeguimiento
){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            Actualización del monitoreo de su jardinera
        </h3>

        <p style='color:black;'>
            Le informamos que la información de seguimiento de una de sus jardineras ha sido actualizada en la plataforma BioUrbis.
        </p>

        <div style='
            background:#f8f9fa;
            border-left:5px solid rgb(184,98,12);
            padding:18px;
            margin:25px 0;
        '>
            <p><strong>Jardinera:</strong> $nombreJardinera</p>
            <p><strong>Seguimiento Nº:</strong> $idSeguimiento</p>
            <p><strong>Fecha de registro:</strong> $fechaSeguimiento</p>
        </div>

        <p style='color:black;'>
            La actualización puede incluir modificaciones en el porcentaje de evolución de la planta, las observaciones registradas, las imágenes de seguimiento o cualquier otra información asociada al monitoreo de la jardinera.
        </p>

        <p style='color:black;'>
            Le recomendamos ingresar a la plataforma para consultar la información actualizada y verificar el estado actual de su cultivo.
        </p>

        <p style='color:black;'>
            Si usted no reconoce esta modificación o considera que existe algún error, comuníquese con el administrador o con el equipo de soporte de BioUrbis.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por utilizar BioUrbis.
        </h4>
    ";
}

function correoInactivacionMonitoreo(
    $nombreUsuario,
    $nombreJardinera,
    $idSeguimiento,
    $fechaSeguimiento
){

    return "
        <h2 style='color:rgb(184,98,12); margin-bottom:10px;'>
            Hola $nombreUsuario
        </h2>

        <h3 style='color:black;'>
            El estado de un seguimiento o monitoreo de su jardinera ha sido modificado
        </h3>

        <p style='color:black;'>
            Le informamos que el estado de uno de los registros de monitoreo asociados a su jardinera ha sido modificado en la plataforma BioUrbis.
        </p>

        <div style='
            background:#f8f9fa;
            border-left:5px solid rgb(184,98,12);
            padding:18px;
            margin:25px 0;
        '>
            <p><strong>Jardinera:</strong> $nombreJardinera</p>
            <p><strong>Monitoreo Nº:</strong> $idSeguimiento</p>
            <p><strong>Fecha de registro:</strong> $fechaSeguimiento</p>
        </div>

        <p style='color:black;'>
            Si usted no reconoce esta modificación o considera que existe algún error, comuníquese con el administrador o con el equipo de soporte de BioUrbis.
        </p>

        <h4 style='color:rgb(184,98,12); margin-top:35px;'>
            Gracias por utilizar BioUrbis.
        </h4>
    ";
}