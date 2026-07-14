<?php 
    session_start();
    $usuarioActivo=$_SESSION["numeroDocumento"];
    $_SESSION["alerta"]="";

    $page = isset($_GET['page']) ? $_GET['page'] : 'profile';

    $_SESSION["origenActualizacion"] = "usuario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Usuario | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style_HomeUsuario.css">
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

        //Abrir la conexion a la base de datos a través de una función
        $conexion_db=abrirConexionDB();

        //Recuperar la fecha actual del equipo
        $fechaActual=recuperarFechaActual();

        //Consultar los datos del usuario que inicio sesión
        $datosUsuario=consultarDatosUsuario($usuarioActivo);

        //Consultar todos los tipos de clima registrados
        $resultadoConsultarTiposClima=consultarTiposClimaActivos();

        //Consultar todos los tipos de documentos
        $resultadoConsultarTiposDocumento=consultarTiposDocumentosActivos();

        //Consultar los datos de todas las semillas
        $resultadoConsultaSemillas=consultarSemillasActivasConFichaYEtapa(false);

        //Consultar las solicitudes asociadas a un usuario en estado pendiente
        $resultadoConsultarSolicitudes=consultarSolicitudes($usuarioActivo);

        //Consultar las solicitudes asociadas a un usuario en cualquier estado
        $resultadoConsultarHistorialSolicitudes=consultarHistorialSolicitudesUsuario($usuarioActivo);

        //Consultar la cantidad de alertas activas asociadas a un usuario
        $resultadoConsultarAlertaActivas=consultarCantidadAlertasUsuario($usuarioActivo);

        //Recuperar el resultado del count de la consulta 
        $cantidadFilas=mysqli_fetch_assoc($resultadoConsultarAlertaActivas);

        //Consultar el tiempo de actividad del usuario
        $tiempoActividad=calcularActividadUsuario($datosUsuario["usuFechaIngreso"]);

        //Funcion para consultar las jardineras asociadas a un usuario
        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);

        //Funcion para consultar las jardineras asociadas a un usuario con informacion de sus factores externos y evoluciones registradas
        $jardinerasReporte=consultarJardinerasConDetalles($usuarioActivo);

        //== USUARIO ==
            //Actualizar el avatar del usuario
            if(isset($_FILES["imgAvatar"]) && $_FILES["imgAvatar"]["error"] == 0){
                $nombre = $_FILES["imgAvatar"]["name"];
                $tmp = $_FILES["imgAvatar"]["tmp_name"];
                $size = $_FILES["imgAvatar"]["size"];

                // Obtener extensión
                $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

                // Extensiones permitidas
                $extPermitidas = ['jpg','jpeg','png','webp'];

                if(in_array($extension, $extPermitidas) && $size <= 2*1024*1024){

                    // Nombre único
                    $nombreF = uniqid("avatar_") . "." . $extension;

                    // Ruta destino
                    $rutaImagen = "../images/avatares/" . $nombreF;

                    // Mover archivo
                    if(move_uploaded_file($tmp, $rutaImagen)){

                        if(agregarImagenPerfil($usuarioActivo, $rutaImagen)){ 
                            registrarActividadUsuario("Perfil","Actualizar", "Actualizó su imagen de perfil", $usuarioActivo); 
                            ?>
                            <meta http-equiv="refresh" content="1">
                        <?php   
                        }else{
                            $_SESSION["alerta"] = "errorAlSubirImagen";
                        }
                    }else{
                        $_SESSION["alerta"] = "errorSubidaImagen";
                    }
                }else{
                    $_SESSION["alerta"] = "formatoImagenInvalido";
                }
            }

            //Agregar una nueva solicitud
            if(isset($_POST["enviarSolicitudBtn"])){
                //Recuperar los valores del formulario
                $tipoSolicitud=trim($_POST["typeRequest"]);
                $mensaje=ucfirst(strtolower(trim($_POST["message"])));;

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
                        //Evaluar si la consulta se ejecutó correctamente
                        if(registrarSolicitudVariada($fechaActual, $tipoSolicitud, $mensaje, $semillaSeleccionada, $usuarioActivo)==true){
                            //Enviar correo electrónico para confirmar su solicitud 
                            require_once("../functions/enviarCorreos.php");

                            $correo = $datosUsuario["usuCorreo"];
                            $nombre = $datosUsuario["usuNombre"];

                            //Enviar correo
                            $enviado = enviarCorreo(
                                $correo,
                                $nombre,
                                "BioUrbis - Solicitud recibida con éxito",
                                correoSolicitudUsuario(
                                    $nombre,
                                    $tipoSolicitud,
                                    $mensaje
                                )
                            );

                            if(!$enviado){
                                $_SESSION["alerta"]="errorAlEnviarCorreoSolicitud";
                            }

                            //Registrar la actividad del usuario
                            registrarActividadUsuario("Solicitud","Crear", "Registró una nueva solicitud de tipo ${$tipoSolicitud}", $usuarioActivo);

                            header("Location: homeUsuario.php?page=request");
                            exit();
                        }else{
                            $_SESSION["alerta"]="errorEnviarSolicitud";
                        }
                    }
                }else{
                    //Evaluar si la consulta se ejecutó correctamente
                    if(registrarSolicitudVariada($fechaActual, $tipoSolicitud, $mensaje, null, $usuarioActivo)==true){
                        //Enviar correo electrónico para confirmar su solicitud 
                        require_once("../functions/enviarCorreos.php");

                        $correo = $datosUsuario["usuCorreo"];
                        $nombre = $datosUsuario["usuNombre"];

                        //Enviar correo
                        $enviado = enviarCorreo(
                            $correo,
                            $nombre,
                            "BioUrbis - Solicitud recibida con éxito",
                            correoSolicitudUsuario(
                                $nombre,
                                $tipoSolicitud,
                                $mensaje
                            )
                        );

                        if(!$enviado){
                            $_SESSION["alerta"]="errorAlEnviarCorreoSolicitud";
                        }

                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Solicitud","Crear", "Registró una nueva solicitud de tipo ${$tipoSolicitud}", $usuarioActivo);

                        header("Location: homeUsuario.php?page=request");
                        exit();
                    }else{
                        $_SESSION["alerta"]="errorEnviarSolicitud";
                    }
                }
            }


        //==

        //== JARDINERA ==
            //Crear una nueva jardinera
            if(isset($_POST["btn-crearJardinera"])){
                //Consulta para verificar la cantidad de jardineras que tiene un usuario activo
                $resultadoConsultarCantidadJardineras=consultarCantidadJardineras($usuarioActivo);

                //Evalua si encuentra un registro del usuario activo para verificar la cantidad de jardineras que tiene
                if(mysqli_num_rows($resultadoConsultarCantidadJardineras)>0){
                    $datosConsulta=arregloDatos($resultadoConsultarCantidadJardineras);

                    //Evalua si el usuario activo ha alcanzado el limite de jardineras permitidas para registrar
                    if($datosConsulta["usuCantidadJardineras"]>=5){
                        $_SESSION["alerta"]="limiteJardineras";
                    }else{
                        //El sistema procede a recuperar los datos y registrar una nueva jardinera para el usuario activo
                        $nombreJardinera=ucwords(strtolower(trim($_POST["gardenName"])));
                        $descripcionJardinera=ucfirst(strtolower(trim($_POST["gardenDescription"])));
                        $semillaJardinera=trim($_POST["gardenSeed"]);

                        //Si el resultado de la consulta es exitoso
                        if(agregarJardinera($nombreJardinera, $descripcionJardinera, $semillaJardinera, $usuarioActivo)){
                            //Consulta para recuperar la cantidad de jardineras que tiene el usuario activo
                            $datosUsuario=consultarDatosUsuario($usuarioActivo);
                            //Recuperar la cantidad de jardineras del usuario
                            $cantidadJardineras=$datosUsuario["usuCantidadJardineras"];

                            //Incrementar la cantidad de jardineras que tiene el usuario
                            $cantidadJardineras=$cantidadJardineras+1;
                            
                            //Si el resultado de la actualizacion es exitosa
                            if(actualizarCantidadJardinerasUsuario($usuarioActivo, $cantidadJardineras)){
                                //Registrar la actividad del usuario
                                registrarActividadUsuario("Jardinera","Crear", "Registró una nueva jardinera", $usuarioActivo); 

                                //Redireccionar al usuario a la página de mis jardineras para mostrar la nueva jardinera registrada
                                header("Location: homeUsuario.php?page=gardens");
                                exit();
                            }else{
                                //Mostrar mensaje de error
                                $_SESSION["alerta"]="registroFallidoJardinera";
                            }
                        }else{
                            //Mostrar mensaje de error
                            $_SESSION["alerta"]="registroFallidoJardinera";
                        }
                    }
                }else{
                    //Mostrar mensaje de error
                    $_SESSION["alerta"]="errorConsulta";
                }
            }

            //Actualizar los datos de una jardinera
            if(isset($_POST["actualizarJardineraBtn"])){
                //Recuperar el id de la jardinera a actualizar desde el formulario
                $idJardinera=$_POST["gardenId"];
            
                //Recuperar los datos ingresados por el usuario desde el formulario
                $nombreJardinera = ucwords(strtolower(trim($_POST["updateGardenName"])));
                $descripcionJardinera = ucfirst(strtolower(trim($_POST["updateGardenDescription"])));

                //Consultar los datos actuales de la jardinera a actualizar
                $datosJardinera=consultarDatosJardineraPorId($idJardinera);

                //Actualizar el nombre de la jardinera
                $_SESSION["nuevoNombreJardinera"] = ($nombreJardinera!="") ? $nombreJardinera : $datosJardinera["jarNombre"];

                //Actualizar la descripcion de la jardinera
                $_SESSION["nuevaDescripcionJardinera"] = ($descripcionJardinera!="") ? $descripcionJardinera : $datosJardinera["jarDescripcion"];

                //Actualizar los valores de la jardinera con sus nuevos valores almacenados en las sesiones
                $nombreJardinera=$_SESSION["nuevoNombreJardinera"];
                $descripcionJardinera=$_SESSION["nuevaDescripcionJardinera"];

                if(actualizarJardinera($idJardinera, $nombreJardinera, $descripcionJardinera)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Jardinera","Actualizar", "Actualizó los datos de la jardinera: {$nombreJardinera}", $usuarioActivo);

                    $_SESSION["alerta"]="jardineraActualizada";
                }else{
                    $_SESSION["alerta"]="errorAlActualizarJardinera";
                }
            }
        
        //==
        
        //== FACTORES EXTERNOS ==
            //Agregar factores externos de una jardinera
            if(isset($_POST["agregarFactoresExternosBtn"])){
                //Recuperar el id de la jardinera desde el formulario
                $idJardinera=$_POST["gardenSelectedId"];

                //Recuperar los valores del formulario
                $humedad=trim($_POST["humidity"]);
                $cantidadAgua=trim($_POST["amountWater"]);
                $temperatura=trim($_POST["temperature"]);
                $clima=trim($_POST["weather"]);

                //Si el registro es exitoso
                if(agregarFactoresExternos($idJardinera, $humedad, $cantidadAgua, $temperatura, $clima)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Factores Externos","Crear", "Agregó factores externos para la jardinera N° {$idJardinera}", $usuarioActivo);

                    $_SESSION["alerta"]="factorExternoRegistrado";

                //Si el registro no es exitoso
                }else{
                    $_SESSION["alerta"]="errorAlRegistrarFactor";
                }
            }

            //Generar factores externos de una jardinera
            if(isset($_POST["generarFactoresExternosBtn"])){
                //Recuperar los valores del formulario 
                $idJardinera=$_POST["gardenIdGenerateExternalFactor"];
                $humedad=$_POST["humidityGenerated"];
                $cantidadAgua=$_POST["amountWaterGenerated"];
                $temperatura=$_POST["temperatureGenerated"];
                $tipoClima=$_POST["typeWeatherGenerated"];

                $idTipoClima=consultarTipoClimaPorDescripcion($tipoClima);

                if(agregarFactoresExternos($idJardinera, $humedad, $cantidadAgua, $temperatura, $idTipoClima)){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Factores Externos","Generar", "Generó un factor externo automático para la jardinera N° {$idJardinera}", $usuarioActivo);

                    $_SESSION["alerta"]="factorExternoRegistrado";
                }else{
                    $_SESSION["alerta"]="errorAlRegistrarFactor";
                }
            }
        
        //==

        //== EVOLUCION 
            //Agregar evolucion de una jardinera
            if(isset($_POST["agregarEvolucionBtn"])){
                //Recuperar los valores del formulario
                $idJardinera = trim($_POST["gardenEvolutionId"]);
                $idFase = trim($_POST["faseEvolutionId"]);
                $notaEvolucion = ucfirst(strtolower(trim($_POST["notaEvolucion"])));

                //Inicializar la variable de la ruta del imagen
                $rutaImagen = ""; 

                // Validar y procesar la imagen de evolución si se ha subido una
                if(isset($_FILES['imagenEvolucion']) && $_FILES['imagenEvolucion']['error'] == 0){
                    //Recuperar el nombre de la imagen
                    $nombre = $_FILES['imagenEvolucion']['name'];
                    //Recuperar la ruta temporal de la imagen
                    $rutaTemp = $_FILES['imagenEvolucion']['tmp_name'];
                    //Recuperar el tamaño de la imagen
                    $size = $_FILES['imagenEvolucion']['size'];

                    //Validar la extension y el tamaño de la imagen
                    $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

                    //Arreglo con las extensiones permitidas
                    $extPermitidas = ['jpg','jpeg','png','webp'];

                    //Si la extension de la imagen se encuentra dentro de las extensiones permitidas y el tamaño 
                    if(in_array($extension, $extPermitidas) && $size <= 2*1024*1024){

                        //Renombrar la imagen con un nombre unico utilizando la función uniqid y la extension original de la imagen
                        $nombreF = uniqid("img_") . "." . $extension;

                        //Recuperar la ruta de la image
                        $rutaDestino = "../images/seguimiento/" . $nombreF;

                        //Mover la imagen a la carpeta de destino y actualizar la ruta de la imagen en la base de datos
                        if(move_uploaded_file($rutaTemp, $rutaDestino)){
                            $rutaImagen = $rutaDestino;
                        }else{
                            $_SESSION["alerta"] = "errorSubidaImagen";
                            return;
                        }
                    }else{
                        $_SESSION["alerta"] = "formatoImagenInvalido";
                        return;
                    }
                }

                //Inicializar la variable que almacena el total del porcentaje
                $totalPorcentaje = 0;

                //Para cada pregunta del formulario, si el nombre del input comienza con "preg_", se recupera el valor de la respuesta y se suma al total del porcentaje
                foreach($_POST as $key => $value){
                    if(strpos($key, "preg_") === 0){
                        $idPregunta = str_replace("preg_", "", $key);
                        $respuesta = $value;
                        //Incrementar el porcentaje 
                        $totalPorcentaje += $respuesta;
                    }
                }

                //Registrar la evolución de la jardinera en la base de datos, incluyendo la nota, la ruta de la imagen y el porcentaje total obtenido
                $fechaRegistroEvolucion= agregarEvolucionJardinera(
                    $idJardinera, 
                    $notaEvolucion, 
                    $rutaImagen, 
                    $totalPorcentaje
                );

                //Si el registro es exitoso
                if($fechaRegistroEvolucion!=false){
                    //Registrar la actividad del usuario
                    registrarActividadUsuario("Evolución Jardinera","Crear", "Agregó evolución para la jardinera N° {$idJardinera}", $usuarioActivo);

                    $_SESSION["alerta"] = "evolucionRegistrada"; 
                
                    //Si el porcentaje del seguimiento es igual a 100
                    if($totalPorcentaje === 100){
                        //Obtener los datos de la jardinera
                        $datosJardinera=consultarDatosJardineraPorId($idJardinera);

                        //Obtener los datos de la semilla
                        $datosSemilla=consultarDatosSemilla($datosJardinera["idSemilla"]);

                        //Obtener los datos de la etapa de crecimiento de la semilla de la jardinera
                        $datosEtapaCrecimiento=consultarDatosEtapaCrecimiento($datosSemilla["idEtapaCrecimiento"]);
                        
                        //Evaluar en que fase se encuentra actualmente la jardinera
                        switch($idFase){

                            //Si la fase es germinacion, pasa a desarrollo vegetativo
                            case 1: 
                                //Recuperar los dias minimos de cada fase actual 
                                $diasMinimos= $datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"];
                                $nuevaFase = 2; 
                            break;

                            //Si la fase es desarrollo vegetativo, pasa a floracion
                            case 2: 
                                //Recuperar los dias minimos de cada fase actual 
                                $diasMinimos= $datosEtapaCrecimiento["etapaCreDiasFloracionMin"];
                                $nuevaFase = 3; 
                            break;

                            //Si la fase es floracion, pasa a llenado de granos
                            case 3: 
                                //Recuperar los dias minimos de cada fase actual 
                                $diasMinimos= $datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"];
                                $nuevaFase = 4; 
                            break;

                            //Si la fase es llenado de granos, pasa a cosecha
                            case 4: 
                                //Recuperar los dias minimos de cada fase actual 
                                $diasMinimos= $datosEtapaCrecimiento["etapaCreDiasCosechaMin"];
                                $nuevaFase = 5; 
                            break;

                            default: 
                                $nuevaFase = $idFase;
                        }

                        //Calcular la diferencia de dias entre la fecha de creacion de la jardinera y la fecha actual
                        $diferenciaFechas=calcularDiasEntreFechas($datosJardinera["jarFechaCreacion"], $fechaActual);

                        if($diferenciaFechas>=$diasMinimos){  
                            //Obtener el porcentaje de la nueva fase de la jardinera
                            $datosFase = consultarDatosFase($nuevaFase);
                            $porcentajeFase = $datosFase["fasePorcentaje"];

                            //Actualizar el porcentaje y la fase de la jardinera
                            $resultadoActualizarEvolucion = actualizarEvolucionJardinera(
                                $idJardinera, 
                                $porcentajeFase, 
                                $nuevaFase
                            );

                            //Si el resultado de la actualización es exitoso, mostrar un mensaje de éxito
                            if($resultadoActualizarEvolucion){
                                $_SESSION["alerta"] = "jardineraEvolucionada";
                            }
                            
                        }else{
                            //Mostrar una alerta cuando la jardinera no cumple los dias minimos para pasar de fase
                            $_SESSION["alerta"] = "tiempoInsuficienteEvolucion";
                        }

                        //Actualizar el estado del registro de la evolucion 
                        actualizarEstadoEvolucionJardinera($idJardinera);
                    }else{
                        //Si el porcentaje del seguimiento no llega al puntaje necesario, mantener la fase actual
                        $nuevaFase = $idFase;
                        $_SESSION["alerta"] = "jardineraNoEvolucionada";
                    }
                }else{
                    $_SESSION["alerta"] = "errorAlRegistrarEvolucion";
                }
            }
        //==

        //== ALERTAS 
            //Si la pagina actual es profile, mostrar alertas
            if($page=="profile"){
                //Procesador para evaluar las posibles alertas de todas las jardineras del usuario que ingreso
                include("procesadorAlertas.php");

                //Consultar las alertas asociadas a un usuario
                $resultadoConsultarAlertas=consultarAlertas($usuarioActivo);

                $alertas = [];
                //Arreglo con los tipos de alertas de factores externos permitidos
                $alertasFactoresExternos=[
                    "bajaHumedad",
                    "altaHumedad", 
                    "bajaTemperatura", 
                    "altaTemperatura", 
                    "bajaCantidadAgua", 
                    "altaCantidadAgua", 
                    "climaInadecuado"
                ];

                //Arreglo con los tipos de alertas de fecha permitidos
                $alertasFechas=[
                    "proximoDesarrolloVegetativo",
                    "enGerminacion",
                    "terminandoGerminacion",

                    "proximoFloracion",
                    "enDesarrolloVegetativo",
                    "terminandoDesarrolloVegetativo",

                    "proximoLlenadoGranos",
                    "enFloracion",
                    "terminandoFloracion",

                    "proximoCosecha",
                    "enLlenadoGranos",
                    "terminandoLlenadoGranos",

                    "proximoCulminarCiclo",
                    "enCosecha",
                    "terminandoCosecha"
                ];

                //Mientras el usuario tenga alertas activas
                while($row = mysqli_fetch_assoc($resultadoConsultarAlertas)){
                    //Evaluar si el tipo de la alerta se encuentra dentro de los tipos permitidos de factores externos
                    if(in_array($row["alerTipo"], $alertasFactoresExternos) && $row["alerEstado"]=="Activa"){
                        $simbolo = ($row['alerTipo']==="bajaCantidadAgua" || $row['alerTipo']==="altaCantidadAgua" ) ? "mL" : "º";
                        //Almacenar el contenido de la alerta en un arreglo unidimensional
                        $alertas[] = [
                            "id" => $row["idAlerta"],
                            "html" => "<b>Jardinera: </b> {$row['jarNombre']}<br>
                                    <b>Semilla: </b> {$row['semNombre']}<br>
                                    <b>Fecha: </b> {$row['alerFecha']}<br>
                                    <b>Alerta: </b> {$row['alerDescripcion']}<br>
                                    <b>Recomendación: </b> {$row['alerRecomendacion']}<br>
                                    <b>Valor Obtenido: </b> {$row['alerValorRegistrado']}{$simbolo}<br>
                                    <b>Rango Recomendado: </b> {$row['alerRangoRecomendado']}"
                        ];

                        //Enviar correo de notificación al usuario por cada alerta activa
                        $tipoCorreo = "factores";
                        include("../php/mailNotificacionAlerta.php");

                    //Evaluar si el tipo de la alerta se encuentra dentro de los tipos permitidos de fechas
                    }elseif(in_array($row["alerTipo"], $alertasFechas) && $row["alerEstado"]=="Activa"){
                        //Almacenar el contenido de la alerta en un arreglo unidimensional
                        $alertas[] = [
                            "id" => $row["idAlerta"],
                            "html" => "<b>Jardinera: </b> {$row['jarNombre']}<br>
                                    <b>Semilla: </b> {$row['semNombre']}<br>
                                    <b>Fecha: </b> {$row['alerFecha']}<br>
                                    <b>Alerta: </b> {$row['alerDescripcion']}<br>
                                    <b>Recomendación: </b> {$row['alerRecomendacion']}<br>"
                        ];

                        //Enviar correo de notificación al usuario por cada alerta activa
                        $tipoCorreo = "fechas";
                        include("../php/mailNotificacionAlerta.php");
                    }  
                }
            }    

            //Si el usuario acepta una alerta, actualizar su estado a inactiva
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["idAlerta"])) {
                $idAlerta=$_POST["idAlerta"];

                actualizarEstadoAlerta($idAlerta);

                registrarActividadUsuario("Alerta","Confirmar", "Usuario confirmó la alerta activa Nº {$idAlerta} de alguna de sus jardineras", $usuarioActivo);

                echo json_encode(["ok" => true]);

                exit();
            }                      
        //==   
    ?>
    <script>
        //Almacenar el valor del arreglo en php en una constante en js
        const alertas = <?php echo json_encode($alertas); ?>;

        //Crear la notificacion pequeña en la parte inferior izquierda
        const Toast = Swal.mixin({
            toast: true,
            position: "bottom-end",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Ignorar",
            timer: 8000,
            timerProgressBar: true,
            width: 480,
            padding: "18px",
            iconColor: "rgb(184, 98, 12)",
            background: "#ffffff",
            color: "#2c3e50",
            buttonsStyling: false,
            customClass: {
                popup: "toast-pro",
                confirmButton: "btn-aceptar",
                cancelButton: "btn-ignorar",
                title: "toast-title",
                htmlContainer: "toast-content"
            }
        });
                
        //Funcion asincrona para mostrar las alertas una por una, esperando a que se cierre la alerta anterior para mostrar la siguiente
        async function mostrarAlertas() {
            for (let alerta of alertas) {

                const resultado = await Toast.fire({
                    icon: "warning",
                    html: alerta.html
                });

                if (resultado.isConfirmed) {

                    const formData = new FormData();
                    formData.append("idAlerta", alerta.id);

                    await fetch(window.location.href, {
                        method: "POST",
                        body: formData
                    });

                    window.location.reload();

                    continue; 
                }
            }
        }
        
        mostrarAlertas();
    </script>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <button class="sidebar-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="brand">
                    <i class="fas fa-seedling"></i>
                    <span>BioUrbis</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Sidebar -->
        <aside class="sidebar collapsed" id="sidebar">
            <nav class="sidebar-nav">
                <a href="homeUsuario.php?page=profile" class="nav-item action-card" data-action="view-profile" title="Perfil" style="border:none">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>

                <a href="homeUsuario.php?page=gardens" class="nav-item action-card" data-action="view-gardens" title="Jardineras" style="border:none">
                    <i class="fas fa-leaf"></i>
                    <span>Jardineras</span>
                </a>

                <a href="homeUsuario.php?page=add-garden" class="nav-item action-card" data-action="add-garden" title="Agregar Jardineras" style="border:none">
                    <i class="fas fa-plus-circle"></i>
                    <span>Agregar Jardineras</span>
                </a>

                <a href="homeUsuario.php?page=externalFactors" class="nav-item action-card" data-action="add-view--external-factors" title="Factores Externos" style="border:none">
                    <i class="fas fa-cloud-sun"></i>
                    <span>Factores Externos</span>
                </a>

                <a href="homeUsuario.php?page=gardenEvolution" class="nav-item action-card" data-action="add-view-garden-evolution" title="Evolución Jardineras" style="border:none">
                   <i class="fas fa-hourglass-half"></i>
                    <span>Evolución Jardineras</span>
                </a>

                <a href="homeUsuario.php?page=monitoring" class="nav-item action-card" data-action="view-monitoring" title="Monitoreos" style="border:none">
                    <i class="fas fa-chart-line"></i>
                    <span>Monitoreos</span>
                </a>

                <a href="homeUsuario.php?page=reports" class="nav-item action-card" data-action="view-report" title="Reportes" style="border:none">
                    <i class="fas fa-file-alt"></i>
                    <span>Reportes</span>
                </a>

                <a href="homeUsuario.php?page=request" class="nav-item action-card" data-action="view-request" title="Solicitudes" style="border:none">
                    <i class="fas fa-inbox"></i>
                    <span>Solicitudes</span>
                </a>

                <a href="homeUsuario.php?page=logout" class="nav-item action-card" data-action="logout" title="Cerrar Sesión" style="border:none">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </a>
            </nav>
        </aside>
        <!-- SCRIPT -->
        <script>
            const toggleBtn = document.getElementById("menuToggle");
            const sidebar = document.getElementById("sidebar");

            toggleBtn.addEventListener("click", () => {
                sidebar.classList.toggle("collapsed");
            });
        </script>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Profile Page -->
            <div class="page <?php echo ($page == 'profile') ? 'active' : ''; ?>" id="profile-page">
                <div class="content-wrapper">
                    <div class="profile-header">
                        <div class="profile-info">
                            <div class="profile-avatar">
                                <img src="<?php echo $datosUsuario["usuImagen"] ?>" alt="Avatar del usuario" id="profileImage">
                                <form action="homeUsuario.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="imgAvatar" id="imgAvatar" style="display:none;" onchange="enviarFormulario()">
                                    <button type="button" onclick="subirImagen()" class="edit-avatar" id="editAvatarBtn" name ="editAvatarBtn" >
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="profile-details">
                                <h1 id="profileName"><?php echo $datosUsuario["usuNombre"] ?></h1>
                                <p class="role" id="profileRole"><?php echo $datosUsuario["usuTipoUsuario"] ?></p>
                                <p class="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="profileLocation">Barrio <?php echo $datosUsuario["usuBarrio"] ?></span>
                                </p>
                            </div>
                        </div>
                        <button class="edit-btn" id="editProfileBtn">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="gardenCount"><?php echo $datosUsuario["usuCantidadJardineras"]?></span>
                                <span class="stat-label">
                                    <?php echo ($datosUsuario["usuCantidadJardineras"]==1) ? "Jardinera" : "Jardineras"; ?>
                                </span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="plantCount"><?php echo $cantidadFilas["cantidadAlertas"]?></span>
                                <span class="stat-label">Alertas activas</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="experienceYears"><?php echo $tiempoActividad?></span>
                                <span class="stat-label">Tiempo de Actividad</span>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <h2>Acciones Rápidas</h2>
                        <div class="actions-grid">
                            <a href="homeUsuario.php?page=gardens" class="nav-item action-card" data-action="view-gardens" title="Mis Jardineras" style="border:none">
                                <i class="fas fa-eye"></i>
                                <span>Ver Jardineras</span>
                            </a>
                            <a href="homeUsuario.php?page=add-garden" class="nav-item action-card" data-action="add-garden" title="Agregar Jardinera" style="border:none">
                                <i class="fas fa-plus"></i>
                                <span>Nueva Jardinera</span>
                            </a>
                            <a href="homeUsuario.php?page=request" class="nav-item action-card" data-action="view-request" title="Solicitudes" style="border:none">
                                <i class="fas fa-search"></i>
                                <span>Ver Solicitudes</span>
                            </a>

                            <a href="homeUsuario.php?page=reports" class="nav-item action-card" data-action="view-report" title="Reportes" style="border:none">
                                <i class="fas fa-chart-bar"></i>
                                <span>Generar Reporte</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Garden Page -->
            <div class="page <?php echo ($page == 'add-garden') ? 'active' : ''; ?>" id="add-garden-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-plus-circle"></i> Agregar una Nueva Jardinera</h1>
                        <p>Agregue una nueva jardinera y comience a monitorear sus cultivos</p>
                    </div>

                    <form class="garden-form" id="addGardenForm" action="homeUsuario.php" method="post" autocomplete="on">
                        <div class="form-section">
                            <h3>Información Básica</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="gardenName">Nombre de la Jardinera</label>
                                    <input type="text" id="gardenName" name="gardenName" placeholder="Ej: Mi Primer Jardinera"> 
                                    <p id="errorNombreJardineraAgregarJardinera" class="error-message"></p>
                                </div> 
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-group">
                                <label for="gardenSeed">Selección de Semillas</label>
                                <select name="gardenSeed" id="gardenSeed" class="select">
                                    <option name="opcion" value="0">Seleccione la semilla</option>
                                    <?php
                                        while($datosSemilla=mysqli_fetch_assoc($resultadoConsultaSemillas)){
                                        ?>
                                        <option value="<?php echo $datosSemilla["idSemilla"] ?>"><?php echo $datosSemilla["semNombre"] ?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <p id="errorSemillaAgregarJardinera" class="error-message"></p>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-group">
                                <label for="gardenDescription">Descripción de la jardinera</label>
                                <textarea id="gardenDescription" name="gardenDescription" rows="4" placeholder="Escriba la descripción de su jardinera..."></textarea>
                                <p id="errorDescripcionAgregarJardinera" class="error-message"></p>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" id="cancelAddGarden" data-close-modal>Cancelar</button>
                            <button type="submit" class="btn-primary" id="btn-crearJardinera" name="btn-crearJardinera">Crear Jardinera</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- My Gardens Page -->
            <div class="page <?php echo ($page == 'gardens') ? 'active' : ''; ?>" id="gardens-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Mis Jardineras</h1>
                        <p>Administra tus jardineras y sigue su evolución</p>
                        <a href="homeUsuario.php?page=add-garden" class="btn-primary" id="addNewGardenBtn" style="text-decoration:none;">
                            <i class="fas fa-plus"></i> Nueva Jardinera
                        </a>
                    </div>
                    <?php 
                    if(mysqli_num_rows($resultadoConsultarJardineras)>0){ ?>
                        <div class="gardens-grid">
                        <?php
                            while($datosJardinera=mysqli_fetch_assoc($resultadoConsultarJardineras)){
                                $nombreJardinera=$datosJardinera["jarNombre"];
                                if($datosJardinera["jarEstado"]==="Activa"){
                                    $fase=consultarDatosFase($datosJardinera["idFase"]);
                                    $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]); ?>
                                    <div class="garden-card">
                                        <div class="garden-card-header">
                                            <div>
                                                <h3><?php echo $nombreJardinera?></h3>
                                                <div class="garden-type"><?php echo $fase["faseNombre"] ?></div>
                                            </div>
                                            <button type="button" class="garden-action-btn edit-btn updateGardenBtn" data-id="<?php echo $datosJardinera['idJardinera']; ?>" title="Actualizar jardinera">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                        <div class="garden-description"><?php echo $datosJardinera["jarDescripcion"] ?></div>
                                        <ul class="info-list">
                                            <li><span>Semilla</span><span><?php echo $semilla["semNombre"] ?></span></li>
                                            <li><span>Creación</span><span><?php echo $datosJardinera["jarFechaCreacion"] ?></span></li>
                                        </ul>
                                    </div>
                                    <?php
                                }else{ ?>
                                    <div class="garden-card" style="text-align:center;">
                                        <div class="empty-state-icon" style="display:flex; justify-content:center; margin: 0 auto; margin-bottom:20px">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <h3>La jardinera "<?php echo $nombreJardinera ?>" no se encuentra disponible</h3>
                                        <p>Fue desactivada por un administrador.</p>
                                        <p>Si necesita usarla, solicite su activación</p>
                                    </div>
                                    <?php
                                }  
                            } ?>
                        </div>
                        <?php
                    } else {
                        echo ' <div class="empty-state full-width">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3>No hay jardineras registradas</h3>
                            <p>Cuando registre una jardinera, aparecerá aquí para que pueda administrarla.</p>
                        </div>';
                    }
                    ?>
                </div>
            </div>

            <!-- My External Factors Page -->
            <div class="page <?php echo ($page == 'externalFactors') ? 'active' : ''; ?>" id="externalFactors-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Factores Externos</h1>
                        <p>Consulte los registros ambientales de sus jardineras.</p>
                    </div>
                    <div class="gardens-grid">
                        <?php
                        $registroFactores=1;
                        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);
                        if(mysqli_num_rows($resultadoConsultarJardineras)>0){
                            while($datosJardinera=mysqli_fetch_assoc($resultadoConsultarJardineras)){
                                $nombreJardinera=$datosJardinera["jarNombre"];

                                if($datosJardinera["jarEstado"]==="Activa"){
                                    $resultadoConsultarFEPorJardinera=consultarFactoresExternosPorJardinera($datosJardinera['idJardinera']);
                                    
                                    if(mysqli_num_rows($resultadoConsultarFEPorJardinera)>0){
                                        $fase=consultarDatosFase($datosJardinera["idFase"]);
                                        $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]); ?>
                                        <div class="garden-card">
                                            <div class="garden-card-header">
                                                <div>
                                                    <h3><?php echo $datosJardinera["jarNombre"] ?></h3>
                                                    <div class="garden-type"><?php echo $fase["faseNombre"] ?> • <?php echo $semilla["semNombre"] ?></div>
                                                </div>
                                            </div>
                                            <p class="garden-description">Factores externos Registrados</p>
                                            <div class="external-factors-history">
                                                <?php 
                                                while($datosFactoresExternos=mysqli_fetch_assoc($resultadoConsultarFEPorJardinera)){
                                                    $numeroRegistro = $registroFactores++;
                                                    $resultadoConsultarTipoClima=consultarTipoClima($datosFactoresExternos["idTipoClima"]);

                                                    if(mysqli_num_rows($resultadoConsultarTipoClima)>0){
                                                        $datosTipoClima=arregloDatos($resultadoConsultarTipoClima);
                                                        $datosFactoresExternos["idTipoClima"]=$datosTipoClima["tipoClimaDescripcion"];
                                                    }
                                                ?>
                                                    <div class="external-factor-card">
                                                        <div class="external-factor-header">
                                                            <i class="fas fa-cloud-sun"></i>
                                                            <span>Registro Nº <?php echo $numeroRegistro?></span>
                                                        </div>
                                                        <ul class="info-list">
                                                            <li>
                                                                <span class="info-label">Humedad</span>
                                                                <span class="info-value"><?php echo $datosFactoresExternos["factHumedad"] ?>%</span>
                                                            </li>

                                                            <li>
                                                                <span class="info-label">Clima</span>
                                                                <span class="info-value"><?php echo $datosFactoresExternos["idTipoClima"] ?></span>
                                                            </li>

                                                            <li>
                                                                <span class="info-label">Temperatura</span>
                                                                <span class="info-value"><?php echo $datosFactoresExternos["factTemperatura"] ?>°C</span>
                                                            </li>

                                                            <li>
                                                                <span class="info-label">Agua</span>
                                                                <span class="info-value"><?php echo $datosFactoresExternos["factCantidadAgua"] ?> ml</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="garden-buttons">
                                                <button class="btn-primary addNewExternalFactorsBtn"
                                                    data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                                    <i class="fas fa-plus"></i> Agregar
                                                </button>

                                                <button class="btn-primary generateNewExternalFactorsBtn"
                                                    data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                                    <i class="fas fa-plus"></i> Generar
                                                </button>
                                            </div>
                                        </div>
                                        <?php
                                    }else{ ?>
                                        <div class="empty-state full-width">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h3>No hay factores externos registrados para la jardinera "<?php echo $nombreJardinera; ?>"</h3>
                                            <p>Cuando registre un factor externo, aparecerá aquí para que pueda visualizarlo.</p>

                                            <table>
                                                <th>
                                                    <button class="btn-primary addNewExternalFactorsBtn btn-nohay"
                                                        data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                                        <i class="fas fa-plus"></i> Agregar
                                                    </button>
                                                </th>
                                                <th></th>
                                                <th>
                                                    <button class="btn-primary generateNewExternalFactorsBtn btn-nohay"
                                                        data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                                        <i class="fas fa-plus"></i> Generar
                                                    </button>
                                                </th>
                                            </table>  
                                        </div>
                                        <?php
                                    }                 
                                }else{ ?>
                                    <div class="empty-state full-width">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <h3>La jardinera "<?php echo $nombreJardinera ?>" no se encuentra disponible</h3>
                                        <p>Fue desactivada por un administrador.</p>
                                        <p>Si necesita usarla, solicite su activación</p>
                                    </div>
                                    <?php
                                }    
                            } 
                        }else{
                            echo '<div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3>No hay jardineras disponibles para mostrar sus registros de factores externos</h3>
                                <p>Cuando registre una jardinera, tendrá la opción para monitorear el crecimiento de la misma.</p>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- My Garden Evolution Page -->
            <div class="page <?php echo ($page == 'gardenEvolution') ? 'active' : ''; ?>" id="gardenEvolution-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Evolución Jardineras</h1>
                        <p>Revise el progreso visual y las notas de cada jardinera.</p>
                    </div>
                    <div class="gardens-grid">
                        <?php
                        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);
                        if(mysqli_num_rows($resultadoConsultarJardineras)>0){

                            while($datosJardinera=mysqli_fetch_assoc($resultadoConsultarJardineras)){
                                $nombreJardinera=$datosJardinera["jarNombre"];

                                if($datosJardinera["jarEstado"]==="Activa"){
                                    $resultadoConsultarEvolucionPorJardinera=consultarEvolucionPorJardinera($datosJardinera['idJardinera']);

                                    if(mysqli_num_rows($resultadoConsultarEvolucionPorJardinera)>0){
                                        $fase=consultarDatosFase($datosJardinera["idFase"]);
                                        $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]);

                                        $registro = 1; ?>
                                        <div class="garden-card">
                                            <div class="garden-card-header">
                                                <div>
                                                    <h3><?php echo $datosJardinera["jarNombre"] ?></h3>
                                                    <div class="garden-type"><?php echo $fase["faseNombre"] ?> • <?php echo $semilla["semNombre"] ?></div>
                                                </div>
                                            </div>
                                            <p class="garden-description">Evoluciones registradas</p>
                                                <div class="evolution-history">
                                                    <?php while($datosEvolucion=mysqli_fetch_assoc($resultadoConsultarEvolucionPorJardinera)){
                                                        $registroText = $registro++;
                                                    ?>
                                                        <div class="evolution-card">
                                                            <div class="evolution-card-header">
                                                                <i class="fas fa-seedling"></i>
                                                                <span>Registro Nº <?php echo $registroText; ?></span>
                                                            </div>

                                                            <ul class="info-list">

                                                                <li>
                                                                    <span class="info-label">Fecha</span>
                                                                    <span class="info-value"><?php echo $datosEvolucion["segJardineraFecha"] ?></span>
                                                                </li>

                                                                <li>
                                                                    <span class="info-label">Evolución</span>
                                                                    <span class="info-value">
                                                                        <?php echo $datosEvolucion["segJardineraPorcentaje"] ?>%
                                                                    </span>
                                                                </li>

                                                            </ul>

                                                            <div class="evolution-note-card">
                                                                <i class="fas fa-book"></i><strong>Nota</strong>
                                                                <p><?php 
                                                                echo ($datosEvolucion["segJardineraNota"] !="") ? $datosEvolucion["segJardineraNota"]  : "No se registró ninguna nota";
                                                                ?></p>
                                                            </div>

                                                            <?php if(!empty($datosEvolucion['segJardineraImagen'])): ?>

                                                                <div class="evolution-image-container">
                                                                    <img class="evolution-image"
                                                                        src="<?php echo $datosEvolucion['segJardineraImagen'] ?>"
                                                                        alt="Imagen de evolución">
                                                                </div>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php } ?>
                                                    </div>
                                            <div class="garden-buttons">
                                                <button class="btn-primary addNewGardenEvolutionBtn" data-id="<?php echo $datosJardinera['idJardinera']; ?>" data-fase="<?php echo $datosJardinera['idFase']; ?>" style="margin-top:10px">
                                                    <i class="fas fa-plus"></i> Agregar
                                                </button>
                                            </div>
                                        </div>
                                        <?php
                                    }else{ ?>
                                        <div class="empty-state full-width">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h3>No hay evoluciones registradas para la jardinera "<?php echo $nombreJardinera; ?>"</h3>
                                            <p>Cuando registre un formulario de evolución, aparecerá aquí para que pueda visualizarlo.</p>
                                            
                                            <button class="btn-primary addNewGardenEvolutionBtn btn-nohay" data-id="<?php echo $datosJardinera['idJardinera']; ?>" data-fase="<?php echo $datosJardinera['idFase']; ?>">
                                                <i class="fas fa-plus"></i> Agregar
                                            </button>
                                        </div>
                                        <?php    
                                    }
                                }else{ ?>
                                    <div class="empty-state full-width">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <h3>La jardinera "<?php echo $nombreJardinera ?>" no se encuentra disponible</h3>
                                        <p>Fue desactivada por un administrador.</p>
                                        <p>Si necesita usarla, solicite su activación</p>
                                    </div>
                                    <?php
                                }     
                            }
                        }else{
                            echo '<div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3>No hay jardineras disponibles para mostrar los registros de su evolución</h3>
                                <p>Cuando registre una jardinera, tendrá la opción para monitorear la evolución de la misma.</p>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Monitoring Page -->
            <div class="page <?php echo ($page == 'monitoring') ? 'active' : ''; ?>" id="monitoring-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-chart-line"></i> Monitoreo</h1>
                        <p>Supervise el estado de salud de sus jardineras</p>
                    </div>
                    <?php 
                        //Consulta de las jardineras activas del usuario
                        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);
                        $i=1;
                        if(mysqli_num_rows($resultadoConsultarJardineras)>0){
                            while($datosJardinera=mysqli_fetch_assoc($resultadoConsultarJardineras)){
                                $nombreJardinera=$datosJardinera["jarNombre"];
                                
                                if($datosJardinera["jarEstado"]==="Activa"){
                                    $idJardinera=$datosJardinera["idJardinera"];
                                    $semilla = consultarDatosSemilla($datosJardinera["idSemilla"]);
                                    $fase = consultarDatosFase($datosJardinera["idFase"]);
                                    $diasCrecimiento = calcularDiasEntreFechas($datosJardinera["jarFechaCreacion"], date("Y-m-d"));

                                    $resultadoConsultarEvolucionJardinera=consultarEvolucionPorJardinera($idJardinera);

                                    if(mysqli_num_rows($resultadoConsultarEvolucionJardinera)>0){
                                        $fechas = [];
                                        $porcentajes=[];

                                        while($datosEvolucionJardinera=mysqli_fetch_assoc($resultadoConsultarEvolucionJardinera)){
                                            $fechas[] = $datosEvolucionJardinera['segJardineraFecha'];
                                            $porcentajes[] = $datosEvolucionJardinera['segJardineraPorcentaje'];
                                        }

                                        $resultadoConsultarFactoresExternos=consultarFactoresExternosPorJardinera($idJardinera);

                                        $temp = [];
                                        $hum = [];
                                        $agua = [];

                                        while($datosFactoresExternos= mysqli_fetch_assoc($resultadoConsultarFactoresExternos)){
                                            $temp[] = $datosFactoresExternos['factTemperatura'];
                                            $hum[] = $datosFactoresExternos['factHumedad'];
                                            $agua[] = $datosFactoresExternos['factCantidadAgua'];
                                        }

                                        $promedioTemperatura= calcularPromedio($temp);
                                        $promedioHumedad = calcularPromedio($hum);
                                        $promedioAgua= calcularPromedio($agua);

                                        $indiceSalud = ($promedioTemperatura * 0.4) + ($promedioHumedad * 0.4) + ($promedioAgua * 0.2);

                                        $tendencia = calcularTendencia($porcentajes);
                                        ?>
                                        <div class="monitoring-garden-card">
                                            <div class="monitoring-header">
                                                <div>
                                                    <h2>Jardinera: </h2>"<?php echo $nombreJardinera; ?>"
                                                </div>

                                                <div class="monitoring-health">
                                                    <span>Salud</span>
                                                    <strong><?php echo round($indiceSalud,1); ?>%</strong>
                                                </div>
                                            </div>

                                            <div class="monitoring-grid">
                                                <div class="monitoring-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-chart-line"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Porcentaje de Evolución</h3>
                                                        <p><?php echo $datosJardinera["jarPorcentajeEvolucion"]; ?>%</p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-temperature-high"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Promedio Temperatura</h3>
                                                        <p><?php echo $promedioTemperatura; ?>°C</p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-droplet"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Promedio Humedad</h3>
                                                        <p><?php echo $promedioHumedad; ?>%</p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-cloud-rain"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Promedio Agua</h3>
                                                        <p><?php echo $promedioAgua; ?> ml</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="monitoring-summary-grid">
                                                <div class="monitoring-card small-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-seedling"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Semilla</h3>
                                                        <p><?php echo $semilla["semNombre"] ?? 'No disponible'; ?></p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card small-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-leaf"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Fase actual</h3>
                                                        <p><?php echo $fase["faseNombre"] ?? 'No disponible'; ?></p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card small-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-calendar-days"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Días de crecimiento</h3>
                                                        <p><?php echo $diasCrecimiento; ?></p>
                                                    </div>
                                                </div>

                                                <div class="monitoring-card small-card">
                                                    <div class="monitoring-icon">
                                                        <i class="fas fa-heart-pulse"></i>
                                                    </div>

                                                    <div class="monitoring-info">
                                                        <h3>Salud de la jardinera</h3>
                                                        <p><?php echo round($indiceSalud, 1); ?>%</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="monitoring-charts">
                                                <div class="section">
                                                    <h2>Factores Externos</h2>
                                                    <canvas id="factoresChart<?php echo $i; ?>"></canvas>
                                                </div>

                                                <div class="section">
                                                    <h2>Tendencia de Crecimiento</h2>
                                                    <canvas id="tendenciaChart<?php echo $i; ?>"></canvas>
                                                </div>
                                            </div>

                                            <div class="section">
                                                <h2>Alertas y Recomendaciones</h2>
                                                <?php
                                                    //Consultar las alertas activas de la jardinera
                                                    $resultadoConsultarAlertas=consultarAlertas($usuarioActivo);
                                                        
                                                    //Si existe algun registro de alerta activa
                                                    if(mysqli_num_rows($resultadoConsultarAlertas)>0){
                                                        echo "<div class='monitoring-alerts'>";
                                                        while($datosAlertas=mysqli_fetch_assoc($resultadoConsultarAlertas)){
                                                            echo "<div class='alert-item'>";
                                                            echo "<div class='alert-date'>" . date("d/m/Y", strtotime($datosAlertas["alerFecha"])) . "</div>";
                                                            echo "<div class='alert-message'>" . htmlspecialchars($datosAlertas["alerDescripcion"], ENT_QUOTES, 'UTF-8') . "</div>";
                                                            echo "</div>";
                                                        }
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="empty-state full-width">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-inbox"></i>
                                                            </div>
                                                            <h3>El usuario no tiene alertas activas de ninguna de sus jardineras</h3>
                                                            <p>Cuando el sistema genere una nueva alerta aparecerá aquí automáticamente.</p>
                                                        </div>';
                                                    }
                                                ?>
                                            </div>

                                            <script>
                                                window.jardineras = window.jardineras || [];

                                                window.jardineras.push({
                                                    id: <?php echo $i; ?>,
                                                    fechas: <?php echo json_encode($fechas); ?>,
                                                    crecimiento: <?php echo json_encode($porcentajes); ?>,
                                                    temperatura: <?php echo json_encode($temp); ?>,
                                                    humedad: <?php echo json_encode($hum); ?>,
                                                    agua: <?php echo json_encode($agua); ?>,
                                                    tendencia: <?php echo json_encode($tendencia); ?>
                                                });
                                            </script>
                                        </div>
                                        <?php
                                        $i++;
                                    }else{
                                    ?>
                                        <div class="empty-state full-width">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h3>No hay monitoreos registrados para la jardinera "<?php echo $nombreJardinera; ?>"</h3>
                                            <p>
                                                No hay seguimientos o monitoreos registrados en el sistema, lo invitamos a registrar uno nuevo y visualizar su crecimiento gráficamente.
                                            </p>
                                        </div> 
                                    <?php }    
                                }else{ ?>
                                    <div class="empty-state full-width">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <h3>La jardinera "<?php echo $nombreJardinera ?>" no se encuentra disponible</h3>
                                        <p>Fue desactivada por un administrador.</p>
                                        <p>Si necesita usarla, solicite su activación</p>
                                    </div>
                                    <?php
                                }      
                            }
                        }else{
                            ?>
                            <div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3>No hay jardineras disponibles</h3>
                                <p>
                                    No existen jardineras registradas en el sistema, lo invitamos a registrar una nueva y visualizar su crecimiento gráficamente.
                                </p>
                            </div>
                        <?php }     
                    ?>
                </div>
            </div>

            <!-- Reports Page -->
            <div class="page <?php echo ($page == 'reports') ? 'active' : ''; ?>" id="reports-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-file-alt"></i> Reportes</h1>
                        <button class="btn-primary" id="generateReportBtn">
                            <i class="fas fa-plus"></i> Generar Reporte
                        </button>
                    </div>

                    <div class="reports-filters">
                        <div class="filter-group">
                            <label for="reportType">Tipo de Reporte</label>
                            <select id="reportType" class="report-item">
                                <option value="monthly">Mensual</option>
                                <option value="weekly">Semanal</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="reportGarden">Jardinera</label>

                            <select id="reportGarden" class="report-item">
                                <option value="all">Todas las jardineras</option>

                                <?php
                                $seenGardens = [];

                                if (!empty($jardinerasReporte) && is_array($jardinerasReporte)) {

                                    foreach ($jardinerasReporte as $gardenOption) {

                                        $gardenId = $gardenOption['idJardinera'] ?? '';
                                        $gardenName = $gardenOption['jarNombre'] ?? 'Jardinera';

                                        if ($gardenId === '' || in_array($gardenId, $seenGardens, true)) {
                                            continue;
                                        }

                                        $seenGardens[] = $gardenId;

                                        echo "<option value='{$gardenId}'>{$gardenName}</option>";
                                    }
                                }else{
                                    echo '<div class="empty-state full-width">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <h3>No hay jardineras disponibles para generar algún reporte</h3>
                                        <p>Cuando registre una jardinera, tendrá la opción para generar un reporte de la misma.</p>
                                    </div>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="filter-group" id="reportCustomRange" style="display:none;">
                            <label>Rango personalizado</label>
                            <div class="custom-range-inputs">
                                <input type="date" id="reportStartDate" class="report-item" />
                                <input type="date" id="reportEndDate" class="report-item" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Request Page -->
            <div class="page <?php echo ($page == 'request') ? 'active' : ''; ?>" id="request-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Mis Solicitudes</h1>
                        <button class="btn-primary" id="sendRequestBtn">
                            <i class="fas fa-plus"></i> Enviar Solicitud
                        </button>
                    </div>
                    <div class="request-list">
                    <?php
                        if(mysqli_num_rows($resultadoConsultarSolicitudes) > 0) {
                            while($datosSolicitud=mysqli_fetch_assoc($resultadoConsultarSolicitudes)){
                                $estadoClass = strtolower(str_replace(' ', '-', trim($datosSolicitud["soliEstado"])));
                                $descripcion = htmlspecialchars($datosSolicitud["soliDescripcion"], ENT_QUOTES, 'UTF-8');
                                echo "<div class='request-card'>";
                                echo "<div class='request-card-header'>";
                                echo "<div><h3>" . htmlspecialchars($datosSolicitud["soliAsunto"], ENT_QUOTES, 'UTF-8') . "</h3>";
                                echo "<span class='request-meta'>" . htmlspecialchars($datosSolicitud["soliFecha"], ENT_QUOTES, 'UTF-8') . "</span></div>";
                                echo "<span class='request-status " . $estadoClass . "'>" . htmlspecialchars($datosSolicitud["soliEstado"], ENT_QUOTES, 'UTF-8') . "</span>";
                                echo "</div>";
                                echo "<p class='request-description'>" . $descripcion . "</p>";

                                if($datosSolicitud["soliAsunto"] == "Admisión Nueva Semilla"){
                                    echo "<div class='request-extra'><strong>Semilla:</strong> " . htmlspecialchars($datosSolicitud["soliSemilla"], ENT_QUOTES, 'UTF-8') . "</div>";
                                }

                                echo "</div>";
                            }
                        } else {
                            echo '<div class="empty-state full-width">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3>No hay solicitudes registradas</h3>
                                <p>Cuando registre una nueva solicitud aparecerá aquí automáticamente.</p>
                            </div>';
                        }
                    ?>
                    </div>

                    <div class="request-list">
                        <div class="page-header">
                            <h1><i class="fas fa-history"></i> Mi Historial de Solicitudes</h1>
                        </div>
                        <?php
                            if(mysqli_num_rows($resultadoConsultarHistorialSolicitudes)> 0) { 
                                while($datosSolicitud=mysqli_fetch_assoc($resultadoConsultarHistorialSolicitudes)){ 
                                    $descripcion = htmlspecialchars($datosSolicitud["soliDescripcion"], ENT_QUOTES, 'UTF-8');
                                    echo "<div class='request-card'>";
                                    echo "<div class='request-card-header'>";
                                    echo "<div><h3>" . htmlspecialchars($datosSolicitud["soliAsunto"], ENT_QUOTES, 'UTF-8') . "</h3>";
                                    echo "<span class='request-meta'>" . htmlspecialchars($datosSolicitud["soliFecha"], ENT_QUOTES, 'UTF-8') . "</span></div>";
                                    if($datosSolicitud["soliEstado"]==="Rechazada"){ 
                                        echo "<span class='request-status rejected'>" . htmlspecialchars($datosSolicitud["soliEstado"], ENT_QUOTES, 'UTF-8') . "</span>";
                                    }else if($datosSolicitud["soliEstado"]==="Confirmada"){ 
                                        echo "<span class='request-status confirmed'>" . htmlspecialchars($datosSolicitud["soliEstado"], ENT_QUOTES, 'UTF-8') . "</span>";
                                    }
                                    echo "</div>";
                                    echo "<p class='request-description'>" . $descripcion . "</p>";

                                    if($datosSolicitud["soliAsunto"] == "Admisión Nueva Semilla"){
                                        echo "<div class='request-extra'><strong>Semilla:</strong> " . htmlspecialchars($datosSolicitud["soliSemilla"], ENT_QUOTES, 'UTF-8') . "</div>";
                                    }
                                    echo "</div>";
                                }
                            } else {
                                echo '<div class="empty-state full-width">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h3>No existen registros en el historial de solicitudes</h3>
                                    <p>Cuando registre una nueva solicitud y sea revisada por un administrador aparecerá aquí automáticamente.</p>
                                </div>';
                            }
                        ?>
                    </div>
                </div> 
            </div>
        </main>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <!-- Modals -->
    <div class="modal" id="editProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Perfil</h3>
                <button class="modal-close" id="closeEditProfile">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editProfileForm" action="procesadorActualizarDatos.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="editName">Nombre Completo</label>
                    <input type="text" id="editName" name="name" placeholder="<?php echo $datosUsuario["usuNombre"] ; ?>" autocomplete="name">
                </div>
                <p id="errorNombreCompletoActualizarPerfil" class="error-message"></p>

                <div class="form-group">
                    <label for="editTypeIdProfile">Tipo de Documento</label>
                    <select name="editTypeIdProfile" id="editTypeIdProfile"  class="select">
                        <option name="opcion" value ="<?php echo $datosUsuario["idTipoDocumento"] ?>">Seleccionar opción</option>
                        <?php 
                            while($datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <p id="errorTipoDocumentoActualizarPerfil" class="error-message"></p>

                <div class="form-group">
                    <label for="editEmail">Correo Electrónico</label>
                    <input type="text" id="editEmail" name="email" placeholder="<?php echo $datosUsuario["usuCorreo"] ?>" autocomplete="email">
                </div>
                <p id="errorCorreoActualizarPerfil" class="error-message"></p>

                <div class="form-group">
                    <label for="editLocation">Barrio o Localidad</label>
                    <input type="text" id="editLocation" name="location" placeholder="<?php echo $datosUsuario["usuBarrio"] ?>" autocomplete="location">
                </div>
                <p id="errorBarrioActualizarPerfil" class="error-message"></p>

                <div class="form-group">
                    <label for="editPassword">Contraseña</label>
                    <input type="password" id="editPassword" name="password" autocomplete="password">
                </div>
                <p id="errorContrasenaActualizarPerfil" class="error-message"></p>

                <div class="form-group">
                    <label for="confirmarContrasenaActualizarPerfilUsuario">Confirmar Contraseña</label>
                    <input type="password"
                        name="confirmarContrasena"
                        id="confirmarContrasenaActualizarPerfilUsuario"
                        autocomplete="new-password">
                </div>
                <p id="errorConfirmarContrasenaActualizarPerfilUsuario" class="error-message"></p>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelEditProfile">Cancelar</button>
                    <button type="submit" class="btn-primary" name="guardarCambiosBtn" id="guardarCambiosBtn">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Garden Modal -->
    <div class="modal" id="updateGardenModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Actualizar Jardinera</h3>
                <button class="modal-close" id="closeUpdateGarden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateGardenForm" action="homeUsuario.php" method="POST" autocomplete="on">
                <div class="form-section">
                    <h4>Información Básica</h4>
                    <input type="hidden" id="updateGardenId" name="gardenId">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="updateGardenName">Nombre de la Jardinera</label>
                            <input type="text" id="updateGardenName" name="updateGardenName" placeholder="Ej: Mi Primer Jardinera">
                        </div>
                        <p id="errorNombreJardineraActualizarJardinera" class="error-message"></p>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Descripción</h3>
                    <div class="form-group">
                        <label for="updateGardenDescription">Descripción de la jardinera</label>
                        <textarea id="updateGardenDescription" name="updateGardenDescription" rows="4" placeholder="Escriba la descripción de su jardinera..."></textarea>
                    </div>
                    <p id="errorDescripcionActualizarJardinera" class="error-message"></p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateGarden" data-close-modal>Cancelar</button>
                    <button type="submit" class="btn-primary" name="actualizarJardineraBtn" id="actualizarJardineraBtn" data-modal="updateGardenModal">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Send Request Modal -->
    <div class="modal" id="sendRequestModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Enviar Solicitud</h3>
                <button class="modal-close" id="closeSendRequest">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="sendRequestForm" action="homeUsuario.php" method="POST" autocomplete="on">
                <div class="form-group">
                    <label for="editTypeId">Tipo de Solicitud</label>
                    <select id="typeRequest" name="typeRequest" class="select">
                        <option name="opcion" value="">Seleccionar opción</option>
                        <option value="Admisión Nueva Semilla">Admisión Nueva Semilla</option>
                        <option value="Soporte Técnico">Soporte Técnico</option>
                        <option value="Reportar Problema">Reportar Problema</option>
                        <option value="Actualización Estado de la Jardinera">Actualización Estado de la Jardinera</option>
                        <option value="Peticiones Administrativas">Peticiones Administrativas</option>
                        <option value="Sugerencias">Sugerencias</option>
                    </select>
                    <p id="errorTipoSolicitudEnviarSolicitud" class="error-message"></p>
                </div>

                <div class="form-group" id="newSeedField" style="display:none;">
                    <label for="newSeed">Nombre de la nueva semilla</label>
                    <input type="text" id="newSeed" name="newSeed" placeholder="Ej: Mango">
                    <p id="errorNuevaSemillaEnviarSolicitud" class="error-message"></p>
                </div>
                
                            
                <div class="form-group">
                    <label for="message">Mensaje</label>
                    <textarea class="form-control border-0" placeholder="Escriba su solicitud aquí..." id="message"  name="message" style="height: 100px"></textarea>
                    <p id="errorDescripcionEnviarSolicitud" class="error-message"></p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelSendRequest">Cancelar</button>
                    <button type="submit" class="btn-primary" name="enviarSolicitudBtn" id="enviarSolicitudBtn" data-modal="sendRequestModal">Enviar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add External Factors Modal -->
    <div class="modal" id="addExternalFactorsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Factores Externos</h3>
                <button class="modal-close" id="closeAddExternalFactors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addExternalFactorsForm" action="homeUsuario.php" method="POST" autocomplete="on">
                <input type="hidden" id="gardenSelectedId" name="gardenSelectedId">
                <div class="form-section">
                    <h4>Información Básica</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="humidity">Húmedad</label>
                            <input type="number" id="humidity" name="humidity" placeholder="Ej: 60">
                        </div>
                    </div>
                </div>
                <p id="errorHumedadAgregarFactor" class="error-message"></p>

                <div class="form-section">
                    <div class="form-group">
                        <label for="amountWater">Cantidad Agua</label>
                        <input type="number" id="amountWater" name="amountWater" placeholder="Ej: 100">
                    </div>
                </div>
                <p id="errorCantidadAguaAgregarFactor" class="error-message"></p>

                <div class="form-section">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="temperature">Temperatura</label>
                            <input type="number" id="temperature" name="temperature" placeholder="Ej: 25">
                        </div>
                    </div>
                    <p id="errorTemperaturaAgregarFactor" class="error-message"></p>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label for="weather">Clima</label>
                        <select name="weather" id="weather"  class="select">
                            <option name="opcion" value="0">Seleccionar opción</option>
                            <?php 
                                while($datosTiposClima=mysqli_fetch_assoc($resultadoConsultarTiposClima)){//Bucle para recorrer todos los tipos de climas registrados
                            ?>
                                <option value="<?php echo $datosTiposClima["idTipoClima"]?>"><?php echo $datosTiposClima["tipoClimaDescripcion"]?></option> 
                            <?php 
                                } 
                                ?>
                        </select>
                    </div>
                    <p id="errorClimaAgregarFactor" class="error-message"></p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddExternalFactors">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarFactoresExternosBtn" id="agregarFactoresExternosBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Generate External Factors Modal -->
    <div class="modal" id="generateExternalFactorsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Generar Factores Externos</h3>
                <button class="modal-close" id="closeGenerateExternalFactors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="generateExternalFactorsForm" action="homeUsuario.php" method="POST" autocomplete="on">

                <input type="hidden" id="gardenIdGenerateExternalFactor" name="gardenIdGenerateExternalFactor">

                <?php
                    $apiKey = "47cbf9d7f344d3a63c9da5d4db33df11";
                    $ciudad = "Bogota";

                    $url = "https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey&units=metric&lang=es";

                    $respuesta = file_get_contents($url);
                    $data = json_decode($respuesta, true);

                    if ($data["cod"] == 200) {

                        $temperatura = $data["main"]["temp"];
                        $humedad = $data["main"]["humidity"];

                        if(isset($data["rain"]["1h"])){
                            $cantidadAgua = $data["rain"]["1h"];
                        }elseif(isset($data["rain"]["3h"])){
                            $cantidadAgua = $data["rain"]["3h"];
                        }else{
                            $cantidadAgua = 0;
                        }

                        $tipoClima = obtenerTipoClima($temperatura,$humedad);
                ?>

                <div class="generatedFactors">

                    <div class="generatedFactors-header">
                        <div class="generatedFactors-icon">
                            <i class="fas fa-cloud-sun"></i>
                        </div>

                        <div>
                            <h4>Factores obtenidos automáticamente</h4>
                            <p>Estos datos fueron consultados desde OpenWeather.</p>
                        </div>
                    </div>

                    <div class="generatedFactors-grid">

                        <div class="generatedFactor-card">
                            <i class="fas fa-temperature-high temperature"></i>

                            <span>Temperatura</span>

                            <strong><?php echo $temperatura; ?> °C</strong>
                        </div>

                        <div class="generatedFactor-card">
                            <i class="fas fa-droplet humidity"></i>

                            <span>Humedad</span>

                            <strong><?php echo $humedad; ?> %</strong>
                        </div>

                        <div class="generatedFactor-card">
                            <i class="fas fa-cloud-rain water"></i>

                            <span>Agua</span>

                            <strong><?php echo $cantidadAgua; ?> ml</strong>
                        </div>

                        <div class="generatedFactor-card">
                            <i class="fas fa-cloud weather"></i>

                            <span>Tipo de clima</span>

                            <strong><?php echo $tipoClima; ?></strong>
                        </div>

                    </div>

                </div>

                <?php
                    }else{
                ?>

                    <div class="generatedFactors-error">
                        <i class="fas fa-circle-exclamation"></i>
                        No fue posible obtener la información climática.
                    </div>

                <?php } ?>

                <input type="hidden" id="humidityGenerated" name="humidityGenerated" value="<?php echo $humedad ?>">
                <input type="hidden" id="temperatureGenerated" name="temperatureGenerated" value="<?php echo $temperatura ?>">
                <input type="hidden" id="amountWaterGenerated" name="amountWaterGenerated" value="<?php echo $cantidadAgua ?>">
                <input type="hidden" id="typeWeatherGenerated" name="typeWeatherGenerated" value="<?php echo $tipoClima ?>">

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelGenerateExternalFactors">
                        Cancelar
                    </button>

                    <button type="submit" class="btn-primary" name="generarFactoresExternosBtn" id="generarFactoresExternosBtn">
                        <i class="fas fa-plus"></i>
                        Generar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Add Garden Evolution Modal -->
    <div class="modal" id="addGardenEvolutionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Evolución Jardinera</h3>
                <button class="modal-close" id="closeGardenEvolution">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addGardenEvolutionForm" action="homeUsuario.php" method="POST" enctype="multipart/form-data" autocomplete="on">
                <input type="hidden" id="gardenEvolutionId" name="gardenEvolutionId">
                <input type="hidden" id="faseEvolutionId" name="faseEvolutionId">

                <div class="form-section">
                    <div class="section-title">Preguntas de Seguimiento</div>
                    <div class="vf-header">
                        <span></span>
                        <span class="vf-true">Sí</span>
                        <span class="vf-false">No</span>
                    </div>

                    <div id="contenedorPreguntas"></div>
                </div>
                <p id="errorPreguntasAgregarEvolucion" class="error-message"></p>

                <div class="form-section">
                    <div class="section-title">Agregar imágenes</div>
                    
                    <input 
                        type="file" 
                        id="imagenesEvolucion" 
                        name="imagenEvolucion" 
                        accept="image/*" 
                    >
                </div>
                <div class="form-section">
                    <div class="section-title">Nota</div>
                    <textarea 
                        id="notaEvolucion" 
                        name="notaEvolucion" 
                        rows="4" 
                        placeholder="Escriba una observación sobre la evolución de su jardinera..."
                    ></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddGardenEvolution">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarEvolucionBtn" id="agregarEvolucionBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.gardenReportData = <?php echo json_encode($jardinerasReporte, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;
        window.alertas = <?php echo json_encode($alertas ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;
        window.userReportData = {
            nombre: <?php echo json_encode($datosUsuario['usuNombre'] ?? '', JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>,
            documento: <?php echo json_encode($datosUsuario['usuNumeroDocumento'] ?? '', JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>,
            correo: <?php echo json_encode($datosUsuario['usuCorreo'] ?? '', JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>,
            barrio: <?php echo json_encode($datosUsuario['usuBarrio'] ?? '', JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>,
            alertasActivas: <?php echo json_encode((int)($cantidadFilas['cantidadAlertas'] ?? 0), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>
        };
    </script>
    <?php 
    //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){ 
            switch ($_SESSION["alerta"]){
                case "errorAlSubirImagen": ?>
                    <script>
                        //Mensaje cuando el avatar o imagen del usuario no se pudo subir 
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",
                                                            
                            //Si el usuario acepta volver a subir su imagen
                            rutaTrue:"homeUsuario.php?page=profile",

                            //Si el usuario no acepta volver a subir su imagen
                            rutaFalse:"homeUsuario.php?page=profile"
                        })
                    </script>
                    <?php
                break;
                case "registroFallidoJardinera": ?>
                    <script>
                        //Mensaje cuando se produce un error a la hora de agregar una jardinera
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado!",
                            text:"Ha ocurrido un error al momento de agregar su jardinera",
                            icon:"error", //Agregar iconos de acuerdo al mensaje (warning, info, question, error)
                            footer:"Le solicitamos volver a intentarlo más tarde",
                                 
                            //Si el usuario acepta ingresar otra vez su jardinera
                            rutaTrue:"homeUsuario.php?page=add-garden",

                            //Si el usuario no acepta ingresar otra vez su jardinera
                            rutaFalse:"homeUsuario.php?page=profile"
                        });
                    </script>
                    <?php
                break;
                case 'errorConsulta': ?>
                    <script>
                        //Mensaje cuando surge un error en alguna consulta
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a agregar una jardinera
                            rutaTrue:"homeUsuario.php?page=add-garden",

                            //Si el usuario no acepta volver a agregar una jardinera
                            rutaFalse:"homeUsuario.php?page=profile"
                        })
                    </script>
                    <?php
                break;
                case 'errorEnviarSolicitud': ?>
                    <script>
                        //Mensaje cuando surge un error al enviar la solicitud
                        mostrarMensaje({
                            title:"¡Ha ocurrido un error inesperado al enviar su solicitud!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a agregar una jardinera
                            rutaTrue:"homeUsuario.php?page=request",

                            //Si el usuario no acepta volver a agregar una jardinera
                            rutaFalse:"homeUsuario.php?page=request"
                        })
                    </script>
                    <?php
                break;
                case 'semillaExistente': ?>
                    <script>
                        //Mensaje cuando surge el usuario solicita una semilla que ya existe
                        mostrarMensaje({
                            title:"¡Semilla ya registrada!",
                            text:"La semilla que intenta solicitar ya se encuentra en nuestro sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar una nueva solicitud
                            rutaTrue:"homeUsuario.php?page=request",

                            //Si el usuario no acepta volver a enviar una nueva solicitud
                            rutaFalse:"homeUsuario.php?page=request"
                        })
                    </script>
                    <?php
                break;
                case 'jardineraActualizada': ?>
                    <script>
                        //Mensaje cuando la jardinera se actualiza exitosamente
                        mostrarMensaje({
                            title:"¡Jardinera Actualizada!",
                            text:"Su jardinera ha sido actualizada exitosamente",
                            icon:"success",

                            showCancelButton: false, 

                            //Si el usuario acepta el mensaje emergente
                            rutaTrue:"homeUsuario.php?page=gardens",

                            //Si el usuario no acepta el mensaje emergente
                            rutaFalse:"homeUsuario.php?page=gardens"
                        })
                    </script>
                    <?php
                break;

                case 'errorAlActualizarJardinera': ?>
                    <script>
                        //Mensaje cuando surge un error cuando se actualiza la informacion de una jardinera
                        mostrarMensaje({
                            title:"¡Error a la hora de actualizar su jardinera!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar la informacion de la jardinera
                            rutaTrue:"homeUsuario.php?page=gardens",

                            //Si el usuario no acepta volver a enviar la informacion de la jardinera
                            rutaFalse:"homeUsuario.php?page=gardens"
                        })
                    </script>
                    <?php
                break;

                case 'factorExternoRegistrado': ?>
                    <script>
                        //Mensaje cuando se registra exitosamente los factores externos de una jardinera
                        mostrarMensaje({
                            title:"¡Formulario de seguimiento registrado exitosamente!",
                            text:"Gracias por brindarnos la información de su jardinera, estaremos trabajando para su correcto crecimiento",
                            icon:"success",

                            showCancelButton: false, 

                            //Si el usuario acepta regresara la pagina de factores externos
                            rutaTrue:"homeUsuario.php?page=externalFactors",

                            //Si el usuario no acepta regresar a la pagina de factores externos
                            rutaFalse:"homeUsuario.php?page=externalFactors"
                        })
                    </script>
                    <?php
                break;

                case 'errorAlRegistrarFactor': ?>
                    <script>
                        //Mensaje cuando surge un error a la hora de registrar un factor externo
                        mostrarMensaje({
                            title:"¡Error a la hora de registrar el formulario de seguimiento!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar la informacion del formulario
                            rutaTrue:"homeUsuario.php?page=externalFactors",

                            //Si el usuario no acepta volver a enviar la informacion del formulario
                            rutaFalse:"homeUsuario.php?page=externalFactors"
                        })
                    </script>
                    <?php
                break;

                case 'evolucionRegistrada': ?>
                    <script>
                        //Mensaje cuando se registra exitosamente la evolucion de una jardinera
                        mostrarMensaje({
                            title:"¡Evolución registrada exitosamente!",
                           text: "Gracias por compartir la evolución de su jardinera. Seguiremos trabajando para mejorar su experiencia en nuestro sistema.",
                            icon:"success",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar la informacion del formulario
                            rutaTrue:"homeUsuario.php?page=gardenEvolution",

                            //Si el usuario no acepta volver a enviar la informacion del formulario
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

                case 'errorAlRegistrarEvolucion': ?>
                    <script>
                        //Mensaje cuando surge un error a la hora de registrar la evolucion de la jardinera
                        mostrarMensaje({
                            title:"¡Error a la hora de registrar la evolución de la jardinera!",
                            text:"Por favor, vuelva a intentarlo más tarde o comuníquese con un administrador del sistema",
                            icon:"error",

                            showCancelButton: false, 

                            //Si el usuario acepta volver a enviar la informacion del formulario
                            rutaTrue:"homeUsuario.php?page=gardenEvolution",

                            //Si el usuario no acepta volver a enviar la informacion del formulario
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

                case 'errorSubidaImagen': ?>
                    <script>
                        //Mensaje cuando surge un error a la hora de subir la imagen del seguimiento de la evolucion de la jardinera
                        mostrarMensaje({
                            title:"¡Error al subir la imagen!",
                            text:"No se pudo guardar la imagen correctamente. Inténtelo nuevamente.",
                            icon:"error",

                            showCancelButton: false,

                            rutaTrue:"homeUsuario.php?page=gardenEvolution",
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

                case 'formatoImagenInvalido': ?>
                    <script>
                        //Mensaje cuando el formato de la imagen que se intenta subir no es permitido
                        mostrarMensaje({
                            title:"Formato de imagen inválido",
                            text:"La imagen debe ser JPG, PNG o WEBP y no superar los 2MB.",
                            icon:"warning",

                            showCancelButton: false,

                            rutaTrue:"homeUsuario.php?page=gardenEvolution",
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

                case 'jardineraEvolucionada': ?>
                    <script>
                        //Mensaje cuando la jardinera cambia de fase en el sistema
                        mostrarMensaje({
                            title:"¡Jardinera actualizada!",
                            text:"La evolución de su jardinera se registró con éxito y ha avanzado a la siguiente fase.",
                            icon:"success",

                            showCancelButton: false, 

                            // Redirección después del mensaje
                            rutaTrue:"homeUsuario.php?page=gardenEvolution",
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

                case 'jardineraNoEvolucionada': ?>
                    <script>
                       mostrarMensaje({
                            title:"Seguimiento registrado",
                            text:"La evolución de su jardinera fue registrada correctamente, pero aún no cumple con los criterios necesarios para avanzar de fase.",
                            icon:"info",

                            showCancelButton: false, 

                            // Redirección
                            rutaTrue:"homeUsuario.php?page=gardenEvolution",
                            rutaFalse:"homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break;

               case 'tiempoInsuficienteEvolucion': ?>
                    <script>
                    mostrarMensaje({
                            title: "Seguimiento registrado",
                            text: "La evolución de su jardinera se registró correctamente, pero aún no han pasado los días mínimos necesarios para avanzar de fase.",
                            icon: "info",

                            showCancelButton: false, 

                            // Redirección
                            rutaTrue: "homeUsuario.php?page=gardenEvolution",
                            rutaFalse: "homeUsuario.php?page=gardenEvolution"
                        })
                    </script>
                    <?php
                break; 

                case "errorAlEnviarCorreoSolicitud": ?>
                    <script>
                        mostrarMensaje({
                            title:"¡Error a la hora de enviar el correo electrónico!",
                            text:"Recargue la página y vuelva a intentarlo",
                            icon:"error",

                            rutaTrue:"homeUsuario.php?page=request",

                            rutaFalse:"homeUsuario.php?page=request"
                        });
                    </script>
                    <?php
                break;

                case "errorAlEnviarCorreoAlerta": ?>
                    <script>
                        mostrarMensaje({
                            title:"¡Error a la hora de enviar el correo electrónico!",
                            text:"Recargue la página y vuelva a intentarlo",
                            icon:"error",

                            rutaTrue:"homeUsuario.php?page=profile",

                            rutaFalse:"homeUsuario.php?page=profile"
                        });
                    </script>
                    <?php
                break;   
            }
            unset($_SESSION["alerta"]);
        }
    ?>
    <script src="../js/scripts_HomeUsuario.js"></script>
    <script src="../js/script_ValidacionFormularios.js" defer></script>
</body>
</html>
