<?php 
    session_start();
    $usuarioActivo=$_SESSION["numeroDocumento"];
    $_SESSION["alerta"]="";

    $page = isset($_GET['page']) ? $_GET['page'] : 'profile';
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
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        //Incluir las funciones de la app
        include("../functions/funciones.php");

        //Abrir la conexion a la base de datos a través de una función
        $conexion_db=abrirConexionDB();

        //Consultar los datos del usuario que inicio sesión
        $datosUsuario=consultarDatosUsuario($usuarioActivo);

        //Consultar todos los tipos de clima registrados
        $resultadoConsultarTiposClima=consultarTiposClima();

        //Consultar todos los tipos de documentos
        $resultadoConsultarTiposDocumento=consultarTiposDocumentos();

        //Consultar los datos de todas las semillas
        $resultadoConsultaSemillas=consultarSemillas();

        //Consultar las solicitudes asociadas a un usuario en estado pendiente
        $resultadoConsultarSolicitudes=consultarSolicitudes($usuarioActivo);

        //Consultar la cantidad de alertas activas asociadas a un usuario
        $resultadoConsultarAlertaActivas=consultarCantidadAlertasUsuario($usuarioActivo);

        //Recuperar el resultado del count de la consulta 
        $cantidadFilas=mysqli_fetch_assoc($resultadoConsultarAlertaActivas);

        //Consultar el tiempo de actividad del usuario
        $tiempoActividad=calcularActividadUsuario($datosUsuario["usuFechaIngreso"]);

        //Funcion para consultar las jardineras asociadas a un usuario
        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);


        //Si esta inicializada la variable global imgAvatar
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

                    $resultadoAgregarImagen = agregarImagenPerfil($usuarioActivo, $rutaImagen);

                    if($resultadoAgregarImagen == true){ ?>
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

        //Si la variable global btn-crearJardinera esta inicializada, se incluye el procesador para agregar una nueva jardinera
        if(isset($_POST["btn-crearJardinera"])){
            include ("procesadorAgregarJardinera.php");
        }

        //Si la variable global enviarSolicitudBtn esta inicializada, se incluye el procesador para agregar una nueva solicitud
        if(isset($_POST["enviarSolicitudBtn"])){
            include("procesadorEnviarSolicitud.php");
        }

        //Si la variable global actualizarJardineraBtn esta inicializada, se incluye el procesador para actualizar los datos de una jardinera
        if(isset($_POST["actualizarJardineraBtn"])){
            //Recuperar el id de la jardinera a actualizar desde el formulario
            $idJardinera=$_POST["gardenId"];
        
            //Recuperar los datos ingresados por el usuario desde el formulario
            $nombreJardinera=$_POST["updateGardenName"];
            $descripcionJardinera=$_POST["updateGardenDescription"];

            //Consultar los datos actuales de la jardinera a actualizar
            $datosJardinera=consultarDatosJardineraPorId($idJardinera);

            //Actualizar el nombre de la jardinera
            if($nombreJardinera!=""){
                $_SESSION["nuevoNombreJardinera"]=$nombreJardinera;
            }else{
                $_SESSION["nuevoNombreJardinera"]=$datosJardinera["jarNombre"];
            }

            //Actualizar la descripcion de la jardinera
            if($descripcionJardinera!=""){
                $_SESSION["nuevaDescripcionJardinera"]=$descripcionJardinera;
            }else{
                $_SESSION["nuevaDescripcionJardinera"]=$datosJardinera["jarDescripcion"];
            }

            //Actualizar los valores de la jardinera con sus nuevos valores almacenados en las sesiones
            $nombreJardinera=$_SESSION["nuevoNombreJardinera"];
            $descripcionJardinera=$_SESSION["nuevaDescripcionJardinera"];

            //Ejecutar la función para actualizar los datos de la jardinera y mostrar un mensaje de acuerdo al resultado de la consulta
            $resultadoActualizarJardinera=actualizarJardinera($idJardinera, $nombreJardinera, $descripcionJardinera);
            if($resultadoActualizarJardinera==true){
                $_SESSION["alerta"]="jardineraActualizada";
            }else{
                $_SESSION["alerta"]="errorAlActualizarJardinera";
            }
        }

        //Si la variable global agregarFactoresExternosBtn esta inicializada
        if(isset($_POST["agregarFactoresExternosBtn"])){
            //Recuperar el id de la jardinera a actualizar desde el formulario
            $idJardinera=$_POST["gardenSelectedId"];

            //Recuperar los valores del formulario
            $humedad=$_POST["humidity"];
            $cantidadAgua=$_POST["amountWater"];
            $temperatura=$_POST["temperature"];
            $clima=$_POST["weather"];

            //Ejecutar la funcion para insertar el registro en la base de datos
            $resultadoAgregarFactoresExternos=agregarFactoresExternos($idJardinera, $humedad, $cantidadAgua, $temperatura, $clima);

            //Si el registro es exitoso
            if($resultadoAgregarFactoresExternos==true){
                $_SESSION["alerta"]="factorExternoRegistrado";

            //Si el registro no es exitoso
            }else{
                $_SESSION["alerta"]="errorAlRegistrarFactor";
            }
        }

        //Si la pagina actual es profile, mostrar alertas
        if($page=="profile"){
            //Procesador para evaluar las posibles alertas de todas las jardineras del usuario que ingreso
            include("procesadorAlertas.php");

            //Consultar las alertas asociadas a un usuario
            $resultadoConsultarAlertas=consultarAlertas($usuarioActivo);

            $alertas = [];

            //Mientras el usuario tenga alertas activas
            while($row = mysqli_fetch_array($resultadoConsultarAlertas)){
                //Almacenar el contenido de la alerta en un arreglo unidimensional
                $alertas[] = [
                    "id" => $row["idAlerta"],
                    "html" => "<b>Jardinera: </b> {$row['jarNombre']}<br>
                            <b>Semilla: </b> {$row['semNombre']}<br>
                            <b>Fecha: </b> {$row['alerFecha']}<br>
                            <b>Alerta: </b> {$row['alerDescripcion']}<br>
                            <b>Valor Obtenido: </b> {$row['alerValorRegistrado']}º<br>
                            <b>Rango Recomendado: </b> {$row['alerRangoRecomendado']}"
                ];

                //Enviar correo de notificación al usuario por cada alerta activa
                include("../php/mailNotificacionAlerta.php");
            }
        }

    //Si el usuario oprime el boton agregar evolucion del formulario
    if(isset($_POST["agregarEvolucionBtn"])){
        $fechaActual=recuperarFechaActual();

        //Recuperar los valores del formulario
        $idJardinera = $_POST["gardenEvolutionId"];
        $idFase = $_POST["faseEvolutionId"];
        $notaEvolucion = $_POST["notaEvolucion"];

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
        if($fechaRegistroEvolucion!==false){
            $_SESSION["alerta"] = "evolucionRegistrada";

            //Si el porcentaje del seguimiento es igual a 100
            if($totalPorcentaje == 100){
                //Obtener los datos de la jardinera
                $datosJardinera=consultarDatosJardineraPorId($idJardinera);

                //Obtener los datos de la semilla
                $datosSemilla=consultarDatosSemilla($datosJardinera["idSemilla"]);

                //Obtener los datos de la etapa de crecimiento de la semilla de la jardinera
                $datosEtapaCrecimiento=consultarEtapaCrecimientoSemilla($datosSemilla["idEtapaCrecimiento"]);
                
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
                    $datosFase = consultarDatosFaseJardinera($nuevaFase);
                    $porcentajeFase = $datosFase["fasePorcentaje"];

                    //Actualizar el porcentaje y la fase de la jardinera
                    $resultadoActualizarEvolucion = actualizarEvolucionJardinera(
                        $idJardinera, 
                        $porcentajeFase, 
                        $nuevaFase
                    );

                    //Si el resultado de la actualización es exitoso, mostrar un mensaje de éxito
                    if($resultadoActualizarEvolucion == true){
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
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["idAlerta"])) {

        actualizarEstadoAlerta($_POST["idAlerta"]);

        echo json_encode(["ok" => true]);
        exit();
    }

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

                    window.location.href = "homeUsuario.php?page=profile";

                    return; 
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
                <button class="back-btn" id="backBtn">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="brand">
                    <i class="fas fa-seedling"></i>
                    <span>BioUrbis</span>
                </div>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Buscar jardines, plantas..." class="search-input" id="searchInput">
                    <button class="search-btn" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
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

                <a href="homeUsuario.php?page=gardens" class="nav-item action-card" data-action="view-gardens" title="Mis Jardineras" style="border:none">
                    <i class="fas fa-leaf"></i>
                    <span>Mis Jardineras</span>
                </a>

                <a href="homeUsuario.php?page=add-garden" class="nav-item action-card" data-action="add-garden" title="Agregar Jardinera" style="border:none">
                    <i class="fas fa-plus-circle"></i>
                    <span>Agregar Jardinera</span>
                </a>

                <a href="homeUsuario.php?page=externalFactors" class="nav-item action-card" data-action="add-view--external-factors" title="Registrar Factores Externos" style="border:none">
                    <i class="fas fa-cloud-sun"></i>
                    <span>Registrar Factores Externos</span>
                </a>

                <a href="homeUsuario.php?page=gardenEvolution" class="nav-item action-card" data-action="add-view-garden-evolution" title="Evolución Jardinera" style="border:none">
                   <i class="fas fa-hourglass-half"></i>
                    <span>Evolución Jardinera</span>
                </a>

                <a href="homeUsuario.php?page=monitoring" class="nav-item action-card" data-action="view-monitoring" title="Monitoreo" style="border:none">
                    <i class="fas fa-chart-line"></i>
                    <span>Monitoreo</span>
                </a>

                <a href="homeUsuario.php?page=reports" class="nav-item action-card" data-action="view-report" title="Reportes" style="border:none">
                    <i class="fas fa-file-alt"></i>
                    <span>Reportes</span>
                </a>

                <a href="homeUsuario.php?page=request" class="nav-item action-card" data-action="view-request" title="Solicitudes" style="border:none">
                    <i class="fas fa-inbox"></i>
                    <span>Mis Solicitudes</span>
                </a>

                <a href="homeUsuario.php?page=logout" class="nav-item action-card" data-action="logout" title="Cerrar Sesión" style="border:none">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </a>
            </nav>
        </aside>

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
                                    <?php 
                                    if($datosUsuario["usuCantidadJardineras"]==1){
                                        echo "Jardinera";
                                    }else{
                                        echo "Jardineras";
                                    }
                                    ?>
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

                    <div class="section">
                        <h2>Actividad Reciente</h2>
                        <div class="activity-list" id="activityList">
                            <!-- Activities will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Garden Page -->
            <div class="page <?php echo ($page == 'add-garden') ? 'active' : ''; ?>" id="add-garden-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-plus-circle"></i> Agregar una Nueva Jardinera</h1>
                        <p>Crea una nueva jardinera y comienza a monitorear tus plantas</p>
                    </div>

                    <form class="garden-form" id="addGardenForm" action="homeUsuario.php" method="post" autocomplete="on">
                        <div class="form-section">
                            <h3>Información Básica</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="gardenName">Nombre de la Jardinera</label>
                                    <input type="text" id="gardenName" name="gardenName" autocomplete="gardenName" required> 
                                </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gardenSeed">Selección de Semillas</label>
                            <select name="gardenSeed" id="gardenSeed" class="select" autocomplete="gardenSeed" required>
                                <option name="opcion" value="0">Seleccione la semilla</option>
                                <?php
                                    while($datosSemilla=mysqli_fetch_array($resultadoConsultaSemillas)){
                                    ?>
                                    <option value="<?php echo $datosSemilla["idSemilla"] ?>"><?php echo $datosSemilla["semNombre"] ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-section">
                            <div class="form-group">
                                <label for="gardenDescription">Descripción de la jardinera</label>
                                <textarea id="gardenDescription" name="gardenDescription" rows="4" autocomplete="gardenDescription" required></textarea>
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
                        //Si existen jardineras registradas por parte del usuario
                        if(mysqli_num_rows($resultadoConsultarJardineras)){
                            //Mientras existan jardineras registradas 
                            while($datosJardinera=mysqli_fetch_array($resultadoConsultarJardineras)){
                                //Recuperar la fase y semilla relacionada a la jardinera
                                $fase=consultarDatosFaseJardinera($datosJardinera["idFase"]);
                                $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]);
                                
                                //Mostrar la informacion basica de la jardinera
                                echo "Nombre: ", $datosJardinera["jarNombre"] . "<br>";
                                echo "Descripcion: ", $datosJardinera["jarDescripcion"]  . "<br>";
                                echo "Fecha Creación: ", $datosJardinera["jarFechaCreacion"]  . "<br>";
                                echo "Fase: ", $fase["faseNombre"] . "<br>";
                                echo "Semilla: ", $semilla["semNombre"]  . "<br>"; 
                            
                                ?>       
                                <button type="button" class="btn-primary updateGardenBtn" 
                                data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                    <i class="fas fa-edit"></i> Actualizar
                                </button> <br>
                                
                                <?php  
                            }
                        }else{
                            //Mensaje cuando no jardineras registradas
                            echo "No hay jardineras registradas";
                        }
                        
                    ?>
                    
                </div>
            </div>

            <!-- My External Factors Page -->
            <div class="page <?php echo ($page == 'externalFactors') ? 'active' : ''; ?>" id="externalFactors-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Factores Externos</h1>    
                    </div>
                    <?php
                        //Resultado de la consulta de las jardineras de relacionadas al usuario
                        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);
                        echo "Mis jardineras <br>";
                        $i=1;

                        //Mientras existan jardineras registradas por el usuario
                        while($datosJardinera=mysqli_fetch_array($resultadoConsultarJardineras)){
                            //Recuperar la fase y semilla de la cada jardinera
                            $fase=consultarDatosFaseJardinera($datosJardinera["idFase"]);
                            $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]);
                            
                            //Mostrar la informacion basica de la jardinera
                            echo "Nombre: ", $datosJardinera["jarNombre"] . "<br>";
                            echo "Fase: ", $fase["faseNombre"] . "<br>";
                            echo "Fecha Creación: ", $datosJardinera["jarFechaCreacion"]  . "<br>";

                            echo "<hr>";
                            echo "Factores Externos Registrados <br>";

                            //Consulta de los factores externos registrados por cada jardinera
                            $resultadoConsultarFEPorJardinera=consultarFactoresExternosPorJardinera($datosJardinera['idJardinera']);

                            //Evaluar si hay factores externos registrados para cada jardinera registrada
                            if(mysqli_num_rows($resultadoConsultarFEPorJardinera)){

                                //Mientras existan factores externos registrados
                                while($datosFactoresExternos=mysqli_fetch_array($resultadoConsultarFEPorJardinera)){
                                    
                                    //Consultar el tipo de clima del factor externo y reemplazarlo en el arreglo creado
                                    $resultadoConsultarTipoClima=consultarTipoClima($datosFactoresExternos["idTipoClima"]);
                                    if(mysqli_num_rows($resultadoConsultarTipoClima)){
                                        $datosTipoClima=arregloDatos($resultadoConsultarTipoClima);
                                        $datosFactoresExternos["idTipoClima"]=$datosTipoClima["tipoClimaDescripcion"];
                                    }
                                
                                    //Mostrar la informacion basica del factor externo 
                                    echo "Registro Nº", $i, "<br>";
                                    echo "Humedad: ", $datosFactoresExternos["factHumedad"],"º <br>";
                                    echo "Tipo Clima: ", $datosFactoresExternos["idTipoClima"],"<br>";
                                    echo "Temperatura: ", $datosFactoresExternos["factTemperatura"], "º <br>";
                                    echo "Cantidad Agua: ", $datosFactoresExternos["factCantidadAgua"],"ml";
                                
                                    $i++;
                                }
                            }else{
                                echo "No hay factores externos registrados para esta jardinera";
                            }  
                            ?>
                                <br>
                                <button class="btn-primary addNewExternalFactorsBtn"
                                    data-id="<?php echo $datosJardinera['idJardinera']; ?>">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            <hr>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <!-- My Garden Evolution Page -->
            <div class="page <?php echo ($page == 'gardenEvolution') ? 'active' : ''; ?>" id="gardenEvolution-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Evolución Jardineras</h1>
                    </div>
                    <?php
                        //Consulta de las jardineras activas del usuario
                       $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);
                        echo "Mis jardineras <br>";
                        $i=1;

                        //Mientras existan jardineras registradas por el usuario
                        while($datosJardinera=mysqli_fetch_array($resultadoConsultarJardineras)){

                            //Recuperar la fase y semilla relacionada a la jardinera
                            $fase=consultarDatosFaseJardinera($datosJardinera["idFase"]);
                            $semilla=consultarDatosSemilla($datosJardinera["idSemilla"]);
                            
                            //Mostrar la informacion basica de la jardinera
                            echo "Nombre: ", $datosJardinera["jarNombre"] . "<br>";
                            echo "Fase: ", $fase["faseNombre"] . "<br>";
                            echo "Fecha Creación: ", $datosJardinera["jarFechaCreacion"]  . "<br>";

                            echo "<hr>";
                            echo "Evolucion Jardinera <br>";
                            //Consultar los registros de evoluciones de la jardinera
                            $resultadoConsultarEvolucionPorJardinera=consultarEvolucionPorJardinera($datosJardinera['idJardinera']);

                            //Evaluar si existen registros de evoluciones registradas para cada jardinera del usuario
                            if(mysqli_num_rows($resultadoConsultarEvolucionPorJardinera)){
                                //Mientras existan registros de evoluciones por jardinera
                                while($datosEvolucion=mysqli_fetch_array($resultadoConsultarEvolucionPorJardinera)){

                                    //Mostrar la informacion basica de cada evolucion registrada
                                    echo "Registro Nº", $i, "<br>";
                                    echo "Fecha: ", $datosEvolucion["segJardineraFecha"]," <br>";
                                    echo "Nota: ", $datosEvolucion["segJardineraNota"]," <br>";

                                    //Evaluar si el usuario subio una imagen al registro de la evolucion 
                                    if($datosEvolucion['segJardineraImagen']==""){
                                        echo "Imagen: No hay registro de una imagen <br>";
                                    }else{
                                        echo "Imagen: <img src='" . $datosEvolucion['segJardineraImagen'] . "' width='200'> <br>";
                                    }

                                    echo "Porcentaje Obtenido: ", $datosEvolucion["segJardineraPorcentaje"], "% <br>";
                                    echo "<hr>";
                                    $i++;
                                }
                            }else{
                                echo "No hay registros de evoluciones para esta jardinera";
                            } 
                            ?>
                            <br>
                            <button class="btn-primary addNewGardenEvolutionBtn"
                                data-id="<?php echo $datosJardinera['idJardinera']; ?>"
                                data-fase="<?php echo $datosJardinera['idFase']; ?>">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                            <hr>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <!-- Monitoring Page -->
            <div class="page <?php echo ($page == 'monitoring') ? 'active' : ''; ?>" id="monitoring-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-chart-line"></i> Monitoreo</h1>
                        <p>Supervisa el estado de salud de tus jardineras</p>
                    </div>

                    <div class="monitoring-grid">
                        <div class="monitoring-card">
                            <h3>Estado General</h3>
                            <div class="status-indicator good">
                                <i class="fas fa-check-circle"></i>
                                <span>Excelente</span>
                            </div>
                            <p>Todas las jardineras están en buen estado</p>
                        </div>

                        <div class="monitoring-card">
                            <h3>Riego</h3>
                            <div class="progress-bar">
                                <div class="progress" style="width: 75%"></div>
                            </div>
                            <p>75% de jardines regados hoy</p>
                        </div>

                        <div class="monitoring-card">
                            <h3>Temperatura</h3>
                            <div class="temperature">
                                <i class="fas fa-thermometer-half"></i>
                                <span>24°C</span>
                            </div>
                            <p>Temperatura óptima</p>
                        </div>

                        <div class="monitoring-card">
                            <h3>Humedad</h3>
                            <div class="humidity">
                                <i class="fas fa-tint"></i>
                                <span>65%</span>
                            </div>
                            <p>Nivel adecuado</p>
                        </div>
                    </div>

                    <div class="section">
                        <h2>Alertas y Recomendaciones</h2>
                        <div class="alerts-list" id="alertsList">
                            <!-- Alerts will be populated by JavaScript -->
                        </div>
                    </div>
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
                            <label for="reportGarden">Semilla</label>
                            <select id="reportGarden" class="report-item">
                                <option value="all">Todas las semillas</option>
                                <?php
                                    $seenSemillas = [];
                                    if (!empty($jardinerasReporte) && is_array($jardinerasReporte)) {
                                        foreach ($jardinerasReporte as $gardenOption) {
                                            $seedId = $gardenOption['idSemilla'] ?? '';
                                            $seedName = $gardenOption['semNombre'] ?? 'Semilla';
                                            if ($seedId === '' || in_array($seedId, $seenSemillas, true)) {
                                                continue;
                                            }
                                            $seenSemillas[] = $seedId;
                                            echo "<option value=\"{$seedId}\">{$seedName}</option>";
                                        }
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

                    <div class="reports-list" id="reportsList">
                        <!-- Reports will be populated by JavaScript -->
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
                    <?php
                        while($datosSolicitud=mysqli_fetch_array($resultadoConsultarSolicitudes)){
                            //Evalua si el tipo de solicitud es Adminisión Nueva Semilla para mostrar el nombre de la semilla asociada a la solicitud
                            if($datosSolicitud["soliAsunto"]=="Admisión Nueva Semilla"){

                                echo "Fecha: ", $datosSolicitud["soliFecha"] . "<br>";
                                echo "Asunto: ", $datosSolicitud["soliAsunto"]  . "<br>";
                                echo "Semilla: ", $datosSolicitud["soliSemilla"] . "<br>";
                                echo "Descripción: ", $datosSolicitud["soliDescripcion"]  . "<br>";
                                echo "Estado: ", $datosSolicitud["soliEstado"]  . "<br><br>";
                            }else{

                                //Si no es el tipo, muestra las demas solicitudes
                                echo "Fecha: ", $datosSolicitud["soliFecha"] . "<br>";
                                echo "Asunto: ", $datosSolicitud["soliAsunto"]  . "<br>";
                                echo "Descripción: ", $datosSolicitud["soliDescripcion"]  . "<br>";
                                echo "Estado: ", $datosSolicitud["soliEstado"]  . "<br><br>";
                            }
                        }
                    ?>
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
                <div class="form-group">
                    <label for="editTypeIdProfile">Tipo de Documento</label>
                    <select name="editTypeIdProfile" id="editTypeIdProfile"  class="select" autocomplete="editTypeIdProfile">
                        <option name="opcion" value ="<?php echo $datosUsuario["idTipoDocumento"] ?>">Seleccionar opción</option>
                        <?php 
                            while($datosTipoDocumento=mysqli_fetch_array($resultadoConsultarTiposDocumento)){//Bucle para recorrer todos los tipos de documentos registrados
                        ?>
                            <option value="<?php echo $datosTipoDocumento["idTipoDocumento"]?>"><?php echo $datosTipoDocumento["tipoDocDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editEmail">Correo Electrónico</label>
                    <input type="email" id="editEmail" name="email" placeholder="<?php echo $datosUsuario["usuCorreo"] ?>" autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="editLocation">Barrio o Localidad</label>
                    <input type="text" id="editLocation" name="location" placeholder="<?php echo $datosUsuario["usuBarrio"] ?>" autocomplete="location">
                </div>
                <div class="form-group">
                    <label for="editPassword">Contraseña</label>
                    <input type="password" id="editPassword" name="password" autocomplete="password">
                </div>
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
                            <input type="text" id="updateGardenName" name="updateGardenName" autocomplete="updateGardenName">
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Descripción</h3>
                    <div class="form-group">
                        <label for="updateGardenDescription">Descripción de la jardinera</label>
                        <textarea id="updateGardenDescription" name="updateGardenDescription" rows="4" autocomplete="updateGardenDescription"></textarea>
                    </div>
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
                    <select id="typeRequest" name="typeRequest" class="select" autocomplete="typeRequest" required>
                        <option name="opcion">Seleccionar opción</option>
                        <option value="Admisión Nueva Semilla">Admisión Nueva Semilla</option>
                        <option value="Soporte Técnico">Soporte Técnico</option>
                        <option value="Reportar Problema">Reportar Problema</option>
                        <option value="Actualización Estado de la Jardinera">Actualización Estado de la Jardinera</option>
                        <option value="Peticiones Administrativas">Peticiones Administrativas</option>
                        <option value="Sugerencias">Sugerencias</option>
                    </select>
                </div>

                <div class="form-group" id="newSeedField" style="display:none;">
                    <label for="newSeed">Nombre de la nueva semilla</label>
                    <input type="text" id="newSeed" name="newSeed" autocomplete="newSeed">
                </div>

                <div class="form-group">
                    <label for="message">Mensaje</label>
                    <textarea class="form-control border-0" placeholder="Describa su solicitud aquí" id="message"  name="message" style="height: 100px" autocomplete="message" required></textarea>
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
                            <input type="number" id="humidity" name="humidity" autocomplete="humidity" required>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-group">
                        <label for="amountWater">Cantidad Agua</label>
                        <input type="number" id="amountWater" name="amountWater" autocomplete="amountWater" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="temperature">Temperatura</label>
                        <input type="number" id="temperature" name="temperature" autocomplete="temperature" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="weather">Clima</label>
                    <select name="weather" id="weather"  class="select" autocomplete="weather" required>
                        <option name="opcion">Seleccionar opción</option>
                        <?php 
                            while($datosTiposClima=mysqli_fetch_array($resultadoConsultarTiposClima)){//Bucle para recorrer todos los tipos de climas registrados
                        ?>
                            <option value="<?php echo $datosTiposClima["idTipoClima"]?>"><?php echo $datosTiposClima["tipoClimaDescripcion"]?></option> 
                        <?php 
                            } 
                            ?>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddExternalFactors">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarFactoresExternosBtn" id="agregarFactoresExternosBtn">Agregar</button>
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
                <div class="form-section">
                    <div class="section-title">Agregar imágenes</div>
                    
                    <input 
                        type="file" 
                        id="imagenesEvolucion" 
                        name="imagenEvolucion" 
                        accept="image/*" 
                        autocomplete="imagenEvolucion"
                    >
                </div>
                <div class="form-section">
                    <div class="section-title">Nota</div>
                    <textarea 
                        id="notaEvolucion" 
                        name="notaEvolucion" 
                        rows="4" 
                        placeholder="Escribe una observación sobre la evolución de la jardinera..."
                        autocomplete="notaEvolucion"
                    ></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddGardenEvolution">Cancelar</button>
                    <button type="submit" class="btn-primary" name="agregarEvolucionBtn" id="agregarEvolucionBtn">Agregar</button>
                </div>
            </form>
        </div>
    </div>
    
    
    <script src="../js/scripts_HomeUsuario.js"></script>
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
            }
            unset($_SESSION["alerta"]);
        }
    ?>
</body>
</html>
