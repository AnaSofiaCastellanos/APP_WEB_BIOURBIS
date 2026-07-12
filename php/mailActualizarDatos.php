<?php
    require_once("../functions/enviarCorreos.php");

    if(isset($_POST["codigoVerificacion"])){
        $_SESSION["alerta"]="codigoExistente";
    }else{
        //Generar código
        if(function_exists("random_int")){
            $codVerificacion=random_int(1000,9999);
        }else{
            $codVerificacion=mt_rand(1000,9999);
        }

        $_SESSION["codigoVerificacion"]=$codVerificacion;

        $correoUsuario=$datosUsuario["usuCorreo"];
        $nombreUsuario=$datosUsuario["usuNombre"];

        //Enviar correo
        $enviado=enviarCorreo(
            $correoUsuario,
            $nombreUsuario,
            "BioUrbis - Confirmación para actualización de su información",
            correoVerificacionActualizacionDatos(
                $nombreUsuario,
                $codVerificacion
            )
        );

        if($enviado){
            echo "<script>
                    window.location.replace('../php/procesadorActualizarDatos.php');
                </script>";
        }else{
            $_SESSION["alerta"]="errorAlEnviarCorreoActualizarDatos";
        }
    }
?>