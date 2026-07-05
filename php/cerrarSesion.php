<?php
    session_start();

    include("../functions/funciones.php");

    $conexion_db = abrirConexionDB();

    if(isset($_SESSION["numeroDocumento"])){

        $usuarioActivo = $_SESSION["numeroDocumento"];
        $fechaHoraActual=recuperarFechaActualConHora();

        if(actualizarUltimoAcceso($usuarioActivo, $fechaHoraActual)){
            // Registrar actividad
            registrarActividadUsuario("Perfil","Cerrar sesión","Cerró sesión en el sistema",$usuarioActivo);
        }
    }
    session_unset();
    session_destroy();

    header("Location: ../forms/formAcceso.php");
    exit();
?>