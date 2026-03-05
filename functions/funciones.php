<?php 
    //Función para consultar el tipo de semilla de cada semilla
    function consultarTipoSemilla($idTipoSemilla, $conexion){
        $queryConsultarTipoSemilla="SELECT tipoSemDescripcion FROM tipo_semilla WHERE idTipoSemilla='$idTipoSemilla'";
        $resultado=mysqli_query($conexion, $queryConsultarTipoSemilla);

        return $resultado;
    }

    //Función para consultar el tipo de tierra de cada semilla
    function consultarTipoTierra($idTipoTierra, $conexion){
        $queryConsultarTipoTierra="SELECT tipoTierraDescripcion FROM tipo_tierra WHERE idTipoTierra='$idTipoTierra'";
        $resultado=mysqli_query($conexion, $queryConsultarTipoTierra);

        return $resultado;
    }

    //Función para consultar el tipo de clima de cada semilla
    function consultarTipoClima($idTipoClima, $conexion){
        $queryConsultarTipoClima="SELECT tipoClimaDescripcion FROM tipo_clima WHERE idTipoClima='$idTipoClima'";
        $resultado=mysqli_query($conexion, $queryConsultarTipoClima);

        return $resultado;
    }

    //Función para crear arreglos con todos los datos de una consulta
    function arregloDatos($resultadoConsulta){
        $datos=mysqli_fetch_array($resultadoConsulta);

        return $datos;
    }

    //Función para consultar todas las semillas de la base de datos
    function consultarSemillas($conexion){
        $queryConsultarSemillas="SELECT * FROM semilla";
        $resultado=mysqli_query($conexion, $queryConsultarSemillas);

        return $resultado;
    }

    //Función para consultar la existencia de una semilla específica
    function consultarExistenciaSemilla($idSemilla, $conexion){
        $queryConsultarSemilla="SELECT * FROM semilla WHERE idSemilla='$idSemilla'";
        $resultado=mysqli_query($conexion, $queryConsultarSemilla);

        if($resultado && mysqli_num_rows($resultado) > 0){
            return true;
        }else{
            return false;
        }
    }

    //Función para consultar la información de una semilla específica
    function consultarDatosSemilla($idSemilla, $conexion){
        $queryConsultarSemilla="SELECT * FROM semilla WHERE idSemilla='$idSemilla'";
        $resultadoConsulta=mysqli_query($conexion, $queryConsultarSemilla);

        $datosSemilla=arregloDatos($resultadoConsulta);

        $idTipoSemilla=$datosSemilla["idTipoSemilla"];

        $resultadoConsultaTipoSemilla=consultarTipoSemilla($idTipoSemilla, $conexion);
        if(mysqli_num_rows($resultadoConsultaTipoSemilla)){
            $datosTipoSemilla=mysqli_fetch_array($resultadoConsultaTipoSemilla);
            $datosSemilla["idTipoSemilla"]=$datosTipoSemilla["tipoSemDescripcion"];
        }

        return $datosSemilla;
    }

    //Función para consultar la ficha técnica de una semilla específica
    function consultarFichaTecnicaSemilla($idSemilla, $conexion){
        $queryConsultarFichaTecnicaSemilla="SELECT * FROM ficha_tecnica WHERE idSemilla='$idSemilla'";
        $resultadoConsulta=mysqli_query($conexion, $queryConsultarFichaTecnicaSemilla);

        $datosFichaTecnica=arregloDatos($resultadoConsulta);

        $idTipoTierra=$datosFichaTecnica["idTipoTierra"];
        $resultadoConsultaTipoTierra=consultarTipoTierra($idTipoTierra, $conexion);

        if(mysqli_num_rows($resultadoConsultaTipoTierra)){
            $datosTipoTierra=mysqli_fetch_array($resultadoConsultaTipoTierra);
            $datosFichaTecnica["idTipoTierra"]=$datosTipoTierra["tipoTierraDescripcion"];
        }

        $idTipoClima=$datosFichaTecnica["idTipoClima"];
        $resultadoConsultaTipoClima=consultarTipoClima($idTipoClima, $conexion);

        if(mysqli_num_rows($resultadoConsultaTipoClima)){
            $datosTipoClima=mysqli_fetch_array($resultadoConsultaTipoClima);
            $datosFichaTecnica["idTipoClima"]=$datosTipoClima["tipoClimaDescripcion"];
        }

        return $datosFichaTecnica;
    }

    //Función para consultar la etapa de crecimiento de una semilla específica
    function consultarEtapaCrecimientoSemilla($idEtapa,$conexion){

        $queryConsultarEtapaCrecimiento="SELECT * FROM etapas_crecimiento WHERE idEtapaCrecimiento='$idEtapa'";
        $resultadoConsulta=mysqli_query($conexion, $queryConsultarEtapaCrecimiento); 

        $datosEtapaCrecimiento=arregloDatos($resultadoConsulta);

        return $datosEtapaCrecimiento;
    }

    //Función para consultar el tipo de documento del usuario
    function consultarTipoDocumento($idTipoDocumento, $conexion){
        $queryConsultarTipoDocumento="SELECT * FROM tipo_documento WHERE idTipoDocumento='$idTipoDocumento'";
        $resultado=mysqli_query($conexion, $queryConsultarTipoDocumento);

        return $resultado;
    }

    //Función para consultar si un usuario existe en la base de datos
    function consultarUsuarioExistente($numeroDocumento, $conexion){
        $queryConsultarUsuario="SELECT * FROM usuario WHERE usuNumeroDocumento='$numeroDocumento'";
        $resultadoConsulta=mysqli_query($conexion, $queryConsultarUsuario);

        return $resultadoConsulta;
    }

    //Función para consultar los datos del usuario
    function consultarDatosUsuario($numeroDocumento, $conexion){
        $queryConsultarUsuario="SELECT * FROM usuario WHERE usuNumeroDocumento='$numeroDocumento'";
        $resultadoConsulta=mysqli_query($conexion, $queryConsultarUsuario);

        $datosUsuario=arregloDatos($resultadoConsulta);

        $idTipoDocumento=$datosUsuario["idTipoDocumento"];
        $resultadoConsultarTipoDocumento=consultarTipoDocumento($idTipoDocumento, $conexion);

        if(mysqli_num_rows($resultadoConsultarTipoDocumento)){
            $datosTipoDocumento=mysqli_fetch_array($resultadoConsultarTipoDocumento);
            $datosUsuario["idTipoDocumento"]=$datosTipoDocumento["tipoDocDescripcion"];
        }

        return $datosUsuario;
    }

    //Función para actualizar la contraseña del usuario
    function actualizarContrasena($numeroDocumento, $contrasena, $conexion){
        $contrasena=md5($contrasena);
        $queryActualizarContrasena="UPDATE usuario SET usuContrasena='$contrasena' WHERE usuNumeroDocumento='$numeroDocumento'";
        $resultado=mysqli_query($conexion, $queryActualizarContrasena);

        return $resultado;
    }

    //Calcular tiempo de actividad del usuario
    function calcularActividadUsuario($fechaIngreso){
        //Recuperar la fecha y hora actual del sistema
        date_default_timezone_set('America/Bogota');

        //Crear objetos de la clase DateTime para realizar operaciones y comparaciones con ellas
        $fechaActual=new DateTime(date('Y-m-d'));
        $fechaIngreso=new DateTime($fechaIngreso);
        
        //Calcular diferencia entre las dos fechas
        $diferencia=$fechaActual->diff($fechaIngreso);
        $actividadEnDias=$diferencia->days;

        if($actividadEnDias<30){
            //Actividad del usuario en días
            return $actividadEnDias . " días";
        } elseif($actividadEnDias<365){
            //Actividad del usuario en meses
            return ($diferencia->y *12 + $diferencia->m. " meses");
        }else{
            //Actividad del usuario en años
            return $diferencia->y . " años";
        }
    }
?>