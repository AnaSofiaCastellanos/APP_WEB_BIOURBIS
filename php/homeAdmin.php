<?php 
    session_start();
    //Número de documento del usuario autenticado
    $usuarioActivo=$_SESSION["numeroDocumento"];
    //Tipo de usuario
    $_SESSION["origenActualizacion"] = "admin";

    //Sesion donde se almacenan las alertas de los mensajes emergentes
    $_SESSION["alerta"]="";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador | BioUrbis</title>

    <link rel="stylesheet" href="../css/style_HomeAdmin.css">
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="../lib/jspdf/jspdf.umd.min.js"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        //Incluir las funciones de la app
        include("../functions/funciones.php");

        //Abrir la conexion a la base de datos 
        $conexion_db=abrirConexionDB();

        //Recuperar la fecha actual
        $fechaActual = recuperarFechaActual();

        //Consultar los datos del usuario que inicio sesión
        $datosAdmin=consultarDatosUsuario($usuarioActivo);

        //=== PERFIL ADMINISTRADOR ===
            //Actualizar la imagen o el avatar del perfil del usuario
            if(isset($_FILES["imgAvatar"])){
                $nombre=$_FILES["imgAvatar"]["name"];
                $tmp=$_FILES["imgAvatar"]["tmp_name"];

                $nombreF=time()."_img_".$nombre;
                $rutaImagen="../images/avatares/".$nombreF;

                if(move_uploaded_file($tmp, $rutaImagen)){                    
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Perfil Administrador","Actualizar", "Modificación de avatar en su perfil", $usuarioActivo);

                    if(agregarImagenPerfil($usuarioActivo, $rutaImagen)){ ?>
                        <meta http-equiv="refresh" content="1">
                    <?php   
                    }else{
                        $_SESSION["alerta"]="errorAlSubirImagen";
                    }
                }
            }
        //===

        //=== TIPO DE DOCUMENTO ===
            //Agregar tipo de documento
            if(isset($_POST["agregarTipoDocumentoBtn"])){
                $descripcion = ucwords(strtolower(trim($_POST["addTypeDocumentDescription"])));

                if(consultarExistenciaTipoDocumento($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(agregarTipoDocumento($descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Documento","Insertar", "Agregó un nuevo tipo de documento", $usuarioActivo);

                        $_SESSION["alerta"]="tipoDocumentoRegistrado";
                    }else{
                        $_SESSION["alerta"]="errorDeRegistro";
                    }
                }
            }

            //Actualizar tipo de documento
            if(isset($_POST["actualizarTipoDocumentoBtn"])){
                $id=$_POST["updateTypeDocumentId"];
                $descripcion = ucwords(strtolower(trim($_POST["updateTypeDocumentDescription"])));

                if(consultarExistenciaTipoDocumento($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(actualizarTipoDocumento($id, $descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Documento","Actualizar", "Actualizó el tipo de documento Nº {$id}", $usuarioActivo);

                        $_SESSION["alerta"]="tipoDocumentoActualizado";
                    }else{
                        $_SESSION["alerta"]="errorDeActualizacion";
                    }
                }
            }

            //Inactivar tipo de documento
            if(isset($_POST["inactivarTipoDocumentoBtn"])){
                $id=$_POST["inactivateTypeDocumentId"];

                $datosTipoDocumento=consultarDatosTipoDocumento($id);

                $estado = ($datosTipoDocumento["tipoDocEstado"]==="Activo") ? "Inactivo" : "Activo";

                if(actualizarEstadoTipoDocumento($id, $estado)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Tipo Documento","Activar/Inactivar", "Modificó el estado del tipo de documento Nº {$id}", $usuarioActivo);

                    $_SESSION["alerta"]="estadoTipoDocumentoActualizado";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacionEstado";
                }
            }
        //===

        //=== PERFIL USUARIO ===
            //Actualizar el perfil del usuario por parte de un administrador
            if(isset($_POST["actualizarPerfilUsuarioBtn"])){
                $id=$_POST["updateUserProfileId"];   

                $datosUsuario=consultarDatosUsuario($id);

                $nombre = ucwords(strtolower(trim($_POST["updateName"])));
                $tipo = trim($_POST["updateTypeUser"]);
                $tipoDocumento = trim($_POST["updateTypeId"]);
                $correo = strtolower(trim($_POST["updateEmail"]));
                $estadoCorreo = trim($_POST["updateEmailStatus"]);
                $barrio = ucwords(strtolower(trim($_POST["updateLocation"]))); 

                $avatar=$_FILES["updateAvatar"];  
            
                $cantidadJardineras = trim($_POST["updateGardensAmount"]); 

                //Actualizar el nombre del usuario
                $_SESSION["nuevoNombreUsuario"] = ($nombre!="") ? $nombre : $datosUsuario["usuNombre"];

                //Actualizar tipo de usuario
                $_SESSION["nuevoTipoUsuario"] = ($tipo!="0") ?  $tipo : $datosUsuario["usuTipoUsuario"];

                //Actualizar el tipo de documento del usuario
                $descripcionTipoDoc=$datosUsuario["idTipoDocumento"];

                $queryConsultarIdTipoDoc="SELECT * FROM tipo_documento WHERE tipoDocDescripcion='$descripcionTipoDoc'";
                $resultadoQuery=mysqli_query($conexion_db, $queryConsultarIdTipoDoc);

                if(mysqli_num_rows($resultadoQuery)>0){
                    $datosTipoDoc=mysqli_fetch_assoc($resultadoQuery);
                    $_SESSION["nuevoTipoDocumento"]=$datosTipoDoc["idTipoDocumento"];
                };

                //Actualizar correo
                $_SESSION["nuevoCorreoUsuario"] = ($correo!="") ? $correo : $datosUsuario["usuCorreo"];

                //Actualizar estado del correo
                $_SESSION["nuevoEstadoCorreo"] = ($estadoCorreo!="") ? $estadoCorreo : $datosUsuario["usuEstadoCorreo"];

                //Actualizar barrio o ubicación
                $_SESSION["nuevoBarrioUsuario"] = ($barrio!="") ? $barrio : $datosUsuario["usuBarrio"];

                //Actualizar avatar
                if(isset($avatar) && $avatar["error"]===0){
                    $nombreAvatar=$avatar["name"];
                    $tmpAvatar=$avatar["tmp_name"];

                    $nombreAvatarF=time()."_img_".$nombreAvatar;
                    $rutaImagenAvatar="../images/avatares/".$nombreAvatarF;

                    if(move_uploaded_file($tmpAvatar, $rutaImagenAvatar)){
                        $_SESSION["nuevoAvatarUsuario"]=$rutaImagenAvatar;
                    }else{
                        $_SESSION["alerta"]="errorAlSubirImagen";
                    }
                }else{
                    $_SESSION["nuevoAvatarUsuario"]=$datosUsuario["usuImagen"];
                }

                //Actualizar cantidad de jardineras
                $_SESSION["nuevoCantidadJardineras"] = ($cantidadJardineras!="") ? $cantidadJardineras: $datosUsuario["usuCantidadJardineras"];

                $nuevoNombre=$_SESSION["nuevoNombreUsuario"];
                $nuevoTipoUsuario=$_SESSION["nuevoTipoUsuario"];
                $nuevoTipoDocumento=$_SESSION["nuevoTipoDocumento"];
                $nuevoCorreo=$_SESSION["nuevoCorreoUsuario"];
                $nuevoEstadoCorreo=$_SESSION["nuevoEstadoCorreo"];
                $nuevoBarrio=$_SESSION["nuevoBarrioUsuario"];
                $nuevoAvatar=$_SESSION["nuevoAvatarUsuario"];
                $nuevaCantidadJardineras=$_SESSION["nuevoCantidadJardineras"];

                $resultadoActualizarPerfilUsuario=actualizarUsuario($nuevoNombre, $nuevoTipoUsuario, $nuevoTipoDocumento, $nuevoCorreo, $nuevoEstadoCorreo, $nuevoBarrio, $nuevoAvatar, $nuevaCantidadJardineras, $id);
                if($resultadoActualizarPerfilUsuario){

                    //Enviar correo electrónico para notificar al usuario su modificacion
                    require_once("../functions/enviarCorreos.php");

                    //Administrador que realizó la actualización
                    $nombreAdmin = $datosAdmin["usuNombre"];

                    //Usuario actualizado
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    //Enviar correo
                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,

                        "BioUrbis - Actualización de la información de su cuenta",

                        correoActualizacionCuenta(
                            $nombre,
                            $nombreAdmin,
                            $fechaActual,
                            $datosUsuario["usuNumeroDocumento"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Perfil Usuario","Actualizar", "Actualizó la información personal del usuario identificado con N° {$id}", $usuarioActivo);

                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="usuarioActualizado";

                }else{
                    $_SESSION["alerta"]="errorDeActualizacion";
                }
            } 

            //Inactivar la cuenta del usuario
            if(isset($_POST["inactivarUsuarioBtn"])){
                $id=$_POST["inactivateProfileId"];

                $datosUsuario=consultarDatosUsuario($id);

                $estado = ($datosUsuario["usuEstado"]==="Activo") ? "Inactivo": "Activo";

                if(actualizarEstadoUsuario($id, $estado)){
                    //Enviar correo electrónico para notificar al usuario su inactivacion
                    require_once("../functions/enviarCorreos.php");

                    //Administrador que realizó el cambio
                    $nombreAdmin = $datosAdmin["usuNombre"];

                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    //Enviar correo
                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Modificación del estado de su cuenta",

                        correoModificacionEstadoCuenta(
                            $nombre,
                            $nombreAdmin,
                            $fechaActual,
                            $datosUsuario["usuNumeroDocumento"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"]="errorAlEnviarCorreoInformativo";
                    }

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Perfil Usuario","Activar/Inactivar", "Modificó el estado de la cuenta del usuario identificado con N° {$id}", $usuarioActivo);

                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="estadoUsuarioActualizado";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacionEstado";
                }
            }
        //===

        //=== SOLICITUD ===
            //Cambiar el estado de la solicitud a confirmada
            if(isset($_POST["confirmarSolicitudBtn"])){
                $id=$_POST["confirmarSolicitudBtn"];  
                $estado="Confirmada";

                if(actualizarEstadoSolicitud($id, $estado)){
                    $datosSolicitud=consultarDatosSolicitud($id);
                    $tipoSolicitud=$datosSolicitud["soliAsunto"];

                    if($datosSolicitud["usuNumeroDocumento"]!==null){
                        require_once("../functions/enviarCorreos.php");
                        
                        $datosUsuario=consultarDatosUsuario($datosSolicitud["usuNumeroDocumento"]);

                        //Usuario al que se le modificó la cuenta
                        $correo = $datosUsuario["usuCorreo"];
                        $nombre = $datosUsuario["usuNombre"];

                        //Enviar correo
                        $enviado = enviarCorreo(
                            $correo,
                            $nombre,
                            "BioUrbis - Confirmación de su solicitud",

                            correoSolicitudConfirmada(
                                $nombre,
                                $tipoSolicitud
                            )
                        );

                        if(!$enviado){
                            $_SESSION["alerta"]="errorAlEnviarCorreoInformativo";
                        }
                    }

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Solicitud","Confirmar", "Confirmación de la solicitud N° {$id}", $usuarioActivo);

                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="estadoSolicitudActualizado";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacionEstado";
                }
            }

            //Cambiar el estado de la solicitud a rechazada
            if(isset($_POST["rechazarSolicitudBtn"])){
                $id=$_POST["rechazarSolicitudBtn"];  
                $estado="Rechazada";

                if(actualizarEstadoSolicitud($id, $estado)){
                    require_once("../functions/enviarCorreos.php");

                    $datosSolicitud=consultarDatosSolicitud($id);
                    $tipoSolicitud=$datosSolicitud["soliAsunto"];

                    if($datosSolicitud["usuNumeroDocumento"]!==null){
                        $datosUsuario=consultarDatosUsuario($datosSolicitud["usuNumeroDocumento"]);
                        
                        //Usuario al que se le modificó la cuenta
                        $correo = $datosUsuario["usuCorreo"];
                        $nombre = $datosUsuario["usuNombre"];

                        //Enviar correo
                        $enviado = enviarCorreo(
                            $correo,
                            $nombre,
                            "BioUrbis - Rechazo de su solicitud",

                            correoSolicitudRechazada(
                                $nombre,
                                $tipoSolicitud
                            )
                        );

                        if(!$enviado){
                            $_SESSION["alerta"]="errorAlEnviarCorreoInformativo";
                        }
                    }

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Solicitud","Rechazar", "Rechazo de la solicitud N° {$id}", $usuarioActivo);

                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="estadoSolicitudActualizado";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacionEstado";
                }
            }
        //===

        //=== RESEÑA ===
            //Inactivar la reseña de un usuario
            if(isset($_POST["inactivarResenaBtn"])){
                $id=$_POST["inactivateReviewId"];  

                $datosResena=consultarDatosResenaPorId($id);

                $estado = ($datosResena["resenaEstado"]==="Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoResena($id, $estado)){
                    require_once("../functions/enviarCorreos.php");

                    //Usuario al que se le modificó la cuenta
                    $correo = $datosResena["resenaCorreo"];
                    $nombre = $datosResena["resenaNombreUsuario"];

                    //Enviar correo
                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Su reseña ha sido desactivada",
                        correoResenaBloqueada($nombre)
                    );

                    if(!$enviado){
                        $_SESSION["alerta"]="errorAlEnviarCorreoInformativo";
                    }

                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Reseña","Actualizar", "Actualizar el estado de la reseña N° {$id}", $usuarioActivo);

                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="estadoResenaActualizado";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacionEstado";
                }
            }
        //===

        //=== SEMILLA ===
            //Agregar una nueva semilla
            if(isset($_POST["agregarSemillaBtn"])){
                $nombre = ucwords(strtolower(trim($_POST["addSeedName"])));

                $resultadoConsultarExistenciaSemilla=consultarExistenciaSemillaPorNombre($nombre);
                if(mysqli_num_rows($resultadoConsultarExistenciaSemilla)>0){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    $imagen=$_FILES["addSeedImage"];

                    $observaciones = ucfirst(trim($_POST["addSeedObservations"]));
                    $tipoSemilla=$_POST["addSeedType"];

                    $nombreImagen=$imagen["name"];
                    $tmpImagen=$imagen["tmp_name"];

                    $nombreImagenF=time().$nombreImagen;
                    $rutaImagenJardinera="images/semillas/img_".$nombreImagenF;

                    move_uploaded_file($tmpImagen, $rutaImagenJardinera);

                    if(agregarSemilla($nombre, $rutaImagenJardinera, $observaciones, $tipoSemilla)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Semilla","Agregar", "Agregó una nueva semilla", $usuarioActivo);

                        //Ejecutar mensaje de que los datos se actualizaron correctamente
                        $_SESSION["alerta"]="semillaRegistrada";
                    }else{
                        $_SESSION["alerta"]="errorDeRegistro";
                    }
                } 
            }

            //Actualizar una semilla
            if(isset($_POST["actualizarSemillaBtn"])){
                $id=$_POST["updateSeedId"]; 

                $datosSemilla=consultarDatosSemilla($id);

                $nombre = ucwords(strtolower(trim($_POST["updateSeedName"])));
                $imagen=$_FILES["updateSeedImage"];
                $observaciones = ucfirst(trim($_POST["updateSeedObservations"]));
                $tipoSemilla=$_POST["updateSeedType"];

                //Actualizar el nombre de la semilla
                $_SESSION["nuevoNombreSemilla"] = ($nombre!="") ? $nombre : $datosSemilla["semNombre"];

                //Actualizar la imagen de la semilla
                if(!empty($imagen["name"])){
                    $nombreImagen=$imagen["name"];
                    $tmpImagen=$imagen["tmp_name"];

                    $nombreImagenF=time().$nombreImagen;
                    $rutaNuevaImagenJardinera="images/semillas/img_".$nombreImagenF;

                    if(move_uploaded_file($tmpImagen, $rutaNuevaImagenJardinera)){
                        $_SESSION["nuevaImagenSemilla"]=$rutaNuevaImagenJardinera;
                    }else{
                        $_SESSION["alerta"]="errorAlSubirImagen";
                    }

                }else{
                    $_SESSION["nuevaImagenSemilla"]=$datosSemilla["semImagen"];
                }

                //Actualizar la observacion de la semilla
                $_SESSION["nuevaObservacionSemilla"] = ($observaciones!="") ? $observaciones : $datosSemilla["semObservaciones"];

                //Actualizar el tipo de semilla
                $_SESSION["nuevoTipoSemilla"] = ($tipoSemilla!="0") ? $tipoSemilla : $datosSemilla["idTipoSemilla"];

                $nuevoNombre=$_SESSION["nuevoNombreSemilla"];
                $nuevaImagen=$_SESSION["nuevaImagenSemilla"];
                $nuevaObservacion=$_SESSION["nuevaObservacionSemilla"];
                $nuevoTipoSemilla=$_SESSION["nuevoTipoSemilla"];

                if(actualizarSemilla($id, $nuevoNombre,  $nuevaImagen, $nuevaObservacion, $nuevoTipoSemilla)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Semilla","Actualizar", "Actualizó la semilla N° {$id}", $usuarioActivo);
                    
                    //Ejecutar mensaje de que los datos se actualizaron correctamente
                    $_SESSION["alerta"]="semillaActualizada";
                }else{
                    $_SESSION["alerta"]="errorDeActualizacion";
                }
            }

            //Modificar el estado  del tipo de semilla
            if(isset($_POST["inactivarSemillaBtn"])){
                $id = $_POST["inactivateSeedId"];

                //Consultar los datos de la semilla
                $datosSemilla = consultarDatosTodasSemilla($id);

                //Cambiar el estado
                $estado = ($datosSemilla["semEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoSemilla($id, $estado)){
                    //Registrar actividad
                    registrarActividadUsuario("Semilla","Actualizar","Actualizó el estado de la semilla N° {$id}",$usuarioActivo);

                    //Mostrar mensaje de éxito
                    $_SESSION["alerta"] = "estadoSemillaActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== TIPO DE SEMILLA ===
            //Agregar un nuevo tipo de semilla
            if(isset($_POST["agregarTipoSemillaBtn"])){
                $descripcion = ucwords(strtolower(trim($_POST["addTypeSeedDescription"])));

                if(consultarExistenciaTipoSemilla($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(registrarTipoSemilla($descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo de Semilla","Registrar", "Registró un nuevo tipo de semilla",$usuarioActivo);

                        //Ejecutar mensaje de que los datos se registraron correctamente
                        $_SESSION["alerta"] = "tipoSemillaRegistrado";
                    }else{
                        $_SESSION["alerta"] = "errorDeRegistro";
                    }
                }  
            }
            
            //Actualizar el tipo de semilla
            if(isset($_POST["actualizarTipoSemillaBtn"])){
                $id = $_POST["updateTypeSeedId"];
                $descripcion = ucwords(strtolower(trim($_POST["updateTypeSeedDescription"])));

                if(consultarExistenciaTipoSemilla($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(actualizarTipoSemilla($id, $descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Semilla","Actualizar","Actualizó el tipo de semilla Nº {$id}", $usuarioActivo);

                        $_SESSION["alerta"] = "tipoSemillaActualizado";
                    }else{
                        $_SESSION["alerta"] = "errorDeActualizacion";
                    }
                }
            }

            //Actualizar el estado del tipo de semilla
            if(isset($_POST["inactivarTipoSemillaBtn"])){
                $id = $_POST["inactivateTypeSeedId"];

                $datosTipoSemilla = consultarDatosTipoSemilla($id);

                $estado = ($datosTipoSemilla["tipoSemEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoTipoSemilla($id, $estado)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Tipo Semilla","Activar/Inactivar","Modificó el estado del tipo de semilla Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoTipoSemillaActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== FICHA TECNICA ===
            //Agregar un nueva ficha tecnica
            if(isset($_POST["agregarFichaTecnicaBtn"])){
                $datosFichaTecnica = [
                    "idSemilla" => trim($_POST["addSeedTS"]),
                    "idTipoClima" => trim($_POST["addTypeWeather"]),
                    "temperaturaMin" => trim($_POST["addMinTemperature"]),
                    "temperaturaMax" => trim($_POST["addMaxTemperature"]),
                    "humedadMin" => trim($_POST["addMinHumidity"]),
                    "humedadMax" => trim($_POST["addMaxHumidity"]),
                    "cantidadAguaMin" => trim($_POST["addMinWaterAmount"]),
                    "cantidadAguaMax" => trim($_POST["addMaxWaterAmount"]),
                    "idTipoTierra" => trim($_POST["addTypeIdProfile"]),
                    "cantidadTierraMin" => trim($_POST["addMinSoilAmount"]),
                    "cantidadTierraMax" => trim($_POST["addMaxSoilAmount"]),
                    "espacio" => trim($_POST["addPlot"])
                ];

                $resultadoRegistrarFichaTecnica = registrarFichaTecnica($datosFichaTecnica);

                if($resultadoRegistrarFichaTecnica!=false){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Ficha Técnica","Registrar","Registró una nueva ficha técnica",$usuarioActivo);

                    $_SESSION["alerta"] = "fichaTecnicaRegistrada";
                }else{
                    $_SESSION["alerta"] = "errorDeRegistro";
                }
            }
            
            //Actualizar la ficha tecnica
            if(isset($_POST["actualizarFichaTecnicaBtn"])){
                $id = $_POST["updateTechnicalSheetId"];

                $datosFichaTecnica = consultarDatosFichaTecnica($id);

                $tipoClima = trim($_POST["updateTypeWeather"]);
                $temperaturaMin = trim($_POST["updateMinTemperature"]);
                $temperaturaMax = trim($_POST["updateMaxTemperature"]);
                $humedadMin = trim($_POST["updateMinHumidity"]);
                $humedadMax = trim($_POST["updateMaxHumidity"]);
                $cantidadAguaMin = trim($_POST["updateMinWaterAmount"]);
                $cantidadAguaMax = trim($_POST["updateMaxWaterAmount"]);
                $tipoTierra = trim($_POST["updateTypeIdProfile"]);
                $cantidadTierraMin = trim($_POST["updateMinSoilAmount"]);
                $cantidadTierraMax = trim($_POST["updateMaxSoilAmount"]);
                $espacio = trim($_POST["updatePlot"]);

                $_SESSION["nuevoTipoClima"] = ($tipoClima != "0")? $tipoClima : $datosFichaTecnica["idTipoClima"];

                $_SESSION["nuevaTemperaturaMin"] = ($temperaturaMin != "") ? $temperaturaMin : $datosFichaTecnica["fichaTemperaturaMin"];

                $_SESSION["nuevaTemperaturaMax"] = ($temperaturaMax != "") ? $temperaturaMax : $datosFichaTecnica["fichaTemperaturaMax"];

                $_SESSION["nuevaHumedadMin"] = ($humedadMin != "") ? $humedadMin : $datosFichaTecnica["fichaHumedadMin"];

                $_SESSION["nuevaHumedadMax"] = ($humedadMax != "") ? $humedadMax : $datosFichaTecnica["fichaHumedadMax"];

                $_SESSION["nuevaCantidadAguaMin"] = ($cantidadAguaMin != "") ? $cantidadAguaMin : $datosFichaTecnica["fichaCantidadAguaMin"];

                $_SESSION["nuevaCantidadAguaMax"] = ($cantidadAguaMax != "") ? $cantidadAguaMax : $datosFichaTecnica["fichaCantidadAguaMax"];

                $_SESSION["nuevoTipoTierra"] = ($tipoTierra != "0") ? $tipoTierra : $datosFichaTecnica["idTipoTierra"];

                $_SESSION["nuevaCantidadTierraMin"] = ($cantidadTierraMin != "") ? $cantidadTierraMin : $datosFichaTecnica["fichaCantidadTierraMin"];

                $_SESSION["nuevaCantidadTierraMax"] = ($cantidadTierraMax != "")  ? $cantidadTierraMax : $datosFichaTecnica["fichaCantidadTierraMax"];

                $_SESSION["nuevoEspacio"] = ($espacio != "") ? $espacio : $datosFichaTecnica["fichaEspacio"];

                $nuevosDatosFichaTecnica = [
                    "idTipoClima" => $_SESSION["nuevoTipoClima"],
                    "temperaturaMin" => $_SESSION["nuevaTemperaturaMin"],
                    "temperaturaMax" => $_SESSION["nuevaTemperaturaMax"],
                    "humedadMin" => $_SESSION["nuevaHumedadMin"],
                    "humedadMax" => $_SESSION["nuevaHumedadMax"],
                    "cantidadAguaMin" => $_SESSION["nuevaCantidadAguaMin"],
                    "cantidadAguaMax" => $_SESSION["nuevaCantidadAguaMax"],
                    "idTipoTierra" => $_SESSION["nuevoTipoTierra"],
                    "cantidadTierraMin" => $_SESSION["nuevaCantidadTierraMin"],
                    "cantidadTierraMax" => $_SESSION["nuevaCantidadTierraMax"],
                    "espacio" => $_SESSION["nuevoEspacio"]
                ];

                if(actualizarFichaTecnica($id, $nuevosDatosFichaTecnica)){
                    $_SESSION["alerta"] = "fichaTecnicaActualizada";

                    registrarActividadUsuario("Ficha Técnica","Actualizar","Actualizó la ficha técnica N° {$id}", $usuarioActivo);
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }
        //===

        //=== TIPO DE CLIMA ===
            //Agregar un nuevo tipo de clima
            if(isset($_POST["agregarTipoClimaBtn"])){
                $descripcion = ucwords(strtolower(trim($_POST["addTypeWeatherDescription"])));

                if(consultarExistenciaTipoClima($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(registrarTipoClima($descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Clima","Registrar", "Registró un nuevo tipo de clima",$usuarioActivo);

                        $_SESSION["alerta"] = "tipoClimaRegistrado";
                    }else{
                        $_SESSION["alerta"] = "errorDeRegistro";
                    }
                }
            }

            //Actualizar el tipo de clima
            if(isset($_POST["actualizarTipoClimaBtn"])){
                $id = $_POST["updateTypeWeatherId"];
                $descripcion = ucwords(strtolower(trim($_POST["updateTypeWeatherDescription"])));

                if(consultarExistenciaTipoClima($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(actualizarTipoClima($id, $descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Clima","Actualizar","Actualizó el tipo de clima Nº {$id}",$usuarioActivo);

                        $_SESSION["alerta"] = "tipoClimaActualizado";
                    }else{
                        $_SESSION["alerta"] = "errorDeActualizacion";
                    }
                }
            }

            //Actualizar el estado del tipo clima
            if(isset($_POST["inactivarTipoClimaBtn"])){
                $id = $_POST["inactivateTypeWeatherId"];

                $datosTipoClima = consultarDatosTipoClima($id);

                $estado = ($datosTipoClima["tipoClimaEstado"] === "Activo") ? "Inactivo" : "Activo";

                if(actualizarEstadoTipoClima($id, $estado)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Tipo Clima", "Activar/Inactivar","Modificó el estado del tipo de clima Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoTipoClimaActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== TIPO DE TIERRA ===
            //Agregar un nuevo tipo de tierra
            if(isset($_POST["agregarTipoTierraBtn"])){
                $descripcion = ucwords(strtolower(trim($_POST["addTypeSoilDescription"])));

                if(consultarExistenciaTipoTierra($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(registrarTipoTierra($descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Tierra","Registrar","Registró un nuevo tipo de tierra",$usuarioActivo);

                        $_SESSION["alerta"] = "tipoTierraRegistrado";
                    }else{
                        $_SESSION["alerta"] = "errorDeRegistro";
                    }
                }
            }

            //Actualizar el tipo de tierra
            if(isset($_POST["actualizarTipoTierraBtn"])){
                $id = $_POST["updateTypeSoilId"];
                $descripcion = ucwords(strtolower(trim($_POST["updateTypeSoilDescription"])));

                if(consultarExistenciaTipoTierra($descripcion)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    if(actualizarTipoTierra($id, $descripcion)){
                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Tipo Tierra","Actualizar", "Actualizó el tipo de tierra Nº {$id}",$usuarioActivo);

                        $_SESSION["alerta"] = "tipoTierraActualizado";
                    }else{
                        $_SESSION["alerta"] = "errorDeActualizacion";
                    }
                }
            }

            //Actualizar el estado del tipo de tierra
            if(isset($_POST["inactivarTipoTierraBtn"])){
                $id = $_POST["inactivateTypeSoilId"];

                $datosTipoTierra = consultarDatosTipoTierra($id);

                $estado = ($datosTipoTierra["tipoTierraEstado"] === "Activo") ? "Inactivo" : "Activo";

                if(actualizarEstadoTipoTierra($id, $estado)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Tipo Tierra","Activar/Inactivar","Modificó el estado del tipo de tierra Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoTipoTierraActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== ETAPA CRECIMIENTO
            //Agregar una nueva etapa de crecimiento
            if(isset($_POST["agregarEtapaCrecimientoBtn"])){

                $datosEtapaCrecimiento = [
                    "idSemilla" => trim($_POST["addSeedGS"]),
                    "germinacionMin" => trim($_POST["addGerminationMin"]),
                    "germinacionMax" => trim($_POST["addGerminationMax"]),
                    "desarrolloVegetativoMin" => trim($_POST["addVegetativeGrowthMin"]),
                    "desarrolloVegetativoMax" => trim($_POST["addVegetativeGrowthMax"]),
                    "floracionMin" => trim($_POST["addFloweringMin"]),
                    "floracionMax" => trim($_POST["addFloweringMax"]),
                    "llenadoGranosMin" => trim($_POST["addGrainFillingMin"]),
                    "llenadoGranosMax" => trim($_POST["addGrainFillingMax"]),
                    "cosechaMin" => trim($_POST["addHarvestMin"]),
                    "cosechaMax" => trim($_POST["addHarvestMax"])
                ];

                $resultadoRegistrarEtapaCrecimiento = registrarEtapaCrecimiento($datosEtapaCrecimiento);

                if($resultadoRegistrarEtapaCrecimiento!=false){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Etapa Crecimiento","Registrar","Registró una nueva etapa crecimiento",$usuarioActivo);

                    $_SESSION["alerta"] = "etapaCrecimientoRegistrada";
                }else{
                    $_SESSION["alerta"] = "errorDeRegistro";
                }
            }

            //Actualizar una etapa de crecimiento
            if(isset($_POST["actualizarEtapaCrecimientoBtn"])){
                $id = $_POST["updateGrowthStagesId"];

                $datosEtapaCrecimiento = consultarDatosEtapaCrecimiento($id);

                $germinacionMin = trim($_POST["updateGerminationMin"]);
                $germinacionMax = trim($_POST["updateGerminationMax"]);

                $desarrolloVegetativoMin = trim($_POST["updateVegetativeGrowthMin"]);
                $desarrolloVegetativoMax = trim($_POST["updateVegetativeGrowthMax"]);

                $floracionMin = trim($_POST["updateFloweringMin"]);
                $floracionMax = trim($_POST["updateFloweringMax"]);

                $llenadoGranosMin = trim($_POST["updateGrainFillingMin"]);
                $llenadoGranosMax = trim($_POST["updateGrainFillingMax"]);

                $cosechaMin = trim($_POST["updateHarvestMin"]);
                $cosechaMax = trim($_POST["updateHarvestMax"]);

                $_SESSION["nuevaGerminacionMin"] = ($germinacionMin != "") ? $germinacionMin : $datosEtapaCrecimiento["etapaCreDiasGerminacionMin"];

                $_SESSION["nuevaGerminacionMax"] = ($germinacionMax != "") ? $germinacionMax : $datosEtapaCrecimiento["etapaCreDiasGerminacionMax"];

                $_SESSION["nuevoDesarrolloVegetativoMin"] = ($desarrolloVegetativoMin != "") ? $desarrolloVegetativoMin: $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"];

                $_SESSION["nuevoDesarrolloVegetativoMax"] = ($desarrolloVegetativoMax != "") ? $desarrolloVegetativoMax : $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"];

                $_SESSION["nuevaFloracionMin"] = ($floracionMin != "") ? $floracionMin : $datosEtapaCrecimiento["etapaCreDiasFloracionMin"];

                $_SESSION["nuevaFloracionMax"] = ($floracionMax != "") ? $floracionMax  : $datosEtapaCrecimiento["etapaCreDiasFloracionMax"];

                $_SESSION["nuevoLlenadoGranosMin"] = ($llenadoGranosMin != "") ? $llenadoGranosMin : $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"];

                $_SESSION["nuevoLlenadoGranosMax"] = ($llenadoGranosMax != "") ? $llenadoGranosMax : $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"];

                $_SESSION["nuevaCosechaMin"] = ($cosechaMin != "") ? $cosechaMin : $datosEtapaCrecimiento["etapaCreDiasCosechaMin"];

                $_SESSION["nuevaCosechaMax"] = ($cosechaMax != "")  ? $cosechaMax : $datosEtapaCrecimiento["etapaCreDiasCosechaMax"];

                $nuevosDatosEtapaCrecimiento = [
                    "germinacionMin" => $_SESSION["nuevaGerminacionMin"],
                    "germinacionMax" => $_SESSION["nuevaGerminacionMax"],
                    "desarrolloVegetativoMin" => $_SESSION["nuevoDesarrolloVegetativoMin"],
                    "desarrolloVegetativoMax" => $_SESSION["nuevoDesarrolloVegetativoMax"],
                    "floracionMin" => $_SESSION["nuevaFloracionMin"],
                    "floracionMax" => $_SESSION["nuevaFloracionMax"],
                    "llenadoGranosMin" => $_SESSION["nuevoLlenadoGranosMin"],
                    "llenadoGranosMax" => $_SESSION["nuevoLlenadoGranosMax"],
                    "cosechaMin" => $_SESSION["nuevaCosechaMin"],
                    "cosechaMax" => $_SESSION["nuevaCosechaMax"]
                ];

                if(actualizarEtapaCrecimiento($id, $nuevosDatosEtapaCrecimiento)){  

                    registrarActividadUsuario("Etapa Crecimiento", "Actualizar", "Actualizó la etapa de crecimiento N° {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "etapaCrecimientoActualizada";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }
        //===

        //=== JARDINERA
            //Actualizar la jardinera
            if(isset($_POST["actualizarJardineraBtn"])){
                $id = $_POST["updateGardenId"];

                $datosJardinera = consultarDatosJardineraPorId($id);

                $nombre = ucwords(strtolower(trim($_POST["updateGardenName"])));
                $descripcion = ucfirst(strtolower(trim($_POST["updateGardenDescription"])));
                $fase = trim($_POST["updateStageSeed"]);

                $_SESSION["nuevoNombreJardinera"] = ($nombre != "") ? $nombre : $datosJardinera["jarNombre"];

                $_SESSION["nuevaDescripcionJardinera"] = ($descripcion != "") ? $descripcion  : $datosJardinera["jarDescripcion"];

                $_SESSION["nuevaFaseJardinera"] =  ($fase != "0")  ? $fase : $datosJardinera["idFase"];

                $nuevoNombre=$_SESSION["nuevoNombreJardinera"];
                $nuevaDescripcion=$_SESSION["nuevaDescripcionJardinera"];
                $nuevaIdFase=$_SESSION["nuevaFaseJardinera"];

                if(actualizarJardineraAdmin($id, $nuevoNombre, $nuevaDescripcion, $nuevaIdFase)){
                    require_once("../functions/enviarCorreos.php");

                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Actualización de su jardinera",
                        correoActualizacionJardinera(
                            $nombre,
                            $datosJardinera["jarNombre"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Jardinera","Actualizar","Actualizó la jardinera N° {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "jardineraActualizada";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado de la jardinera
            if(isset($_POST["inactivarJardineraBtn"])){

                $id = $_POST["inactivateGardenId"];

                $datosJardinera = consultarDatosJardineraPorId($id);

                $estado = ($datosJardinera["jarEstado"] === "Activa") ? "Inactiva" : "Activa";

                $idUsuario=$datosJardinera["usuNumeroDocumento"];

                $resultadoActualizarEstadoJardinera =actualizarEstadoJardinera($id, $idUsuario, $estado);

                if($resultadoActualizarEstadoJardinera!=false){
                    require_once("../functions/enviarCorreos.php");

                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Jardinera inactivada",
                        correoJardineraInactiva(
                            $nombre,
                            $datosJardinera["jarNombre"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Jardinera","Activar/Inactivar","Modificó el estado de la jardinera Nº {$id}", $usuarioActivo);

                    $_SESSION["alerta"] = "estadoJardineraActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== ALERTAS 
            //Actualizar la alerta
            if(isset($_POST["actualizarAlertaBtn"])){

                $id = $_POST["updateAlertId"];

                $datosAlerta = consultarDatosAlertaPorId($id);

                $tipo = trim($_POST["updateAlertType"]);
                $descripcion = ucfirst(strtolower(trim($_POST["updateAlertDescription"])));
                $recomendacion = ucfirst(strtolower(trim($_POST["updateAlertRecommendation"])));
                $valorRegistrado = trim($_POST["updateAlertRecordedValue"]);
                $rangoRecomendado = trim($_POST["updateAlertRecomendedRange"]);

                $_SESSION["nuevoTipoAlerta"] = ($tipo != "0") ? $tipo : $datosAlerta["alerTipo"];

                $_SESSION["nuevaDescripcionAlerta"] = ($descripcion != "") ? $descripcion : $datosAlerta["alerDescripcion"];

                $_SESSION["nuevaRecomendacionAlerta"] = ($recomendacion != "") ? $recomendacion : $datosAlerta["alerRecomendacion"];

                $_SESSION["nuevoValorRegistradoAlerta"] = ($valorRegistrado != "") ? $valorRegistrado : $datosAlerta["alerValorRegistrado"];

                $_SESSION["nuevoRangoRecomendadoAlerta"] = ($rangoRecomendado != "") ? $rangoRecomendado : $datosAlerta["alerRangoRecomendado"];

                $nuevoTipo = $_SESSION["nuevoTipoAlerta"];
                $nuevaDescripcion = $_SESSION["nuevaDescripcionAlerta"];
                $nuevaRecomendacion = $_SESSION["nuevaRecomendacionAlerta"];
                $nuevoValorRegistrado = $_SESSION["nuevoValorRegistradoAlerta"];
                $nuevoRangoRecomendado = $_SESSION["nuevoRangoRecomendadoAlerta"];

                if(actualizarAlerta(
                    $id,
                    $nuevoTipo,
                    $nuevaDescripcion,
                    $nuevaRecomendacion,
                    $nuevoValorRegistrado,
                    $nuevoRangoRecomendado
                )){

                    registrarActividadUsuario(
                        "Alerta",
                        "Actualizar",
                        "Actualizó la alerta N° {$id}",
                        $usuarioActivo
                    );

                    $_SESSION["alerta"] = "alertaActualizada";

                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado de la alerta
            if(isset($_POST["inactivarAlertaBtn"])){

                $id = $_POST["inactivateAlertId"];

                $datosAlerta = consultarDatosAlertaPorId($id);

                $estado = ($datosAlerta["alerEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoAlertaAdmin($id, $estado)){

                    registrarActividadUsuario(
                        "Alerta",
                        "Activar/Inactivar",
                        "Modificó el estado de la alerta N° {$id}",
                        $usuarioActivo
                    );

                    $_SESSION["alerta"] = "estadoAlertaActualizado";

                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== FACTOR EXTERNO
            //Actualizar el factor externo 
            if(isset($_POST["actualizarFactorExternoBtn"])){

                $id = $_POST["updateExternalFactorId"];

                $datosFactorExterno = consultarDatosFactorExterno($id);

                $humedad = trim($_POST["updateHumidity"]);
                $tipoClima = trim($_POST["updateTypeWeatherF"]);
                $temperatura = trim($_POST["updateTemperature"]);
                $cantidadAgua = trim($_POST["updateWaterAmount"]);

                $_SESSION["nuevaHumedad"] = ($humedad != "") ? $humedad : $datosFactorExterno["factHumedad"];

                $_SESSION["nuevoTipoClima"] = ($tipoClima != "0") ? $tipoClima : $datosFactorExterno["idTipoClima"];

                $_SESSION["nuevaTemperatura"] = ($temperatura != "") ? $temperatura : $datosFactorExterno["factTemperatura"];

                $_SESSION["nuevaCantidadAgua"] = ($cantidadAgua != "") ? $cantidadAgua : $datosFactorExterno["factCantidadAgua"];

                $nuevoHumedad=$_SESSION["nuevaHumedad"];
                $nuevoIdTipoClima=$_SESSION["nuevoTipoClima"];
                $nuevaTemperatura=$_SESSION["nuevaTemperatura"];
                $nuevaCantidadAgua=$_SESSION["nuevaCantidadAgua"];

                if(actualizarFactorExterno($id, $nuevoHumedad,  $nuevoIdTipoClima, $nuevaTemperatura, $nuevaCantidadAgua)){
                    require_once("../functions/enviarCorreos.php");

                    $datosJardinera=consultarDatosJardineraPorId($datosFactorExterno["idJardinera"]);
                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Actualización de factor externo",
                        correoActualizacionFactorExterno(
                            $nombre,
                            $datosJardinera["jarNombre"],
                            $datosFactorExterno["idFactoresExternos"],
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Factor Externo","Actualizar","Actualizó el factor externo Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "factorExternoActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado del factor externo
            if(isset($_POST["inactivarFactorExternoBtn"])){

                $id = $_POST["inactivateExternalFactorId"];

                $datosFactorExterno =  consultarDatosFactorExterno($id);

                $estado = ($datosFactorExterno["factEstado"] === "Evaluado") ? "Registrado" : "Evaluado";

                if(actualizarEstadoFactorExterno($id, $estado)){
                    require_once("../functions/enviarCorreos.php");

                    $datosJardinera=consultarDatosJardineraPorId($datosFactorExterno["idJardinera"]);
                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Factor externo inactivado",
                        correoInactivacionFactorExterno(
                            $nombre,
                            $datosJardinera["jarNombre"],
                            $datosFactorExterno["idFactoresExternos"],
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Factor Externo","Evaluar/Registrar","Modificó el estado del factor externo Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoFactorExternoActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== SEGUIMIENTO JARDINERA - MONITOREO
            //Actualizar el monitoreo
            if(isset($_POST["actualizarMonitoreoBtn"])){

                $id = $_POST["updateMonitoringId"];

                $datosMonitoreo = consultarDatosMonitoreo($id);

                $nota = ucwords(strtolower(trim($_POST["updateNote"])));
                $porcentaje = trim($_POST["updatePercentage"]);
                $imagen = $_FILES["updateImage"];

                $_SESSION["nuevaNotaMonitoreo"] = ($nota != "") ? $nota : $datosMonitoreo["segJardineraNota"];

                $_SESSION["nuevoPorcentajeMonitoreo"] = ($porcentaje != "") ? $porcentaje : $datosMonitoreo["segJardineraPorcentaje"];

                if($imagen["error"] == 0){

                    $nombreImagen = time() . "_" . $imagen["name"];
                    $rutaImagenMonitoreo="../images/seguimiento/" . $nombreImagen;

                    move_uploaded_file($imagen["tmp_name"], $rutaImagenMonitoreo);

                    $_SESSION["nuevaImagenMonitoreo"] = $rutaImagenMonitoreo;

                }else{
                    $_SESSION["nuevaImagenMonitoreo"] =$datosMonitoreo["segJardineraImagen"];
                }

                $nuevaNota=$_SESSION["nuevaNotaMonitoreo"];
                $nuevaImagen=$_SESSION["nuevaImagenMonitoreo"];
                $nuevoPorcentaje=$_SESSION["nuevoPorcentajeMonitoreo"];

                if(actualizarMonitoreoAdmin($id,$nuevaNota,$nuevaImagen,$nuevoPorcentaje)){
                    require_once("../functions/enviarCorreos.php");

                    $datosJardinera=consultarDatosJardineraPorId($datosMonitoreo["idJardinera"]);
                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Actualización del seguimiento de su jardinera",
                        correoActualizacionMonitoreo(
                            $nombre,
                            $datosJardinera["jarNombre"],
                            $datosMonitoreo["idSeguimiento"], 
                            $datosMonitoreo["segJardineraFecha"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Monitoreo","Actualizar","Actualizó el monitoreo Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "monitoreoActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado del monitoreo
            if(isset($_POST["inactivarMonitoreoBtn"])){

                $id = $_POST["inactivateMonitoringId"];

                $datosMonitoreo = consultarDatosMonitoreo($id);

                $estado = ($datosMonitoreo["segJardineraEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoMonitoreo($id, $estado)){
                    require_once("../functions/enviarCorreos.php");

                    $datosJardinera=consultarDatosJardineraPorId($datosMonitoreo["idJardinera"]);
                    $datosUsuario=consultarDatosUsuario($datosJardinera["usuNumeroDocumento"]);
                        
                    //Usuario al que se le modificó la cuenta
                    $correo = $datosUsuario["usuCorreo"];
                    $nombre = $datosUsuario["usuNombre"];

                    $enviado = enviarCorreo(
                        $correo,
                        $nombre,
                        "BioUrbis - Monitoreo inactivado",
                        correoInactivacionMonitoreo(
                            $nombre,
                            $datosJardinera["jarNombre"],
                            $datosMonitoreo["idSeguimiento"],
                            $datosMonitoreo["segJardineraFecha"]
                        )
                    );

                    if(!$enviado){
                        $_SESSION["alerta"] = "errorAlEnviarCorreoInformativo";
                    }

                    registrarActividadUsuario("Monitoreo", "Activar/Inactivar", "Modificó el estado del monitoreo Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoMonitoreoActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== FASE
            //Agregar una nueva fase 
            if(isset($_POST["agregarFaseBtn"])){
                $nombre = ucwords(strtolower(trim($_POST["addStageName"])));

                if(consultarExistenciaFase($nombre)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    $descripcion = ucfirst(strtolower(trim($_POST["addStageDescription"])));
                    $porcentaje = trim($_POST["addStagePercentage"]);

                    $resultadoRegistrarFase = registrarFase($nombre,$descripcion, $porcentaje);

                    if($resultadoRegistrarFase !== false){
                        registrarActividadUsuario("Fase","Registrar","Registró una nueva fase", $usuarioActivo);

                        $_SESSION["alerta"] = "faseRegistrada";
                    }else{
                        $_SESSION["alerta"] = "errorDeRegistro";
                    }
                }
            }
            
            //Actualizar la fase
            if(isset($_POST["actualizarFaseBtn"])){

                $id = $_POST["updateStagesId"];

                $datosFase = consultarDatosFase($id);

                $nombre = ucwords(strtolower(trim($_POST["updateStageName"])));
                $descripcion = ucfirst(strtolower(trim($_POST["updateStageDescription"])));
                $porcentaje = trim($_POST["updateStagePercentage"]);

                $_SESSION["nuevoNombreFase"] = ($nombre != "") ? $nombre : $datosFase["faseNombre"];

                $_SESSION["nuevaDescripcionFase"] = ($descripcion != "") ? $descripcion  : $datosFase["faseDescripcion"];

                $_SESSION["nuevoPorcentajeFase"] =  ($porcentaje != "")  ? $porcentaje : $datosFase["fasePorcentaje"];

                $nombre=$_SESSION["nuevoNombreFase"];
                $descripcion=$_SESSION["nuevaDescripcionFase"];
                $porcentaje=$_SESSION["nuevoPorcentajeFase"];

                if(actualizarFase($id, $nombre,$descripcion, $porcentaje )){
                    registrarActividadUsuario("Fase","Actualizar","Actualizó la fase Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "faseActualizada";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado de la fase
            if(isset($_POST["inactivarFaseBtn"])){

                $id = $_POST["inactivateStagesId"];

                $datosFase = consultarDatosFase($id);

                $estado = ($datosFase["faseEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoFase($id, $estado)){
                    registrarActividadUsuario("Fase","Activar/Inactivar","Modificó el estado de la fase Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoFaseActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===

        //=== PREGUNTA FASE
            //Agregar una nueva pregunta a la fase 
            if(isset($_POST["agregarPreguntaFaseBtn"])){
                $pregunta = ucfirst(strtolower(trim($_POST["addStageQuestionsQuestion"])));

                if(consultarExistenciaPreguntaFase($pregunta)){
                    $_SESSION["alerta"]="errorDeRegistroExistente";
                }else{
                    $idFase=trim($_POST["addStageQuestionsStageId"]);
                    $porcentaje=trim($_POST["addStageQuestionsPercentage"]);

                    if(registrarPreguntaFase($pregunta, $porcentaje, $idFase)){
                        registrarActividadUsuario("Pregunta Fase", "Registrar", "Registró una nueva pregunta para la fase Nº {$idFase}", $usuarioActivo);

                        $_SESSION["alerta"] = "preguntaFaseRegistrada";
                    }else{
                        $_SESSION["alerta"] = "errorDeRegistro";
                    }
                }
            }

            //Actualizar la pregunta de la fase
            if(isset($_POST["actualizarPreguntaFaseBtn"])){

                $id = $_POST["updateStageQuestionId"];

                $datosPreguntaFase = consultarDatosPreguntaFase($id);

                $pregunta = ucfirst(strtolower(trim($_POST["updateStageQuestionsQuestion"])));
                $porcentaje = trim($_POST["updateStageQuestionsPercentage"]);

                $_SESSION["nuevaPreguntaFase"] = ($pregunta != "")  ? $pregunta  : $datosPreguntaFase["pregDescripcion"];

                $_SESSION["nuevoPorcentajePreguntaFase"] = ($porcentaje != "") ? $porcentaje : $datosPreguntaFase["pregPorcentaje"];

                $pregunta = $_SESSION["nuevaPreguntaFase"];
                $porcentaje = $_SESSION["nuevoPorcentajePreguntaFase"];

                if(actualizarPreguntaFase($id, $pregunta, $porcentaje)){
                    registrarActividadUsuario("Pregunta Fase","Actualizar", "Actualizó la pregunta de seguimiento Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "preguntaFaseActualizada";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacion";
                }
            }

            //Actualizar el estado de la pregunta de la fase
            if(isset($_POST["inactivarPreguntaFaseBtn"])){

                $id = $_POST["inactivateStageQuestionsId"];

                $datosPreguntaFase = consultarDatosPreguntaFase($id);

                $estado = ($datosPreguntaFase["pregEstado"] === "Activa") ? "Inactiva" : "Activa";

                if(actualizarEstadoPreguntaFase($id,$estado)){
                    registrarActividadUsuario("Pregunta Fase","Activar/Inactivar","Modificó el estado de la pregunta de seguimiento Nº {$id}",$usuarioActivo);

                    $_SESSION["alerta"] = "estadoPreguntaFaseActualizado";
                }else{
                    $_SESSION["alerta"] = "errorDeActualizacionEstado";
                }
            }
        //===
    ?>

    <div class="dashboard">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">

            <div class="sidebar-top">
                <div class="logo">
                    <h2>BioUrbis</h2>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Mostrar u ocultar menú">
                    <i id="sidebarToggleIcon" class="fas fa-angle-double-left"></i>
                </button>
            </div>

            <nav>
                <ul>
                    <li onclick="showModule('dashboard', this)" class="active">
                        <i class="fas fa-home"></i><span class="menu-text">Inicio</span>
                    </li>

                    <li onclick="showModule('users', this)">
                        <i class="fas fa-users"></i><span class="menu-text">Usuarios</span>
                    </li>

                    <li onclick="showModule('type-documents', this)">
                        <i class="fas fa-address-card"></i><span class="menu-text">Tipos de Documento</span>
                    </li>

                    <li onclick="showModule('requests', this)">
                        <i class="fas fa-file-alt"></i><span class="menu-text">Solicitudes</span>
                    </li>

                    <li onclick="showModule('reviews', this)">
                        <i class="fas fa-star"></i><span class="menu-text">Reseñas</span>
                    </li>

                    <li onclick="showModule('seeds', this)">
                        <i class="fas fa-seedling"></i><span class="menu-text">Semillas</span>
                    </li>

                    <li onclick="showModule('type-seeds', this)">
                        <i class="fas fa-spa"></i><span class="menu-text">Tipos de Semilla</span>
                    </li> 

                    <li onclick="showModule('technical-sheet', this)">
                        <i class="fas fa-clipboard-list"></i><span class="menu-text">Ficha Técnica</span>
                    </li>

                    <li onclick="showModule('type-weathers', this)">
                        <i class="fas fa-cloud-sun"></i><span class="menu-text">Tipos de Clima</span>
                    </li>

                    <li onclick="showModule('type-soils', this)">
                        <i class="fas fa-leaf"></i><span class="menu-text">Tipos de Tierra</span>
                    </li>

                    <li onclick="showModule('growth-stages', this)">
                        <i class="fas fa-chart-line"></i><span class="menu-text">Etapas Crecimiento</span>
                    </li>

                    <li onclick="showModule('gardens', this)">
                        <i class="fas fa-box-open"></i><span class="menu-text">Jardineras</span>
                    </li>

                    <li onclick="showModule('external-factors', this)">
                        <i class="fas fa-cloud-sun"></i><span class="menu-text">Factores Externos</span>
                    </li>

                    <li onclick="showModule('monitoring', this)">
                        <i class="fas fa-chart-bar"></i><span class="menu-text">Seguimiento Jardinera</span>
                    </li>

                    <li onclick="showModule('alerts', this)">
                        <i class="fas fa-bell"></i><span class="menu-text">Alertas</span>
                    </li>

                    <li onclick="showModule('stages', this)">
                        <i class="fas fa-layer-group"></i><span class="menu-text">Fases</span>
                    </li>
                </ul>
            </nav>

            <button class="logout-btn" onclick="cerrarSesion()">
                <i class="fas fa-sign-out-alt"></i><span class="logout-text menu-text">Cerrar Sesión</span>
            </button>
        </aside>

        <!-- MAIN -->
        <main class="main-content">

            <!-- HEADER -->
            <header class="topbar">
            </header>

            <!-- DASHBOARD -->
            <section id="dashboard" class="module active-module">

                <section class="profile-card">

                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img src="<?php echo $datosAdmin["usuImagen"] ?>" alt="Avatar del usuario" id="profileImage">
                            <form action="homeAdmin.php" method="POST" enctype="multipart/form-data">
                                <input type="file" name="imgAvatar" id="imgAvatar" style="display:none;" onchange="enviarFormulario()">
                                <button type="button" onclick="subirImagen()" class="edit-avatar" id="editAvatarBtn" name ="editAvatarBtn" >
                                    <i class="fas fa-camera"></i>
                                </button>
                            </form>
                        </div>

                        <div class="profile-info">
                            <h2><?php echo $datosAdmin["usuNombre"]?></h2>
                            <p>Administrador del Sistema BioUrbis</p>
                            <?php $idAdmin=md5($datosAdmin["usuNumeroDocumento"]) ?>
                            <span>ID: <?php echo $idAdmin?> </span>
                        </div>

                        <button class="edit-btn editAdminProfileBtn" id="editAdminProfileBtn">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                    <div class="info-grid">
                        <div class="info-box">
                            <h3>Información Personal</h3>

                            <div class="info-row">
                                <span>Nombre Completo</span>
                                <p><?php echo $datosAdmin["usuNombre"]?></p>
                            </div>

                            <div class="info-row">
                                <span>Correo Electrónico</span>
                                <p><?php echo $datosAdmin["usuCorreo"]?></p>
                            </div>

                            <div class="info-row">
                                <span>Fecha de Ingreso</span>
                                <p><?php echo $datosAdmin["usuFechaIngreso"]?></p>
                            </div>

                            <div class="info-row">
                                <span>Útimo Acceso</span>
                                <p><?php echo $datosAdmin["usuUltimoAcceso"]?></p>
                            </div>
                        </div>

                        <div class="info-box">
                            <h3>Estado del Sistema</h3>

                            <div class="info-row">
                                <span>Usuario Activos</span>
                                <p><?php $cantidadUsuarios=contarCantidadUsuarioActivos();
                                    echo $cantidadUsuarios. (($cantidadUsuarios==1) ? " usuario" : " usuarios"); ?>
                                </p>
                            </div>

                            <div class="info-row">
                                <span>Solicitudes Pendientes</span>
                                <p>
                                    <?php $cantidadSolicitudes=contarTodasSolicitudesPendientes();
                                    echo $cantidadSolicitudes. (($cantidadSolicitudes==1) ? " solicitud" : " solicitudes"); ?>
                                </p>
                            </div>

                            <div class="info-row">
                                <span>Jardineras Activas</span>
                                <p>
                                    <?php $cantidadJardineras=contarTodasJardinerasActivas();
                                    echo $cantidadJardineras. (($cantidadJardineras==1) ? " jardinera" : " jardineras"); ?>
                                </p>
                            </div>

                            <div class="info-row">
                                <span>Última acción</span>
                                <p>
                                    <?php 
                                    $datosUltimaActividad = consultarUltimaActividad();
                                    $usuario= ($datosUltimaActividad["usuNumeroDocumento"] != 0) ? $datosUltimaActividad["usuNumeroDocumento"] : "Sin usuario asociado";
                                    echo $datosUltimaActividad["actAccion"] . " por " . $usuario  ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="info-box activity-box">
                        <h3><i class="fas fa-history"></i> Actividad Reciente</h3>

                        <div class="activity-list">
                            <?php
                            $resultadoConsultarUltimasActividades = consultarUltimasActividades();

                            while($datosUltimasActividades = mysqli_fetch_assoc($resultadoConsultarUltimasActividades)){
                            ?>
                                <div class="activity-item">

                                    <div class="activity-icon">
                                        <i class="fas fa-user-clock"></i>
                                    </div>

                                    <div class="activity-content">

                                        <div class="activity-title">
                                            <strong><?php echo $datosUltimasActividades["actAccion"]; ?></strong>
                                            <span>en <?php echo $datosUltimasActividades["actModulo"]; ?></span>
                                        </div>

                                        <div class="activity-info">
                                            <span>
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php echo $datosUltimasActividades["actFecha"]; ?>
                                            </span>

                                            <span>
                                                <i class="fas fa-user"></i>
                                                <?php
                                                echo ($datosUltimasActividades["usuNumeroDocumento"] != 0)
                                                ? $datosUltimasActividades["usuNumeroDocumento"]
                                                : "Sin usuario";
                                                ?>
                                            </span>
                                        </div>

                                    </div>

                                </div>
                            <?php } ?>
                        </div>

                    </div>

                </section>
            </section>
            
            <!-- USERS -->
            <section id="users" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Usuarios</h2>
                    </div>
                    <?php
                        $resultadoConsultarTodosUsuarios= consultarTodosUsuarios();
                        if(mysqli_num_rows($resultadoConsultarTodosUsuarios)>0){ ?>
                            <table>
                                <tr>
                                    <th>Fecha Ingreso</th>
                                    <th>Nombre</th>
                                    <th>Tipo Identificación</th>
                                    <th>Número Identificación</th>
                                    <th>Correo Electrónico</th>
                                    <th>Verificado</th>
                                    <th>Barrio</th>
                                    <th>Avatar</th>
                                    <th>Cantidad Jardineras</th>
                                    <th>Estado Cuenta</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                    while($datosUsuario=mysqli_fetch_assoc($resultadoConsultarTodosUsuarios)){
                                        $estado = ($datosUsuario["usuEstadoCorreo"]==="Verificado") ? "Si" : "No";

                                        $modificacionAvatar = ($datosUsuario["usuImagen"]!=="img_imagenDefecto.png") ? "Modificada" : "Por defecto";
                                        ?>
                                        <tr>
                                            <td><?php echo $datosUsuario["usuFechaIngreso"]?></td>
                                            <td><?php echo $datosUsuario["usuNombre"]?></td>
                                            <td><?php echo $datosUsuario["tipoDocDescripcion"]?></td>
                                            <td><?php echo $datosUsuario["usuNumeroDocumento"]?></td>
                                            <td><?php echo $datosUsuario["usuCorreo"]?></td>
                                            <td><?php echo $estado?></td>
                                            <td><?php echo $datosUsuario["usuBarrio"]?></td>
                                            <td><?php echo $modificacionAvatar?></td>
                                            <td><?php echo $datosUsuario["usuCantidadJardineras"]?></td>
                                            <td><?php echo $datosUsuario["usuEstado"]?></td>
                                            
                                            <td class="acciones">
                                                <button class="table-button updateUserProfileBtn" data-id="<?php echo $datosUsuario["usuNumeroDocumento"]?>" id="updateUserProfileBtn">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                                <button class="table-button inactivateProfileBtn" data-id="<?php echo $datosUsuario["usuNumeroDocumento"]?>" id="inactivateProfileBtn">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </table>
                            <?php 
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay registros de usuarios disponibles</h3>
                                <p>
                                    Aún no se han registrado usuarios en el sistema. Cuando se registre el primero, aparecerá aquí automáticamente.
                                </p>
                            </div>
                        <?php }
                    ?>
                </div>
            </section>

            <!-- TYPE DOCUMENTS -->
            <section id="type-documents" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Tipos de Documento</h2>
                        <div class="action-buttons">
                            <button class="action-button addTypeDocumentBtn" id="addTypeDocumentBtn"><i class="fas fa-plus"></i> Agregar Tipo de Documento</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTiposDocumento=consultarTiposDocumentos();
                        if(mysqli_num_rows($resultadoConsultarTiposDocumento)>0){ ?>
                            <table>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                while($datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTiposDocumento)){  ?>
                                    <tr>
                                        <td><?php echo $datosTipoDocumento["idTipoDocumento"]?></td>
                                        <td><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></td>
                                        <td><?php echo $datosTipoDocumento["tipoDocEstado"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateTypeDocumentBtn" id="updateTypeDocumentBtn" data-id="<?php echo $datosTipoDocumento["idTipoDocumento"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateTypeDocumentBtn" id="inactivateTypeDocumentBtn" data-id="<?php echo $datosTipoDocumento["idTipoDocumento"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay tipos de documento disponibles</h3>
                                <p>No existen tipos de documento registrados en el sistema. Puede registrar un nuevo tipo para comenzar.</p>
                            </div>
                        <?php } 
                    ?> 
                </div>
            </section>

            <!-- REQUESTS-->
            <section id="requests" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Solicitudes Pendientes</h2>
                    </div>
                    <div class="info-grid request-grid">
                        <?php
                            $resultadoConsultarSolicitudesPendientes=consultarTodasSolicitudesPendientes();
                            if(mysqli_num_rows($resultadoConsultarSolicitudesPendientes)>0){
                                while($datosSolicitudP=mysqli_fetch_assoc($resultadoConsultarSolicitudesPendientes)){ ?>
                                    <div class="request-card">
                                        <header>
                                            <h3><?php echo $datosSolicitudP["soliAsunto"]?></h3>
                                            <span class="request-status pending"><?php echo $datosSolicitudP["soliEstado"]?></span>
                                        </header>
                                        <div class="request-meta">
                                            <p><strong>Fecha:</strong> <?php echo $datosSolicitudP["soliFecha"]?></p>
                                            <?php if($datosSolicitudP["soliAsunto"]==="Admisión Nueva Semilla"){ ?>
                                            <?php if($datosSolicitudP["soliSemilla"]!==null){ ?>
                                                <p><strong>Semilla:</strong> <?php echo $datosSolicitudP["soliSemilla"]?></p>
                                            <?php } ?>
                                            <?php } ?>
                                            <p><strong>Descripción:</strong> <?php echo $datosSolicitudP["soliDescripcion"]?></p>
                                            <form id="updateStatusRequestForm" action="homeAdmin.php" method="POST">
                                                <button type="submit" name="confirmarSolicitudBtn" value="<?php echo $datosSolicitudP['idSolicitud']; ?>" class="table-button confirmRequestBtn">
                                                    <i class="fas fa-check"></i>  Confirmar</button>
                                                <button id="rechazarSolicitudBtn" name="rechazarSolicitudBtn" value="<?php echo $datosSolicitudP['idSolicitud']; ?>" class="table-button rejectRequestBtn">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                }
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay solicitudes pendientes disponibles</h3>
                                    <p>
                                        No existen solicitudes registradas en el sistema en este momento. Las nuevas solicitudes aparecerán aquí para su gestión.
                                    </p>
                                </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Historial de Solicitudes</h2>
                    </div>
                    <div class="info-grid request-grid">
                        <?php
                            $resultadoConsultarHistorialSolicitudes=consultarHistorialSolicitudes();
                            if(mysqli_num_rows($resultadoConsultarHistorialSolicitudes)>0){
                                while($datosSolicitudH=mysqli_fetch_assoc($resultadoConsultarHistorialSolicitudes)){ ?>
                                    <div class="request-card">
                                        <header>
                                            <h3><?php echo $datosSolicitudH["soliAsunto"]?></h3>
                                            <?php 
                                                if($datosSolicitudH["soliEstado"]==="Rechazada"){ ?>
                                                    <span class="request-status rejected"><?php echo $datosSolicitudH["soliEstado"]?></span>
                                                    <?php
                                                }else if($datosSolicitudH["soliEstado"]==="Confirmada"){ ?>
                                                    <span class="request-status confirmed"><?php echo $datosSolicitudH["soliEstado"]?></span>
                                                    <?php
                                                }
                                            ?>
                                        </header>
                                        <div class="request-meta">
                                            <p><strong>Fecha:</strong> <?php echo $datosSolicitudH["soliFecha"]?></p>
                                            <?php if($datosSolicitudH["soliAsunto"]==="Admisión Nueva Semilla"){ ?>
                                            <?php if($datosSolicitudH["soliSemilla"]!==null){ ?>
                                                <p><strong>Semilla:</strong> <?php echo $datosSolicitudH["soliSemilla"]?></p>
                                            <?php } ?>
                                            <?php } ?>
                                            <p><strong>Descripción:</strong> <?php echo $datosSolicitudH["soliDescripcion"]?></p>
                                        </div>
                                    </div>
                                <?php
                                }
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay registros disponibles en el historial</h3>
                                    <p>El historial de actividades aparecerá aquí cuando se registren acciones en el sistema.</p>
                                </div>
                            <?php }
                        ?>
                    </div>
                </div>
            </section>
            
            <!-- REVIEWS -->
            <section id="reviews" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Reseñas</h2>
                    </div>
                    <div class="info-grid request-grid">
                        <?php 
                            $resultadoConsultarResenas=consultarTodasResenas();
                            if(mysqli_num_rows($resultadoConsultarResenas)>0){
                                while($datosResena=mysqli_fetch_assoc($resultadoConsultarResenas)){ ?>
                                    <div class="request-card">
                                        <header>
                                            <h3><?php echo $datosResena["resenaNombreUsuario"]?></h3>
                                            <?php if($datosResena["usuNumeroDocumento"]!==null){ ?>
                                                <p class="review-meta">Usuario: <?php echo $datosResena["usuNumeroDocumento"]?></p>
                                            <?php } ?>
                                            <button class="table-button inactivateReviewBtn"  name="inactivateReviewBtn" id="inactivateReviewBtn" data-id="<?php echo $datosResena["idResena"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </header>

                                        <span class="review-date"><strong>Fecha: </strong><?php echo $datosResena["resenaFecha"]?></span>
                                        <p class="review-email"><strong>Correo: </strong><?php echo $datosResena["resenaCorreo"]?></p>
                                        <p class="review-email"><strong>Estado: </strong><?php echo $datosResena["resenaEstado"]?></p>

                                        <div class="request-meta">
                                            <p class="review-text"><strong>Descripción: </strong><?php echo $datosResena["resenaDescripcion"]?></p>
                                        </div>
                                    </div>
                                <?php
                                }
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay reseñas disponibles publicadas en la plataforma</h3>
                                    <p>Cuando se registren nuevas reseñas, aparecerán aquí.</p>
                                </div>
                            <?php }   
                        ?> 
                    </div>
                </div>
            </section>

            <!-- SEEDS -->
            <section id="seeds" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Semillas</h2>
                        <div class="action-buttons">
                            <button class="action-button addSeedBtn" id="addSeedBtn"><i class="fas fa-plus"></i> Agregar Semilla</button>
                            <button class="action-button typeSeedBtn" id="typeSeedBtn"><i class="fas fa-sync-alt"></i> Tipos de Semilla</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTodasSemillas=consultarTodasSemillas();
                        if(mysqli_num_rows($resultadoConsultarTodasSemillas)>0){ ?>
                            <table>
                                <tr>
                                    <th>Identicador</th>
                                    <th>Nombre</th>
                                    <th>Ruta Imagen</th>
                                    <th>Observaciones</th>
                                    <th>Tipo de Semilla</th>
                                    <th>Etapa de Crecimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                    while($datosSemilla=mysqli_fetch_assoc($resultadoConsultarTodasSemillas)){
                                        $tieneEtapa= ($datosSemilla["idEtapaCrecimiento"]==0) ? "No": "Sí";
                                        ?>
                                        <tr>
                                            <td><?php echo $datosSemilla["idSemilla"]?></td>
                                            <td><?php echo $datosSemilla["semNombre"]?></td>
                                            <td>
                                                <img src="../<?php echo $datosSemilla["semImagen"]?>" width="50" height="50">
                                            </td>
                                            <td><?php echo $datosSemilla["semObservaciones"]?></td>
                                            <td><?php echo $datosSemilla["tipoSemDescripcion"]?></td>
                                            <td><?php echo $tieneEtapa ?></td>
                                            <td><?php echo $datosSemilla["semEstado"]?></td>
                                            <td class="acciones">
                                                <button class="table-button updateSeedBtn" id="updateSeedBtn" data-id="<?php echo $datosSemilla["idSemilla"]?>">
                                                    <i class="fas fa-edit"></i>
                                                </button> 
                                                <button class="table-button inactivateSeedBtn"  id="inactivateSeedBtn" data-id="<?php echo $datosSemilla["idSemilla"]?>">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay semillas disponibles</h3>
                                <p>No existen registros de semillas en el sistema. Puede registrar nuevas semillas para comenzar.</p>
                            </div>
                        <?php }    
                    ?>
                </div>
            </section>

            <!-- TYPE SEEDS -->
            <section id="type-seeds" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Tipos de Semilla</h2>
                        <div class="action-buttons">
                            <button class="action-button addTypeSeedBtn" id="addTypeSeedBtn"><i class="fas fa-plus"></i> Agregar Tipo de Semilla</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTiposSemilla=consultarTodosTipoSemilla();
                        if(mysqli_num_rows($resultadoConsultarTiposSemilla)>0){ ?>
                            <table>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr> 
                                <?php
                                while($datosTipoSemilla=mysqli_fetch_assoc($resultadoConsultarTiposSemilla)){  ?>
                                    <tr>
                                        <td><?php echo $datosTipoSemilla["idTipoSemilla"]?></td>
                                        <td><?php echo $datosTipoSemilla["tipoSemDescripcion"]?></td>
                                        <td><?php echo $datosTipoSemilla["tipoSemEstado"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateTypeSeedBtn" id="updateTypeSeedBtn" data-id="<?php echo $datosTipoSemilla["idTipoSemilla"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateTypeSeedBtn" id="inactivateTypeSeedBtn" data-id="<?php echo $datosTipoSemilla["idTipoSemilla"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay tipos de semillas disponibles</h3>
                                <p>No existen tipos de semillas registrados en el sistema. Puede registrar un nuevo tipo para comenzar.</p>
                            </div>
                        <?php }
                    ?>
                </div>
            </section>

            <!-- TECHNICAL-SHEET -->
            <section id="technical-sheet" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Ficha Técnica</h2>
                        <div class="action-buttons">
                            <button class="action-button addTechnicalSheetBtn" id="addTechnicalSheetBtn"><i class="fas fa-plus"></i> Agregar Ficha Técnica</button>
                        </div>
                    </div>
                        <?php
                            $resultadoConsultarTodasFichasTecnicas=consultarTodasFichasTecnicas();
                            if(mysqli_num_rows($resultadoConsultarTodasFichasTecnicas)>0){ ?>
                                <table>
                                    <tr>
                                        <th>Semilla</th>
                                        <th>Tipo Clima</th>
                                        <th>Temperatura Min</th>
                                        <th>Temperatura Max</th>
                                        <th>Húmedad Min</th>
                                        <th>Húmedad Max</th>
                                        <th>Cantidad Agua Min</th>
                                        <th>Cantidad Agua Max</th>
                                        <th>Tipo Tierra</th>
                                        <th>Cantidad Tierra Min</th>
                                        <th>Cantidad Tierra Max</th>
                                        <th>Espacio</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <?php
                                        while($datosFichaTecnica=mysqli_fetch_assoc($resultadoConsultarTodasFichasTecnicas)){  ?>
                                            <tr>
                                                <td><?php echo $datosFichaTecnica["semNombre"]?></td>
                                                <td><?php echo $datosFichaTecnica["tipoClimaDescripcion"]?></td>
                                                <td><?php echo $datosFichaTecnica["fichaTemperaturaMin"], "ºC"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaTemperaturaMax"], "ºC"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaHumedadMin"], "%"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaHumedadMax"], "%"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaCantidadAguaMin"], "L"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaCantidadAguaMax"], "L"?></td>
                                                <td><?php echo $datosFichaTecnica["tipoTierraDescripcion"]?></td>
                                                <td><?php echo $datosFichaTecnica["fichaCantidadTierraMin"], "Kg"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaCantidadTierraMax"], "Kg"?></td>
                                                <td><?php echo $datosFichaTecnica["fichaEspacio"], "m²"?></td>
                                                <td>
                                                    <button class="table-button updateTechnicalSheetBtn" id="updateTechnicalSheetBtn" data-id="<?php echo $datosFichaTecnica["idFicha"] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </table>
                                <?php
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay fichas técnicas disponibles</h3>
                                    <p>No existen registros de fichas técnicas en el sistema. Puede registrar una nueva según la lista de semillas que la requieran.</p>
                                </div>
                            <?php }  
                        ?>
                </div>
            </section>

            <!-- TYPE WEATHERS -->
            <section id="type-weathers" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Tipos de Clima</h2>
                        <div class="action-buttons">
                            <button class="action-button addTypeWeatherBtn" id="addTypeWeatherBtn"><i class="fas fa-plus"></i> Agregar Tipo de Clima</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTiposClima=consultarTiposClima();
                        if(mysqli_num_rows($resultadoConsultarTiposClima)>0){ ?>
                            <table>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                while($datosTipoClima=mysqli_fetch_assoc($resultadoConsultarTiposClima)){  ?>
                                    <tr>
                                        <td><?php echo $datosTipoClima["idTipoClima"]?></td>
                                        <td><?php echo $datosTipoClima["tipoClimaDescripcion"]?></td>
                                        <td><?php echo $datosTipoClima["tipoClimaEstado"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateTypeWeatherBtn" id="updateTypeWeatherBtn" data-id="<?php echo $datosTipoClima["idTipoClima"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateTypeWeatherBtn" id="inactivateTypeWeatherBtn" data-id="<?php echo $datosTipoClima["idTipoClima"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay tipos de clima disponibles</h3>
                                <p>No existen tipos de clima registrados en el sistema. Puede registrar un nuevo tipo para comenzar.</p>
                            </div>
                        <?php 
                        }
                    ?> 
                </div>
            </section>

            <!-- TYPE SOILS -->
            <section id="type-soils" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Tipos de Tierra</h2>
                        <div class="action-buttons">
                            <button class="action-button addTypeSoilBtn" id="addTypeSoilBtn"><i class="fas fa-plus"></i> Agregar Tipo de Tierra</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTiposTierra=consultarTiposTierra();
                        if(mysqli_num_rows($resultadoConsultarTiposTierra)>0){ ?>
                            <table>
                                <tr>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                while($datosTipoTierra=mysqli_fetch_assoc($resultadoConsultarTiposTierra)){  ?>
                                    <tr>
                                        <td><?php echo $datosTipoTierra["idTipoTierra"]?></td>
                                        <td><?php echo $datosTipoTierra["tipoTierraDescripcion"]?></td>
                                        <td><?php echo $datosTipoTierra["tipoTierraEstado"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateTypeSoilBtn" id="updateTypeSoilBtn" data-id="<?php echo $datosTipoTierra["idTipoTierra"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateTypeSoilBtn" id="inactivateTypeSoilBtn" data-id="<?php echo $datosTipoTierra["idTipoTierra"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay tipos de tierra disponibles</h3>
                                <p>No existen tipos de tierra registrados en el sistema. Puede registrar un nuevo tipo para comenzar.</p>
                            </div>
                        <?php }
                    ?>
                </div>
            </section>

            <!-- GROWTH-STAGES -->
            <section id="growth-stages" class="module">
                <div class="profile-card">
                    <h2>Etapas de Crecimiento</h2>
                    <div class="action-buttons">
                        <button class="action-button addGrowthStagesBtn" id="addGrowthStagesBtn"><i class="fas fa-plus"></i> Agregar Etapa de Crecimiento</button>
                    </div>
                        <?php
                            $resultadoConsultarTodasEtapasCrecimiento=consultarTodasEtapasCrecimientoSemilla();
                            if(mysqli_num_rows($resultadoConsultarTodasEtapasCrecimiento)>0){ ?>
                                <table>
                                    <tr>
                                        <th>Semilla</th>
                                        <th>Germinación Min</th>
                                        <th>Germinación Max</th>
                                        <th>Desarrollo Vegetativo Min</th>
                                        <th>Desarrollo Vegetativo Max</th>
                                        <th>Floración Min</th>
                                        <th>Floración Max</th>
                                        <th>Llenado de Granos Min</th>
                                        <th>Llenado de Granos Max</th>
                                        <th>Cosecha Min</th>
                                        <th>Cosecha Max</th>
                                        <th>Acciones</th>
                                    </tr>
                                    <?php
                                    while($datosEtapaCrecimiento=mysqli_fetch_assoc($resultadoConsultarTodasEtapasCrecimiento)){  ?>
                                        <tr>
                                            <td><?php echo $datosEtapaCrecimiento["semNombre"]?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasGerminacionMin"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasGerminacionMax"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasFloracionMin"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasFloracionMax"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasCosechaMin"], " días"?></td>
                                            <td><?php echo $datosEtapaCrecimiento["etapaCreDiasCosechaMax"], " días"?></td>
                                            <td>
                                                <button class="table-button updateGrowthStagesBtn" id="updateGrowthStagesBtn" data-id="<?php echo $datosEtapaCrecimiento["idEtapaCrecimiento"]?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    } 
                                    ?>
                                </table>
                                <?php
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay etapas de seguimiento disponibles</h3>
                                    <p>No existen registros de etapas de seguimiento en el sistema. Puede registrar una nueva según la lista de semillas que la requieran.</p>
                                </div>
                            <?php } 
                        ?>
                    </table>
                </div>
            </section>

            <!-- GARDENS -->
            <section id="gardens" class="module">
                <div class="profile-card">
                    <h2>Jardineras</h2>
                    <div class="garden-grid">
                        <?php
                            $resultadoConsultarTodasJardineras = consultarTodasJardineras();
                            if(mysqli_num_rows($resultadoConsultarTodasJardineras)>0){
                                while ($datosJardinera = mysqli_fetch_assoc($resultadoConsultarTodasJardineras)) {  ?>
                                    <article class="garden-card">
                                        <div class="garden-card-header">
                                            <div>
                                                <h3><?php echo $datosJardinera["jarNombre"] ?></h3>
                                                <span class="garden-label">Creada:
                                                    <?php echo $datosJardinera["jarFechaCreacion"] ?></span>
                                            </div>
                                            <span class="garden-status <?php echo strtolower($datosJardinera["jarEstado"]) ?>">
                                                <?php echo $datosJardinera["jarEstado"] ?>
                                            </span>
                                        </div>
                                        <p class="garden-description"><?php echo $datosJardinera["jarDescripcion"] ?></p>
                                        <ul class="garden-meta-list">
                                            <li><strong>Usuario:</strong> <?php echo $datosJardinera["usuNombre"] ?></li>
                                            <li><strong>Semilla:</strong> <?php echo $datosJardinera["semNombre"] ?></li>
                                            <li><strong>Fase:</strong> <?php echo $datosJardinera["faseNombre"] ?></li>
                                            <li><strong>Evolución:</strong>
                                                <?php echo $datosJardinera["jarPorcentajeEvolucion"], "%" ?></li>
                                        </ul>
                                        <div class="garden-card-actions">
                                            <button class="table-button updateGardenBtn" id="updateGardenBtn"
                                                data-id="<?php echo $datosJardinera["idJardinera"] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateGardenBtn" id="inactivateGardenBtn"
                                                data-id="<?php echo $datosJardinera["idJardinera"] ?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </div>
                                    </article>
                                    <?php
                                }
                            }else{
                                ?>
                                <div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <h3>No hay jardineras disponibles</h3>
                                    <p>No existen registros de jardineras en el sistema. Cuando un usuario registre alguna aparecerá aquí automáticamente.</p>
                                </div>
                            <?php } 
                        ?>
                    </div>
                </div>
            </section>

            <!-- EXTERNAL-FACTORS -->
            <section id="external-factors" class="module">
                <div class="profile-card">
                    <h2>Factores Externos</h2>
                    <div class="external-factors-grid">
                    <?php
                        $resultadoConsultarTodosFactoresExternos = consultarTodasFactoresExternos();
                        if(mysqli_num_rows($resultadoConsultarTodosFactoresExternos)>0){
                            $cantidadFactores=1;
                            while ($datosFactorExterno = mysqli_fetch_assoc($resultadoConsultarTodosFactoresExternos)) {  ?>
                                <article class="external-factor-card">
                                    <div class="external-factor-header">
                                        <div>
                                            <h3>Factor Nº<?php echo $cantidadFactores; ?></h3>
                                            <span class="external-factor-label">Jardinera:
                                                <?php echo $datosFactorExterno["jarNombre"] ?></span>
                                        </div>
                                        <span
                                            class="external-factor-status <?php echo strtolower($datosFactorExterno["factEstado"]) ?>">
                                            <?php echo $datosFactorExterno["factEstado"] ?>
                                        </span>
                                    </div>
                                    <ul class="external-factor-meta-list">
                                        <li><strong>Humedad:</strong> <?php echo $datosFactorExterno["factHumedad"], "%" ?></li>
                                        <li><strong>Clima:</strong> <?php echo $datosFactorExterno["tipoClimaDescripcion"] ?>
                                        </li>
                                        <li><strong>Temperatura:</strong>
                                            <?php echo $datosFactorExterno["factTemperatura"], "ºC" ?></li>
                                        <li><strong>Agua:</strong> <?php echo $datosFactorExterno["factCantidadAgua"], "L" ?>
                                        </li>
                                    </ul>
                                    <div class="external-factor-actions">
                                        <button class="table-button updateExternalFactorBtn" id="updateExternalFactorBtn"
                                            data-id="<?php echo $datosFactorExterno["idFactoresExternos"] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-button inactivateExternalFactorBtn"
                                            id="inactivateExternalFactorBtn"
                                            data-id="<?php echo $datosFactorExterno["idFactoresExternos"] ?>">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </div>
                                </article>
                                <?php
                                $cantidadFactores++;
                            }
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay factores externos disponibles</h3>
                                <p>No existen registros de factores externos en el sistema. Cuando un usuario registre alguno aparecerá aquí automáticamente.</p>
                            </div>
                        <?php }  
                    ?>
                    </div>
                </div>
            </section>

            <!-- MONITORING -->
            <section id="monitoring" class="module">
                <div class="profile-card">
                    <h2>Seguimiento Jardinera</h2>

                    <div class="monitoring-grid">
                    <?php
                        $resultadoConsultarTodosSeguimientos = consultarTodasSeguimientos();
                        if(mysqli_num_rows($resultadoConsultarTodosSeguimientos)>0){
                            $cantidadSeguimientos=1;
                            while ($datosSeguimiento = mysqli_fetch_assoc($resultadoConsultarTodosSeguimientos)) {
                                $nota = ($datosSeguimiento["segJardineraNota"] !== "") ? $datosSeguimiento["segJardineraNota"] : "Sin nota";
                                
                                $imagen = ($datosSeguimiento["segJardineraImagen"] !== "") ? $datosSeguimiento["segJardineraImagen"] : null; ?>
                                <article class="monitoring-card">
                                    <div class="monitoring-card-top">
                                        <div class="monitoring-header-text">
                                            <h3>Seguimiento Nº<?php echo $cantidadSeguimientos?></h3>
                                            <span
                                                class="monitoring-label"> Fecha: <?php echo $datosSeguimiento["segJardineraFecha"] ?></span>
                                        </div>
                                        <div class="monitoring-avatar-group">
                                            <?php if ($imagen) { ?>
                                            <div class="monitoring-avatar">
                                                <img src="<?php echo $imagen ?>" alt="Imagen de seguimiento" />
                                            </div>
                                            <?php } ?>
                                            <span
                                                class="monitoring-status <?php echo strtolower($datosSeguimiento["segJardineraEstado"]) ?>">
                                                <?php echo $datosSeguimiento["segJardineraEstado"] ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="monitoring-card-body">
                                        <p class="monitoring-note">Nota: <?php echo $nota ?></p>
                                        <ul class="monitoring-meta-list">
                                            <li><span>Jardinera</span><strong><?php echo $datosSeguimiento["jarNombre"] ?></strong>
                                            </li>
                                            <li><span>Porcentaje</span><strong><?php echo $datosSeguimiento["segJardineraPorcentaje"], "%" ?></strong>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="monitoring-actions">
                                        <button class="table-button updateMonitoringBtn" id="updateMonitoringBtn"
                                            data-id="<?php echo $datosSeguimiento["idSeguimiento"] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="table-button inactivateMonitoringBtn" id="inactivateMonitoringBtn"
                                            data-id="<?php echo $datosSeguimiento["idSeguimiento"] ?>">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </div>
                                </article>
                            <?php
                            $cantidadSeguimientos++;
                            }
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay seguimientos de jardineras disponibles</h3>
                                <p>No existen registros de seguimientos de jardineras en el sistema. Cuando un usuario registre alguno aparecerá aquí automáticamente.</p>
                            </div>
                        <?php }                       
                    ?>
                    </div>
                </div>
            </section>

            <!-- ALERTS -->
            <section id="alerts" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Alertas</h2>
                    </div>
                    <?php
                        //Arreglo con los tipos de alertas de fecha permitidos
                        $alertasFechas=[ "proximoDesarrolloVegetativo", "enGerminacion", "terminandoGerminacion", "proximoFloracion",
                            "enDesarrolloVegetativo", "terminandoDesarrolloVegetativo", "proximoLlenadoGranos", "enFloracion", "terminandoFloracion",
                            "proximoCosecha", "enLlenadoGranos", "terminandoLlenadoGranos", "proximoCulminarCiclo", "enCosecha", "terminandoCosecha"];
                        
                            $resultadoConsultarTodasAlertas=consultarTodasAlertas();
                        if(mysqli_num_rows($resultadoConsultarTodasAlertas)>0){ ?>
                            <table>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Recomendación</th>
                                    <th>Valor Registrado</th>
                                    <th>Rango Recomendado</th>
                                    <th>Estado</th>
                                    <th>Jardinera</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                while($datosAlerta=mysqli_fetch_assoc($resultadoConsultarTodasAlertas)){  ?>
                                    <tr>
                                        <td><?php echo $datosAlerta["alerFecha"]?></td>
                                        <td><?php 
                                            echo formatearTexto($datosAlerta["alerTipo"]);
                                        ?></td>
                                        <td><?php echo $datosAlerta["alerDescripcion"] ?></td>
                                        <td><?php echo $datosAlerta["alerRecomendacion"]?></td>
                                        <td><?php 
                                            if(in_array($datosAlerta["alerTipo"], $alertasFechas) && $datosAlerta["alerValorRegistrado"]==0){
                                                echo "No aplica";
                                            }else{
                                                echo $datosAlerta["alerValorRegistrado"];
                                            }
                                        ?></td>

                                        <td><?php 
                                            if(in_array($datosAlerta["alerTipo"], $alertasFechas) && $datosAlerta["alerRangoRecomendado"]==null){
                                                echo "No aplica";
                                            }else{
                                                echo $datosAlerta["alerRangoRecomendado"];
                                            }
                                        ?></td>
                                        <td><?php echo $datosAlerta["alerEstado"]?></td>
                                        <td><?php echo $datosAlerta["jarNombre"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateAlertBtn" id="updateAlertBtn" data-id="<?php echo $datosAlerta["idAlerta"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateAlertBtn" id="inactivateAlertBtn" data-id="<?php echo $datosAlerta["idAlerta"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay alertas disponibles</h3>
                                <p>No se han generado alertas en el sistema. Cuando se detecte alguna novedad de las jardineras, aparecerá aquí automáticamente.</p>
                            </div>
                        <?php }    
                    ?> 
                </div>
            </section>

            <!-- STAGES -->
            <section id="stages" class="module">
                <div class="profile-card">
                    <div class="module-header">
                        <h2>Fases</h2>
                        <div class="action-buttons">
                            <button class="action-button addStagesBtn" id="addStagesBtn"><i class="fas fa-plus"></i> Agregar Fase</button>
                        </div>
                    </div>
                    <?php
                        $resultadoConsultarTodasFases=consultarTodasFases();
                        if(mysqli_num_rows($resultadoConsultarTodasFases)>0){ ?>
                            <table>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Porcentaje</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                <?php
                                while($datosFase=mysqli_fetch_assoc($resultadoConsultarTodasFases)){  ?>
                                    <tr>
                                        <td><?php echo $datosFase["faseNombre"]?></td>
                                        <td><?php echo $datosFase["faseDescripcion"]?></td>
                                        <td><?php echo $datosFase["fasePorcentaje"], "%"?></td>
                                        <td><?php echo $datosFase["faseEstado"]?></td>
                                        <td class="acciones">
                                            <button class="table-button updateStagesBtn" id="updateStagesBtn" data-id="<?php echo $datosFase["idFase"]?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-button inactivateStagesBtn" id="inactivateStagesBtn" data-id="<?php echo $datosFase["idFase"]?>">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            <button class="table-button stageQuestionsBtn" id="stageQuestionsBtn" data-id="<?php echo $datosFase["idFase"]?>">
                                                <i class="fas fa-question-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                } 
                                ?>
                            </table>
                            <?php
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No hay fases disponibles</h3>
                                <p>No existen fases registradas en el sistema. Puede registrar una nueva fase para comenzar.</p>
                            </div>
                        <?php }    
                    ?> 
                </div>
            </section>
        </main>
    </div>

    <!-- Modals -->

    <!-- Update Admin's Profile -->
    <div class="modal" id="editAdminProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Perfil</h3>
                <button class="modal-close" id="closeEditAdminProfile">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editAdminProfileForm" action="procesadorActualizarDatos.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="editName">Nombre Completo</label>
                    <input type="text" id="editNameAdmin" name="editNameAdmin" placeholder="<?php echo $datosAdmin["usuNombre"] ; ?>" autocomplete="name">
                </div>
                <p id="errorNombreCompletoActualizarPerfilAdmin" class="error-message"></p>

                <div class="form-group">
                    <label for="editTypeIdProfile">Tipo de Documento</label>
                    <select name="editTypeIdProfileAdmin" id="editTypeIdProfileAdmin"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposDocumento=consultarTiposDocumentosActivos();
                            while($datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoDocumentoActualizarPerfilAdmin" class="error-message"></p>

                <div class="form-group">
                    <label for="editEmail">Correo Electrónico</label>
                    <input type="text" id="editEmailAdmin" name="editEmailAdmin" placeholder="<?php echo $datosAdmin["usuCorreo"] ?>" autocomplete="email">
                </div>
                <p id="errorCorreoActualizarPerfilAdmin" class="error-message"></p>

                <div class="form-group">
                    <label for="editLocation">Barrio o Localidad</label>
                    <input type="text" id="editLocationAdmin" name="editLocationAdmin" placeholder="<?php echo $datosAdmin["usuBarrio"] ?>" autocomplete="address-level3">
                </div>
                <p id="errorBarrioActualizarPerfilAdmin" class="error-message"></p>

                <div class="form-group">
                    <label for="editPassword">Contraseña</label>
                    <input type="password" id="editPasswordAdmin" name="editPasswordAdmin">
                </div>
                <p id="errorContrasenaActualizarPerfilAdmin" class="error-message"></p>

                <div class="form-group">
                    <label for="confirmarContrasenaActualizarPerfilAdmin">Confirmar Contraseña</label>
                    <input type="password"
                        name="confirmarContrasena"
                        id="confirmarContrasenaActualizarPerfilAdmin"
                        autocomplete="new-password">
                </div>
                <p id="errorConfirmarContrasenaActualizarPerfilAdmin" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelEditAdminProfile">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarPerfilAdminBtn" id="actualizarPerfilAdminBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update User's Profile-->
    <div class="modal" id="updateUserProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Perfil Usuario</h3>
                <button class="modal-close" id="closeUpdateUserProfile">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateUserProfileForm" action="homeAdmin.php" method="POST" autocomplete="on" enctype="multipart/form-data">
                <input type="hidden" id="updateUserProfileId" name="updateUserProfileId">
                <div class="form-group">
                    <label for="updateName">Nombre Completo</label>
                    <input type="text" id="updateName" name="updateName" autocomplete="name" placeholder="Ej: Maria Peréz">
                </div>
                <p id="errorNombreCompletoActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateTypeUser">Tipo de Usuario</label>
                    <select name="updateTypeUser" id="updateTypeUser"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <option value="Usuario">Usuario</option> 
                        <option value="Administrador">Administrador</option> 
                    </select>
                </div>
                <p id="errorTipoUsuarioActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateTypeId">Tipo de Documento</label>
                    <select name="updateTypeId" id="updateTypeId"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposDocumento=consultarTiposDocumentosActivos();
                            while($datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoDocumentoActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateEmail">Correo Electrónico</label>
                    <input type="text" id="updateEmail" name="updateEmail" autocomplete="email" placeholder="Ej: maria@gmail.com">
                </div>
                <p id="errorCorreoActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateEmailStatus">Estado Correo Electrónco</label>
                    <select name="updateEmailStatus" id="updateEmailStatus"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <option value="Verificado">Verificado</option> 
                        <option value="No verificado">No verificado</option> 
                    </select>
                </div>
                <p id="errorEstadoCorreoActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateLocation">Barrio o Localidad</label>
                    <input type="text" id="updateLocation" name="updateLocation" autocomplete="address-level3" placeholder="Ej: Claret">
                </div>
                <p id="errorBarrioActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateAvatar">Avatar</label>
                    <input type="file" id="updateAvatar" name="updateAvatar">
                </div>
                <p id="errorAvatarActualizarPerfilUsuario" class="error-message"></p>

                <div class="form-group">
                    <label for="updateGardensAmount">Cantidad de Jardineras</label>
                    <input type="text" id="updateGardensAmount" name="updateGardensAmount" placeholder="Ej: 2">
                </div>
                <p id="errorCantidadJardinerasActualizarPerfilUsuario" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateUserProfile">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarPerfilUsuarioBtn" id="actualizarPerfilUsuarioBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    
    <!--Inactivate User's Account-->
    <div class="modal" id="inactivateProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la cuenta del usuario?</h3>
                <button class="modal-close" id="closeInactivateProfile">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateProfileForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateProfileId" name="inactivateProfileId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateProfile">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarUsuarioBtn" id="inactivarUsuarioBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div> 

    <!--Add Type Document-->
    <div class="modal" id="addTypeDocumentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Tipo de Documento</h3>
                <button class="modal-close" id="closeAddTypeDocument">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addTypeDocumentForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="addTypeDocumentDescription">Descripción</label>
                    <input type="text" id="addTypeDocumentDescription" name="addTypeDocumentDescription" placeholder="Ej: Cédula de Ciudadanía">
                </div>
                <p id="errorDescripcionAgregarTipoDocumento" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddTypeDocument">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarTipoDocumentoBtn" id="agregarTipoDocumentoBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Type Document-->
    <div class="modal" id="updateTypeDocumentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Tipo de Documento</h3>
                <button class="modal-close" id="closeUpdateTypeDocument">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateTypeDocumentForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateTypeDocumentId" name="updateTypeDocumentId">
                <div class="form-group">
                    <label for="updateTypeDocumentDescription">Descripción</label>
                    <input type="text" id="updateTypeDocumentDescription" name="updateTypeDocumentDescription" placeholder="Ej: Cédula de Ciudadanía">
                </div>
                <p id="errorDescripcionActualizarTipoDocumento" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateTypeDocument">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarTipoDocumentoBtn" id="actualizarTipoDocumentoBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div> 

    <!--Inactivate Type Document-->
    <div class="modal" id="inactivateTypeDocumentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado del tipo de documento?</h3>
                <button class="modal-close" id="closeInactivateTypeDocument">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateTypeDocumentForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateTypeDocumentId" name="inactivateTypeDocumentId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateTypeDocument">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarTipoDocumentoBtn" id="inactivarTipoDocumentoBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate User's Review-->
    <div class="modal" id="inactivateReviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de inactivar la reseña?</h3>
                <button class="modal-close" id="closeInactivateReview">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateReviewForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateReviewId" name="inactivateReviewId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateReview">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarResenaBtn" id="inactivarResenaBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Add Seed-->
    <div class="modal" id="addSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Semilla</h3>
                <button class="modal-close" id="closeAddSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addSeedForm" action="homeAdmin.php" method="POST" autocomplete="on" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="addSeedName">Nombre</label>
                    <input type="text" id="addSeedName" name="addSeedName" placeholder="Ej: Sandía">
                </div>
                <p id="errorNombreAgregarSemilla" class="error-message"></p>

                 <div class="form-group">
                    <label for="addSeedImage">Imagen</label>
                    <input type="file" id="addSeedImage" name="addSeedImage">
                </div>
                <p id="errorImagenAgregarSemilla" class="error-message"></p>

                <div class="form-group">
                    <label for="addSeedObservations">Observaciones</label>
                    <input type="text" id="addSeedObservations" name="addSeedObservations" placeholder="Escriba las observaciones de la semilla...">
                </div>
                <p id="errorObservacionesAgregarSemilla" class="error-message"></p>

                <div class="form-group">
                    <label for="addSeedType">Tipo de Semilla</label>
                    <select name="addSeedType" id="addSeedType"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposSemilla=consultarTodosTipoSemilla();
                            while($datosTipoSemilla=mysqli_fetch_assoc($resultadoConsultarTiposSemilla)){//Bucle para recorrer todos los tipos de semillas registradas
                        ?>
                            <option value="<?php echo $datosTipoSemilla["idTipoSemilla"]?>"><?php echo $datosTipoSemilla["tipoSemDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoSemillaAgregarSemilla" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarSemillaBtn" id="agregarSemillaBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Seed-->
    <div class="modal" id="updateSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Semilla</h3>
                <button class="modal-close" id="closeUpdateSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateSeedForm" action="homeAdmin.php" method="POST" autocomplete="on" enctype="multipart/form-data">
                <input type="hidden" id="updateSeedId" name="updateSeedId">
                <div class="form-group">
                    <label for="updateSeedName">Nombre</label>
                    <input type="text" id="updateSeedName" name="updateSeedName" placeholder="Ej: Sandía">
                </div>
                <p id="errorNombreActualizarSemilla" class="error-message"></p>

                 <div class="form-group">
                    <label for="updateSeedImage">Imagen</label>
                    <input type="file" id="updateSeedImage" name="updateSeedImage">
                </div>
                <p id="errorImagenActualizarSemilla" class="error-message"></p>

                <div class="form-group">
                    <label for="updateSeedObservations">Observaciones</label>
                    <input type="text" id="updateSeedObservations" name="updateSeedObservations" placeholder="Escriba las observaciones de la semilla...">
                </div>
                <p id="errorObservacionesActualizarSemilla" class="error-message"></p>

                <div class="form-group">
                    <label for="updateSeedType">Tipo de Semilla</label>
                    <select name="updateSeedType" id="updateSeedType"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposSemilla=consultarTiposSemillaActivas();
                            while($datosTipoSemilla=mysqli_fetch_assoc($resultadoConsultarTiposSemilla)){//Bucle para recorrer todos los tipos de semillas registradas
                        ?>
                            <option value="<?php echo $datosTipoSemilla["idTipoSemilla"]?>"><?php echo $datosTipoSemilla["tipoSemDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoSemillaActualizarSemilla" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarSemillaBtn" id="actualizarSemillaBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Seed-->
    <div class="modal" id="inactivateSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro modificar el estado de la semilla?</h3>
                <button class="modal-close" id="closeInactivateSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateSeedForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateSeedId" name="inactivateSeedId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarSemillaBtn" id="inactivarSemillaBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div> 

    <!--Add Type Seed-->
    <div class="modal" id="addTypeSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Tipo de Semilla</h3>
                <button class="modal-close" id="closeAddTypeSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addTypeSeedForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="addTypeSeedDescription">Descripción</label>
                    <input type="text" id="addTypeSeedDescription" name="addTypeSeedDescription" placeholder="Ej: Frutales">
                </div>
                <p id="errorDescripcionAgregarTipoSemilla" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddTypeSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarTipoSemillaBtn" id="agregarTipoSemillaBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Type Seed-->
    <div class="modal" id="updateTypeSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Tipo de Semilla</h3>
                <button class="modal-close" id="closeUpdateTypeSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateTypeSeedForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateTypeSeedId" name="updateTypeSeedId">
                <div class="form-group">
                    <label for="updateTypeSeedDescription">Descripción</label>
                    <input type="text" id="updateTypeSeedDescription" name="updateTypeSeedDescription" placeholder="Ej: Frutales">
                </div>
                <p id="errorDescripcionActualizarTipoSemilla" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateTypeSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarTipoSemillaBtn" id="actualizarTipoSemillaBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Type Seed-->
    <div class="modal" id="inactivateTypeSeedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la semilla?</h3>
                <button class="modal-close" id="closeInactivateTypeSeed">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateTypeSeedForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateTypeSeedId" name="inactivateTypeSeedId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateTypeSeed">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarTipoSemillaBtn" id="inactivarTipoSemillaBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Add Technical Sheet-->
    <div class="modal" id="addTechnicalSheetModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Ficha Técnica</h3>
                <button class="modal-close" id="closeAddTechnicalSheet">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addTechnicalSheetForm" action="homeAdmin.php" method="POST" autocomplete="on"> 

                <div class="form-group">
                    <label for="addSeedTS">Semilla</label>
                    <select name="addSeedTS" id="addSeedTS"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarSemillasSinFicha=consultarSemillasSinFicha();
                            while($datosSemillaSinFicha=mysqli_fetch_assoc($resultadoConsultarSemillasSinFicha)){
                        ?>
                            <option value="<?php echo $datosSemillaSinFicha["idSemilla"]?>"><?php echo $datosSemillaSinFicha["semNombre"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorSemillaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addTypeWeather">Tipo de Clima</label>
                    <select name="addTypeWeather" id="addTypeWeather"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposClima=consultarTiposClimaActivos();
                            while($datosTipoClima=mysqli_fetch_assoc($resultadoConsultarTiposClima)){
                        ?>
                            <option value="<?php echo $datosTipoClima["idTipoClima"]?>"><?php echo $datosTipoClima["tipoClimaDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoClimaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMinTemperature">Temperatura Mínima</label>
                    <input type="text" id="addMinTemperature" name="addMinTemperature" placeholder="20">
                </div>
                <p id="errorTemperaturaMinimaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMaxTemperature">Temperatura Máxima</label>
                    <input type="text" id="addMaxTemperature" name="addMaxTemperature" placeholder="30">
                </div>
                <p id="errorTemperaturaMaximaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMinHumidity">Húmedad Mínima</label>
                    <input type="text" id="addMinHumidity" name="addMinHumidity" placeholder="24">
                </div>
                <p id="errorHumedadMinimaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMaxHumidity">Húmedad Máxima</label>
                    <input type="text" id="addMaxHumidity" name="addMaxHumidity" placeholder="40">
                </div>
                <p id="errorHumedadMaximaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMinWaterAmount">Cantidad de Agua Mínima</label>
                    <input type="text" id="addMinWaterAmount" name="addMinWaterAmount" placeholder="100">
                </div>
                <p id="errorCantidadAguaMinimaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMaxWaterAmount">Cantidad de Agua Máxima</label>
                    <input type="text" id="addMaxWaterAmount" name="addMaxWaterAmount" placeholder="200">
                </div>
                <p id="errorCantidadAguaMaximaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addTypeIdProfile">Tipo de Tierra</label>
                    <select name="addTypeIdProfile" id="addTypeIdProfile"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposTierra=consultarTiposTierraActivos();
                            while($datosTipoTierra=mysqli_fetch_assoc($resultadoConsultarTiposTierra)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoTierra["idTipoTierra"]?>"><?php echo $datosTipoTierra["tipoTierraDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoTierraAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMinSoilAmount">Cantidad de Tierra Mínima</label>
                    <input type="text" id="addMinSoilAmount" name="addMinSoilAmount" placeholder="100">
                </div>
                <p id="errorCantidadTierraMinimaAgregarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="addMaxSoilAmount">Cantidad de Tierra Máxima</label>
                    <input type="text" id="addMaxSoilAmount" name="addMaxSoilAmount" placeholder="200">
                </div>
                <p id="errorCantidadTierraMaximaAgregarFichaTecnica" class="error-message"></p>
                
                <div class="form-group">
                    <label for="addPlot">Espacio</label>
                    <input type="text" id="addPlot" name="addPlot" placeholder="Ej: 250">
                </div>
                <p id="errorEspacioAgregarFichaTecnica" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddTechnicalSheet">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarFichaTecnicaBtn" id="agregarFichaTecnicaBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Technical Sheet-->
    <div class="modal" id="updateTechnicalSheetModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Ficha Técnica</h3>
                <button class="modal-close" id="closeUpdateTechnicalSheet">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateTechnicalSheetForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateTechnicalSheetId" name="updateTechnicalSheetId">
        
                <div class="form-group">
                    <label for="updateTypeWeather">Tipo de Clima</label>
                    <select name="updateTypeWeather" id="updateTypeWeather"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposClima=consultarTiposClimaActivos();
                            while($datosTipoClima=mysqli_fetch_assoc($resultadoConsultarTiposClima)){
                        ?>
                            <option value="<?php echo $datosTipoClima["idTipoClima"]?>"><?php echo $datosTipoClima["tipoClimaDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoClimaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMinTemperature">Temperatura Mínima</label>
                    <input type="text" id="updateMinTemperature" name="updateMinTemperature" placeholder="Ej: 10">
                </div>
                <p id="errorTemperaturaMinimaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMaxTemperature">Temperatura Máxima</label>
                    <input type="text" id="updateMaxTemperature" name="updateMaxTemperature" placeholder="Ej: 30">
                </div>
                <p id="errorTemperaturaMaximaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMinHumidity">Húmedad Mínima</label>
                    <input type="text" id="updateMinHumidity" name="updateMinHumidity" placeholder="Ej: 20">
                </div>
                <p id="errorHumedadMinimaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMaxHumidity">Húmedad Máxima</label>
                    <input type="text" id="updateMaxHumidity" name="updateMaxHumidity" placeholder="Ej: 40">
                </div>
                <p id="errorHumedadMaximaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMinWaterAmount">Cantidad de Agua Mínima</label>
                    <input type="text" id="updateMinWaterAmount" name="updateMinWaterAmount" placeholder="Ej: 100">
                </div>
                <p id="errorCantidadAguaMinimaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMaxWaterAmount">Cantidad de Agua Máxima</label>
                    <input type="text" id="updateMaxWaterAmount" name="updateMaxWaterAmount" placeholder="Ej: 100">
                </div>
                <p id="errorCantidadAguaMaximaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateTypeIdProfile">Tipo de Tierra</label>
                    <select name="updateTypeIdProfile" id="updateTypeIdProfile"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposTierra=consultarTiposTierraActivos();
                            while($datosTipoTierra=mysqli_fetch_assoc($resultadoConsultarTiposTierra)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoTierra["idTipoTierra"]?>"><?php echo $datosTipoTierra["tipoTierraDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoTierraActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMinSoilAmount">Cantidad de Tierra Mínima</label>
                    <input type="text" id="updateMinSoilAmount" name="updateMinSoilAmount" placeholder="Ej: 100">
                </div>
                <p id="errorCantidadTierraMinimaActualizarFichaTecnica" class="error-message"></p>

                <div class="form-group">
                    <label for="updateMaxSoilAmount">Cantidad de Tierra Máxima</label>
                    <input type="text" id="updateMaxSoilAmount" name="updateMaxSoilAmount" placeholder="Ej: 150">
                </div>
                <p id="errorCantidadTierraMaximaActualizarFichaTecnica" class="error-message"></p>
                
                <div class="form-group">
                    <label for="updatePlot">Espacio</label>
                    <input type="text" id="updatePlot" name="updatePlot" placeholder="Ej: 400">
                </div>
                <p id="errorEspacioActualizarFichaTecnica" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateTechnicalSheet">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarFichaTecnicaBtn" id="actualizarFichaTecnicaBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div> 

    <!--Add Type Weather-->
    <div class="modal" id="addTypeWeatherModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Tipo de Clima</h3>
                <button class="modal-close" id="closeAddTypeWeather">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addTypeWeatherForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="addTypeWeatherDescription">Descripción</label>
                    <input type="text" id="addTypeWeatherDescription" name="addTypeWeatherDescription" placeholder="Ej: Soleado">
                </div>
                <p id="errorTipoClimaAgregarTipoClima" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddTypeWeather">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarTipoClimaBtn" id="agregarTipoClimaBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Type Weather-->
   <div class="modal" id="updateTypeWeatherModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Tipo de Clima</h3>
                <button class="modal-close" id="closeUpdateTypeWeather">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateTypeWeatherForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateTypeWeatherId" name="updateTypeWeatherId">
                <div class="form-group">
                    <label for="updateTypeWeatherDescription">Descripción</label>
                    <input type="text" id="updateTypeWeatherDescription" name="updateTypeWeatherDescription" placeholder="Ej: Soleado">
                </div>
                <p id="errorTipoClimaActualizarTipoClima" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateTypeWeather">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarTipoClimaBtn" id="actualizarTipoClimaBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Type Weather-->
    <div class="modal" id="inactivateTypeWeatherModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado del tipo de clima?</h3>
                <button class="modal-close" id="closeInactivateTypeWeather">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateTypeWeatherForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateTypeWeatherId" name="inactivateTypeWeatherId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateTypeWeather">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarTipoClimaBtn" id="inactivarTipoClimaBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div> 

    <!--Add Type Soil-->
    <div class="modal" id="addTypeSoilModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Tipo de Tierra </h3>
                <button class="modal-close" id="closeAddTypeSoil">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addTypeSoilForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="addTypeSoilDescription">Descripción</label>
                    <input type="text" id="addTypeSoilDescription" name="addTypeSoilDescription" placeholder="Ej: Piedroso">
                </div>
                <p id="errorDescripcionAgregarTipoTierra" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddTypeSoil">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarTipoTierraBtn" id="agregarTipoTierraBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Type Soil-->
    <div class="modal" id="updateTypeSoilModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Tipo de Tierra </h3>
                <button class="modal-close" id="closeUpdateTypeSoil">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateTypeSoilForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateTypeSoilId" name="updateTypeSoilId">
                <div class="form-group">
                    <label for="updateTypeSoilDescription">Descripción</label>
                    <input type="text" id="updateTypeSoilDescription" name="updateTypeSoilDescription" placeholder="Ej: Piedroso">
                </div>
                <p id="errorTipoTierraActualizarTipoTierra" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateTypeSoil">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarTipoTierraBtn" id="actualizarTipoTierraBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Type Soil-->
    <div class="modal" id="inactivateTypeSoilModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado del tipo de tierra?</h3>
                <button class="modal-close" id="closeInactivateTypeSoil">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateTypeSoilForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateTypeSoilId" name="inactivateTypeSoilId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateTypeSoil">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarTipoTierraBtn" id="inactivarTipoTierraBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Add Growth Stages-->
    <div class="modal" id="addGrowthStagesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Etapa Crecimiento</h3>
                <button class="modal-close" id="closeAddGrowthStages">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addGrowthStagesForm" action="homeAdmin.php" method="POST" autocomplete="on">

                <div class="form-group">
                    <label for="addSeedGS">Semilla</label>
                    <select name="addSeedGS" id="addSeedGS"  class="select" autocomplete="addSeed">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarSemillasSinEtapa=consultarSemillasSinEtapa();
                           while($datosSemillaSinEtapa=mysqli_fetch_assoc($resultadoConsultarSemillasSinEtapa)){
                        ?>
                            <option value="<?php echo $datosSemillaSinEtapa["idSemilla"]?>"><?php echo $datosSemillaSinEtapa["semNombre"]?></option> 
                        <?php 
                           } 
                            ?>
                    </select>
                </div>
                <p id="errorSemillaAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addGerminationMin">Germinación Mínima</label>
                    <input type="number" id="addGerminationMin" name="addGerminationMin" placeholder="Ej: 10">
                </div>
                <p id="errorGerminacionMinAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addGerminationMax">Germinación Máxima</label>
                    <input type="number" id="addGerminationMax" name="addGerminationMax"  placeholder="Ej: 20">
                </div>
                <p id="errorGerminacionMaxAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addVegetativeGrowthMin">Desarrollo Vegetativo Mínimo</label>
                    <input type="number" id="addVegetativeGrowthMin" name="addVegetativeGrowthMin" placeholder="Ej: 30">
                </div>
                <p id="errorDesarrolloVegetativoMinAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addVegetativeGrowthMax">Desarrollo Vegetativo Máximo</label>
                    <input type="number" id="addVegetativeGrowthMax" name="addVegetativeGrowthMax" placeholder="Ej: 40">
                </div>
                <p id="errorDesarrolloVegetativoMaxAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addFloweringMin">Floración Mínima</label>
                    <input type="number" id="addFloweringMin" name="addFloweringMin" placeholder="Ej: 50">
                </div>
                <p id="errorFloracionMinAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addFloweringMax">Floración Máxima</label>
                    <input type="number" id="addFloweringMax" name="addFloweringMax" placeholder="Ej: 60">
                </div>
                <p id="errorFloracionMaxAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addGrainFillingMin">Llenado de Granos Mínimo</label>
                    <input type="number" id="addGrainFillingMin" name="addGrainFillingMin" placeholder="Ej: 70">
                </div>
                <p id="errorLlenadoGranosMinAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addGrainFillingMax">Llenado de Granos Máximo</label>
                    <input type="number" id="addGrainFillingMax" name="addGrainFillingMax" placeholder="Ej: 80">
                </div>
                <p id="errorLlenadoGranosMaxAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addHarvestMin">Cosecha Mínima</label>
                    <input type="number" id="addHarvestMin" name="addHarvestMin" placeholder="Ej: 90">
                </div>
                <p id="errorCosechaMinAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="addHarvestMax">Cosecha Máxima</label>
                    <input type="number" id="addHarvestMax" name="addHarvestMax" placeholder="Ej: 100">
                </div>
                <p id="errorCosechaMaxAgregarEtapasCrecimiento" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddGrowthStages">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarEtapaCrecimientoBtn" id="agregarEtapaCrecimientoBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Growth Stages-->
    <div class="modal" id="updateGrowthStagesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Etapa Crecimiento</h3>
                <button class="modal-close" id="closeUpdateGrowthStages">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateGrowthStagesForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateGrowthStagesId" name="updateGrowthStagesId">

                <div class="form-group">
                    <label for="updateGerminationMin">Germinación Mínima</label>
                    <input type="text" id="updateGerminationMin" name="updateGerminationMin" placeholder="Ej: 10">
                </div>
                <p id="errorGerminacionMinActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateGerminationMax">Germinación Máxima</label>
                    <input type="text" id="updateGerminationMax" name="updateGerminationMax" placeholder="Ej: 20">
                </div>
                <p id="errorGerminacionMaxActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateVegetativeGrowthMin">Desarrollo Vegetativo Mínimo</label>
                    <input type="text" id="updateVegetativeGrowthMin" name="updateVegetativeGrowthMin" placeholder="Ej: 30">
                </div>
                <p id="errorDesarrolloVegetativoMinActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateVegetativeGrowthMax">Desarrollo Vegetativo Máximo</label>
                    <input type="text" id="updateVegetativeGrowthMax" name="updateVegetativeGrowthMax" placeholder="Ej: 40">
                </div>
                <p id="errorDesarrolloVegetativoMaxActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateFloweringMin">Floración Mínima</label>
                    <input type="text" id="updateFloweringMin" name="updateFloweringMin" placeholder="Ej: 50">
                </div>
                <p id="errorFloracionMinActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateFloweringMax">Floración Máxima</label>
                    <input type="text" id="updateFloweringMax" name="updateFloweringMax" placeholder="Ej: 60">
                </div>
                <p id="errorFloracionMaxActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateGrainFillingMin">Llenado de Granos Mínimo</label>
                    <input type="text" id="updateGrainFillingMin" name="updateGrainFillingMin" placeholder="Ej: 70">
                </div>
                <p id="errorLlenadoGranosMinActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateGrainFillingMax">Llenado de Granos Máximo</label>
                    <input type="text" id="updateGrainFillingMax" name="updateGrainFillingMax"  placeholder="Ej: 80">
                </div>
                <p id="errorLlenadoGranosMaxActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateHarvestMin">Cosecha Mínima</label>
                    <input type="text" id="updateHarvestMin" name="updateHarvestMin" placeholder="Ej: 90">
                </div>
                <p id="errorCosechaMinActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="form-group">
                    <label for="updateHarvestMax">Cosecha Máxima</label>
                    <input type="text" id="updateHarvestMax" name="updateHarvestMax" placeholder="Ej: 100">
                </div>
                <p id="errorCosechaMaxActualizarEtapasCrecimiento" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateGrowthStages">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarEtapaCrecimientoBtn" id="actualizarEtapaCrecimientoBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Gardens-->
    <div class="modal" id="updateGardenModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Jardinera</h3>
                <button class="modal-close" id="closeUpdateGarden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateGardenForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateGardenId" name="updateGardenId">
                <div class="form-group">
                    <label for="updateGardenName">Nombre</label>
                    <input type="text" id="updateGardenName" name="updateGardenName" placeholder="Ej: Jardinera de tomates">
                </div>
                <p id="errorNombreActualizarJardinera" class="error-message"></p>

                <div class="form-group">
                    <label for="updateGardenDescription">Descripción</label>
                    <input type="text" id="updateGardenDescription" name="updateGardenDescription" placeholder="Ej: Jardinera de tomates">
                </div>
                <p id="errorDescripcionActualizarJardinera" class="error-message"></p>

                <div class="form-group">
                    <label for="updateStageSeed">Fase</label>
                    <select name="updateStageSeed" id="updateStageSeed"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarFases=consultarTodasFasesActivas();
                            while($datosFase=mysqli_fetch_assoc($resultadoConsultarFases)){
                        ?>
                            <option value="<?php echo $datosFase["idFase"]?>"><?php echo $datosFase["faseNombre"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorFaseActualizarJardinera" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateGarden">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarJardineraBtn" id="actualizarJardineraBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Gardens-->
    <div class="modal" id="inactivateGardenModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la jardinera?</h3>
                <button class="modal-close" id="closeInactivateGarden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateGardenForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateGardenId" name="inactivateGardenId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateGarden">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarJardineraBtn" id="inactivarJardineraBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update External Factors-->
    <div class="modal" id="updateExternalFactorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Factor Externo</h3>
                <button class="modal-close" id="closeUpdateExternalFactor">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateExternalFactorForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateExternalFactorId" name="updateExternalFactorId">

                <div class="form-group">
                    <label for="updateHumidity">Húmedad Registrada</label>
                    <input type="text" id="updateHumidity" name="updateHumidity" placeholder="Ej: 60">
                </div>
                <p id="errorHumedadActualizarFactorExterno" class="error-message"></p>

                <div class="form-group">
                    <label for="updateTypeWeatherF">Tipo de Clima</label>
                    <select name="updateTypeWeatherF" id="updateTypeWeatherF"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            $resultadoConsultarTiposClima=consultarTiposClimaActivos();
                           while($datosTipoClima=mysqli_fetch_assoc($resultadoConsultarTiposClima)){//Bucle para recorrer todos los tipos de climas registrados
                        ?>
                            <option value="<?php echo $datosTipoClima["idTipoClima"]?>"><?php echo $datosTipoClima["tipoClimaDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoClimaActualizarFactorExterno" class="error-message"></p>

                <div class="form-group">
                    <label for="updateTemperature">Temperatura Registrada</label>
                    <input type="text" id="updateTemperature" name="updateTemperature" placeholder="Ej: 25">
                </div>
                <p id="errorTemperaturaActualizarFactorExterno" class="error-message"></p>
               

                <div class="form-group">
                    <label for="updateWaterAmount">Cantidad de Agua Registrada</label>
                    <input type="text" id="updateWaterAmount" name="updateWaterAmount" placeholder="Ej: 100">
                </div>
                <p id="errorCantidadAguaActualizarFactorExterno" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateExternalFactor">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarFactorExternoBtn" id="actualizarFactorExternoBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate External Factors-->
    <div class="modal" id="inactivateExternalFactorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado del factor externo registrado?</h3>
                <button class="modal-close" id="closeInactivateExternalFactor">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateExternalFactorForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateExternalFactorId" name="inactivateExternalFactorId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateExternalFactor">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarFactorExternoBtn" id="inactivarFactorExternoBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Monitoring-->
    <div class="modal" id="updateMonitoringModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Factor Externo</h3>
                <button class="modal-close" id="closeUpdateMonitoring">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateMonitoringForm" action="homeAdmin.php" method="POST" autocomplete="on" enctype="multipart/form-data">
                <input type="hidden" id="updateMonitoringId" name="updateMonitoringId">
                <div class="form-group">
                    <label for="updateNote">Nota</label>
                    <input type="text" id="updateNote" name="updateNote" placeholder="Ej: Nota del monitoreo">
                </div>
                <p id="errorNotaActualizarMonitoreo" class="error-message"></p>

                <div class="form-group">
                    <label for="updateImage">Imagen</label>
                    <input type="file" id="updateImage" name="updateImage" >
                </div>
                <p id="errorImagenActualizarMonitoreo" class="error-message"></p>

                <div class="form-group">
                    <label for="updatePercentage">Porcentaje</label>
                    <input type="number" id="updatePercentage" name="updatePercentage" placeholder="Ej: 30">
                </div>
                <p id="errorPorcentajeActualizarMonitoreo" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateMonitoring">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarMonitoreoBtn" id="actualizarMonitoreoBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Monitoring-->
    <div class="modal" id="inactivateMonitoringModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado del monitoreo registrado?</h3>
                <button class="modal-close" id="closeInactivateMonitoring">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateMonitoringForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateMonitoringId" name="inactivateMonitoringId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateMonitoring">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarMonitoreoBtn" id="inactivarMonitoreoBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Add Stages-->
    <div class="modal" id="addStagesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Fase</h3>
                <button class="modal-close" id="closeAddStages">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addStagesForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="addStagesId" name="addStagesId">
                <div class="form-group">
                    <label for="addStageName">Nombre</label>
                    <input type="text" id="addStageName" name="addStageName" placeholder="Ej: Fase de crecimiento">
                </div>
                <p id="errorNombreAgregarFase" class="error-message"></p>

                <div class="form-group">
                    <label for="addStageDescription">Descripción</label>
                    <input type="text" id="addStageDescription" name="addStageDescription" placeholder="Ej: Fase donde el cultivo crece">
                </div>
                <p id="errorDescripcionAgregarFase" class="error-message"></p>

                <div class="form-group">
                    <label for="addStagePercentage">Porcentaje</label>
                    <input type="number" id="addStagePercentage" name="addStagePercentage" placeholder="Ej: 25">
                </div>
                <p id="errorPorcentajeAgregarFase" class="error-message"></p>

        
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddStages">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarFaseBtn" id="agregarFaseBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Stages-->
    <div class="modal" id="updateStagesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Fase</h3>
                <button class="modal-close" id="closeUpdateStages">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateStagesForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateStagesId" name="updateStagesId">
                <div class="form-group">
                    <label for="updateStageName">Nombre</label>
                    <input type="text" id="updateStageName" name="updateStageName"  placeholder="Ej: Fase de crecimiento">
                </div>
                <p id="errorNombreActualizarFase" class="error-message"></p>

                <div class="form-group">
                    <label for="updateStageDescription">Descripción</label>
                    <input type="text" id="updateStageDescription" name="updateStageDescription" placeholder="Ej: Fase donde el cultivo crece">
                </div>
                <p id="errorDescripcionActualizarFase" class="error-message"></p>

                <div class="form-group">
                    <label for="updateStagePercentage">Porcentaje</label>
                    <input type="number" id="updateStagePercentage" name="updateStagePercentage" placeholder="Ej: 25">
                </div>
                <p id="errorPorcentajeActualizarFase" class="error-message"></p>

        
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateStages">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarFaseBtn" id="actualizarFaseBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Stages-->
    <div class="modal" id="inactivateStagesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la fase?</h3>
                <button class="modal-close" id="closeInactivateStages">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateStagesForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateStagesId" name="inactivateStagesId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateStages">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarFaseBtn" id="inactivarFaseBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Stage Questions-->
    <div class="modal" id="stageQuestionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Preguntas Fase</h3>
                <div class="action-buttons">
                    <button class="action-button addStageQuestionsBtn" id="addStageQuestionsBtn"><i class="fas fa-plus"></i> Agregar Pregunta</button>
                </div>
                <button class="modal-close" id="closeStageQuestions">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <table>
                <tbody id="contenedorPreguntas">
                </tbody>
            </table>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancelStageQuestions">Cancelar</button>
            </div>
        </div>
    </div> 

    <!--Add Stage Questions-->
    <div class="modal" id="addStageQuestionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Pregunta Fase</h3>
                <button class="modal-close" id="closeAddStageQuestions">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addStageQuestionsForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden"id="addStageQuestionsStageId" name="addStageQuestionsStageId">
                <div class="form-group">
                    <label for="addStageQuestionsQuestion">Pregunta</label>
                    <input type="text" id="addStageQuestionsQuestion" name="addStageQuestionsQuestion" placeholder="Ej: ¿Cuál es la temperatura óptima?">
                </div>
                <p id="errorPreguntaAgregarPreguntaFase" class="error-message"></p>

                <div class="form-group">
                    <label for="addStageQuestionsPercentage">Porcentaje</label>
                    <input type="number" id="addStageQuestionsPercentage" name="addStageQuestionsPercentage" placeholder="Ej: 30">
                </div>
                <p id="errorPorcentajeAgregarPreguntaFase" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddStageQuestions">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarPreguntaFaseBtn" id="agregarPreguntaFaseBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Stage Questions-->
   <div class="modal" id="updateStageQuestionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Pregunta Fase</h3>
                <button class="modal-close" id="closeUpdateStageQuestions">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateStageQuestionsForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateStageQuestionsId" name="updateStageQuestionId">

                <div class="form-group">
                    <label for="updateStageQuestionsQuestion">Pregunta</label>
                    <input type="text" id="updateStageQuestionsQuestion" name="updateStageQuestionsQuestion" placeholder="Ej: ¿Cuál es la temperatura óptima?">
                </div>
                <p id="errorPreguntaActualizarPreguntaFase" class="error-message"></p>

                <div class="form-group">
                    <label for="updateStageQuestionsPercentage">Porcentaje</label>
                    <input type="number" id="updateStageQuestionsPercentage" name="updateStageQuestionsPercentage" placeholder="Ej: 30">
                </div>
                <p id="errorPorcentajeActualizarPreguntaFase" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateStageQuestions">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarPreguntaFaseBtn" id="actualizarPreguntaFaseBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Stage Questions-->
    <div class="modal" id="inactivateStageQuestionsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la fase seleccionada?</h3>
                <button class="modal-close" id="closeInactivateStageQuestions">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateStageQuestionsForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateStageQuestionsId" name="inactivateStageQuestionsId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateStageQuestions">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarPreguntaFaseBtn" id="inactivarPreguntaFaseBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!--Update Alerts-->
    <?php
        $tiposAlerta=[ "bajaHumedad","altaHumedad", "bajaTemperatura", "altaTemperatura",  "bajaCantidadAgua","altaCantidadAgua", "climaInadecuado", 
        "proximoDesarrolloVegetativo","enGerminacion","terminandoGerminacion", "proximoFloracion","enDesarrolloVegetativo","terminandoDesarrolloVegetativo",
        "proximoLlenadoGranos","enFloracion","terminandoFloracion", "proximoCosecha", "enLlenadoGranos",  "terminandoLlenadoGranos", "proximoCulminarCiclo",
        "enCosecha", "terminandoCosecha" ];
    ?>
    <div class="modal" id="updateAlertModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Alerta</h3>
                <button class="modal-close" id="closeUpdateAlert">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="updateAlertForm" action="homeAdmin.php" method="POST" autocomplete="on">
                <input type="hidden" id="updateAlertId" name="updateAlertId">
                <div class="form-group">
                    <label for="updateAlertType">Tipo</label>
                    <select id="updateAlertType" name="updateAlertType"  class="select">
                        <option name="opcion" value ="0">Seleccionar opción</option>
                        <?php 
                            foreach($tiposAlerta as $tipo){  ?>
                                <option value='<?php $tipo ?>'><?php echo formatearTexto($tipo)?></option>
                                <?php
                            }                        
                        ?>       
                    </select>
                </div>
                <p id="errorTipoActualizarAlerta" class="error-message"></p>

                <div class="form-group">
                    <label for="updateAlertDescription">Descripción</label>
                    <input type="text" id="updateAlertDescription" name="updateAlertDescription" placeholder="Ej: Las condiciones no son las más adecuadas">
                </div>
                <p id="errorDescripcionActualizarAlerta" class="error-message"></p>

                <div class="form-group">
                    <label for="updateAlertRecommendation">Recomendación</label>
                    <input type="text" id="updateAlertRecommendation" name="updateAlertRecommendation" placeholder="Ej: Mover la planta a un lugar mejor ">
                </div>
                <p id="errorRecomendacionActualizarAlerta" class="error-message"></p>

                <div class="form-group">
                    <label for="updateAlertRecordedValue">Valor Registrado</label>
                    <input type="text" id="updateAlertRecordedValue" name="updateAlertRecordedValue" placeholder="Ej: 20 ">
                </div>
                <p id="errorValorRegistradoActualizarAlerta" class="error-message"></p>

                <div class="form-group">
                    <label for="updateAlertRecomendedRange">Rango Recomendado</label>
                    <input type="text" id="updateAlertRecomendedRange" name="updateAlertRecomendedRange" placeholder="Ej: 50-100">
                </div>
                <p id="errorRangoRecomendadoActualizarAlerta" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateAlert">Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarAlertaBtn" id="actualizarAlertaBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!--Inactivate Alerts-->
    <div class="modal" id="inactivateAlertModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>¿Está seguro de modificar el estado de la alerta?</h3>
                <button class="modal-close" id="closeInactivateAlert">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="inactivateAlertForm" action="homeAdmin.php" method="POST">
                <input type="hidden" id="inactivateAlertId" name="inactivateAlertId">
            
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelInactivateAlert">Cancelar</button>
                    <button type="submit" class="btn-primary" name="inactivarAlertaBtn" id="inactivarAlertaBtn">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <?php 
    //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){ 
            switch ($_SESSION["alerta"]){
                case "tipoDocumentoRegistrado": ?>
                    <script>
                        //Mensaje cuando se registro el tipo de documento
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "El tipo de documento fue registrado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "tipoDocumentoActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el tipo de documento se actualiza correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El tipo de documento fue actualizado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoTipoDocumentoActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del tipo de documento ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del tipo de documento modificado!",
                            text: "El estado del tipo de documento fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "usuarioActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando la información del usuario ha sido actualizada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Usuario actualizado!",
                            text: "Los datos del usuario fueron actualizados correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoUsuarioActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la cuenta del usuario ha sido modificada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Estado de la cuenta modificada!",
                            text: "El estado de la cuenta del usuario ha sido modificada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoSolicitudActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la solicitud ha sido modificada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Estado de la solicitud modificada!",
                            text: "El estado de la solicitud ha sido modificada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoResenaActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la reseña ha sido modificada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Estado de la reseña modificada!",
                            text: "El estado de la reseña ha sido modificada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "semillaRegistrada": ?>
                    <script>
                        //Muestra un mensaje cuando la semilla ha sido registrada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "La semilla fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "semillaActualizada": ?>
                    <script>
                        //Muestra un mensaje cuando la información de la semilla ha sido actualizada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Semilla actualizada!",
                            text: "Los datos de la semilla fueron actualizados correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoSemillaActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la semilla ha sido modificada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Estado de la semilla modificado!",
                            text: "El estado de la semilla ha sido modificado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "tipoSemillaRegistrado": ?>
                    <script>
                        //Mensaje cuando se registro el tipo de semilla
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "El tipo de semilla fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "tipoSemillaActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el tipo de semilla se actualiza correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El tipo de semilla fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoTipoSemillaActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del tipo de semilla ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del tipo de semilla modificado!",
                            text: "El estado del tipo de semilla fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "fichaTecnicaRegistrada": ?>
                    <script>
                        //Muestra un mensaje cuando la ficha tecnica ha sido registrada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "La ficha técnica fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "fichaTecnicaActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la ficha tecnica se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La ficha técnica fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "tipoClimaRegistrado": ?>
                    <script>
                        //Mensaje cuando se registro el tipo de clima
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "El tipo de clima fue registrado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "tipoClimaActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el tipo de clima se actualiza correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El tipo de clima fue actualizado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoTipoClimaActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del tipo de clima ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del tipo de clima modificado!",
                            text: "El estado del tipo de clima fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "tipoTierraRegistrado": ?>
                    <script>
                        //Mensaje cuando se registro el tipo de tierra
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "El tipo de tierra fue registrado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "tipoTierraActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el tipo de tierra se actualiza correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El tipo de tierra fue actualizado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoTipoTierraActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del tipo de tierra ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del tipo de tierra modificado!",
                            text: "El estado del tipo de tierra fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;
                
                case "etapaCrecimientoRegistrada": ?>
                    <script>
                        //Muestra un mensaje cuando la etapa de crecimiento ha sido registrada correctamente por el administrador
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "La etapa de crecimiento fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "etapaCrecimientoActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la etapa de crecimiento se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La etapa de crecimiento fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "jardineraActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la jardinera se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La jardinera fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoJardineraActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la jardinera ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado de la jardinera modificado!",
                            text: "El estado de la jardinera fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "alertaActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la alerta se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La alerta fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoAlertaActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la alerta ha sido modificado correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado de la alerta modificado!",
                            text: "El estado de la alerta fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "factorExternoActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el factor externo se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El factor externo fue actualizado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoFactorExternoActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del factor externo ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del factor externo modificado!",
                            text: "El estado del factor externo fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "monitoreoActualizado": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando el monitoreo se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "El monitoreo fue actualizado correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoMonitoreoActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado del monitoreo ha sido modificado correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado del monitoreo modificado!",
                            text: "El estado del monitoreo fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "faseRegistrada": ?>
                    <script>
                        //Mensaje cuando se registro de la fase
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "La fase fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "faseActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la fase se actualiza correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La fase fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoFaseActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la fase ha sido modificado orrectamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado de la fase modificado!",
                            text: "El estado de la fase fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "preguntaFaseRegistrada": ?>
                    <script>
                        //Mensaje cuando se registro de la pregunta de seguimiento
                        mostrarMensaje({
                            title: "¡Registro exitoso!",
                            text: "La pregunta de seguimiento fue registrada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "preguntaFaseActualizada": ?>
                    <script>
                        //Muestra un mensaje de confirmación cuando la pregunta de seguimiento se actualizo correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Actualización exitosa!",
                            text: "La pregunta de seguimiento fue actualizada correctamente en el sistema",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "estadoPreguntaFaseActualizado": ?>
                    <script>
                        //Muestra un mensaje cuando el estado de la pregunta de seguimiento ha sido modificado correctamente en el sistema
                        mostrarMensaje({
                            title: "¡Estado de la pregunta de seguimiento modificado!",
                            text: "El estado de la pregunta de seguimiento fue modificado correctamente",
                            icon: "success",
                            iconColor:"#0d6b52",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                <?php
                break;

                case "errorDeRegistro": ?>
                    <script>
                        //Muestra un mensaje genérico cuando ocurre un error durante el proceso de registro en el sistema
                        mostrarMensaje({
                            title: "¡Error en el registro!",
                            text: "Ocurrió un problema al procesar la solicitud de registro. Intente nuevamente más tarde",
                            icon: "error",
                            iconColor:"#0d6b52",
                            footer: "Si el problema persiste, contacta al administrador",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        });
                    </script>
                <?php
                break;

                case "errorDeActualizacion": ?>
                    <script>
                        //Muestra un mensaje genérico cuando ocurre un error durante el proceso de actualización en el sistema
                        mostrarMensaje({
                            title: "¡Error en la actualización!",
                            text: "Ocurrió un problema al intentar actualizar la información. Intente nuevamente más tarde",
                            icon: "error",
                            iconColor:"#0d6b52",
                            footer: "Si el problema persiste, contacta al administrador",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        });
                    </script>
                <?php
                break;

                case "errorDeActualizacionEstado": ?>
                    <script>
                        //Muestra un mensaje genérico cuando ocurre un error durante el proceso de actualizacion del estado en el sistema
                        mostrarMensaje({
                            title: "¡Error en la actualización del estado!",
                            text: "Ocurrió un problema al intentar modificar el estado del registro. Intente nuevamente más tarde",
                            icon: "error",
                            iconColor:"#0d6b52",
                            footer: "Si el problema persiste, contacta al administrador",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        });
                    </script>
                <?php
                break;

                case "errorAlSubirImagen": ?>
                    <script>
                        //Mensaje cuando el avatar o imagen del usuario no se pudo subir 
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado!",
                            text:"Por favor, vuelva a intentarlo más tarde",
                            icon:"error",
                            iconColor:"#0d6b52",
                                                            
                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        })
                    </script>
                    <?php
                break;

                case "errorDeRegistroExistente": ?>
                    <script>
                        //Muestra un mensaje genérico cuando el usuaario intenta registrar un registro que ya existe en el sistema
                        mostrarMensaje({
                            title: "¡Registro existente!",
                            text: "La información que intenta registrar ya existe en el sistema",
                            icon: "warning",
                            iconColor:"#0d6b52",
                            footer: "Verifique la información e inténtelo nuevamente",

                            rutaTrue: "homeAdmin.php",
                            rutaFalse: "homeAdmin.php"
                        });
                    </script>
                    <?php
                break;

                case "errorAlEnviarCorreoInformativo": ?>
                    <script>
                        mostrarMensaje({
                            title:"¡Error a la hora de enviar el correo electrónico!",
                            text:"Recargue la página y vuelva a intentarlo",
                            icon:"error",
                            rutaTrue:"homeAdmin.php",
                            rutaFalse:"homeAdmin.php"
                        });
                    </script>
                    <?php
                break;
            }
            unset($_SESSION["alerta"]);
        }
    ?>
    <script src="../js/script_ValidacionFormularios.js" defer></script>
    <script src="../js/scripts_HomeAdmin.js"></script>  
</body>
</html>