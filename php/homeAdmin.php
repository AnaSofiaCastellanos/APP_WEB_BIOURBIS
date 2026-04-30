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

        $conexion_db=abrirConexionDB();

        //Consultar los datos del usuario que inicio sesión
        $datosUsuario=consultarDatosUsuario($usuarioActivo);

        //Consultar la cantidad de alertas activas de una usuario en todas sus jardineras
        $queryConsultarAlertasActivas="SELECT COUNT(idAlerta) AS cantidadAlertas FROM alerta INNER JOIN jardinera 
        ON alerta.idJardinera=jardinera.idJardinera WHERE jardinera.usuNumeroDocumento='$usuarioActivo'
        AND alerta.alerEstado='Activa'";
        $resultadoConsultarAlertaActivas=mysqli_query($conexion_db, $queryConsultarAlertasActivas);

        //Recuperar el resultado del count de la consulta 
        $cantidadFilas=mysqli_fetch_assoc($resultadoConsultarAlertaActivas);

        //Consultar el tiempo de actividad del usuario
        $tiempoActividad=calcularActividadUsuario($datosUsuario["usuFechaIngreso"]);

        //Consultar todos los tipos de documentos
        $queryConsultarTiposDocumento="SELECT * FROM tipo_documento";
        $resultadoConsultarTiposDocumento=mysqli_query($conexion_db, $queryConsultarTiposDocumento);

        if(isset($_FILES["imgAvatar"])){
            $nombre=$_FILES["imgAvatar"]["name"];
            $tmp=$_FILES["imgAvatar"]["tmp_name"];

            $nombreF=time()."_img_".$nombre;
            $rutaImagen="../images/avatares/".$nombreF;

            if(move_uploaded_file($tmp, $rutaImagen)){
                $queryAgregarImagen="UPDATE usuario SET usuImagen='$rutaImagen' WHERE usuNumeroDocumento='$usuarioActivo'";
                $resultadoAgregarImagen=mysqli_query($conexion_db, $queryAgregarImagen);

                if($resultadoAgregarImagen==true){ ?>
                    <meta http-equiv="refresh" content="1">
                <?php   
                }else{
                    $_SESSION["alerta"]="errorAlSubirImagen";
                }
            }

        }

        $resultadoConsultaSemillas=consultarSemillas();

        if(isset($_POST["btn-crearJardinera"])){
            include ("procesadorAgregarJardinera.php");
        }

        $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);

        if(isset($_POST["enviarSolicitudBtn"])){
            include("procesadorEnviarSolicitud.php");
        }

        $resultadoConsultarSolicitudes=consultarSolicitudes($usuarioActivo);
    ?>
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
                <a href="homeUsuario.php?page=profile" class="nav-item action-card" data-action="view-profile" title="Perfil" data-action="add-garden" style="border:none">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>

                <a href="homeUsuario.php?page=users" class="nav-item action-card" data-action="view-users" title="Usuarios" style="border:none">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>

                <a href="homeUsuario.php?page=gardens" class="nav-item action-card" data-action="view-admin-gardens" title="Jardineras" style="border:none">
                    <i class="fas fa-seedling"></i>
                    <span>Jardineras</span>
                </a>

                <a href="homeUsuario.php?page=seeds" class="nav-item action-card" data-action="view-seeds" title="Semillas" style="border:none">
                    <i class="fas fa-leaf"></i>
                    <span>Semillas</span>
                </a>

                <a href="homeUsuario.php?page=add-seed" class="nav-item action-card" data-action="add-seed" title="Agregar Semilla" style="border:none">
                   <i class="fas fa-plus-circle"></i>
                    <span>Agregar Semilla</span>
                </a>

                <a href="homeUsuario.php?page=request" class="nav-item action-card" data-action="view-request" title="Solicitudes" style="border:none">
                    <i class="fas fa-inbox"></i>
                    <span>Solicitudes</span>
                </a>

                <a href="homeUsuario.php?page=logout" class="nav-item action-card" data-action="Logout" title="Cerrar Sesión" style="border:none">
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
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="gardenCount"><?php echo $datosUsuario["usuCantidadJardineras"]?></span>
                                <span class="stat-label">Usuarios Activos</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number" id="plantCount"><?php echo $cantidadFilas["cantidadAlertas"]?></span>
                                <span class="stat-label">Jardineras Existentes</span>
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
                            <button class="action-card" data-action="view-users">
                                <i class="fas fa-eye"></i>
                                <span>Ver Usuarios</span>
                            </button>
                            <button class="action-card" data-action="view-gardens">
                                <i class="fas fa-plus"></i>
                                <span>Ver Jardineras</span>
                            </button>
                            <button class="action-card" data-action="add-seed">
                                <i class="fas fa-edit"></i>
                                <span>Agregar Semilla</span>
                            </button>
                            <button class="action-card" data-action="view-request">
                                <i class="fas fa-chart-bar"></i>
                                <span>Ver Solicitudes</span>
                            </button>
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

            <!-- Users Page -->
            <div class="page <?php echo ($page == 'users') ? 'active' : ''; ?>" id="view-users-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-plus-circle"></i> Usuarios</h1>
                        <p></p>
                    </div>
                    

                
                </div>
                <?php
                    echo "Ver info, solicitudes, reseñas, cantidad de jardineras, crud, alterar tabla tipo de doc";
                    ?>
            </div>

            <!-- Gardens Page -->
            <div class="page <?php echo ($page == 'admin-gardens') ? 'active' : ''; ?>" id="gardens-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i>Jardineras</h1>
                        
                    </div>
                    <?php
                    echo "Alertas, evolucion, factores externos, seguimiento (pregunta, opcion) fase";
                    ?>
                    <div class="gardens-grid" id="gardensGrid">
                        
                        
                    </div>
                </div>
            </div>

            <!-- Seeds Page -->
            <div class="page <?php echo ($page == 'seeds') ? 'active' : ''; ?>" id="seeds-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i>Semillas</h1>
                        <button class="btn-primary" id="sendRequestBtn">
                            <i class="fas fa-plus"></i> Enviar Solicitud
                        </button>
                    </div>
                    <?php
                        echo "Ficha, tablas, etapa crecimiento";
                    ?>
                    <div class="gardens-grid" id="gardensGrid">
      
                    </div>
                </div>
            </div>

            <!-- Add Seed Page -->
            <div class="page <?php echo ($page == 'add-seed') ? 'active' : ''; ?>" id="add-seed-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Agregar Semilla</h1>
                    </div>
                    <?php
                        echo "Agregar Semilla";
                    ?>
                    <div class="gardens-grid" id="gardensGrid">
      
                    </div>
                </div>
            </div>

            <!-- My Request Page -->
            <div class="page <?php echo ($page == 'request') ? 'active' : ''; ?>" id="request-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Mis Solicitudes</h1>
                    </div>
                    <?php
                        echo "Solicitudes";
                    ?>
                    <div class="gardens-grid" id="gardensGrid">
      
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
            <form id="editProfileForm" action="procesadorActualizarDatos.php" method="POST">
                <div class="form-group">
                    <label for="editName">Nombre Completo</label>
                    <input type="text" id="editName" name="name" placeholder="<?php echo $datosUsuario["usuNombre"] ?>">
                </div>
                <div class="form-group">
                    <label for="editTypeId">Tipo de Documento</label>
                    <select id="editTypeId" name="typeId" class="select">
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
                    <input type="email" id="editEmail" name="email" placeholder="<?php echo $datosUsuario["usuCorreo"] ?>">
                </div>
                <div class="form-group">
                    <label for="editLocation">Barrio o Localidad</label>
                    <input type="text" id="editLocation" name="location" placeholder="<?php echo $datosUsuario["usuBarrio"] ?>">
                </div>
                <div class="form-group">
                    <label for="editPassword">Contraseña</label>
                    <input type="password" id="editPassword" name="password">
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
            <form id="updateGardenForm">
                <input type="hidden" id="updateGardenId" name="gardenId">
                <div class="form-section">
                    <h4>Información Básica</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="updateGardenName">Nombre de la Jardinera</label>
                            <input type="text" id="updateGardenName" name="gardenName" required>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Descripción</h3>
                    <div class="form-group">
                        <label for="gardenDescription">Descripción de la jardinera</label>
                        <textarea id="gardenDescription" name="gardenDescription" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateGarden">Cancelar</button>
                    <button type="submit" class="btn-primary">Actualizar Jardinera</button>
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
            <form id="sendRequestForm" action="homeUsuario.php" method="POST">
                <div class="form-group">
                    <label for="editTypeId">Tipo de Solicitud</label>
                    <select id="typeRequest" name="typeRequest" class="select">
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
                    <input type="text" id="newSeed" name="newSeed">
                </div>

                <div class="form-group">
                    <label for="message">Mensaje</label>
                    <textarea class="form-control border-0" placeholder="Describa su solicitud aquí" id="message"  name="message" style="height: 100px" required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelSendRequest">Cancelar</button>
                    <button type="submit" class="btn-primary" name="enviarSolicitudBtn" id="enviarSolicitudBtn">Enviar</button>
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
            }
            unset($_SESSION["alerta"]);
        }
    ?>
</body>
</html>
