<?php
require_once("../functions/enviarCorreos.php");

if(isset($_POST["codigoVerificacionC"])){
    $_SESSION["alerta"]="codigoExistente";

}else{
    //Generar código
    if(function_exists("random_int")){
        $codVerificacionC=random_int(1000,9999);
    }else{
        $codVerificacionC=mt_rand(1000,9999);
    }

    $_SESSION["codigoVerificacionC"]=$codVerificacionC;

    $numeroDocumento=$_SESSION["numeroDocumento"];

    //Consultar datos del usuario
    $datosUsuario=consultarDatosUsuario($numeroDocumento);

    $correoUsuario=$datosUsuario["usuCorreo"];
    $nombreUsuario=$datosUsuario["usuNombre"];

    //Enviar correo
    $enviado=enviarCorreo(
        $correoUsuario,
        $nombreUsuario,
        "BioUrbis - Recuperar Contraseña",
        correoRecuperarContrasena(
            $nombreUsuario,
            $codVerificacionC
        )
    );

    if(!$enviado){
        $_SESSION["alerta"]="errorAlEnviarCorreo";
    }
}
?>