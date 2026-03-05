<?php 
    session_start();
    $usuarioActivo=$_SESSION["numeroDocumento"];
    $_SESSION["alerta"]="";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil-Usuario</title>
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
        //Incluir la conexión a la base de datos
        include("../db/conexion.php");
        //Incluir las funciones de la app
        include("../functions/funciones.php");

        //Consultar los datos del usuario que inicio sesión
        $datosUsuario=consultarDatosUsuario($usuarioActivo, $conexion_db);

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
                <a href="" class="nav-item action-card" data-action="view-profile" title="Perfil" data-action="add-garden" style="border:none">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>
                <a href="#" class="nav-item action-card" data-action="view-gardens" title="Mis Jardines" style="border:none">
                    <i class="fas fa-leaf"></i>
                    <span>Mis Jardines</span>
                </a>
                <!---Agregar dentro de mis jardines, form para factores externos y seguimiento jardinera (fase, 
                pregunta, opcion)-->
                <a href="#" class="nav-item action-card" data-action="add-garden" title="Agregar Jardín" style="border:none">
                    <i class="fas fa-plus-circle"></i>
                    <span>Agregar Jardín</span>
                </a>
                <a href="#" class="nav-item action-card" data-action="view-monitoring" title="Monitoreo" style="border:none">
                    <i class="fas fa-chart-line"></i>
                    <span>Monitoreo</span>
                </a>
                <a href="#" class="nav-item action-card" data-action="view-report" title="Reportes" style="border:none">
                    <i class="fas fa-file-alt"></i>
                    <span>Reportes</span>
                </a>
                <a href="#" class="nav-item action-card" data-action="Logout" title="Cerrar Sesión" style="border:none">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Profile Page -->
            <div class="page active" id="profile-page">
                <div class="content-wrapper">
                    <div class="profile-header">
                        <div class="profile-info">
                            <div class="profile-avatar">
                                <img src="../images/1-intro-photo-final.jpg?height=80&width=80" alt="Avatar del usuario" id="profileImage">
                                <button class="edit-avatar" id="editAvatarBtn">
                                    <i class="fas fa-camera"></i>
                                </button>
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
                                <span class="stat-label">Jardines</span>
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
                            <button class="action-card" data-action="view-gardens">
                                <i class="fas fa-eye"></i>
                                <span>Ver Jardines</span>
                            </button>
                            <button class="action-card" data-action="add-garden">
                                <i class="fas fa-plus"></i>
                                <span>Nuevo Jardín</span>
                            </button>
                            <button class="action-card" data-action="update-garden">
                                <i class="fas fa-edit"></i>
                                <span>Actualizar Jardín</span>
                            </button>
                            <button class="action-card" data-action="generate-report">
                                <i class="fas fa-chart-bar"></i>
                                <span>Generar Reporte</span>
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

            <!-- Add Garden Page -->
            <div class="page" id="add-garden-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-plus-circle"></i> Agregar Nuevo Jardín</h1>
                        <p>Crea un nuevo jardín y comienza a monitorear tus plantas</p>
                    </div>

                    <form class="garden-form" id="addGardenForm">
                        <div class="form-section">
                            <h3>Información Básica</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="gardenName">Nombre del Jardín</label>
                                    <input type="text" id="gardenName" name="gardenName" required>
                                </div>
                                <div class="form-group">
                                    <label for="gardenType">Tipo de Jardín</label>
                                    <select id="gardenType" name="gardenType" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="vegetable">Hortalizas</option>
                                        <option value="herbs">Hierbas Medicinales</option>
                                        <option value="flowers">Flores</option>
                                        <option value="mixed">Mixto</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gardenSize">Tamaño (m²)</label>
                                    <input type="number" id="gardenSize" name="gardenSize" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="gardenLocation">Ubicación</label>
                                    <input type="text" id="gardenLocation" name="gardenLocation" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Descripción</h3>
                            <div class="form-group">
                                <label for="gardenDescription">Descripción del jardín</label>
                                <textarea id="gardenDescription" name="gardenDescription" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Selección de Semillas</h3>
                            <div class="seeds-grid" id="seedsGrid">
                                <!-- Seeds will be populated by JavaScript -->
                            </div>
                            <div class="selected-seeds" id="selectedSeeds">
                                <h4>Semillas seleccionadas:</h4>
                                <div class="selected-seeds-list" id="selectedSeedsList">
                                    <p class="no-seeds-selected">No has seleccionado ninguna semilla</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" id="cancelAddGarden">Cancelar</button>
                            <button type="submit" class="btn-primary">Crear Jardín</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- My Gardens Page -->
            <div class="page" id="gardens-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-leaf"></i> Mis Jardines</h1>
                        <button class="btn-primary" id="addNewGardenBtn">
                            <i class="fas fa-plus"></i> Nuevo Jardín
                        </button>
                    </div>

                    <div class="gardens-grid" id="gardensGrid">
                        <!-- Gardens will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Monitoring Page -->
            <div class="page" id="monitoring-page">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h1><i class="fas fa-chart-line"></i> Monitoreo</h1>
                        <p>Supervisa el estado de salud de tus jardines</p>
                    </div>

                    <div class="monitoring-grid">
                        <div class="monitoring-card">
                            <h3>Estado General</h3>
                            <div class="status-indicator good">
                                <i class="fas fa-check-circle"></i>
                                <span>Excelente</span>
                            </div>
                            <p>Todos los jardines están en buen estado</p>
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
            <div class="page" id="reports-page">
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
                            <label for="reportGarden">Jardín</label>
                            <select id="reportGarden" class="report-item">
                                <option value="all">Todos los jardines</option>
                                <option value="garden1">Jardín Principal</option>
                                <option value="garden2">Jardín de Hierbas</option>
                            </select>
                        </div>
                    </div>

                    <div class="reports-list" id="reportsList">
                        <!-- Reports will be populated by JavaScript -->
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
                    <select name="tipoDocumento" id="editTypeId" name="typeId" class="select">
                        <option name="opcion">Seleccionar opción</option>
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
                <h3>Actualizar Jardín</h3>
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
                            <label for="updateGardenName">Nombre del Jardín</label>
                            <input type="text" id="updateGardenName" name="gardenName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateGardenType">Tipo de Jardín</label>
                            <select id="updateGardenType" name="gardenType" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="vegetable">Hortalizas</option>
                                <option value="herbs">Hierbas Medicinales</option>
                                <option value="flowers">Flores</option>
                                <option value="mixed">Mixto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="updateGardenSize">Tamaño (m²)</label>
                            <input type="number" id="updateGardenSize" name="gardenSize" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="updateGardenLocation">Ubicación</label>
                            <input type="text" id="updateGardenLocation" name="gardenLocation" required>
                        </div>
                        <div class="form-group">
                            <label for="updateGardenStatus">Estado</label>
                            <select id="updateGardenStatus" name="gardenStatus" required>
                                <option value="active">Activo</option>
                                <option value="maintenance">Mantenimiento</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelUpdateGarden">Cancelar</button>
                    <button type="submit" class="btn-primary">Actualizar Jardín</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/scripts_HomeUsuario.js"></script>
</body>
</html>
