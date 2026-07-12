<?php
    session_start();

    include("../functions/funciones.php");
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

        $numeroDocumento=$_SESSION["numeroDocumento"];

        //Consultar datos del usuario
        $datosUsuario=consultarDatosUsuario($numeroDocumento);

        $correoUsuario=$datosUsuario["usuCorreo"];
        $nombreUsuario=$datosUsuario["usuNombre"];

        //Enviar correo
        $enviado=enviarCorreo(
            $correoUsuario,
            $nombreUsuario,
            "BioUrbis - Verificación de cuenta",
            correoVerificacionCuenta(
                $nombreUsuario,
                $codVerificacion
            )
        );

        if($enviado){
            echo "<script>
                    window.location.replace('../forms/formVerificarCorreo.php');
                </script>";

        }else{
            $_SESSION["alerta"]="errorAlEnviarCorreo";
            ?>
            <script>
                mostrarMensaje({
                    title:"¡Error a la hora de enviar el correo electrónico!",
                    text:"Recargue la página y vuelva a intentarlo",
                    icon:"error",

                    rutaTrue:"../forms/formRegistro.php",

                    rutaFalse:"../forms/formRegistro.php"
                });
            </script>
            <?php
            unset($_SESSION["alerta"]);
        }
    }
?>
