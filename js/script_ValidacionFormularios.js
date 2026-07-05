let soloTexto = /^[a-zA-ZÁÉÍÓÚáéíóúñÑ0-9\s.,;:¿?¡!()-]+$/;
let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/;
let expresionDescripcion = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

const elementsAdminForm={
    //Formulario para actualizar el perfil del administrador
        formularioActualizarPerfilAdmin:document.getElementById("editAdminProfileForm"),
        nombreCompletoActualizarPerfilAdmin:document.getElementById("editNameAdmin"),
        tipoDocumentoActualizarPerfilAdmin:document.getElementById("editTypeIdProfileAdmin"),
        correoActualizarPerfilAdmin:document.getElementById("editEmailAdmin"),
        barrioActualizarPerfilAdmin:document.getElementById("editLocationAdmin"),
        contrasenaActualizarPerfilAdmin:document.getElementById("editPasswordAdmin"),
        confirmarContrasenaActualizarPerfilAdmin:document.getElementById("confirmarContrasenaActualizarPerfilAdmin"),

        errorNombreCompletoActualizarPerfilAdmin:document.getElementById("errorNombreCompletoActualizarPerfilAdmin"),
        errorTipoDocumentoActualizarPerfilAdmin:document.getElementById("errorTipoDocumentoActualizarPerfilAdmin"),
        errorCorreoActualizarPerfilAdmin:document.getElementById("errorCorreoActualizarPerfilAdmin"),
        errorBarrioActualizarPerfilAdmin:document.getElementById("errorBarrioActualizarPerfilAdmin"),
        errorContrasenaActualizarPerfilAdmin:document.getElementById("errorContrasenaActualizarPerfilAdmin"),
        errorConfirmarContrasenaActualizarPerfilAdmin:document.getElementById("errorConfirmarContrasenaActualizarPerfilAdmin"),
    //
    
    // Formulario para actualizar perfil usuario
        formularioActualizarPerfilUsuario: document.getElementById("updateUserProfileForm"),

        // Campos
        idActualizarPerfilUsuario: document.getElementById("updateUserProfileId"),
        nombreCompletoActualizarPerfilUsuario: document.getElementById("updateName"),
        tipoUsuarioActualizarPerfilUsuario: document.getElementById("updateTypeUser"),
        tipoDocumentoActualizarPerfilUsuario: document.getElementById("updateTypeId"),
        correoActualizarPerfilUsuario: document.getElementById("updateEmail"),
        estadoCorreoActualizarPerfilUsuario: document.getElementById("updateEmailStatus"),
        barrioActualizarPerfilUsuario: document.getElementById("updateLocation"),
        avatarActualizarPerfilUsuario: document.getElementById("updateAvatar"),
        cantidadJardinerasActualizarPerfilUsuario: document.getElementById("updateGardensAmount"),

        // Errores
        errorNombreCompletoActualizarPerfilUsuario: document.getElementById("errorNombreCompletoActualizarPerfilUsuario"),
        errorTipoUsuarioActualizarPerfilUsuario: document.getElementById("errorTipoUsuarioActualizarPerfilUsuario"),
        errorTipoDocumentoActualizarPerfilUsuario: document.getElementById("errorTipoDocumentoActualizarPerfilUsuario"),
        errorCorreoActualizarPerfilUsuario: document.getElementById("errorCorreoActualizarPerfilUsuario"),
        errorEstadoCorreoActualizarPerfilUsuario: document.getElementById("errorEstadoCorreoActualizarPerfilUsuario"),
        errorBarrioActualizarPerfilUsuario: document.getElementById("errorBarrioActualizarPerfilUsuario"),
        errorAvatarActualizarPerfilUsuario: document.getElementById("errorAvatarActualizarPerfilUsuario"),
        errorCantidadJardinerasActualizarPerfilUsuario: document.getElementById("errorCantidadJardinerasActualizarPerfilUsuario"),
    //
    
    // Formulario para agregar tipo de documento
        formularioAgregarTipoDocumento: document.getElementById("addTypeDocumentForm"),

        // Campos
        descripcionAgregarTipoDocumento: document.getElementById("addTypeDocumentDescription"),

        // Errores
        errorDescripcionAgregarTipoDocumento: document.getElementById("errorDescripcionAgregarTipoDocumento"),
    //
    
    // Formulario para actualizar tipo de documento
        formularioActualizarTipoDocumento: document.getElementById("updateTypeDocumentForm"),

        // Campos
        idActualizarTipoDocumento: document.getElementById("updateTypeDocumentId"),
        descripcionActualizarTipoDocumento: document.getElementById("updateTypeDocumentDescription"),

        // Errores
        errorDescripcionActualizarTipoDocumento: document.getElementById("errorDescripcionActualizarTipoDocumento"),
    //
    
    // Formulario para agregar semilla
        formularioAgregarSemilla: document.getElementById("addSeedForm"),

        // Campos
        nombreAgregarSemilla: document.getElementById("addSeedName"),
        imagenAgregarSemilla: document.getElementById("addSeedImage"),
        observacionesAgregarSemilla: document.getElementById("addSeedObservations"),
        tipoSemillaAgregarSemilla: document.getElementById("addSeedType"),

        // Errores
        errorNombreAgregarSemilla: document.getElementById("errorNombreAgregarSemilla"),
        errorImagenAgregarSemilla: document.getElementById("errorImagenAgregarSemilla"),
        errorObservacionesAgregarSemilla: document.getElementById("errorObservacionesAgregarSemilla"),
        errorTipoSemillaAgregarSemilla: document.getElementById("errorTipoSemillaAgregarSemilla"),
    //
    
    // Formulario para actualizar semilla
        formularioActualizarSemilla: document.getElementById("updateSeedForm"),

        // Campos
        idActualizarSemilla: document.getElementById("updateSeedId"),
        nombreActualizarSemilla: document.getElementById("updateSeedName"),
        imagenActualizarSemilla: document.getElementById("updateSeedImage"),
        observacionesActualizarSemilla: document.getElementById("updateSeedObservations"),
        tipoSemillaActualizarSemilla: document.getElementById("updateSeedType"),

        // Errores
        errorNombreActualizarSemilla: document.getElementById("errorNombreActualizarSemilla"),
        errorImagenActualizarSemilla: document.getElementById("errorImagenActualizarSemilla"),
        errorObservacionesActualizarSemilla: document.getElementById("errorObservacionesActualizarSemilla"),
        errorTipoSemillaActualizarSemilla: document.getElementById("errorTipoSemillaActualizarSemilla"),
    //
    
    // Formulario para agregar tipo de semilla
        formularioAgregarTipoSemilla: document.getElementById("addTypeSeedForm"),

        // Campos
        descripcionAgregarTipoSemilla: document.getElementById("addTypeSeedDescription"),

        // Errores
        errorDescripcionAgregarTipoSemilla: document.getElementById("errorDescripcionAgregarTipoSemilla"),
    //
    
    // Formulario para actualizar tipo de semilla
        formularioActualizarTipoSemilla: document.getElementById("updateTypeSeedForm"),

        // Campos
        idActualizarTipoSemilla: document.getElementById("updateTypeSeedId"),
        descripcionActualizarTipoSemilla: document.getElementById("updateTypeSeedDescription"),

        // Errores
        errorDescripcionActualizarTipoSemilla: document.getElementById("errorDescripcionActualizarTipoSemilla"),
    //
    
    // Formulario para agregar ficha técnica
        formularioAgregarFichaTecnica: document.getElementById("addTechnicalSheetForm"),

        // Campos
        semillaAgregarFichaTecnica: document.getElementById("addSeedTS"),
        tipoClimaAgregarFichaTecnica: document.getElementById("addTypeWeather"),
        temperaturaMinimaAgregarFichaTecnica: document.getElementById("addMinTemperature"),
        temperaturaMaximaAgregarFichaTecnica: document.getElementById("addMaxTemperature"),
        humedadMinimaAgregarFichaTecnica: document.getElementById("addMinHumidity"),
        humedadMaximaAgregarFichaTecnica: document.getElementById("addMaxHumidity"),
        cantidadAguaMinimaAgregarFichaTecnica: document.getElementById("addMinWaterAmount"),
        cantidadAguaMaximaAgregarFichaTecnica: document.getElementById("addMaxWaterAmount"),
        tipoTierraAgregarFichaTecnica: document.getElementById("addTypeIdProfile"),
        cantidadTierraMinimaAgregarFichaTecnica: document.getElementById("addMinSoilAmount"),
        cantidadTierraMaximaAgregarFichaTecnica: document.getElementById("addMaxSoilAmount"),
        espacioAgregarFichaTecnica: document.getElementById("addPlot"),

        // Errores
        errorSemillaAgregarFichaTecnica: document.getElementById("errorSemillaAgregarFichaTecnica"),
        errorTipoClimaAgregarFichaTecnica: document.getElementById("errorTipoClimaAgregarFichaTecnica"),
        errorTemperaturaMinimaAgregarFichaTecnica: document.getElementById("errorTemperaturaMinimaAgregarFichaTecnica"),
        errorTemperaturaMaximaAgregarFichaTecnica: document.getElementById("errorTemperaturaMaximaAgregarFichaTecnica"),
        errorHumedadMinimaAgregarFichaTecnica: document.getElementById("errorHumedadMinimaAgregarFichaTecnica"),
        errorHumedadMaximaAgregarFichaTecnica: document.getElementById("errorHumedadMaximaAgregarFichaTecnica"),
        errorCantidadAguaMinimaAgregarFichaTecnica: document.getElementById("errorCantidadAguaMinimaAgregarFichaTecnica"),
        errorCantidadAguaMaximaAgregarFichaTecnica: document.getElementById("errorCantidadAguaMaximaAgregarFichaTecnica"),
        errorTipoTierraAgregarFichaTecnica: document.getElementById("errorTipoTierraAgregarFichaTecnica"),
        errorCantidadTierraMinimaAgregarFichaTecnica: document.getElementById("errorCantidadTierraMinimaAgregarFichaTecnica"),
        errorCantidadTierraMaximaAgregarFichaTecnica: document.getElementById("errorCantidadTierraMaximaAgregarFichaTecnica"),
        errorEspacioAgregarFichaTecnica: document.getElementById("errorEspacioAgregarFichaTecnica"),
    //
    
    // Formulario para actualizar ficha técnica
        formularioActualizarFichaTecnica: document.getElementById("updateTechnicalSheetForm"),

        // Campos
        idActualizarFichaTecnica: document.getElementById("updateTechnicalSheetId"),
        tipoClimaActualizarFichaTecnica: document.getElementById("updateTypeWeather"),
        temperaturaMinimaActualizarFichaTecnica: document.getElementById("updateMinTemperature"),
        temperaturaMaximaActualizarFichaTecnica: document.getElementById("updateMaxTemperature"),
        humedadMinimaActualizarFichaTecnica: document.getElementById("updateMinHumidity"),
        humedadMaximaActualizarFichaTecnica: document.getElementById("updateMaxHumidity"),
        cantidadAguaMinimaActualizarFichaTecnica: document.getElementById("updateMinWaterAmount"),
        cantidadAguaMaximaActualizarFichaTecnica: document.getElementById("updateMaxWaterAmount"),
        tipoTierraActualizarFichaTecnica: document.getElementById("updateTypeIdProfile"),
        cantidadTierraMinimaActualizarFichaTecnica: document.getElementById("updateMinSoilAmount"),
        cantidadTierraMaximaActualizarFichaTecnica: document.getElementById("updateMaxSoilAmount"),
        espacioActualizarFichaTecnica: document.getElementById("updatePlot"),

        // Errores
        errorTipoClimaActualizarFichaTecnica: document.getElementById("errorTipoClimaActualizarFichaTecnica"),
        errorTemperaturaMinimaActualizarFichaTecnica: document.getElementById("errorTemperaturaMinimaActualizarFichaTecnica"),
        errorTemperaturaMaximaActualizarFichaTecnica: document.getElementById("errorTemperaturaMaximaActualizarFichaTecnica"),
        errorHumedadMinimaActualizarFichaTecnica: document.getElementById("errorHumedadMinimaActualizarFichaTecnica"),
        errorHumedadMaximaActualizarFichaTecnica: document.getElementById("errorHumedadMaximaActualizarFichaTecnica"),
        errorCantidadAguaMinimaActualizarFichaTecnica: document.getElementById("errorCantidadAguaMinimaActualizarFichaTecnica"),
        errorCantidadAguaMaximaActualizarFichaTecnica: document.getElementById("errorCantidadAguaMaximaActualizarFichaTecnica"),
        errorTipoTierraActualizarFichaTecnica: document.getElementById("errorTipoTierraActualizarFichaTecnica"),
        errorCantidadTierraMinimaActualizarFichaTecnica: document.getElementById("errorCantidadTierraMinimaActualizarFichaTecnica"),
        errorCantidadTierraMaximaActualizarFichaTecnica: document.getElementById("errorCantidadTierraMaximaActualizarFichaTecnica"),
        errorEspacioActualizarFichaTecnica: document.getElementById("errorEspacioActualizarFichaTecnica"),
    //
    
    // Formulario para agregar tipo de clima
        formularioAgregarTipoClima: document.getElementById("addTypeWeatherForm"),

        // Campos
        descripcionAgregarTipoClima: document.getElementById("addTypeWeatherDescription"),

        // Errores
        errorDescripcionAgregarTipoClima: document.getElementById("errorDescripcionAgregarTipoClima"),
    //
    
    // Formulario para actualizar tipo de clima
        formularioActualizarTipoClima: document.getElementById("updateTypeWeatherForm"),

        // Campos
        idActualizarTipoClima: document.getElementById("updateTypeWeatherId"),
        descripcionActualizarTipoClima: document.getElementById("updateTypeWeatherDescription"),

        // Errores
        errorDescripcionActualizarTipoClima: document.getElementById("errorTipoClimaActualizarTipoClima"),
    //
    
    // Formulario para agregar tipo de tierra
        formularioAgregarTipoTierra: document.getElementById("addTypeSoilForm"),

        // Campos
        descripcionAgregarTipoTierra: document.getElementById("addTypeSoilDescription"),

        // Errores
        errorDescripcionAgregarTipoTierra: document.getElementById("errorDescripcionAgregarTipoTierra"),
    //
    
    // Formulario para actualizar tipo de tierra
        formularioActualizarTipoTierra: document.getElementById("updateTypeSoilForm"),

        // Campos
        idActualizarTipoTierra: document.getElementById("updateTypeSoilId"),
        descripcionActualizarTipoTierra: document.getElementById("updateTypeSoilDescription"),

        // Errores
        errorDescripcionActualizarTipoTierra: document.getElementById("errorTipoTierraActualizarTipoTierra"),
    //
    
    // Formulario para agregar etapas de crecimiento
        formularioAgregarEtapasCrecimiento: document.getElementById("addGrowthStagesForm"),

        // Campos
        idAgregarEtapasCrecimiento: document.getElementById("addGrowthStagesId"),
        semillaAgregarEtapasCrecimiento: document.getElementById("addSeedGS"),
        germinacionMinimaAgregarEtapasCrecimiento: document.getElementById("addGerminationMin"),
        germinacionMaximaAgregarEtapasCrecimiento: document.getElementById("addGerminationMax"),
        desarrolloVegetativoMinimoAgregarEtapasCrecimiento: document.getElementById("addVegetativeGrowthMin"),
        desarrolloVegetativoMaximoAgregarEtapasCrecimiento: document.getElementById("addVegetativeGrowthMax"),
        floracionMinimaAgregarEtapasCrecimiento: document.getElementById("addFloweringMin"),
        floracionMaximaAgregarEtapasCrecimiento: document.getElementById("addFloweringMax"),
        llenadoGranosMinimoAgregarEtapasCrecimiento: document.getElementById("addGrainFillingMin"),
        llenadoGranosMaximoAgregarEtapasCrecimiento: document.getElementById("addGrainFillingMax"),
        cosechaMinimaAgregarEtapasCrecimiento: document.getElementById("addHarvestMin"),
        cosechaMaximaAgregarEtapasCrecimiento: document.getElementById("addHarvestMax"),

        // Errores
        errorSemillaAgregarEtapasCrecimiento: document.getElementById("errorSemillaAgregarEtapasCrecimiento"),
        errorGerminacionMinimaAgregarEtapasCrecimiento: document.getElementById("errorGerminacionMinAgregarEtapas"),
        errorGerminacionMaximaAgregarEtapasCrecimiento: document.getElementById("errorGerminacionMaxAgregarEtapas"),
        errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento: document.getElementById("errorDesarrolloVegetativoMinAgregarEtapas"),
        errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento: document.getElementById("errorDesarrolloVegetativoMaxAgregarEtapas"),
        errorFloracionMinimaAgregarEtapasCrecimiento: document.getElementById("errorFloracionMinAgregarEtapas"),
        errorFloracionMaximaAgregarEtapasCrecimiento: document.getElementById("errorFloracionMaxAgregarEtapas"),
        errorLlenadoGranosMinimoAgregarEtapasCrecimiento: document.getElementById("errorLlenadoGranosMinAgregarEtapas"),
        errorLlenadoGranosMaximoAgregarEtapasCrecimiento: document.getElementById("errorLlenadoGranosMaxAgregarEtapas"),
        errorCosechaMinimaAgregarEtapasCrecimiento: document.getElementById("errorCosechaMinAgregarEtapas"),
        errorCosechaMaximaAgregarEtapasCrecimiento: document.getElementById("errorCosechaMaxAgregarEtapas"),
    //
    
    // Formulario para actualizar etapas de crecimiento
        formularioActualizarEtapasCrecimiento: document.getElementById("updateGrowthStagesForm"),

        // Campos
        idActualizarEtapasCrecimiento: document.getElementById("updateGrowthStagesId"),
        germinacionMinimaActualizarEtapasCrecimiento: document.getElementById("updateGerminationMin"),
        germinacionMaximaActualizarEtapasCrecimiento: document.getElementById("updateGerminationMax"),
        desarrolloVegetativoMinimoActualizarEtapasCrecimiento: document.getElementById("updateVegetativeGrowthMin"),
        desarrolloVegetativoMaximoActualizarEtapasCrecimiento: document.getElementById("updateVegetativeGrowthMaxCrecimiento"),
        floracionMinimaActualizarEtapasCrecimiento: document.getElementById("updateFloweringMin"),
        floracionMaximaActualizarEtapasCrecimiento: document.getElementById("updateFloweringMax"),
        llenadoGranosMinimoActualizarEtapasCrecimiento: document.getElementById("updateGrainFillingMin"),
        llenadoGranosMaximoActualizarEtapasCrecimiento: document.getElementById("updateGrainFillingMax"),
        cosechaMinimaActualizarEtapasCrecimiento: document.getElementById("updateHarvestMin"),
        cosechaMaximaActualizarEtapasCrecimiento: document.getElementById("updateHarvestMax"),

        // Errores
        errorGerminacionMinimaActualizarEtapasCrecimiento: document.getElementById("errorGerminacionMinActualizarEtapasCrecimiento"),
        errorGerminacionMaximaActualizarEtapasCrecimiento: document.getElementById("errorGerminacionMaxActualizarEtapasCrecimiento"),
        errorDesarrolloVegetativoMinimoActualizarEtapasCrecimiento: document.getElementById("errorDesarrolloVegetativoMinActualizarEtapasCrecimiento"),
        errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento: document.getElementById("errorDesarrolloVegetativoMaxActualizarEtapas"),
        errorFloracionMinimaActualizarEtapasCrecimiento: document.getElementById("errorFloracionMinActualizarEtapasCrecimiento"),
        errorFloracionMaximaActualizarEtapasCrecimiento: document.getElementById("errorFloracionMaxActualizarEtapasCrecimiento"),
        errorLlenadoGranosMinimoActualizarEtapasCrecimiento: document.getElementById("errorLlenadoGranosMinActualizarEtapasCrecimiento"),
        errorLlenadoGranosMaximoActualizarEtapasCrecimiento: document.getElementById("errorLlenadoGranosMaxActualizarEtapasCrecimiento"),
        errorCosechaMinimaActualizarEtapasCrecimiento: document.getElementById("errorCosechaMinActualizarEtapasCrecimiento"),
        errorCosechaMaximaActualizarEtapasCrecimiento: document.getElementById("errorCosechaMaxActualizarEtapasCrecimiento"),
    //
    
    // Formulario para actualizar jardinera
        formularioActualizarJardinera: document.getElementById("updateGardenForm"),

        // Campos
        idActualizarJardinera: document.getElementById("updateGardenId"),
        nombreActualizarJardinera: document.getElementById("updateGardenName"),
        descripcionActualizarJardinera: document.getElementById("updateGardenDescription"),
        faseActualizarJardinera: document.getElementById("updateStageSeed"),

        // Errores
        errorNombreActualizarJardinera: document.getElementById("errorNombreActualizarJardinera"),
        errorDescripcionActualizarJardinera: document.getElementById("errorDescripcionActualizarJardinera"),
        errorFaseActualizarJardinera: document.getElementById("errorFaseActualizarJardinera"),
    //
    
    // Formulario para actualizar factor externo
        formularioActualizarFactorExterno: document.getElementById("updateExternalFactorForm"),

        // Campos
        idActualizarFactorExterno: document.getElementById("updateExternalFactorId"),
        humedadActualizarFactorExterno: document.getElementById("updateHumidity"),
        tipoClimaActualizarFactorExterno: document.getElementById("updateTypeWeatherF"),
        temperaturaActualizarFactorExterno: document.getElementById("updateTemperature"),
        cantidadAguaActualizarFactorExterno: document.getElementById("updateWaterAmount"),

        // Errores
        errorHumedadActualizarFactorExterno: document.getElementById("errorHumedadActualizarFactorExterno"),
        errorTipoClimaActualizarFactorExterno: document.getElementById("errorTipoClimaActualizarFactorExterno"),
        errorTemperaturaActualizarFactorExterno: document.getElementById("errorTemperaturaActualizarFactorExterno"),
        errorCantidadAguaActualizarFactorExterno: document.getElementById("errorCantidadAguaActualizarFactorExterno"),
    //
    
    // Formulario para actualizar monitoreo
        formularioActualizarMonitoreo: document.getElementById("updateMonitoringForm"),

        // Campos
        idActualizarMonitoreo: document.getElementById("updateMonitoringId"),
        notaActualizarMonitoreo: document.getElementById("updateNote"),
        imagenActualizarMonitoreo: document.getElementById("updateImage"),
        porcentajeActualizarMonitoreo: document.getElementById("updatePercentage"),

        // Errores
        errorNotaActualizarMonitoreo: document.getElementById("errorNotaActualizarMonitoreo"),
        errorImagenActualizarMonitoreo: document.getElementById("errorImagenActualizarMonitoreo"),
        errorPorcentajeActualizarMonitoreo: document.getElementById("errorPorcentajeActualizarMonitoreo"),
    //

    // Formulario para agregar fase
        formularioAgregarFase: document.getElementById("addStagesForm"),

        // Campos
        idAgregarFase: document.getElementById("addStagesId"),
        nombreAgregarFase: document.getElementById("addStageName"),
        descripcionAgregarFase: document.getElementById("addStageDescription"),
        porcentajeAgregarFase: document.getElementById("addStagePercentage"),

        // Errores
        errorNombreAgregarFase: document.getElementById("errorNombreAgregarFase"),
        errorDescripcionAgregarFase: document.getElementById("errorDescripcionAgregarFase"),
        errorPorcentajeAgregarFase: document.getElementById("errorPorcentajeAgregarFase"),
    //

    // Formulario para actualizar fase
        formularioActualizarFase: document.getElementById("updateStagesForm"),

        // Campos
        idActualizarFase: document.getElementById("updateStagesId"),
        nombreActualizarFase: document.getElementById("updateStageName"),
        descripcionActualizarFase: document.getElementById("updateStageDescription"),
        porcentajeActualizarFase: document.getElementById("updateStagePercentage"),

        // Errores
        errorNombreActualizarFase: document.getElementById("errorNombreActualizarFase"),
        errorDescripcionActualizarFase: document.getElementById("errorDescripcionActualizarFase"),
        errorPorcentajeActualizarFase: document.getElementById("errorPorcentajeActualizarFase"),
    //

    // Formulario para agregar pregunta fase
        formularioAgregarPreguntaFase: document.getElementById("addStageQuestionsForm"),

        // Campos
        preguntaAgregarPreguntaFase: document.getElementById("addStageQuestionsQuestion"),
        porcentajeAgregarPreguntaFase: document.getElementById("addStageQuestionsPercentage"),

        // Errores
        errorPreguntaAgregarPreguntaFase: document.getElementById("errorPreguntaAgregarPreguntaFase"),
        errorPorcentajeAgregarPreguntaFase: document.getElementById("errorPorcentajeAgregarPreguntaFase"),
    //
    
    // Formulario para actualizar pregunta fase
        formularioActualizarPreguntaFase: document.getElementById("updateStageQuestionsForm"),

        // Campos
        idActualizarPreguntaFase: document.getElementById("updateStageQuestionId"),
        preguntaActualizarPreguntaFase: document.getElementById("updateStageQuestionsQuestion"),
        porcentajeActualizarPreguntaFase: document.getElementById("updateStageQuestionsPercentage"),

        // Errores
        errorPreguntaActualizarPreguntaFase: document.getElementById("errorPreguntaActualizarPreguntaFase"),
        errorPorcentajeActualizarPreguntaFase: document.getElementById("errorPorcentajeActualizarPreguntaFase"),
    // 
}
const elementsForm={
    //Formulario de autenticación del usuario
        formularioAcceso: document.getElementById("formularioAcceso"),

        //Campos del formulario
        numeroDocumentoAcceso: document.getElementById("numDocumentoAcceso"),
        contrasenaAcceso: document.getElementById("contrasenaAcceso"),

        //Errores de cada campo del formulario
        errorNumDocumentoAcceso: document.getElementById("errorNumDocumento"),
        errorContrasenaAcceso: document.getElementById("errorContrasena"), 
    // 

    //Formulario de registro del usuario
        formularioRegistro: document.getElementById("formularioRegistro"),

        //Campos del formulario
        nombreCompleto: document.getElementById("nombreCompletoRegistro"),
        tipoDocumentoRegistro: document.getElementById("tipoDocumentoRegistro"),
        numeroDocumentoRegistro:document.getElementById("numeroDocumentoRegistro"),
        barrioRegistro:document.getElementById("barrioRegistro"),
        correoElectronicoRegistro:document.getElementById("correoElectronicoRegistro"),
        contrasenaRegistro:document.getElementById("contrasenaRegistro"),
        confirmarContrasenaRegistro:document.getElementById("confirmarContrasenaRegistro"),
        confirmarUsoDatos:document.getElementById("confirmarUsoDatos"),

        //Errores de cada campo del formulario
        errorNombreCompletoRegistro: document.getElementById("errorNombreCompletoRegistro"),
        errorTipoDocumentoRegistro: document.getElementById("errorTipoDocumentoRegistro"),
        errorNumDocumentoRegistro: document.getElementById("errorNumDocumentoRegistro"),
        errorBarrioRegistro: document.getElementById("errorBarrioRegistro"),
        errorCorreoElectronicoRegistro: document.getElementById("errorCorreoElectronicoRegistro"),
        errorContrasenaRegistro: document.getElementById("errorContrasenaRegistro"),
        errorConfirmarContrasenaRegistro: document.getElementById("errorConfirmarContrasenaRegistro"),
        errorConfirmarUsoDatos: document.getElementById("errorConfirmarUsoDatos"), 
    // 
    
    //Formularios para recuperar la contraseña del usuario
        formularioRecuperarDocumento: document.getElementById("formularioRecuperarDocumento"),
        formularioRecuperarCodigo: document.getElementById("formularioRecuperarCodigo"),
        formularioRecuperarNuevaContrasena: document.getElementById("formularioNuevaContrasena"),

        //Campos del formulario
        numeroDocumentoRecuperarContrasena: document.getElementById("numDocumentoRecuperarContrasena"),
        codigoVerificacionRecuperarContrasena: document.getElementById("codVerificacionRecuperarContrasena"),
        nuevaContrasenaRecuperarContrasena: document.getElementById("nuevaContrasenaRecuperarContrasena"), 

        //Errores de cada campo del formulario
        errorNumDocumentoRecuperarContrasena: document.getElementById("errorNumDocumentoRecuperarContrasena"),
        errorCodigoVerificacionRecuperarContrasena: document.getElementById("errorCodigoVerificacionRecuperarContrasena"),
        errorNuevaContrasenaRecuperarContrasena: document.getElementById("errorNuevaContrasenaRecuperarContrasena"), 
    // 
    
    //Formulario para verificar el correo del usuario
        formularioVerificarCorreo: document.getElementById("formularioVerificarCorreo"),

        //Campos del formulario
        codigoVerificacionVerificarCorreo: document.getElementById("codVerificacionVerificarCorreo"),

        //Errores de cada campo del formulario
        errorCodigoVerificacionVerificarCorreo: document.getElementById("errorCodigoVerificacionVerificarCorreo"), 
    // 
    
    //Formulario para solicitar una nueva semilla
        formularioSolicitarSemilla:document.getElementById("formularioSolicitarSemilla"),

        //Campos del formulario
        nombreCompletoSolicitarSemilla: document.getElementById("gname"),
        correoSolicitarSemilla: document.getElementById("gmail"),
        semillaSolicitarSemilla:document.getElementById("seed"), 
        mensajeSolicitarSemilla:document.getElementById("message"),

        //Errores de cada campo del formulario
        errorNombreCompletoSolicitarSemilla:document.getElementById("errorNombreCompletoSolicitarSemilla"),
        errorCorreoSolicitarSemilla:document.getElementById("errorCorreoSolicitarSemilla"),
        errorSemillaSolicitarSemilla:document.getElementById("errorSemillaSolicitarSemilla"),
        errorMensajeSolicitarSemilla:document.getElementById("errorMensajeSolicitarSemilla"), 
    // 
    
    //Formulario para enviar una nueva reseña
        formularioEnviarResena: document.getElementById("formularioEnviarResena"),

        //Campos del formulario
        nombreCompletoEnviarResena:document.getElementById("nameEnviarResena"), 
        correoEnviarResena:document.getElementById("gmailEnviarResena"),
        mensajeEnviarResena:document.getElementById("messageEnviarResena"),

        //Errores de cada campo del formulario
        errorNombreCompletoEnviarResena:document.getElementById("errorNombreCompletoEnviarResena"),
        errorCorreoEnviarResena:document.getElementById("errorCorreoEnviarResena"),
        errorMensajeEnviarResena:document.getElementById("errorMensajeEnviarResena"),
    // 
    
    //Formulario para agregar una nueva jardinera
        formularioAgregarJardinera:document.getElementById("addGardenForm"), 

        //Campos del formulario
        nombreJardineraAgregarJardinera: document.getElementById("gardenName"),
        semillaAgregarJardinera: document.getElementById("gardenSeed"),
        descripcionAgregarJardinera: document.getElementById("gardenDescription"),

        //Errores de cada campo del formulario
        errorNombreJardineraAgregarJardinera: document.getElementById("errorNombreJardineraAgregarJardinera"),
        errorSemillaAgregarJardinera: document.getElementById("errorSemillaAgregarJardinera"),
        errorDescripcionAgregarJardinera: document.getElementById("errorDescripcionAgregarJardinera"), 
    // 
    
    //Formulario para actualizar el perfil del usuario
        formularioActualizarPerfil: document.getElementById("editProfileForm"),

        //Campos del formulario
        nombreCompletoActualizarPerfil: document.getElementById("editName"),
        tipoDocumentoActualizarPerfil: document.getElementById("editTypeIdProfile"),
        correoActualizarPerfil:document.getElementById("editEmail"), 
        barrioActualizarPerfil:document.getElementById("editLocation"), 
        contrasenaActualizarPerfil:document.getElementById("editPassword"),
        confirmarContrasenaActualizarPerfilUsuario:document.getElementById("confirmarContrasenaActualizarPerfilUsuario"),

        //Errores de cada campo del formulario
        errorNombreCompletoActualizarPerfil: document.getElementById("errorNombreCompletoActualizarPerfil"),
        errorTipoDocumentoActualizarPerfil: document.getElementById("errorTipoDocumentoActualizarPerfil"),
        errorCorreoActualizarPerfil: document.getElementById("errorCorreoActualizarPerfil"),
        errorBarrioActualizarPerfil: document.getElementById("errorBarrioActualizarPerfil"),
        errorContrasenaActualizarPerfil: document.getElementById("errorContrasenaActualizarPerfil"),
        errorConfirmarContrasenaActualizarPerfilUsuario: document.getElementById("errorConfirmarContrasenaActualizarPerfilUsuario"),
    // 
    
    //Formulario para actualizar la jardinera
        formularioActualizarJardinera:document.getElementById("updateGardenForm"),

        //Campos del formulario
        nombreJardineraActualizarJardinera: document.getElementById("updateGardenName"),
        descripcionActualizarJardinera: document.getElementById("updateGardenDescription"),

        //Errores de cada campo del formulario
        errorNombreJardineraActualizarJardinera: document.getElementById("errorNombreJardineraActualizarJardinera"),
        errorDescripcionActualizarJardinera: document.getElementById("errorDescripcionActualizarJardinera"),
    // 
    
    //Formulario para enviar una solicitud de varios tipos
        formularioEnviarSolicitud: document.getElementById("sendRequestForm"), 

        //Campos del formulario
        tipoSolicitudEnviarSolicitud: document.getElementById("typeRequest"),
        nuevaSemillaEnviarSolicitud: document.getElementById("newSeedField"), 
        descripcionEnviarSolicitud: document.getElementById("message"), 

        //Errores de cada campo del formulario
        errorTipoSolicitudEnviarSolicitud: document.getElementById("errorTipoSolicitudEnviarSolicitud"),
        errorNuevaSemillaEnviarSolicitud: document.getElementById("errorNuevaSemillaEnviarSolicitud"),
        errorDescripcionEnviarSolicitud: document.getElementById("errorDescripcionEnviarSolicitud"), 
    // 
    
    //Formulario para agrega un factor externo de la jardinera
        formularioAgregarFactoresExternos:document.getElementById("addExternalFactorsForm"), 

        //Campos del formulario
        humedadAgregarFactor:document.getElementById("humidity"),
        cantidadAguaAgregarFactor:document.getElementById("amountWater"),
        temperaturaAgregarFactor:document.getElementById("temperature"),
        climaAgregarFactor:document.getElementById("weather"),

        //Errores de cada campo del formulario
        errorHumedadAgregarFactor:document.getElementById("errorHumedadAgregarFactor"), 
        errorCantidadAguadAgregarFactor:document.getElementById("errorCantidadAguaAgregarFactor"), 
        errorTemperaturaAgregarFactor:document.getElementById("errorTemperaturaAgregarFactor"), 
        errorClimaAgregarFactor:document.getElementById("errorClimaAgregarFactor"), 
    // 
    
    //Formulario para agregar una evolucion de la jardinera
        formularioAgregarEvolucion:document.getElementById("addGardenEvolutionForm"), 

        //Errores de cada campo del formulario
        errorPreguntasAgregarEvolucion:document.getElementById("errorPreguntasAgregarEvolucion"),
    // 
}

//Funcion para limpiar los errores del formulario de para autenticarse
function limpiarErroresAcceso(){
    elementsForm.errorNumDocumentoAcceso.textContent = "";
    elementsForm.errorContrasenaAcceso.textContent = "";
}

//Funcion para limpiar los errores del formulario de para registrarse
function limpiarErroresRegistro(){
    elementsForm.errorNombreCompletoRegistro.textContent="";
    elementsForm.errorTipoDocumentoRegistro.textContent="";
    elementsForm.errorNumDocumentoRegistro.textContent="";
    elementsForm.errorBarrioRegistro.textContent="";
    elementsForm.errorCorreoElectronicoRegistro.textContent="";
    elementsForm.errorContrasenaRegistro.textContent="";
    elementsForm.errorConfirmarContrasenaRegistro.textContent="";
    elementsForm.errorConfirmarUsoDatos.textContent="";
}

//Funcion para limpiar los errores del formulario de para solicitar una nueva semilla
function limpiarErroresSolicitarSemilla(){
    elementsForm.errorNombreCompletoSolicitarSemilla.textContent="";
    elementsForm.errorCorreoSolicitarSemilla.textContent="";
    elementsForm.errorSemillaSolicitarSemilla.textContent="";
    elementsForm.errorMensajeSolicitarSemilla.textContent="";
}

//Funcion para limpiar los errores del formulario de para enviar una reseña
function limpiarErroresEnviarResena(){
    elementsForm.errorNombreCompletoEnviarResena.textContent="";
    elementsForm.errorCorreoEnviarResena.textContent="";
    elementsForm.errorMensajeEnviarResena.textContent="";
}

//Funcion para limpiar los errores del formulario de para agregar una nueva jardinera
function limpiarErroresAgregarJardinera(){
    elementsForm.errorNombreJardineraAgregarJardinera.textContent="";
    elementsForm.errorSemillaAgregarJardinera.textContent="";
    elementsForm.errorDescripcionAgregarJardinera.textContent="";
}

//Funcion para limpiar los errores del formulario de para actualizar el perfil
function limpiarErroresActualizarPerfil(){
    elementsForm.errorNombreCompletoActualizarPerfil.textContent="";
    elementsForm.errorTipoDocumentoActualizarPerfil.textContent="";
    elementsForm.errorCorreoActualizarPerfil.textContent="";
    elementsForm.errorBarrioActualizarPerfil.textContent="";
    elementsForm.errorContrasenaActualizarPerfil.textContent="";
    elementsForm.errorConfirmarContrasenaActualizarPerfilUsuario.textContent="";
}

//Funcion para limpiar los errores del formulario de para actualizar la jardinera
function limpiarErroresActualizarJardinera(){
    elementsForm.errorNombreJardineraActualizarJardinera.textContent="";
    elementsForm.errorDescripcionActualizarJardinera.textContent="";
}

//Funcion para limpiar los errores del formulario de para enviar una solicitud de diferentes tipos
function limpiarErroresEnviarSolicitud(){
    elementsForm.errorTipoSolicitudEnviarSolicitud.textContent="";
    elementsForm.errorNuevaSemillaEnviarSolicitud.textContent="";
    elementsForm.errorDescripcionEnviarSolicitud.textContent="";
}

//Funcion para limpiar los errores del formulario de para agregar un factor externo
function limpiarErroresAgregarFactoresExternos(){
    elementsForm.errorHumedadAgregarFactor.textContent="";
    elementsForm.errorCantidadAguadAgregarFactor.textContent=""; 
    elementsForm.errorTemperaturaAgregarFactor.textContent=""; 
    elementsForm.errorClimaAgregarFactor.textContent="";
}

// Perfil administrador
function limpiarErroresActualizarPerfilAdmin(){
    elementsAdminForm.errorNombreCompletoActualizarPerfilAdmin.textContent = "";
    elementsAdminForm.errorTipoDocumentoActualizarPerfilAdmin.textContent = "";
    elementsAdminForm.errorCorreoActualizarPerfilAdmin.textContent = "";
    elementsAdminForm.errorBarrioActualizarPerfilAdmin.textContent = "";
    elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent = "";
    elementsAdminForm.errorConfirmarContrasenaActualizarPerfilAdmin.textContent = "";
}

// Perfil usuario
function limpiarErroresActualizarPerfilUsuario(){
    elementsAdminForm.errorNombreCompletoActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorTipoUsuarioActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorTipoDocumentoActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorCorreoActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorEstadoCorreoActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorBarrioActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorAvatarActualizarPerfilUsuario.textContent = "";
    elementsAdminForm.errorCantidadJardinerasActualizarPerfilUsuario.textContent = "";
}

// Tipo documento
function limpiarErroresAgregarTipoDocumento(){
    elementsAdminForm.errorDescripcionAgregarTipoDocumento.textContent = "";
}

function limpiarErroresActualizarTipoDocumento(){
    elementsAdminForm.errorDescripcionActualizarTipoDocumento.textContent = "";
}

// Semilla
function limpiarErroresAgregarSemilla(){
    elementsAdminForm.errorNombreAgregarSemilla.textContent = "";
    elementsAdminForm.errorImagenAgregarSemilla.textContent = "";
    elementsAdminForm.errorObservacionesAgregarSemilla.textContent = "";
    elementsAdminForm.errorTipoSemillaAgregarSemilla.textContent = "";
}

function limpiarErroresActualizarSemilla(){
    elementsAdminForm.errorNombreActualizarSemilla.textContent = "";
    elementsAdminForm.errorImagenActualizarSemilla.textContent = "";
    elementsAdminForm.errorObservacionesActualizarSemilla.textContent = "";
    elementsAdminForm.errorTipoSemillaActualizarSemilla.textContent = "";
}

// Tipo semilla
function limpiarErroresAgregarTipoSemilla(){
    elementsAdminForm.errorDescripcionAgregarTipoSemilla.textContent = "";
}

function limpiarErroresActualizarTipoSemilla(){
    elementsAdminForm.errorDescripcionActualizarTipoSemilla.textContent = "";
}

// Tipo clima
function limpiarErroresAgregarTipoClima(){
    elementsAdminForm.errorDescripcionAgregarTipoClima.textContent = "";
}

function limpiarErroresActualizarTipoClima(){
    elementsAdminForm.errorDescripcionActualizarTipoClima.textContent = "";
}

// Tipo tierra
function limpiarErroresAgregarTipoTierra(){
    elementsAdminForm.errorDescripcionAgregarTipoTierra.textContent = "";
}

function limpiarErroresActualizarTipoTierra(){
    elementsAdminForm.errorDescripcionActualizarTipoTierra.textContent = "";
}

// Ficha técnica
function limpiarErroresAgregarFichaTecnica(){
    elementsAdminForm.errorSemillaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorTipoClimaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorTemperaturaMinimaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorHumedadMinimaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadAguaMinimaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorTipoTierraAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadTierraMinimaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.textContent = "";
    elementsAdminForm.errorEspacioAgregarFichaTecnica.textContent = "";
}

function limpiarErroresActualizarFichaTecnica(){
    elementsAdminForm.errorTipoClimaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorTemperaturaMinimaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorTemperaturaMaximaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorHumedadMinimaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorHumedadMaximaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadAguaMinimaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadAguaMaximaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorTipoTierraActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadTierraMinimaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorCantidadTierraMaximaActualizarFichaTecnica.textContent = "";
    elementsAdminForm.errorEspacioActualizarFichaTecnica.textContent = "";
}

// Etapas crecimiento
function limpiarErroresAgregarEtapasCrecimiento(){
    elementsAdminForm.errorSemillaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorGerminacionMinimaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorFloracionMinimaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorLlenadoGranosMinimoAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorCosechaMinimaAgregarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.textContent = "";
}

function limpiarErroresActualizarEtapasCrecimiento(){
    elementsAdminForm.errorGerminacionMinimaActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorGerminacionMaximaActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorDesarrolloVegetativoMinimoActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorFloracionMinimaActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorFloracionMaximaActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorLlenadoGranosMinimoActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorLlenadoGranosMaximoActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorCosechaMinimaActualizarEtapasCrecimiento.textContent = "";
    elementsAdminForm.errorCosechaMaximaActualizarEtapasCrecimiento.textContent = "";
}

// Jardinera
function limpiarErroresActualizarJardinera(){
    elementsAdminForm.errorNombreActualizarJardinera.textContent = "";
    elementsAdminForm.errorSemillaActualizarJardinera.textContent = "";
    elementsAdminForm.errorDescripcionActualizarJardinera.textContent = "";
    elementsAdminForm.errorFaseActualizarJardinera.textContent = "";
}

// Factor externo
function limpiarErroresActualizarFactorExterno(){
    elementsAdminForm.errorHumedadActualizarFactorExterno.textContent = "";
    elementsAdminForm.errorTipoClimaActualizarFactorExterno.textContent = "";
    elementsAdminForm.errorTemperaturaActualizarFactorExterno.textContent = "";
    elementsAdminForm.errorCantidadAguaActualizarFactorExterno.textContent = "";
}

// Monitoreo
function limpiarErroresActualizarMonitoreo(){
    elementsAdminForm.errorNotaActualizarMonitoreo.textContent = "";
    elementsAdminForm.errorImagenActualizarMonitoreo.textContent = "";
    elementsAdminForm.errorPorcentajeActualizarMonitoreo.textContent = "";
}

// Fase
function limpiarErroresAgregarFase(){
    elementsAdminForm.errorNombreAgregarFase.textContent = "";
    elementsAdminForm.errorDescripcionAgregarFase.textContent = "";
    elementsAdminForm.errorPorcentajeAgregarFase.textContent = "";
}

function limpiarErroresActualizarFase(){
    elementsAdminForm.errorNombreActualizarFase.textContent = "";
    elementsAdminForm.errorDescripcionActualizarFase.textContent = "";
    elementsAdminForm.errorPorcentajeActualizarFase.textContent = "";
}

// Pregunta fase
function limpiarErroresAgregarPreguntaFase(){
    elementsAdminForm.errorPreguntaAgregarPreguntaFase.textContent = "";
    elementsAdminForm.errorPorcentajeAgregarPreguntaFase.textContent = "";
}

function limpiarErroresActualizarPreguntaFase(){
    elementsAdminForm.errorPreguntaActualizarPreguntaFase.textContent = "";
    elementsAdminForm.errorPorcentajeActualizarPreguntaFase.textContent = "";
}

//=== FORMULARIO AUTENTICACION ===

//Si existe el elemento del formulario para autenticarse
if(elementsForm.formularioAcceso){
    //Crear el evento del formulario a la hora de enviarlo
    elementsForm.formularioAcceso.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let numeroDocumento=elementsForm.numeroDocumentoAcceso.value.trim();
        let contrasenaAcceso=elementsForm.contrasenaAcceso.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAcceso();

        //Evaluar el contenido de cada elemento del formulario

        //Validar si el campo se encuentra vacio
        if(numeroDocumento===""){
            elementsForm.errorNumDocumentoAcceso.textContent="Por favor, ingrese su número de documento";
            elementsForm.errorNumDocumentoAcceso.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        //Validar si el campo es un numero
        if(isNaN(numeroDocumento)){
            elementsForm.errorNumDocumentoAcceso.textContent = "El número de documento debe ser un valor numérico";
            elementsForm.errorNumDocumentoAcceso.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        //Validar la cantidad de caracteres del campo
        if(numeroDocumento.length<10){
            elementsForm.errorNumDocumentoAcceso.textContent="El número de documento debe tener al menos 10 dígitos";
            elementsForm.errorNumDocumentoAcceso.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
        
        if(contrasenaAcceso===""){
            elementsForm.errorContrasenaAcceso.textContent="Por favor, ingrese su contraseña";
            elementsForm.errorContrasenaAcceso.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(contrasenaAcceso.length<6){
            elementsForm.errorContrasenaAcceso.textContent="La contraseña debe tener al menos 6 caracteres";
            elementsForm.errorContrasenaAcceso.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        // Al menos una mayúscula
        if(!/[A-Z]/.test(contrasenaAcceso)){
            elementsForm.errorContrasenaAcceso.textContent = "La contraseña debe contener al menos una letra mayúscula";
            elementsForm.errorContrasenaAcceso.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos una minúscula
        if(!/[a-z]/.test(contrasenaAcceso)){
            elementsForm.errorContrasenaAcceso.textContent = "La contraseña debe contener al menos una letra minúscula";
            elementsForm.errorContrasenaAcceso.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos un número
        if(!/[0-9]/.test(contrasenaAcceso)){
            elementsForm.errorContrasenaAcceso.textContent = "La contraseña debe contener al menos un número";
            elementsForm.errorContrasenaAcceso.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos un carácter especial
        if(!/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(contrasenaAcceso)){
            elementsForm.errorContrasenaAcceso.textContent = "La contraseña debe contener al menos un carácter especial";
            elementsForm.errorContrasenaAcceso.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });

}

// === FORMULARIO REGISTRO ===
//Si existe el elemento del formulario para registrarse
if(elementsForm.formularioRegistro){
    elementsForm.formularioRegistro.addEventListener("submit", function(event){

        let nombreCompleto=elementsForm.nombreCompleto.value.trim();
        let tipoDocumento=elementsForm.tipoDocumentoRegistro.value.trim();
        let numeroDocumento=elementsForm.numeroDocumentoRegistro.value.trim();
        let barrio=elementsForm.barrioRegistro.value.trim();
        let correoElectronico=elementsForm.correoElectronicoRegistro.value.trim();
        let contrasena=elementsForm.contrasenaRegistro.value.trim();
        let confirmarContrasena=elementsForm.confirmarContrasenaRegistro.value.trim();
        let confirmarUsoDatos=elementsForm.confirmarUsoDatos.checked;


        limpiarErroresRegistro(); 

        if(nombreCompleto===""){
            elementsForm.errorNombreCompletoRegistro.textContent="Por favor,ingrese su nombre completo";
            elementsForm.errorNombreCompletoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(nombreCompleto.length<3 || nombreCompleto.length>50){
            elementsForm.errorNombreCompletoRegistro.textContent="Por favor, ingrese un nombre completo válido";
            elementsForm.errorNombreCompletoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        //Validar si el contenido del campo contiene los caracteres de texto
        if(!soloTexto.test(nombreCompleto)){
            elementsForm.errorNombreCompletoRegistro.textContent="El nombre completo debe ser un valor de texto";
            elementsForm.errorNombreCompletoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
        if(tipoDocumento===""){
            elementsForm.errorTipoDocumentoRegistro.textContent="Por favor, seleccione su tipo de documento";
            elementsForm.errorTipoDocumentoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(numeroDocumento===""){
            elementsForm.errorNumDocumentoRegistro.textContent="Por favor, ingrese su número de documento";
            elementsForm.errorNumDocumentoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(numeroDocumento.length<10){
            elementsForm.errorNumDocumentoRegistro.textContent="El número de documento debe tener al menos 10 dígitos";
            elementsForm.errorNumDocumentoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(numeroDocumento)){
            elementsForm.errorNumDocumentoRegistro.textContent = "El número de documento debe ser un valor numérico";
            elementsForm.errorNumDocumentoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(barrio===""){
            elementsForm.errorBarrioRegistro.textContent="Por favor, ingrese su barrio o localidad";
            elementsForm.errorBarrioRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(barrio.length<3){
            elementsForm.errorBarrioRegistro.textContent="Por favor, ingrese un barrio o localidad válido";
            elementsForm.errorBarrioRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(barrio)){
            elementsForm.errorBarrioRegistro.textContent ="El barrio o localidad solo debe contener letras";

            elementsForm.errorBarrioRegistro.style.marginBottom = "10px";

            event.preventDefault();
            return;
        }

        if(correoElectronico===""){
            elementsForm.errorCorreoElectronicoRegistro.textContent="Por favor, ingrese su correo electrónico";
            elementsForm.errorCorreoElectronicoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(correoElectronico.length<5 || !regexCorreo.test(correoElectronico)){
            elementsForm.errorCorreoElectronicoRegistro.textContent="Por favor, ingrese un correo electrónico válido";
            elementsForm.errorCorreoElectronicoRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(contrasena===""){
            elementsForm.errorContrasenaRegistro.textContent="Por favor, ingrese su contraseña";
            elementsForm.errorContrasenaRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(contrasena.length<6){
            elementsForm.errorContrasenaRegistro.textContent="La contraseña debe tener al menos 6 caracteres";
            elementsForm.errorContrasenaRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        // Al menos una mayúscula
        if(!/[A-Z]/.test(contrasena)){
            elementsForm.errorContrasenaRegistro.textContent = "La contraseña debe contener al menos una letra mayúscula";
            elementsForm.errorContrasenaRegistro.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos una minúscula
        if(!/[a-z]/.test(contrasena)){
            elementsForm.errorContrasenaRegistro.textContent = "La contraseña debe contener al menos una letra minúscula";
            elementsForm.errorContrasenaRegistro.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos un número
        if(!/[0-9]/.test(contrasena)){
            elementsForm.errorContrasenaRegistro.textContent = "La contraseña debe contener al menos un número";
            elementsForm.errorContrasenaRegistro.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        // Al menos un carácter especial
        if(!/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(contrasena)){
            elementsForm.errorContrasenaRegistro.textContent = "La contraseña debe contener al menos un carácter especial";
            elementsForm.errorContrasenaRegistro.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(confirmarContrasena===""){
            elementsForm.errorConfirmarContrasenaRegistro.textContent="Por favor, confirme su contraseña";
            elementsForm.errorConfirmarContrasenaRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(confirmarContrasena!==contrasena){
            elementsForm.errorConfirmarContrasenaRegistro.textContent="Las contraseñas no coinciden";
            elementsForm.errorConfirmarContrasenaRegistro.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!confirmarUsoDatos){
            elementsForm.errorConfirmarUsoDatos.textContent="Debe aceptar el uso y tratamiento de sus datos personales";
            elementsForm.errorConfirmarUsoDatos.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    }); 
}

//Si existe el elemento del formulario para recuperar el documento
if(elementsForm.formularioRecuperarDocumento){
    elementsForm.formularioRecuperarDocumento.addEventListener("submit", function(event){
        let numeroDocumento= elementsForm.numeroDocumentoRecuperarContrasena.value.trim();
        elementsForm.errorNumDocumentoRecuperarContrasena.textContent="";

        if(numeroDocumento===""){
            elementsForm.errorNumDocumentoRecuperarContrasena.textContent="Por favor, ingrese su número de documento";
            elementsForm.errorNumDocumentoRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(numeroDocumento)){
            elementsForm.errorNumDocumentoRecuperarContrasena.textContent = "El número de documento debe ser un valor numérico";
            elementsForm.errorNumDocumentoRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(numeroDocumento.length<10){
            elementsForm.errorNumDocumentoRecuperarContrasena.textContent="El número de documento debe tener al menos 10 dígitos";
            elementsForm.errorNumDocumentoRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }   
    })  
}

//Si existe el elemento del formulario para recuperar el codigo de verificacion
if(elementsForm.formularioRecuperarCodigo){
    elementsForm.formularioRecuperarCodigo.addEventListener("submit", function(event){
        let codigoVerificacion=elementsForm.codigoVerificacionRecuperarContrasena.value.trim();
        elementsForm.errorCodigoVerificacionRecuperarContrasena.textContent="";

        if(codigoVerificacion===""){
            elementsForm.errorCodigoVerificacionRecuperarContrasena.textContent="Por favor, ingrese el código de verificación";
            elementsForm.errorCodigoVerificacionRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(codigoVerificacion)){
            elementsForm.errorCodigoVerificacionRecuperarContrasena.textContent = "El código de verificación debe ser un valor numérico";
            elementsForm.errorCodigoVerificacionRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(codigoVerificacion.length<4 || codigoVerificacion.length>4){
            elementsForm.errorCodigoVerificacionRecuperarContrasena.textContent="El código de verificación debe tener 4 dígitos";
            elementsForm.errorCodigoVerificacionRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }        
    })  
}

//Si existe el elemento del formulario para recuperar la nueva contraseña
if(elementsForm.formularioRecuperarNuevaContrasena){
    elementsForm.formularioRecuperarNuevaContrasena.addEventListener("submit", function(event){
        let nuevaContrasena=elementsForm.nuevaContrasenaRecuperarContrasena.value.trim();
        elementsForm.errorNuevaContrasenaRecuperarContrasena.textContent="";

        if(nuevaContrasena===""){
            elementsForm.errorNuevaContrasenaRecuperarContrasena.textContent="Por favor, ingrese su nueva contraseña";
            elementsForm.errorNuevaContrasenaRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
        
        if(nuevaContrasena.length<6){
            elementsForm.errorNuevaContrasenaRecuperarContrasena.textContent="La nueva contraseña debe tener al menos 6 caracteres";
            elementsForm.errorNuevaContrasenaRecuperarContrasena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    }) 
}

//Si existe el elemento del formulario para verificar el correo
if(elementsForm.formularioVerificarCorreo){
    elementsForm.formularioVerificarCorreo.addEventListener("submit", function(event){
        let codigoVerificacion=elementsForm.codigoVerificacionVerificarCorreo.value.trim();
        elementsForm.errorCodigoVerificacionVerificarCorreo.textContent="";

        if(codigoVerificacion===""){
            elementsForm.errorCodigoVerificacionVerificarCorreo.textContent="Por favor, ingrese el código de verificación";
            elementsForm.errorCodigoVerificacionVerificarCorreo.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(codigoVerificacion)){
            elementsForm.errorCodigoVerificacionVerificarCorreo.textContent = "El código de verificación debe ser un valor numérico";
            elementsForm.errorCodigoVerificacionVerificarCorreo.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(codigoVerificacion.length<4 || codigoVerificacion.length>4){
            elementsForm.errorCodigoVerificacionVerificarCorreo.textContent="El código de verificación debe tener 4 dígitos";
            elementsForm.errorCodigoVerificacionVerificarCorreo.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    })
}

//Si existe el elemento del formulario para solicitar una nueva semilla
if(elementsForm.formularioSolicitarSemilla){
    elementsForm.formularioSolicitarSemilla.addEventListener("submit", function(event){
        let nombreCompleto=elementsForm.nombreCompletoSolicitarSemilla.value.trim(); 
        let correo=elementsForm.correoSolicitarSemilla.value.trim();
        let semilla=elementsForm.semillaSolicitarSemilla.value.trim();
        let mensaje=elementsForm.mensajeSolicitarSemilla.value.trim();

        limpiarErroresSolicitarSemilla();

        if(nombreCompleto===""){
            elementsForm.errorNombreCompletoSolicitarSemilla.textContent="Por favor, ingrese su nombre completo";
            elementsForm.errorNombreCompletoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(nombreCompleto)){
            elementsForm.errorNombreCompletoSolicitarSemilla.textContent="El nombre completo debe ser un valor de texto";
            elementsForm.errorNombreCompletoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(nombreCompleto.length<3 || nombreCompleto.length>50){
            elementsForm.errorNombreCompletoSolicitarSemilla.textContent="Ingrese un nombre completo válido";
            elementsForm.errorNombreCompletoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(correo===""){
            elementsForm.errorCorreoSolicitarSemilla.textContent="Por favor, ingrese su correo electrónico";
            elementsForm.errorCorreoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(correo.length<5){
            elementsForm.errorCorreoSolicitarSemilla.textContent="Ingrese un correo electrónico válido";
            elementsForm.errorCorreoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!regexCorreo.test(correo)){
            elementsForm.errorCorreoSolicitarSemilla.textContent="Ingrese un correo electrónico válido";
            elementsForm.errorCorreoSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(semilla===""){
            elementsForm.errorSemillaSolicitarSemilla.textContent="Por favor, ingrese el nombre de la semilla que desea solicitar";
            elementsForm.errorSemillaSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(semilla)){
            elementsForm.errorSemillaSolicitarSemilla.textContent="El nombre de la semilla debe ser un valor de texto";
            elementsForm.errorSemillaSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(semilla.length<3 || semilla.length>50){
            elementsForm.errorSemillaSolicitarSemilla.textContent="Ingrese un nombre de semilla válido";
            elementsForm.errorSemillaSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(mensaje===""){
            elementsForm.errorMensajeSolicitarSemilla.textContent="Por favor, ingrese un mensaje con los detalles de su solicitud";
            elementsForm.errorMensajeSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(mensaje)){
            elementsForm.errorMensajeSolicitarSemilla.textContent="El mensaje debe ser un valor de texto";
            elementsForm.errorMensajeSolicitarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para enviar una reseña
if(elementsForm.formularioEnviarResena){
    elementsForm.formularioEnviarResena.addEventListener("submit", function(event){
        let nombreCompleto=elementsForm.nombreCompletoEnviarResena.value.trim(); 
        let correo=elementsForm.correoEnviarResena.value.trim();
        let mensaje=elementsForm.mensajeEnviarResena.value.trim();

        limpiarErroresEnviarResena();

        if(nombreCompleto!==""){
            if(nombreCompleto.length<3 || nombreCompleto.length>50){
                elementsForm.errorNombreCompletoEnviarResena.textContent="Ingrese un nombre completo válido";
                elementsForm.errorNombreCompletoEnviarResena.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(!soloTexto.test(nombreCompleto)){
                elementsForm.errorNombreCompletoEnviarResena.textContent="El nombre completo debe ser un valor de texto";
                elementsForm.errorNombreCompletoEnviarResena.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        if(correo===""){
            elementsForm.errorCorreoEnviarResena.textContent="Por favor, ingrese su correo electrónico";
            elementsForm.errorCorreoEnviarResena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(correo.length<5){
            elementsForm.errorCorreoEnviarResena.textContent="Ingrese un correo electrónico válido";
            elementsForm.errorCorreoEnviarResena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!regexCorreo.test(correo)){
            elementsForm.errorCorreoEnviarResena.textContent="Ingrese un correo electrónico válido";
            elementsForm.errorCorreoEnviarResena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(mensaje===""){
            elementsForm.errorMensajeEnviarResena.textContent="Por favor, ingrese un mensaje con su reseña o comentario";
            elementsForm.errorMensajeEnviarResena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(mensaje)){
            elementsForm.errorMensajeEnviarResena.textContent="El mensaje debe ser un valor de texto";
            elementsForm.errorMensajeEnviarResena.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    })   
}

//Si existe el elemento del formulario para agregar una jardinera
if(elementsForm.formularioAgregarJardinera){
    elementsForm.formularioAgregarJardinera.addEventListener("submit", function(event){

        let nombreJardinera=elementsForm.nombreJardineraAgregarJardinera.value.trim();
        let semilla=elementsForm.semillaAgregarJardinera.value.trim();
        let descripcion=elementsForm.descripcionAgregarJardinera.value.trim();

        limpiarErroresAgregarJardinera();

        if(nombreJardinera===""){
            elementsForm.errorNombreJardineraAgregarJardinera.textContent="Por favor, ingrese el nombre de la jardinera";
            event.preventDefault();
            return;
        }

        if(nombreJardinera.length<3 || nombreJardinera.length>50){
            elementsForm.errorNombreJardineraAgregarJardinera.textContent="Ingrese un nombre válido para la jardinera";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(nombreJardinera)){
            elementsForm.errorNombreJardineraAgregarJardinera.textContent="El nombre de la jardinera debe ser un valor de texto";
            event.preventDefault();
            return;
        }

        if(semilla==="0"){
            elementsForm.errorSemillaAgregarJardinera.textContent="Por favor, seleccione una semilla para la jardinera";
            elementsForm.errorSemillaAgregarJardinera.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(descripcion===""){
            elementsForm.errorDescripcionAgregarJardinera.textContent="Por favor, ingrese una descripción para la jardinera";
            elementsForm.errorDescripcionAgregarJardinera.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(descripcion)){
            elementsForm.errorDescripcionAgregarJardinera.textContent="La descripción debe ser un valor de texto";
            elementsForm.errorDescripcionAgregarJardinera.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(descripcion.length<10 || descripcion.length>200){
            elementsForm.errorDescripcionAgregarJardinera.textContent="Ingrese una descripción válida (entre 10 y 200 caracteres)";
            elementsForm.errorDescripcionAgregarJardinera.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    })
}

//Si existe el elemento del formulario para actualizar el perfil
if(elementsForm.formularioActualizarPerfil){
    elementsForm.formularioActualizarPerfil.addEventListener("submit", function(event){
        let nombreCompleto=elementsForm.nombreCompletoActualizarPerfil.value.trim();
        let tipoDocumento=elementsForm.tipoDocumentoActualizarPerfil.value.trim();
        let correo=elementsForm.correoActualizarPerfil.value.trim();
        let barrio=elementsForm.barrioActualizarPerfil.value.trim();
        let contrasena=elementsForm.contrasenaActualizarPerfil.value.trim();
        let confirmarContrasena=elementsForm.confirmarContrasenaActualizarPerfilUsuario.value.trim();

        limpiarErroresActualizarPerfil();

        if(nombreCompleto!==""){
            if(!soloTexto.test(nombreCompleto)){
                elementsForm.errorNombreCompletoActualizarPerfil.textContent="El nombre debe ser un valor de texto";
                elementsForm.errorNombreCompletoActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombreCompleto.length<3 || nombreCompleto.length>50){
                elementsForm.errorNombreCompletoActualizarPerfil.textContent="Ingrese un nombre válido";
                elementsForm.errorNombreCompletoActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        if(correo!==""){
            if(correo.length<5 || !regexCorreo.test(correo)){
                elementsForm.errorCorreoActualizarPerfil.textContent="Ingrese un correo electrónico válido";
                elementsForm.errorCorreoActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        if(barrio!==""){
            if(!soloTexto.test(barrio)){
                elementsForm.errorBarrioActualizarPerfil.textContent="El barrio o localidad solo debe contener letras";
                elementsForm.errorBarrioActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
            if(barrio.length<3){
                elementsForm.errorBarrioActualizarPerfil.textContent="Ingrese un barrio o localidad válido";
                elementsForm.errorBarrioActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        if(contrasena!==""){
            if(contrasena.length<6){
                elementsForm.errorContrasenaActualizarPerfil.textContent="La contraseña debe tener al menos 6 caracteres";
                elementsForm.errorContrasenaActualizarPerfil.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            // Al menos una mayúscula
            if(!/[A-Z]/.test(contrasena)){
                elementsForm.errorContrasenaActualizarPerfil.textContent = "La contraseña debe contener al menos una letra mayúscula";
                elementsForm.errorContrasenaActualizarPerfil.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos una minúscula
            if(!/[a-z]/.test(contrasena)){
                elementsForm.errorContrasenaActualizarPerfil.textContent = "La contraseña debe contener al menos una letra minúscula";
                elementsForm.errorContrasenaActualizarPerfil.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos un número
            if(!/[0-9]/.test(contrasena)){
                elementsForm.errorContrasenaActualizarPerfil.textContent = "La contraseña debe contener al menos un número";
                elementsForm.errorContrasenaActualizarPerfil.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos un carácter especial
            if(!/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(contrasena)){
                elementsForm.errorContrasenaActualizarPerfil.textContent = "La contraseña debe contener al menos un carácter especial";
                elementsForm.errorContrasenaActualizarPerfil.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(confirmarContrasena===""){
                elementsForm.errorConfirmarContrasenaActualizarPerfilUsuario.textContent="Por favor, confirme su contraseña";
                elementsForm.errorConfirmarContrasenaActualizarPerfilUsuario.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(confirmarContrasena!==contrasena){
                elementsForm.errorConfirmarContrasenaActualizarPerfilUsuario.textContent="Las contraseñas no coinciden";
                elementsForm.errorConfirmarContrasenaActualizarPerfilUsuario.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }
    })
}

//Si existe el elemento del formulario para actualizar la jardinera
if(elementsForm.formularioActualizarJardinera){
    elementsForm.formularioActualizarJardinera.addEventListener("submit", function(event){
        let nombreJardinera=elementsForm.nombreJardineraActualizarJardinera.value.trim();
        let descripcion=elementsForm.descripcionActualizarJardinera.value.trim();

        limpiarErroresActualizarJardinera();

        if(nombreJardinera!==""){
            if(nombreJardinera.length<3 || nombreJardinera.length>50){
                elementsForm.errorNombreJardineraActualizarJardinera.textContent="Ingrese un nombre de jardinera válido";
                elementsForm.errorNombreJardineraActualizarJardinera.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
            
            if(!soloTexto.test(nombreJardinera)){
                elementsForm.errorNombreJardineraActualizarJardinera.textContent="El nombre de la jardinera debe ser un valor de texto";
                elementsForm.errorNombreJardineraActualizarJardinera.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        if(descripcion!==""){
            if(descripcion.length<10 || descripcion.length>200){
                elementsForm.errorDescripcionActualizarJardinera.textContent="Ingrese una descripción válida (entre 10 y 200 caracteres)";
                elementsForm.errorDescripcionActualizarJardinera.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(!soloTexto.test(descripcion)){
                elementsForm.errorDescripcionActualizarJardinera.textContent="La descripción debe ser un valor de texto";
                elementsForm.errorDescripcionActualizarJardinera.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }
    })
}

//Si existe el elemento del formulario para enviar una solicitud de varios tipos
if(elementsForm.formularioEnviarSolicitud){
    elementsForm.formularioEnviarSolicitud.addEventListener("submit", function(event){

        let tipoSolicitud=elementsForm.tipoSolicitudEnviarSolicitud.value.trim(); 
        let nuevaSemilla=elementsForm.nuevaSemillaEnviarSolicitud.value; 
        let descripcion=elementsForm.descripcionEnviarSolicitud.value;

        limpiarErroresEnviarSolicitud();

        if(tipoSolicitud===""){
            elementsForm.errorTipoSolicitudEnviarSolicitud.textContent="Por favor, seleccione un tipo de solicitud"; 
            elementsForm.errorTipoSolicitudEnviarSolicitud.style.marginBottom="10px"; 
            event.preventDefault(); 
            return; 
        }

        if(tipoSolicitud==="Admisión Nueva Semilla"){
            if(nuevaSemilla!==""){
                if(!soloTexto.test(nuevaSemilla)){
                    elementsForm.errorNuevaSemillaEnviarSolicitud.textContent="La nueva semilla debe ser un valor de texto"; 
                    elementsForm.errorNuevaSemillaEnviarSolicitud.style.marginBottom="10px"; 
                    event.preventDefault(); 
                    return; 
                }

                if(nuevaSemilla.length<3 || nuevaSemilla.length >50){
                    elementsForm.errorNuevaSemillaEnviarSolicitud.textContent="Ingrese una nueva semilla válida"; 
                    elementsForm.errorNuevaSemillaEnviarSolicitud.style.marginBottom="10px"; 
                    event.preventDefault(); 
                    return; 
                }
            }
        }

        if(descripcion===""){
            elementsForm.errorDescripcionEnviarSolicitud.textContent="Por favor, ingrese una descripción para su solicitud"; 
            elementsForm.errorDescripcionEnviarSolicitud.style.marginBottom="10px"; 
            event.preventDefault(); 
            return; 
        }

        if(!soloTexto.test(descripcion)){
            elementsForm.errorDescripcionEnviarSolicitud.textContent="La descripción debe ser un valor de texto"; 
            elementsForm.errorDescripcionEnviarSolicitud.style.marginBottom="10px"; 
            event.preventDefault(); 
            return; 
        }

        if(descripcion.length<10 || descripcion.length>200){
            elementsForm.errorDescripcionEnviarSolicitud.textContent="Ingrese una descripción válida (entre 10 y 200 caracteres)";
            elementsForm.errorDescripcionEnviarSolicitud.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    })
}

//Si existe el elemento del formulario para agregar factores externos
if(elementsForm.formularioAgregarFactoresExternos){
    elementsForm.formularioAgregarFactoresExternos.addEventListener("submit", function(){
        let humedad=elementsForm.humedadAgregarFactor.value; 
        let cantidadAgua=elementsForm.cantidadAguaAgregarFactor.value; 
        let temperatura=elementsForm.temperaturaAgregarFactor.value; 
        let clima=elementsForm.climaAgregarFactor.value; 

        limpiarErroresAgregarFactoresExternos();

        if(humedad===""){
            elementsForm.errorHumedadAgregarFactor.textContent = "Por favor, ingrese la húmedad de su jardinera";
            elementsForm.errorHumedadAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(Number(humedad))){
            elementsForm.errorHumedadAgregarFactor.textContent = "La húmedad debe ser un valor numérico";
            elementsForm.errorHumedadAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(humedad.length>10){
            elementsForm.errorHumedadAgregarFactor.textContent = "Ingrese un valor válido para la humedad";
            elementsForm.errorHumedadAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(cantidadAgua===""){
            elementsForm.errorCantidadAguaAgregarFactor.textContent = "Por favor, ingrese la cantidad de agua de su jardinera";
            elementsForm.errorCantidadAguaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(Number(humedad))){
            elementsForm.errorCantidadAguaAgregarFactor.textContent = "La cantidad de agua debe ser un valor numérico";
            elementsForm.errorCantidadAguaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(cantidadAgua.length>10){
            elementsForm.errorCantidadAguaAgregarFactor.textContent = "Ingrese un valor válido para la cantidad de agua";
            elementsForm.errorCantidadAguaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(temperatura===""){
            elementsForm.errorTemperaturaAgregarFactor.textContent = "Por favor, ingrese la temperatura de su jardinera";
            elementsForm.errorTemperaturaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(isNaN(Number(humedad))){
            elementsForm.errorTemperaturaAgregarFactor.textContent = "La temperatura debe ser un valor numérico";
            elementsForm.errorTemperaturaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(temperatura.length>10){
            elementsForm.errorTemperaturaAgregarFactor.textContent = "Ingrese un valor válido para la temperatura";
            elementsForm.errorTemperaturaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(clima==="0"){
            elementsForm.errorClimaAgregarFactor.textContent = "Por favor, seleccione un clima para su jardinera";
            elementsForm.errorClimaAgregarFactor.style.marginBottom="10px";
            event.preventDefault();
            return;
        }
    })
}

//Si existe el elemento del formulario para agregar una evolucion de la jardinera
if(elementsForm.formularioAgregarEvolucion){
    elementsForm.formularioAgregarEvolucion.addEventListener("submit", function(){

        elementsForm.errorPreguntasAgregarEvolucion.textContent="";

        // Obtiene todos los grupos de radios
        let preguntas = document.querySelectorAll(".vf-item");

        let todasRespondidas = true;

        preguntas.forEach(function(pregunta){

            let seleccionado = pregunta.querySelector('input[type="radio"]:checked');

            if(!seleccionado){
                todasRespondidas = false;
            }

        });

        if(!todasRespondidas){

            elementsForm.errorPreguntasAgregarEvolucion.textContent = "Debe responder todas las preguntas";
            elementsForm.errorPreguntasAgregarEvolucion.style.marginBottom = "10px";

            event.preventDefault();
            return;
        }
    }) 
}

// === HOME ADMIN ==

//Si existe el elemento del formulario para actualizar el perfil del administrador
if(elementsAdminForm.formularioActualizarPerfilAdmin){
    console.log("hola");
    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarPerfilAdmin.addEventListener("submit", function(event){
        console.log("hola2");

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombreCompleto=elementsAdminForm.nombreCompletoActualizarPerfilAdmin.value.trim();
        let tipoDocumento=elementsAdminForm.tipoDocumentoActualizarPerfilAdmin.value.trim();
        let correoElectronico=elementsAdminForm.correoActualizarPerfilAdmin.value.trim();
        let barrio=elementsAdminForm.barrioActualizarPerfilAdmin.value.trim();
        let contrasena=elementsAdminForm.contrasenaActualizarPerfilAdmin.value.trim();
        let confirmarContrasena=elementsAdminForm.confirmarContrasenaActualizarPerfilAdmin.value.trim();
    
        //Limpiar los errores de cada elemento
        limpiarErroresActualizarPerfilAdmin();

        //Validar nombre completo
        if(nombreCompleto!==""){
            if(!soloTexto.test(nombreCompleto)){
                elementsAdminForm.errorNombreCompletoActualizarPerfilAdmin.textContent="El nombre completo debe ser un valor de texto";
                elementsAdminForm.errorNombreCompletoActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombreCompleto.length<3){
                elementsAdminForm.errorNombreCompletoActualizarPerfilAdmin.textContent="El nombre completo debe tener al menos 3 caracteres";
                elementsAdminForm.errorNombreCompletoActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        //Validar correo electrónico
        if(correoElectronico!==""){
            if(!regexCorreo.test(correoElectronico)){
                elementsAdminForm.errorCorreoActualizarPerfilAdmin.textContent="Por favor, ingrese un correo electrónico válido";
                elementsAdminForm.errorCorreoActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        //Validar barrio o localidad
        if(barrio!==""){
            if(!soloTexto.test(barrio)){
                elementsAdminForm.errorBarrioActualizarPerfilAdmin.textContent="El barrio o localidad debe ser un valor de texto";
                elementsAdminForm.errorBarrioActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(barrio.length<3){
                elementsAdminForm.errorBarrioActualizarPerfilAdmin.textContent="El barrio o localidad debe tener al menos 3 caracteres";
                elementsAdminForm.errorBarrioActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }
        }

        //Validar contraseña
        if(contrasena!==""){
            if(contrasena.length<6){
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent="La contraseña debe tener al menos 6 caracteres";
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            // Al menos una mayúscula
            if(!/[A-Z]/.test(contrasena)){
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent = "La contraseña debe contener al menos una letra mayúscula";
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos una minúscula
            if(!/[a-z]/.test(contrasena)){
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent = "La contraseña debe contener al menos una letra minúscula";
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos un número
            if(!/[0-9]/.test(contrasena)){
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent = "La contraseña debe contener al menos un número";
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            // Al menos un carácter especial
            if(!/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(contrasena)){
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.textContent = "La contraseña debe contener al menos un carácter especial";
                elementsAdminForm.errorContrasenaActualizarPerfilAdmin.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(confirmarContrasena===""){
                elementsAdminForm.errorConfirmarContrasenaActualizarPerfilAdmin.textContent="Por favor, confirme su contraseña";
                event.preventDefault();
                return;
            }

            if(confirmarContrasena!==contrasena){
                elementsAdminForm.errorConfirmarContrasenaActualizarPerfilAdmin.textContent="Las contraseñas no coinciden";
                event.preventDefault();
                return;
            }
        } 
    });
}

//Si existe el elemento del formulario para actualizar perfil de usuario
if(elementsAdminForm.formularioActualizarPerfilUsuario){
    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarPerfilUsuario.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombreCompleto = elementsAdminForm.nombreCompletoActualizarPerfilUsuario.value.trim();
        let tipoUsuario = elementsAdminForm.tipoUsuarioActualizarPerfilUsuario.value.trim();
        let tipoDocumento = elementsAdminForm.tipoDocumentoActualizarPerfilUsuario.value.trim();
        let correo = elementsAdminForm.correoActualizarPerfilUsuario.value.trim();
        let estadoCorreo = elementsAdminForm.estadoCorreoActualizarPerfilUsuario.value.trim();
        let barrio = elementsAdminForm.barrioActualizarPerfilUsuario.value.trim();
        let avatar = elementsAdminForm.avatarActualizarPerfilUsuario.value.trim();
        let cantidadJardineras = elementsAdminForm.cantidadJardinerasActualizarPerfilUsuario.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarPerfilUsuario();

        //Validar nombre completo
        if(nombreCompleto !== ""){
            if(!soloTexto.test(nombreCompleto)){
                elementsAdminForm.errorNombreCompletoActualizarPerfilUsuario.textContent="El nombre completo debe ser un valor de texto";
                elementsAdminForm.errorNombreCompletoActualizarPerfilUsuario.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombreCompleto.length < 3){
                elementsAdminForm.errorNombreCompletoActualizarPerfilUsuario.textContent = "El nombre completo debe tener al menos 3 caracteres";
                elementsAdminForm.errorNombreCompletoActualizarPerfilUsuario.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar correo
        if(correo !== ""){
            if(!regexCorreo.test(correo)){
                elementsAdminForm.errorCorreoActualizarPerfilUsuario.textContent = "Ingrese un correo electrónico válido";
                elementsAdminForm.errorCorreoActualizarPerfilUsuario.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar barrio
        if(barrio !== ""){
            if(!soloTexto.test(barrio)){
                elementsAdminForm.errorBarrioActualizarPerfilUsuario.textContent="El barrio o localidad debe ser un valor de texto";
                elementsAdminForm.errorBarrioActualizarPerfilUsuario.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(barrio.length < 3){
                elementsAdminForm.errorBarrioActualizarPerfilUsuario.textContent = "El barrio o localidad debe tener al menos 3 caracteres";
                elementsAdminForm.errorBarrioActualizarPerfilUsuario.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cantidad de jardineras
        if(cantidadJardineras !== ""){
            if(isNaN(cantidadJardineras)){
                elementsAdminForm.errorCantidadJardinerasActualizarPerfilUsuario.textContent = "La cantidad de jardineras debe ser un valor numérico";
                elementsAdminForm.errorCantidadJardinerasActualizarPerfilUsuario.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(cantidadJardineras) < 0){
                elementsAdminForm.errorCantidadJardinerasActualizarPerfilUsuario.textContent = "La cantidad de jardineras no puede ser negativa";
                elementsAdminForm.errorCantidadJardinerasActualizarPerfilUsuario.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }        
    });
}

//Si existe el elemento del formulario para agregar tipo de documento
if(elementsAdminForm.formularioAgregarTipoDocumento){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarTipoDocumento.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionAgregarTipoDocumento.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarTipoDocumento();

        //Validar descripción
        if(descripcion === ""){
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.textContent = "Por favor, ingrese la descripción del tipo de documento";
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud mínima
        if(descripcion.length < 3){
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.textContent = "La descripción debe tener al menos 3 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud máxima
        if(descripcion.length > 50){
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.textContent = "La descripción no puede superar los 50 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoDocumento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar tipo de documento
if(elementsAdminForm.formularioActualizarTipoDocumento){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarTipoDocumento.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionActualizarTipoDocumento.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarTipoDocumento();

        //Validar descripción
        if(descripcion !== ""){
            //Validar longitud mínima
            if(descripcion.length < 3){
                elementsAdminForm.errorDescripcionActualizarTipoDocumento.textContent = "La descripción debe tener al menos 3 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoDocumento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            //Validar longitud máxima
            if(descripcion.length > 50){
                elementsAdminForm.errorDescripcionActualizarTipoDocumento.textContent = "La descripción no puede superar los 50 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoDocumento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }   
    });
}

//Si existe el elemento del formulario para agregar semilla
if(elementsAdminForm.formularioAgregarSemilla){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarSemilla.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombre = elementsAdminForm.nombreAgregarSemilla.value.trim();
        let imagen = elementsAdminForm.imagenAgregarSemilla.value.trim();
        let observaciones = elementsAdminForm.observacionesAgregarSemilla.value.trim();
        let tipoSemilla = elementsAdminForm.tipoSemillaAgregarSemilla.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarSemilla();

        //Validar nombre
        if(nombre === ""){
            elementsAdminForm.errorNombreAgregarSemilla.textContent = "Por favor, ingrese el nombre de la semilla";
            elementsAdminForm.errorNombreAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(nombre)){
            elementsAdminForm.errorNombreAgregarSemilla.textContent="El nombre de la semilla debe ser un valor de texto";
            elementsAdminForm.errorNombreAgregarSemilla.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(nombre.length < 3){
            elementsAdminForm.errorNombreAgregarSemilla.textContent = "El nombre de la semilla debe tener al menos 3 caracteres";
            elementsAdminForm.errorNombreAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(nombre.length > 50){
            elementsAdminForm.errorNombreAgregarSemilla.textContent = "El nombre de la semilla no puede superar los 50 caracteres";
            elementsAdminForm.errorNombreAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar imagen
        if(imagen === ""){
            elementsAdminForm.errorImagenAgregarSemilla.textContent = "Por favor, seleccione una imagen";
            elementsAdminForm.errorImagenAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar observaciones
        if(observaciones === ""){
            elementsAdminForm.errorObservacionesAgregarSemilla.textContent = "Por favor, ingrese las observaciones";
            elementsAdminForm.errorObservacionesAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(observaciones.length < 5){
            elementsAdminForm.errorObservacionesAgregarSemilla.textContent = "Las observaciones deben tener al menos 5 caracteres";
            elementsAdminForm.errorObservacionesAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(observaciones.length > 255){
            elementsAdminForm.errorObservacionesAgregarSemilla.textContent = "Las observaciones no pueden superar los 255 caracteres";
            elementsAdminForm.errorObservacionesAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar tipo de semilla
        if(tipoSemilla === "0"){
            elementsAdminForm.errorTipoSemillaAgregarSemilla.textContent = "Por favor, seleccione un tipo de semilla";
            elementsAdminForm.errorTipoSemillaAgregarSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

    });
}

//Si existe el elemento del formulario para actualizar semilla
if(elementsAdminForm.formularioActualizarSemilla){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarSemilla.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombre = elementsAdminForm.nombreActualizarSemilla.value.trim();
        let imagen = elementsAdminForm.imagenActualizarSemilla.value.trim();
        let observaciones = elementsAdminForm.observacionesActualizarSemilla.value.trim();
        let tipoSemilla = elementsAdminForm.tipoSemillaActualizarSemilla.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarSemilla();

        //Validar nombre
        if(nombre !== ""){
            if(!soloTexto.test(nombre)){
                elementsAdminForm.errorNombreActualizarSemilla.textContent="El nombre de la semilla debe ser un valor de texto";
                elementsAdminForm.errorNombreActualizarSemilla.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombre.length < 3){
                elementsAdminForm.errorNombreActualizarSemilla.textContent = "El nombre de la semilla debe tener al menos 3 caracteres";
                elementsAdminForm.errorNombreActualizarSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(nombre.length > 50){
                elementsAdminForm.errorNombreActualizarSemilla.textContent = "El nombre de la semilla no puede superar los 50 caracteres";
                elementsAdminForm.errorNombreActualizarSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar observaciones
        if(observaciones !== ""){
            if(observaciones.length < 5){
                elementsAdminForm.errorObservacionesActualizarSemilla.textContent = "Las observaciones deben tener al menos 5 caracteres";
                elementsAdminForm.errorObservacionesActualizarSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(observaciones.length > 255){
                elementsAdminForm.errorObservacionesActualizarSemilla.textContent = "Las observaciones no pueden superar los 255 caracteres";
                elementsAdminForm.errorObservacionesActualizarSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para agregar tipo de semilla
if(elementsAdminForm.formularioAgregarTipoSemilla){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarTipoSemilla.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionAgregarTipoSemilla.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarTipoSemilla();

        //Validar descripción
        if(descripcion === ""){
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.textContent = "Por favor, ingrese la descripción del tipo de semilla";
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud mínima
        if(descripcion.length < 3){
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.textContent = "La descripción del tipo de semilla debe tener al menos 3 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud máxima
        if(descripcion.length > 50){
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.textContent = "La descripción del tipo de semilla no puede superar los 50 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoSemilla.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar tipo de semilla
if(elementsAdminForm.formularioActualizarTipoSemilla){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarTipoSemilla.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionActualizarTipoSemilla.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarTipoSemilla();

        //Validar descripción
        if(descripcion !== ""){
            //Validar longitud mínima
            if(descripcion.length < 3){
                elementsAdminForm.errorDescripcionActualizarTipoSemilla.textContent = "La descripción del tipo de semilla debe tener al menos 3 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            //Validar longitud máxima
            if(descripcion.length > 50){
                elementsAdminForm.errorDescripcionActualizarTipoSemilla.textContent = "La descripción del tipo de semilla no puede superar los 50 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoSemilla.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para agregar ficha técnica
if(elementsAdminForm.formularioAgregarFichaTecnica){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarFichaTecnica.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let semilla = elementsAdminForm.semillaAgregarFichaTecnica.value.trim();
        let tipoClima = elementsAdminForm.tipoClimaAgregarFichaTecnica.value.trim();
        let temperaturaMinima = elementsAdminForm.temperaturaMinimaAgregarFichaTecnica.value.trim();
        let temperaturaMaxima = elementsAdminForm.temperaturaMaximaAgregarFichaTecnica.value.trim();
        let humedadMinima = elementsAdminForm.humedadMinimaAgregarFichaTecnica.value.trim();
        let humedadMaxima = elementsAdminForm.humedadMaximaAgregarFichaTecnica.value.trim();
        let cantidadAguaMinima = elementsAdminForm.cantidadAguaMinimaAgregarFichaTecnica.value.trim();
        let cantidadAguaMaxima = elementsAdminForm.cantidadAguaMaximaAgregarFichaTecnica.value.trim();
        let tipoTierra = elementsAdminForm.tipoTierraAgregarFichaTecnica.value.trim();
        let cantidadTierraMinima = elementsAdminForm.cantidadTierraMinimaAgregarFichaTecnica.value.trim();
        let cantidadTierraMaxima = elementsAdminForm.cantidadTierraMaximaAgregarFichaTecnica.value.trim();
        let espacio = elementsAdminForm.espacioAgregarFichaTecnica.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarFichaTecnica();

        //Validar semilla
        if(semilla === "" || semilla === "0"){
            elementsAdminForm.errorSemillaAgregarFichaTecnica.textContent = "Por favor, seleccione una semilla";
            elementsAdminForm.errorSemillaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar tipo de clima
        if(tipoClima === "" || tipoClima === "0"){
            elementsAdminForm.errorTipoClimaAgregarFichaTecnica.textContent = "Por favor, seleccione un tipo de clima";
            elementsAdminForm.errorTipoClimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar temperatura mínima
        if(temperaturaMinima === ""){
            elementsAdminForm.errorTemperaturaMinimaAgregarFichaTecnica.textContent = "Por favor, ingrese la temperatura mínima";
            elementsAdminForm.errorTemperaturaMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(temperaturaMinima)){
            elementsAdminForm.errorTemperaturaMinimaAgregarFichaTecnica.textContent = "La temperatura mínima debe ser un valor numérico";
            elementsAdminForm.errorTemperaturaMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar temperatura máxima
        if(temperaturaMaxima === ""){
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.textContent = "Por favor, ingrese la temperatura máxima";
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(temperaturaMaxima)){
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.textContent = "La temperatura máxima debe ser un valor numérico";
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(temperaturaMaxima) < Number(temperaturaMinima)){
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.textContent = "La temperatura máxima no puede ser menor que la mínima";
            elementsAdminForm.errorTemperaturaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar humedad mínima
        if(humedadMinima === ""){
            elementsAdminForm.errorHumedadMinimaAgregarFichaTecnica.textContent = "Por favor, ingrese la humedad mínima";
            elementsAdminForm.errorHumedadMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(humedadMinima)){
            elementsAdminForm.errorHumedadMinimaAgregarFichaTecnica.textContent = "La humedad mínima debe ser un valor numérico";
            elementsAdminForm.errorHumedadMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar humedad máxima
        if(humedadMaxima === ""){
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.textContent = "Por favor, ingrese la humedad máxima";
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(humedadMaxima)){
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.textContent = "La humedad máxima debe ser un valor numérico";
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(humedadMaxima) < Number(humedadMinima)){
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.textContent = "La humedad máxima no puede ser menor que la mínima";
            elementsAdminForm.errorHumedadMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cantidad de agua mínima
        if(cantidadAguaMinima === ""){
            elementsAdminForm.errorCantidadAguaMinimaAgregarFichaTecnica.textContent = "Por favor, ingrese la cantidad de agua mínima";
            elementsAdminForm.errorCantidadAguaMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cantidadAguaMinima)){
            elementsAdminForm.errorCantidadAguaMinimaAgregarFichaTecnica.textContent = "La cantidad de agua mínima debe ser un valor numérico";
            elementsAdminForm.errorCantidadAguaMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cantidad de agua máxima
        if(cantidadAguaMaxima === ""){
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.textContent = "Por favor, ingrese la cantidad de agua máxima";
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cantidadAguaMaxima)){
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.textContent = "La cantidad de agua máxima debe ser un valor numérico";
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(cantidadAguaMaxima) < Number(cantidadAguaMinima)){
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.textContent = "La cantidad de agua máxima no puede ser menor que la mínima";
            elementsAdminForm.errorCantidadAguaMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar tipo de tierra
        if(tipoTierra === "" || tipoTierra === "0"){
            elementsAdminForm.errorTipoTierraAgregarFichaTecnica.textContent = "Por favor, seleccione un tipo de tierra";
            elementsAdminForm.errorTipoTierraAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cantidad de tierra mínima
        if(cantidadTierraMinima === ""){
            elementsAdminForm.errorCantidadTierraMinimaAgregarFichaTecnica.textContent = "Por favor, ingrese la cantidad de tierra mínima";
            elementsAdminForm.errorCantidadTierraMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cantidadTierraMinima)){
            elementsAdminForm.errorCantidadTierraMinimaAgregarFichaTecnica.textContent = "La cantidad de tierra mínima debe ser un valor numérico";
            elementsAdminForm.errorCantidadTierraMinimaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cantidad de tierra máxima
        if(cantidadTierraMaxima === ""){
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.textContent = "Por favor, ingrese la cantidad de tierra máxima";
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cantidadTierraMaxima)){
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.textContent = "La cantidad de tierra máxima debe ser un valor numérico";
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(cantidadTierraMaxima) < Number(cantidadTierraMinima)){
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.textContent = "La cantidad de tierra máxima no puede ser menor que la mínima";
            elementsAdminForm.errorCantidadTierraMaximaAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar espacio
        if(espacio === ""){
            elementsAdminForm.errorEspacioAgregarFichaTecnica.textContent = "Por favor, ingrese el espacio";
            elementsAdminForm.errorEspacioAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(espacio)){
            elementsAdminForm.errorEspacioAgregarFichaTecnica.textContent = "El espacio debe ser un valor numérico";
            elementsAdminForm.errorEspacioAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(espacio) <= 0){
            elementsAdminForm.errorEspacioAgregarFichaTecnica.textContent = "El espacio debe ser mayor a cero";
            elementsAdminForm.errorEspacioAgregarFichaTecnica.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar ficha técnica
if(elementsAdminForm.formularioActualizarFichaTecnica){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarFichaTecnica.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let tipoClima = elementsAdminForm.tipoClimaActualizarFichaTecnica.value.trim();
        let temperaturaMinima = elementsAdminForm.temperaturaMinimaActualizarFichaTecnica.value.trim();
        let temperaturaMaxima = elementsAdminForm.temperaturaMaximaActualizarFichaTecnica.value.trim();
        let humedadMinima = elementsAdminForm.humedadMinimaActualizarFichaTecnica.value.trim();
        let humedadMaxima = elementsAdminForm.humedadMaximaActualizarFichaTecnica.value.trim();
        let cantidadAguaMinima = elementsAdminForm.cantidadAguaMinimaActualizarFichaTecnica.value.trim();
        let cantidadAguaMaxima = elementsAdminForm.cantidadAguaMaximaActualizarFichaTecnica.value.trim();
        let tipoTierra = elementsAdminForm.tipoTierraActualizarFichaTecnica.value.trim();
        let cantidadTierraMinima = elementsAdminForm.cantidadTierraMinimaActualizarFichaTecnica.value.trim();
        let cantidadTierraMaxima = elementsAdminForm.cantidadTierraMaximaActualizarFichaTecnica.value.trim();
        let espacio = elementsAdminForm.espacioActualizarFichaTecnica.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarFichaTecnica();

        //Validar temperatura mínima
        if(temperaturaMinima !== ""){
            if(isNaN(temperaturaMinima)){
                elementsAdminForm.errorTemperaturaMinimaActualizarFichaTecnica.textContent = "La temperatura mínima debe ser un valor numérico";
                elementsAdminForm.errorTemperaturaMinimaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar temperatura máxima
        if(temperaturaMaxima !== ""){
            if(isNaN(temperaturaMaxima)){
                elementsAdminForm.errorTemperaturaMaximaActualizarFichaTecnica.textContent = "La temperatura máxima debe ser un valor numérico";
                elementsAdminForm.errorTemperaturaMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(temperaturaMaxima) < Number(temperaturaMinima)){
                elementsAdminForm.errorTemperaturaMaximaActualizarFichaTecnica.textContent = "La temperatura máxima no puede ser menor que la mínima";
                elementsAdminForm.errorTemperaturaMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }    

        //Validar humedad mínima
        if(humedadMinima !== ""){
            if(isNaN(humedadMinima)){
                elementsAdminForm.errorHumedadMinimaActualizarFichaTecnica.textContent = "La humedad mínima debe ser un valor numérico";
                elementsAdminForm.errorHumedadMinimaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
        
        //Validar humedad máxima
        if(humedadMaxima !== ""){
            if(isNaN(humedadMaxima)){
                elementsAdminForm.errorHumedadMaximaActualizarFichaTecnica.textContent = "La humedad máxima debe ser un valor numérico";
                elementsAdminForm.errorHumedadMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(humedadMaxima) < Number(humedadMinima)){
                elementsAdminForm.errorHumedadMaximaActualizarFichaTecnica.textContent = "La humedad máxima no puede ser menor que la mínima";
                elementsAdminForm.errorHumedadMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cantidad de agua mínima
        if(cantidadAguaMinima !== ""){
            if(isNaN(cantidadAguaMinima)){
                elementsAdminForm.errorCantidadAguaMinimaActualizarFichaTecnica.textContent = "La cantidad de agua mínima debe ser un valor numérico";
                elementsAdminForm.errorCantidadAguaMinimaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cantidad de agua máxima
        if(cantidadAguaMaxima !== ""){
            if(isNaN(cantidadAguaMaxima)){
                elementsAdminForm.errorCantidadAguaMaximaActualizarFichaTecnica.textContent = "La cantidad de agua máxima debe ser un valor numérico";
                elementsAdminForm.errorCantidadAguaMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(cantidadAguaMaxima) < Number(cantidadAguaMinima)){
                elementsAdminForm.errorCantidadAguaMaximaActualizarFichaTecnica.textContent = "La cantidad de agua máxima no puede ser menor que la mínima";
                elementsAdminForm.errorCantidadAguaMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
        
        //Validar cantidad de tierra mínima
        if(cantidadTierraMinima !== ""){
            if(isNaN(cantidadTierraMinima)){
                elementsAdminForm.errorCantidadTierraMinimaActualizarFichaTecnica.textContent = "La cantidad de tierra mínima debe ser un valor numérico";
                elementsAdminForm.errorCantidadTierraMinimaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cantidad de tierra máxima
        if(cantidadTierraMaxima !== ""){
            if(isNaN(cantidadTierraMaxima)){
                elementsAdminForm.errorCantidadTierraMaximaActualizarFichaTecnica.textContent = "La cantidad de tierra máxima debe ser un valor numérico";
                elementsAdminForm.errorCantidadTierraMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(cantidadTierraMaxima) < Number(cantidadTierraMinima)){
                elementsAdminForm.errorCantidadTierraMaximaActualizarFichaTecnica.textContent = "La cantidad de tierra máxima no puede ser menor que la mínima";
                elementsAdminForm.errorCantidadTierraMaximaActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar espacio
        if(espacio !== ""){
            if(isNaN(espacio)){
                elementsAdminForm.errorEspacioActualizarFichaTecnica.textContent = "El espacio debe ser un valor numérico";
                elementsAdminForm.errorEspacioActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(espacio) <= 0){
                elementsAdminForm.errorEspacioActualizarFichaTecnica.textContent = "El espacio debe ser mayor a cero";
                elementsAdminForm.errorEspacioActualizarFichaTecnica.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para agregar tipo de clima
if(elementsAdminForm.formularioAgregarTipoClima){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarTipoClima.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionAgregarTipoClima.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarTipoClima();

        //Validar descripción
        if(descripcion === ""){
            elementsAdminForm.errorDescripcionAgregarTipoClima.textContent = "Por favor, ingrese la descripción del tipo de clima";
            elementsAdminForm.errorDescripcionAgregarTipoClima.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud mínima
        if(descripcion.length < 3){
            elementsAdminForm.errorDescripcionAgregarTipoClima.textContent = "La descripción debe tener al menos 3 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoClima.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud máxima
        if(descripcion.length > 50){
            elementsAdminForm.errorDescripcionAgregarTipoClima.textContent = "La descripción no puede superar los 50 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoClima.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar tipo de clima
if(elementsAdminForm.formularioActualizarTipoClima){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarTipoClima.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionActualizarTipoClima.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarTipoClima();

        //Validar descripción
        if(descripcion !== ""){
            //Validar longitud mínima
            if(descripcion.length < 3){
                elementsAdminForm.errorDescripcionActualizarTipoClima.textContent = "La descripción debe tener al menos 3 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoClima.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            //Validar longitud máxima
            if(descripcion.length > 50){
                elementsAdminForm.errorDescripcionActualizarTipoClima.textContent = "La descripción no puede superar los 50 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoClima.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(!expresionDescripcion.test(descripcion)){
                elementsAdminForm.errorDescripcionActualizarTipoClima.textContent = "La descripción solo puede contener letras y espacios";
                elementsAdminForm.errorDescripcionActualizarTipoClima.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }  
    });
}

//Si existe el elemento del formulario para agregar tipo de tierra
if(elementsAdminForm.formularioAgregarTipoTierra){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarTipoTierra.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionAgregarTipoTierra.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarTipoTierra();

        //Validar descripción
        if(descripcion === ""){
            elementsAdminForm.errorDescripcionAgregarTipoTierra.textContent = "Por favor, ingrese la descripción del tipo de tierra";
            elementsAdminForm.errorDescripcionAgregarTipoTierra.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud mínima
        if(descripcion.length < 3){
            elementsAdminForm.errorDescripcionAgregarTipoTierra.textContent = "La descripción debe tener al menos 3 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoTierra.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar longitud máxima
        if(descripcion.length > 50){
            elementsAdminForm.errorDescripcionAgregarTipoTierra.textContent = "La descripción no puede superar los 50 caracteres";
            elementsAdminForm.errorDescripcionAgregarTipoTierra.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(!expresionDescripcion.test(descripcion)){
            elementsAdminForm.errorDescripcionAgregarTipoTierra.textContent = "La descripción solo puede contener letras y espacios";
            elementsAdminForm.errorDescripcionAgregarTipoTierra.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar tipo de tierra
if(elementsAdminForm.formularioActualizarTipoTierra){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarTipoTierra.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let descripcion = elementsAdminForm.descripcionActualizarTipoTierra.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarTipoTierra();

        //Validar descripción
        if(descripcion !== ""){
            //Validar longitud mínima
            if(descripcion.length < 3){
                elementsAdminForm.errorDescripcionActualizarTipoTierra.textContent = "La descripción debe tener al menos 3 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoTierra.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            //Validar longitud máxima
            if(descripcion.length > 50){
                elementsAdminForm.errorDescripcionActualizarTipoTierra.textContent = "La descripción no puede superar los 50 caracteres";
                elementsAdminForm.errorDescripcionActualizarTipoTierra.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(!expresionDescripcion.test(descripcion)){
                elementsAdminForm.errorDescripcionActualizarTipoTierra.textContent = "La descripción solo puede contener letras y espacios";
                elementsAdminForm.errorDescripcionActualizarTipoTierra.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

        }
        
    });
}

//Si existe el elemento del formulario para agregar etapas de crecimiento
if(elementsAdminForm.formularioAgregarEtapasCrecimiento){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarEtapasCrecimiento.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let semilla = elementsAdminForm.semillaAgregarEtapasCrecimiento.value.trim();

        let germinacionMinima = elementsAdminForm.germinacionMinimaAgregarEtapasCrecimiento.value.trim();
        let germinacionMaxima = elementsAdminForm.germinacionMaximaAgregarEtapasCrecimiento.value.trim();

        let desarrolloVegetativoMinimo = elementsAdminForm.desarrolloVegetativoMinimoAgregarEtapasCrecimiento.value.trim();
        let desarrolloVegetativoMaximo = elementsAdminForm.desarrolloVegetativoMaximoAgregarEtapasCrecimiento.value.trim();

        let floracionMinima = elementsAdminForm.floracionMinimaAgregarEtapasCrecimiento.value.trim();
        let floracionMaxima = elementsAdminForm.floracionMaximaAgregarEtapasCrecimiento.value.trim();

        let llenadoGranosMinimo = elementsAdminForm.llenadoGranosMinimoAgregarEtapasCrecimiento.value.trim();
        let llenadoGranosMaximo = elementsAdminForm.llenadoGranosMaximoAgregarEtapasCrecimiento.value.trim();

        let cosechaMinima = elementsAdminForm.cosechaMinimaAgregarEtapasCrecimiento.value.trim();
        let cosechaMaxima = elementsAdminForm.cosechaMaximaAgregarEtapasCrecimiento.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarEtapasCrecimiento();

        //Validar semilla
        if(semilla === "" || semilla === "0"){
            elementsAdminForm.errorSemillaAgregarEtapasCrecimiento.textContent = "Por favor, seleccione una semilla";
            elementsAdminForm.errorSemillaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar germinación mínima
        if(germinacionMinima === ""){
            elementsAdminForm.errorGerminacionMinimaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la germinación mínima";
            elementsAdminForm.errorGerminacionMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(germinacionMinima) || Number(germinacionMinima) < 0){
            elementsAdminForm.errorGerminacionMinimaAgregarEtapasCrecimiento.textContent = "La germinación mínima debe ser un número positivo";
            elementsAdminForm.errorGerminacionMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar germinación máxima
        if(germinacionMaxima === ""){
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la germinación máxima";
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(germinacionMaxima) || Number(germinacionMaxima) < 0){
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.textContent = "La germinación máxima debe ser un número positivo";
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(germinacionMaxima) < Number(germinacionMinima)){
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.textContent = "La germinación máxima no puede ser menor que la mínima";
            elementsAdminForm.errorGerminacionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar desarrollo vegetativo mínimo
        if(desarrolloVegetativoMinimo === ""){
            elementsAdminForm.errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento.textContent = "Por favor, ingrese el desarrollo vegetativo mínimo";
            elementsAdminForm.errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(desarrolloVegetativoMinimo) || Number(desarrolloVegetativoMinimo) < 0){
            elementsAdminForm.errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento.textContent = "El desarrollo vegetativo mínimo debe ser un número positivo";
            elementsAdminForm.errorDesarrolloVegetativoMinimoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar desarrollo vegetativo máximo
        if(desarrolloVegetativoMaximo === ""){
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.textContent = "Por favor, ingrese el desarrollo vegetativo máximo";
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(desarrolloVegetativoMaximo) || Number(desarrolloVegetativoMaximo) < 0){
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.textContent = "El desarrollo vegetativo máximo debe ser un número positivo";
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(desarrolloVegetativoMaximo) < Number(desarrolloVegetativoMinimo)){
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.textContent = "El desarrollo vegetativo máximo no puede ser menor que el mínimo";
            elementsAdminForm.errorDesarrolloVegetativoMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar floración mínima
        if(floracionMinima === ""){
            elementsAdminForm.errorFloracionMinimaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la floración mínima";
            elementsAdminForm.errorFloracionMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(floracionMinima) || Number(floracionMinima) < 0){
            elementsAdminForm.errorFloracionMinimaAgregarEtapasCrecimiento.textContent = "La floración mínima debe ser un número positivo";
            elementsAdminForm.errorFloracionMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar floración máxima
        if(floracionMaxima === ""){
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la floración máxima";
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(floracionMaxima) || Number(floracionMaxima) < 0){
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.textContent = "La floración máxima debe ser un número positivo";
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(floracionMaxima) < Number(floracionMinima)){
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.textContent = "La floración máxima no puede ser menor que la mínima";
            elementsAdminForm.errorFloracionMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar llenado de granos mínimo
        if(llenadoGranosMinimo === ""){
            elementsAdminForm.errorLlenadoGranosMinimoAgregarEtapasCrecimiento.textContent = "Por favor, ingrese el llenado de granos mínimo";
            elementsAdminForm.errorLlenadoGranosMinimoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(llenadoGranosMinimo) || Number(llenadoGranosMinimo) < 0){
            elementsAdminForm.errorLlenadoGranosMinimoAgregarEtapasCrecimiento.textContent = "El llenado de granos mínimo debe ser un número positivo";
            elementsAdminForm.errorLlenadoGranosMinimoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar llenado de granos máximo
        if(llenadoGranosMaximo === ""){
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.textContent = "Por favor, ingrese el llenado de granos máximo";
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(llenadoGranosMaximo) || Number(llenadoGranosMaximo) < 0){
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.textContent = "El llenado de granos máximo debe ser un número positivo";
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(llenadoGranosMaximo) < Number(llenadoGranosMinimo)){
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.textContent = "El llenado de granos máximo no puede ser menor que el mínimo";
            elementsAdminForm.errorLlenadoGranosMaximoAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cosecha mínima
        if(cosechaMinima === ""){
            elementsAdminForm.errorCosechaMinimaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la cosecha mínima";
            elementsAdminForm.errorCosechaMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cosechaMinima) || Number(cosechaMinima) < 0){
            elementsAdminForm.errorCosechaMinimaAgregarEtapasCrecimiento.textContent = "La cosecha mínima debe ser un número positivo";
            elementsAdminForm.errorCosechaMinimaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar cosecha máxima
        if(cosechaMaxima === ""){
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.textContent = "Por favor, ingrese la cosecha máxima";
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(cosechaMaxima) || Number(cosechaMaxima) < 0){
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.textContent = "La cosecha máxima debe ser un número positivo";
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(cosechaMaxima) < Number(cosechaMinima)){
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.textContent = "La cosecha máxima no puede ser menor que la mínima";
            elementsAdminForm.errorCosechaMaximaAgregarEtapasCrecimiento.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar etapas de crecimiento
if(elementsAdminForm.formularioActualizarEtapasCrecimiento){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarEtapasCrecimiento.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let germinacionMinima = elementsAdminForm.germinacionMinimaActualizarEtapasCrecimiento.value.trim();
        let germinacionMaxima = elementsAdminForm.germinacionMaximaActualizarEtapasCrecimiento.value.trim();
        let desarrolloVegetativoMinimo = elementsAdminForm.desarrolloVegetativoMinimoActualizarEtapasCrecimiento.value.trim();
        let desarrolloVegetativoMaximo = elementsAdminForm.desarrolloVegetativoMaximoActualizarEtapasCrecimiento.value.trim();
        let floracionMinima = elementsAdminForm.floracionMinimaActualizarEtapasCrecimiento.value.trim();
        let floracionMaxima = elementsAdminForm.floracionMaximaActualizarEtapasCrecimiento.value.trim();
        let llenadoGranosMinimo = elementsAdminForm.llenadoGranosMinimoActualizarEtapasCrecimiento.value.trim();
        let llenadoGranosMaximo = elementsAdminForm.llenadoGranosMaximoActualizarEtapasCrecimiento.value.trim();
        let cosechaMinima = elementsAdminForm.cosechaMinimaActualizarEtapasCrecimiento.value.trim();
        let cosechaMaxima = elementsAdminForm.cosechaMaximaActualizarEtapasCrecimiento.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarEtapasCrecimiento();

        //Validar germinación mínima
        if(germinacionMinima !== ""){
            if(isNaN(germinacionMinima) || Number(germinacionMinima) < 0){
                elementsAdminForm.errorGerminacionMinimaActualizarEtapasCrecimiento.textContent = "La germinación mínima debe ser un valor numérico válido";
                elementsAdminForm.errorGerminacionMinimaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar germinación máxima
        if(germinacionMaxima !== ""){
            if(isNaN(germinacionMaxima) || Number(germinacionMaxima) < 0){
                elementsAdminForm.errorGerminacionMaximaActualizarEtapasCrecimiento.textContent = "La germinación máxima debe ser un valor numérico válido";
                elementsAdminForm.errorGerminacionMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(germinacionMaxima) < Number(germinacionMinima)){
                elementsAdminForm.errorGerminacionMaximaActualizarEtapasCrecimiento.textContent = "La germinación máxima no puede ser menor que la mínima";
                elementsAdminForm.errorGerminacionMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar desarrollo vegetativo mínimo
        if(desarrolloVegetativoMinimo !== ""){
            if(isNaN(desarrolloVegetativoMinimo) || Number(desarrolloVegetativoMinimo) < 0){
                elementsAdminForm.errorDesarrolloVegetativoMinimoActualizarEtapasCrecimiento.textContent = "El desarrollo vegetativo mínimo debe ser un valor numérico válido";
                elementsAdminForm.errorDesarrolloVegetativoMinimoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        } 

        //Validar desarrollo vegetativo máximo
        if(desarrolloVegetativoMaximo !== ""){
            if(isNaN(desarrolloVegetativoMaximo) || Number(desarrolloVegetativoMaximo) < 0){
                elementsAdminForm.errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento.textContent = "El desarrollo vegetativo máximo debe ser un valor numérico válido";
                elementsAdminForm.errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(desarrolloVegetativoMaximo) < Number(desarrolloVegetativoMinimo)){
                elementsAdminForm.errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento.textContent = "El desarrollo vegetativo máximo no puede ser menor que el mínimo";
                elementsAdminForm.errorDesarrolloVegetativoMaximoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }   
        }

        //Validar floración mínima
        if(floracionMinima !== ""){
            if(isNaN(floracionMinima) || Number(floracionMinima) < 0){
                elementsAdminForm.errorFloracionMinimaActualizarEtapasCrecimiento.textContent = "La floración mínima debe ser un valor numérico válido";
                elementsAdminForm.errorFloracionMinimaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar floración máxima
        if(floracionMaxima !== ""){
            if(isNaN(floracionMaxima) || Number(floracionMaxima) < 0){
                elementsAdminForm.errorFloracionMaximaActualizarEtapasCrecimiento.textContent = "La floración máxima debe ser un valor numérico válido";
                elementsAdminForm.errorFloracionMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(floracionMaxima) < Number(floracionMinima)){
                elementsAdminForm.errorFloracionMaximaActualizarEtapasCrecimiento.textContent = "La floración máxima no puede ser menor que la mínima";
                elementsAdminForm.errorFloracionMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar llenado de granos mínimo
        if(llenadoGranosMinimo !== ""){
            if(isNaN(llenadoGranosMinimo) || Number(llenadoGranosMinimo) < 0){
                elementsAdminForm.errorLlenadoGranosMinimoActualizarEtapasCrecimiento.textContent = "El llenado de granos mínimo debe ser un valor numérico válido";
                elementsAdminForm.errorLlenadoGranosMinimoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar llenado de granos máximo
        if(llenadoGranosMaximo !== ""){
            if(isNaN(llenadoGranosMaximo) || Number(llenadoGranosMaximo) < 0){
                elementsAdminForm.errorLlenadoGranosMaximoActualizarEtapasCrecimiento.textContent = "El llenado de granos máximo debe ser un valor numérico válido";
                elementsAdminForm.errorLlenadoGranosMaximoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(llenadoGranosMaximo) < Number(llenadoGranosMinimo)){
                elementsAdminForm.errorLlenadoGranosMaximoActualizarEtapasCrecimiento.textContent = "El llenado de granos máximo no puede ser menor que el mínimo";
                elementsAdminForm.errorLlenadoGranosMaximoActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cosecha mínima
        if(cosechaMinima !== ""){
            if(isNaN(cosechaMinima) || Number(cosechaMinima) < 0){
                elementsAdminForm.errorCosechaMinimaActualizarEtapasCrecimiento.textContent = "La cosecha mínima debe ser un valor numérico válido";
                elementsAdminForm.errorCosechaMinimaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cosecha máxima
        if(cosechaMaxima !== ""){
            if(isNaN(cosechaMaxima) || Number(cosechaMaxima) < 0){
                elementsAdminForm.errorCosechaMaximaActualizarEtapasCrecimiento.textContent = "La cosecha máxima debe ser un valor numérico válido";
                elementsAdminForm.errorCosechaMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(cosechaMaxima) < Number(cosechaMinima)){
                elementsAdminForm.errorCosechaMaximaActualizarEtapasCrecimiento.textContent = "La cosecha máxima no puede ser menor que la mínima";
                elementsAdminForm.errorCosechaMaximaActualizarEtapasCrecimiento.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para actualizar jardinera
if(elementsAdminForm.formularioActualizarJardinera){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarJardinera.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombre = elementsAdminForm.nombreActualizarJardinera.value.trim();
        let semilla = elementsAdminForm.semillaActualizarJardinera.value.trim();
        let descripcion = elementsAdminForm.descripcionActualizarJardinera.value.trim();
        let fase = elementsAdminForm.faseActualizarJardinera.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarJardinera();

        //Validar nombre
        if(nombre !== ""){
            if(!soloTexto.test(nombre)){
                elementsAdminForm.errorNombreActualizarJardinera.textContent="El nombre de la jardinera debe ser un valor de texto";
                elementsAdminForm.errorNombreActualizarJardinera.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombre.length < 3){
                elementsAdminForm.errorNombreActualizarJardinera.textContent = "El nombre debe tener al menos 3 caracteres";
                elementsAdminForm.errorNombreActualizarJardinera.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(nombre.length > 100){
                elementsAdminForm.errorNombreActualizarJardinera.textContent = "El nombre no puede superar los 100 caracteres";
                elementsAdminForm.errorNombreActualizarJardinera.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar descripción
        if(descripcion !== ""){
            if(descripcion.length < 5){
                elementsAdminForm.errorDescripcionActualizarJardinera.textContent = "La descripción debe tener al menos 5 caracteres";
                elementsAdminForm.errorDescripcionActualizarJardinera.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(descripcion.length > 255){
                elementsAdminForm.errorDescripcionActualizarJardinera.textContent = "La descripción no puede superar los 255 caracteres";
                elementsAdminForm.errorDescripcionActualizarJardinera.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para actualizar factor externo
if(elementsAdminForm.formularioActualizarFactorExterno){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarFactorExterno.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let humedad = elementsAdminForm.humedadActualizarFactorExterno.value.trim();
        let tipoClima = elementsAdminForm.tipoClimaActualizarFactorExterno.value.trim();
        let temperatura = elementsAdminForm.temperaturaActualizarFactorExterno.value.trim();
        let cantidadAgua = elementsAdminForm.cantidadAguaActualizarFactorExterno.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarFactorExterno();

        //Validar humedad
        if(humedad !== ""){
            if(isNaN(humedad)){
                elementsAdminForm.errorHumedadActualizarFactorExterno.textContent = "La humedad debe ser un valor numérico";
                elementsAdminForm.errorHumedadActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(humedad) < 0){
                elementsAdminForm.errorHumedadActualizarFactorExterno.textContent = "La humedad no puede ser negativa";
                elementsAdminForm.errorHumedadActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(humedad) > 100){
                elementsAdminForm.errorHumedadActualizarFactorExterno.textContent = "La humedad no puede superar el 100%";
                elementsAdminForm.errorHumedadActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar temperatura
        if(temperatura !== ""){
            if(isNaN(temperatura)){
                elementsAdminForm.errorTemperaturaActualizarFactorExterno.textContent = "La temperatura debe ser un valor numérico";
                elementsAdminForm.errorTemperaturaActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar cantidad de agua
        if(cantidadAgua !== ""){
            if(isNaN(cantidadAgua)){
                elementsAdminForm.errorCantidadAguaActualizarFactorExterno.textContent = "La cantidad de agua debe ser un valor numérico";
                elementsAdminForm.errorCantidadAguaActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(cantidadAgua) < 0){
                elementsAdminForm.errorCantidadAguaActualizarFactorExterno.textContent = "La cantidad de agua no puede ser negativa";
                elementsAdminForm.errorCantidadAguaActualizarFactorExterno.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para actualizar monitoreo
if(elementsAdminForm.formularioActualizarMonitoreo){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarMonitoreo.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nota = elementsAdminForm.notaActualizarMonitoreo.value.trim();
        let imagen = elementsAdminForm.imagenActualizarMonitoreo.value.trim();
        let porcentaje = elementsAdminForm.porcentajeActualizarMonitoreo.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarMonitoreo();

        //Validar nota
        if(nota !== ""){
            if(nota.length < 5){
                elementsAdminForm.errorNotaActualizarMonitoreo.textContent = "La nota debe tener al menos 5 caracteres";
                elementsAdminForm.errorNotaActualizarMonitoreo.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(nota.length > 500){
                elementsAdminForm.errorNotaActualizarMonitoreo.textContent = "La nota no puede superar los 500 caracteres";
                elementsAdminForm.errorNotaActualizarMonitoreo.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar porcentaje
        if(porcentaje !== ""){
            if(isNaN(porcentaje)){
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.textContent = "El porcentaje debe ser un valor numérico";
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) < 0){
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.textContent = "El porcentaje no puede ser negativo";
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) > 100){
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.textContent = "El porcentaje no puede ser superior a 100";
                elementsAdminForm.errorPorcentajeActualizarMonitoreo.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para agregar fase
if(elementsAdminForm.formularioAgregarFase){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarFase.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombre = elementsAdminForm.nombreAgregarFase.value.trim();
        let descripcion = elementsAdminForm.descripcionAgregarFase.value.trim();
        let porcentaje = elementsAdminForm.porcentajeAgregarFase.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarFase();

        //Validar nombre
        if(nombre === ""){
            elementsAdminForm.errorNombreAgregarFase.textContent = "Por favor, ingrese el nombre de la fase";
            elementsAdminForm.errorNombreAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(!soloTexto.test(nombre)){
            elementsAdminForm.errorNombreAgregarFase.textContent="El nombre de la fase debe ser un valor de texto";
            elementsAdminForm.errorNombreAgregarFase.style.marginBottom="10px";
            event.preventDefault();
            return;
        }

        if(nombre.length < 3){
            elementsAdminForm.errorNombreAgregarFase.textContent = "El nombre de la fase debe tener al menos 3 caracteres";
            elementsAdminForm.errorNombreAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(nombre.length > 50){
            elementsAdminForm.errorNombreAgregarFase.textContent = "El nombre de la fase no puede superar los 50 caracteres";
            elementsAdminForm.errorNombreAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar descripción
        if(descripcion === ""){
            elementsAdminForm.errorDescripcionAgregarFase.textContent = "Por favor, ingrese la descripción de la fase";
            elementsAdminForm.errorDescripcionAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(descripcion.length < 3){
            elementsAdminForm.errorDescripcionAgregarFase.textContent = "La descripción debe tener al menos 3 caracteres";
            elementsAdminForm.errorDescripcionAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(descripcion.length > 255){
            elementsAdminForm.errorDescripcionAgregarFase.textContent = "La descripción no puede superar los 255 caracteres";
            elementsAdminForm.errorDescripcionAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar porcentaje
        if(porcentaje === ""){
            elementsAdminForm.errorPorcentajeAgregarFase.textContent = "Por favor, ingrese el porcentaje";
            elementsAdminForm.errorPorcentajeAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(porcentaje)){
            elementsAdminForm.errorPorcentajeAgregarFase.textContent = "El porcentaje debe ser un valor numérico";
            elementsAdminForm.errorPorcentajeAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(porcentaje) < 0 || Number(porcentaje) > 100){
            elementsAdminForm.errorPorcentajeAgregarFase.textContent = "El porcentaje debe estar entre 0 y 100";
            elementsAdminForm.errorPorcentajeAgregarFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar fase
if(elementsAdminForm.formularioActualizarFase){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarFase.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let nombre = elementsAdminForm.nombreActualizarFase.value.trim();
        let descripcion = elementsAdminForm.descripcionActualizarFase.value.trim();
        let porcentaje = elementsAdminForm.porcentajeActualizarFase.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarFase();

        //Validar nombre
        if(nombre !== ""){
            if(!soloTexto.test(nombre)){
                elementsAdminForm.errorNombreActualizarFase.textContent="El nombre de la fase debe ser un valor de texto";
                elementsAdminForm.errorNombreActualizarFase.style.marginBottom="10px";
                event.preventDefault();
                return;
            }

            if(nombre.length < 3){
                elementsAdminForm.errorNombreActualizarFase.textContent = "El nombre de la fase debe tener al menos 3 caracteres";
                elementsAdminForm.errorNombreActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(nombre.length > 50){
                elementsAdminForm.errorNombreActualizarFase.textContent = "El nombre de la fase no puede superar los 50 caracteres";
                elementsAdminForm.errorNombreActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar descripción
        if(descripcion !== ""){
            if(descripcion.length < 3){
                elementsAdminForm.errorDescripcionActualizarFase.textContent = "La descripción debe tener al menos 3 caracteres";
                elementsAdminForm.errorDescripcionActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(descripcion.length > 255){
                elementsAdminForm.errorDescripcionActualizarFase.textContent = "La descripción no puede superar los 255 caracteres";
                elementsAdminForm.errorDescripcionActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        } 

        //Validar porcentaje
        if(porcentaje !== ""){
            if(isNaN(porcentaje)){
                elementsAdminForm.errorPorcentajeActualizarFase.textContent = "El porcentaje debe ser un valor numérico";
                elementsAdminForm.errorPorcentajeActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) < 0){
                elementsAdminForm.errorPorcentajeActualizarFase.textContent = "El porcentaje no puede ser negativo";
                elementsAdminForm.errorPorcentajeActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) > 100){
                elementsAdminForm.errorPorcentajeActualizarFase.textContent = "El porcentaje no puede ser mayor a 100";
                elementsAdminForm.errorPorcentajeActualizarFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}

//Si existe el elemento del formulario para agregar pregunta fase
if(elementsAdminForm.formularioAgregarPreguntaFase){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioAgregarPreguntaFase.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let pregunta = elementsAdminForm.preguntaAgregarPreguntaFase.value.trim();
        let porcentaje = elementsAdminForm.porcentajeAgregarPreguntaFase.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresAgregarPreguntaFase();

        //Validar pregunta
        if(pregunta === ""){
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.textContent = "Por favor, ingrese la pregunta";
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(pregunta.length < 5){
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.textContent = "La pregunta debe tener al menos 5 caracteres";
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(pregunta.length > 255){
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.textContent = "La pregunta no puede superar los 255 caracteres";
            elementsAdminForm.errorPreguntaAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        //Validar porcentaje
        if(porcentaje === ""){
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.textContent = "Por favor, ingrese el porcentaje";
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(isNaN(porcentaje)){
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.textContent = "El porcentaje debe ser un valor numérico";
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(porcentaje) < 0){
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.textContent = "El porcentaje no puede ser negativo";
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }

        if(Number(porcentaje) > 100){
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.textContent = "El porcentaje no puede ser mayor a 100";
            elementsAdminForm.errorPorcentajeAgregarPreguntaFase.style.marginBottom = "10px";
            event.preventDefault();
            return;
        }
    });
}

//Si existe el elemento del formulario para actualizar pregunta fase
if(elementsAdminForm.formularioActualizarPreguntaFase){

    //Crear el evento del formulario a la hora de enviarlo
    elementsAdminForm.formularioActualizarPreguntaFase.addEventListener("submit", function(event){

        //Definir las variables a utilizar con los elementos que componen el formulario
        let pregunta = elementsAdminForm.preguntaActualizarPreguntaFase.value.trim();
        let porcentaje = elementsAdminForm.porcentajeActualizarPreguntaFase.value.trim();

        //Limpiar los errores de cada elemento
        limpiarErroresActualizarPreguntaFase();

        //Validar pregunta
        if(pregunta !== ""){
            if(pregunta.length < 5){
                elementsAdminForm.errorPreguntaActualizarPreguntaFase.textContent = "La pregunta debe tener al menos 5 caracteres";
                elementsAdminForm.errorPreguntaActualizarPreguntaFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(pregunta.length > 255){
                elementsAdminForm.errorPreguntaActualizarPreguntaFase.textContent = "La pregunta no puede superar los 255 caracteres";
                elementsAdminForm.errorPreguntaActualizarPreguntaFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }

        //Validar porcentaje
        if(porcentaje !== ""){
            if(isNaN(porcentaje)){
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.textContent = "El porcentaje debe ser un valor numérico";
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) < 0){
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.textContent = "El porcentaje no puede ser negativo";
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }

            if(Number(porcentaje) > 100){
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.textContent = "El porcentaje no puede ser mayor a 100";
                elementsAdminForm.errorPorcentajeActualizarPreguntaFase.style.marginBottom = "10px";
                event.preventDefault();
                return;
            }
        }
    });
}