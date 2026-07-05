<?php
    //Recuperar los valores del formulario
    $tipoSolicitud=trim($_POST["typeRequest"]);
    $mensaje=ucfirst(strtolower(trim($_POST["message"])));;

    //Recuperar la fecha actual del equipo
    $fechaActual=recuperarFechaActual();

    //Evaluar el tipo de solicitud y si el campo de semilla se encuentra activa
    if($tipoSolicitud=="Admisión Nueva Semilla" && !empty($_POST["newSeed"])){
        //Recuperar la semilla seleccionada por el usuario
        $semillaSeleccionada=ucfirst(strtolower(trim($_POST["newSeed"])));

        //Consulta para verificar si la semilla ya se encuentra registrada en el sistema
        $resultadoConsultarExistenciaSemilla=consultarExistenciaSemillaPorNombre($semillaSeleccionada);

        //Evalua si existe algun registro con ese nombre
        if(mysqli_num_rows($resultadoConsultarExistenciaSemilla)>0){
            $_SESSION["alerta"]="semillaExistente";
        }else{
            //Consulta cuando el tipo de solicitud es para admitir una nueva semilla
            $queryEnviarSolicitud="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion, soliSemilla, soliEstado, usuNumeroDocumento)
            VALUES('$fechaActual', '$tipoSolicitud', '$mensaje', '$semillaSeleccionada', 'Pendiente', '$usuarioActivo')";
            $resultadoEnviarSolicitud=mysqli_query($conexion_db, $queryEnviarSolicitud);

            //Evaluar si la consulta se ejecutó correctamente
            if($resultadoEnviarSolicitud==true){
                //Enviar correo electrónico para confirmar su solicitud 
                include("mailConfirmacionSolicitudPerfil.php");

                //Registrar la actividad del usuario
                registrarActividadUsuario("Solicitud","Crear", "Registró una nueva solicitud de semilla", $usuarioActivo);

                header("Location: homeUsuario.php?page=request");
                exit();
            }else{
                $_SESSION["alerta"]="errorEnviarSolicitud";
            }
        }
    }else{
        //Consulta para los demás tipos de solicitud
        $queryEnviarSolicitud="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion,  soliEstado, usuNumeroDocumento)
        VALUES('$fechaActual', '$tipoSolicitud', '$mensaje', 'Pendiente', '$usuarioActivo')";
        $resultadoEnviarSolicitud=mysqli_query($conexion_db, $queryEnviarSolicitud);

        //Evaluar si la consulta se ejecutó correctamente
        if($resultadoEnviarSolicitud==true){
            //Enviar correo electrónico para confirmar su solicitud 
            include("mailConfirmacionSolicitudPerfil.php");

            //Registrar la actividad del usuario
            registrarActividadUsuario("Solicitud","Crear", "Registró una nueva solicitud", $usuarioActivo);

            header("Location: homeUsuario.php?page=request");
            exit();
        }else{
            $_SESSION["alerta"]="errorEnviarSolicitud";
        }
    }
?>