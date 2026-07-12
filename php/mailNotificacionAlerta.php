<?php
    require_once("../functions/enviarCorreos.php");

    $correo = $datosUsuario["usuCorreo"];
    $nombre = $datosUsuario["usuNombre"];

    //Datos comunes
    $nombreJardinera = $row["jarNombre"];
    $semilla = $row["semNombre"];
    $fechaAlerta = $row["alerFecha"];
    $descripcionAlerta = $row["alerDescripcion"];
    $recomendacionAlerta = $row["alerRecomendacion"];

    //Valores únicamente para alertas de factores
    $valorRegistrado = null;
    $rangoRecomendado = null;

    if($tipoCorreo == "factores"){
        $valorRegistrado = $row["alerValorRegistrado"];
        $rangoRecomendado = $row["alerRangoRecomendado"];
    }

    $enviado = enviarCorreo(
        $correo,
        $nombre,
        "BioUrbis - Notificación de alerta en jardinera",
        correoAlertaJardinera(
            $tipoCorreo,
            $nombre,
            $nombreJardinera,
            $semilla,
            $fechaAlerta,
            $descripcionAlerta,
            $recomendacionAlerta,
            $valorRegistrado,
            $rangoRecomendado
        )
    );
    if(!$enviado){
        $_SESSION["alerta"] = "errorAlEnviarCorreoAlerta";
    }
?>
