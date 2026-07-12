<?php 
    /* FUNCIONES GENERALES */

        //Función para recuperar la fecha actual del equipo
        function recuperarFechaActual(){
            //Recuperar la fecha y hora actual del sistema
            date_default_timezone_set("America/Bogota");
            $fechaActual=date('Y-m-d');

            return $fechaActual;
        }

        //Función para recuperar la fecha y hora actual del sistema
        function recuperarFechaActualConHora(){
            //Recuperar la fecha y hora actual del sistema
            date_default_timezone_set("America/Bogota");
            $fechaActual=date('Y-m-d H:i:s');

            return $fechaActual;
        }

        //Función para abrir la conexión a la base de datos
        function abrirConexionDB(){

            static $conexion_db = null;

            if($conexion_db === null){

                $host = "localhost";
                $username = "root";
                $password = "";
                $dbName = "biourbis_db";

                $conexion_db = mysqli_connect($host, $username, $password, $dbName);

                if(!$conexion_db){
                    die("Error al conectar a la base de datos: " . mysqli_connect_error());
                }

                mysqli_set_charset($conexion_db, "utf8");
            }

            return $conexion_db;
        }

        //Función para cerrar la conexion a la base de datos
        function cerrarConexionDB($conexion){
            mysqli_close($conexion);
        }

        //Función para crear arreglos con todos los datos de una consulta
        function arregloDatos($resultadoConsulta){
            $datos=mysqli_fetch_assoc($resultadoConsulta);

            return $datos;
        }
        
        //Función para calcular la cantidad de días entre dos fechas
        function calcularDiasEntreFechas($fechaMin, $fechaMax){
            $fecha1= new DateTime($fechaMin);
            $fecha2= new DateTime($fechaMax);

            $diferencia=$fecha1->diff($fecha2);

            return $diferencia->days;
        }

        //Función para calcular el promedio de un arreglo de números
        function calcularPromedio($arreglo){
            if(count($arreglo) == 0){
                return 0; // evita error
            }

            $i = 0;
            $total = 0;

            while($i < count($arreglo)){
                $total += floatval($arreglo[$i]); // asegura que sea número
                $i++;
            }

            return round($total / count($arreglo), 2);
        }

        //Función para calcular la tendencia de crecimiento de una jardinera
        function calcularTendencia($porcentajes){

            $tendencia = [];

            for($i = 1; $i < count($porcentajes); $i++){
                $tendencia[] = $porcentajes[$i] - $porcentajes[$i - 1];
            }

            return $tendencia;
        }
    /**/

    /* USUARIOS */

        //Función para consultar si un usuario existe por su id en la base de datos
        function consultarUsuarioExistente($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM usuario WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos del usuario en forma de arreglo
        function consultarDatosUsuario($id){
            $conexion=abrirConexionDB();

            $resultadoConsulta=consultarUsuarioExistente($id);

            $datosUsuario=arregloDatos($resultadoConsulta);

            $idTipoDocumento=$datosUsuario["idTipoDocumento"];
            $resultadoConsultarTipoDocumento=consultarTipoDocumento($idTipoDocumento);

            if(mysqli_num_rows($resultadoConsultarTipoDocumento)>0){
                $datosTipoDocumento=mysqli_fetch_assoc($resultadoConsultarTipoDocumento);
                $datosUsuario["idTipoDocumento"]=$datosTipoDocumento["tipoDocDescripcion"];
            }

            return $datosUsuario;
        }

        //Función para consultar si un usuario se encuentra verificado
        function consultarSiUsuarioVerficado($id){
            $conexion=abrirConexionDB();

            $query="SELECT usuEstadoCorreo FROM usuario WHERE usuNumeroDocumento='$id' AND usuEstadoCorreo='Verificado'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la cantidad de jardineras de un usuario
        function consultarCantidadJardineras($id){
            $conexion=abrirConexionDB();

            $query="SELECT usuCantidadJardineras FROM usuario WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        function actualizarCantidadJardinerasUsuario($id, $cantidad){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuCantidadJardineras='$cantidad' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
        
        //Función para registrar un usuario en la base de datos
        function registrarUsuario($nombre,$tipoDocumento, $documento, $correo, $contrasena, $barrio, $fecha){
            $conexion=abrirConexionDB();

            $query="INSERT INTO usuario (usuNombre, idTipoDocumento, usuNumeroDocumento, usuCorreo, usuEstadoCorreo, 
            usuCantidadJardineras, usuTipoUsuario, usuEstado, usuContrasena, usuBarrio, usuFechaIngreso) 
            VALUES('$nombre','$tipoDocumento', '$documento', '$correo','No verificado', 0, 'Usuario', 'Activo', '$contrasena', '$barrio','$fecha')";
            
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar la contraseña del usuario
        function actualizarContrasena($id, $contrasena){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuContrasena='$contrasena' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado del correo electronico
        function actualizarEstadoCorreo($id){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuEstadoCorreo='Verificado' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el último acceso del usuario
        function actualizarUltimoAcceso($id, $fechaHora){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuUltimoAcceso='$fechaHora' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para calcular tiempo de actividad del usuario
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
                if($actividadEnDias==1){
                    return $actividadEnDias . " día";
                }else{
                    return $actividadEnDias . " días";
                }
            } elseif($actividadEnDias<365){
                //Actividad del usuario en meses
                if($actividadEnDias==1){
                    return ($diferencia->y *12 + $diferencia->m. " mes");
                }else{
                    return ($diferencia->y *12 + $diferencia->m. " meses");
                }
            }else{
                //Actividad del usuario en años
                if($actividadEnDias==1){
                    return $diferencia->y . " año";
                }else{
                    return $diferencia->y . " años";
                }
            }
        }

        //Función para consultar todos los usuarios de tipo usuario del sistema
        function consultarTodosUsuarios(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM usuario 
            INNER JOIN tipo_documento ON usuario.idTipoDocumento=tipo_documento.idTipoDocumento
            WHERE usuTipoUsuario='Usuario'"; 
            $resultado=mysqli_query($conexion, $query); 

            return $resultado; 
        }

        //Función para contar todos los usuarios activos
        function contarCantidadUsuarioActivos(){
            $conexion=abrirConexionDB();

            $query="SELECT COUNT(usuNumeroDocumento) AS cantidadUsuarios FROM usuario WHERE usuEstado='Activo' AND usuTipoUsuario='Usuario'";
            $resultado=mysqli_query($conexion, $query);

            $fila=mysqli_fetch_assoc($resultado);

            return $fila["cantidadUsuarios"];
        }

        //Función para actualizar la informacion de un usuario
        function actualizarUsuario($nombre, $tipoUsuario, $tipoDocumento, $correo, $estadoCorreo, $barrio, $avatar, $cantidadJardineras, $usuario){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuNombre='$nombre', usuTipoUsuario='$tipoUsuario', idTipoDocumento='$tipoDocumento',
            usuCorreo='$correo', usuEstadoCorreo='$estadoCorreo', usuBarrio='$barrio', usuImagen='$avatar', usuCantidadJardineras='$cantidadJardineras' 
            WHERE usuNumeroDocumento='$usuario'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un usuario
        function actualizarEstadoUsuario($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuEstado='$estado' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Funcion para agregar la ruta de la imagen de perfil del usuario a la base de datos
        function agregarImagenPerfil($id, $rutaImagen){
            $conexion=abrirConexionDB();

            $query="UPDATE usuario SET usuImagen='$rutaImagen' WHERE usuNumeroDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/
        
    /*TIPOS DOCUMENTO */
        //Función para consultar el tipo de documento por su id
        function consultarTipoDocumento($id){
            $conexion=abrirConexionDB();

            $query="SELECT tipoDocDescripcion FROM tipo_documento WHERE idTipoDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todos los tipos de documentos
        function consultarTiposDocumentos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_documento ";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos del tipo de documento por su id, retorna un arreglo con la info
        function consultarDatosTipoDocumento($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_documento WHERE idTipoDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar todos los tipos de documento activos
        function consultarTiposDocumentosActivos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_documento WHERE tipoDocEstado='Activo'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar si existe un tipo de documento en el sistema por su descripcion
        function consultarExistenciaTipoDocumento($descripcion){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_documento WHERE tipoDocDescripcion='$descripcion'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para agregar un nuevo tipo de documento
        function agregarTipoDocumento($descripcion){
            $conexion=abrirConexionDB();

            $query="INSERT INTO tipo_documento (tipoDocDescripcion) VALUES ('$descripcion')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar un tipo de documento por su id 
        function actualizarTipoDocumento($id,$descripcion){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_documento  SET tipoDocDescripcion= '$descripcion' WHERE idTipoDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un tipo de documento por su id 
        function actualizarEstadoTipoDocumento($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_documento  SET tipoDocEstado='$estado' WHERE idTipoDocumento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* ACTIVIDAD USUARIO */
        //Función para registrar la actividad del usuario en la base de datos
        function registrarActividadUsuario($modulo, $accion, $descripcion, $usuario){
            $conexion=abrirConexionDB();

            $fechaActual=recuperarFechaActualConHora();
            $query="INSERT INTO actividad (actFecha, actModulo, actAccion, actDescripcion, usuNumeroDocumento) VALUES('$fechaActual', '$modulo', '$accion', '$descripcion', '$usuario')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la última actividad registrada en la base de datos
        function consultarUltimaActividad(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM actividad ORDER BY idActividad DESC LIMIT 1";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar las últimas actividades registradas en la base de datos
        function consultarUltimasActividades(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM actividad ORDER BY idActividad DESC LIMIT 10";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* SOLICITUDES */

        //Función para consultar todas las solicitudes asociadas a un usuario en estado pendiente
        function consultarSolicitudes($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT soliFecha, soliAsunto, soliSemilla, soliDescripcion, soliEstado, usuNumeroDocumento FROM solicitud 
            WHERE usuNumeroDocumento='$usuario' AND soliEstado='Pendiente'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todas las solicitudes asociadas a un usuario en cualquier estado
        function consultarHistorialSolicitudesUsuario($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT soliFecha, soliAsunto, soliSemilla, soliDescripcion, soliEstado, usuNumeroDocumento FROM solicitud 
            WHERE usuNumeroDocumento='$usuario' AND (soliEstado='Confirmada' OR soliEstado='Rechazada')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para contar todas las solicitudes pendientes
        function contarTodasSolicitudesPendientes(){
            $conexion=abrirConexionDB();

            $query="SELECT COUNT(idSolicitud) AS cantidadSolicitudes FROM solicitud WHERE soliEstado='Pendiente'";
            $resultado=mysqli_query($conexion, $query);

            $fila=mysqli_fetch_assoc($resultado); 

            return $fila["cantidadSolicitudes"];
        }

        //Función para consultar todas las solicitudes pendientes
        function consultarTodasSolicitudesPendientes(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM solicitud WHERE soliEstado='Pendiente'  ORDER BY idSolicitud DESC";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar las últimas solicitudes ya confirmadas o rechazadas
        function consultarHistorialSolicitudes(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM solicitud WHERE soliEstado='Confirmada' OR soliEstado='Rechazada'
            ORDER BY idSolicitud DESC LIMIT 5";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar toda la información de una solicitud
        function consultarDatosSolicitud($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM solicitud WHERE idSolicitud='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para registrar una solicitud en la base de datos con el estado pendiente
        function registrarSolicitud($fecha, $tipo, $mensaje){
            $conexion=abrirConexionDB();

            $query="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion, soliEstado) VALUES('$fecha', '$tipo', '$mensaje', 'Pendiente')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para registrar una solicitud variada en la base de datos con el estado pendiente
        function registrarSolicitudVariada($fecha, $tipo, $mensaje, $semilla, $usuario){
            $conexion=abrirConexionDB();
            
            if($tipo==="Admisión Nueva Semilla"){
                $query="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion, soliSemilla, soliEstado, usuNumeroDocumento)
                VALUES('$fecha', '$tipo', '$mensaje', '$semilla', 'Pendiente', '$usuario')";
            }else{
                $query="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion,  soliEstado, usuNumeroDocumento)
                VALUES('$fecha', '$tipo', '$mensaje', 'Pendiente', '$usuario')";
            }
            
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
        
        //Función para actualizar el estado de la solicitud
        function actualizarEstadoSolicitud($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE solicitud SET soliEstado='$estado' WHERE idSolicitud='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* RESEÑAS */
        //Función para consultar todas las reseñas ordenadas por su id
        function consultarTodasResenas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM resena ORDER BY idResena DESC";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar las 5 últimas reseñas que se muestran en el index
        function consultarResenasIndex(){
            $conexion=abrirConexionDB();
            
            $query="SELECT * FROM resena WHERE resenaEstado='Activa' ORDER BY resenaFecha DESC LIMIT 15";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de una reseña por su id, retorna un arreglo
        function consultarDatosResenaPorId($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM resena WHERE idResena='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para registrar una nueva reseña
        function registrarResena($fecha, $nombre, $correo, $mensaje, $idUsuario, $conIdentificacion){
            $conexion=abrirConexionDB();
            
            if($conIdentificacion){
                $query="INSERT INTO resena (resenaFecha, resenaNombreUsuario, resenaCorreo, resenaDescripcion, usuNumeroDocumento) 
                VALUES('$fecha', '$nombre' ,'$correo', '$mensaje', '$idUsuario')";
            }else{
                $query="INSERT INTO resena (resenaFecha, resenaNombreUsuario, resenaCorreo, resenaDescripcion) 
                VALUES('$fecha','$nombre', '$correo', '$mensaje')";
            }

            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de una reseña 
        function actualizarEstadoResena($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE resena SET resenaEstado='$estado' WHERE idResena='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /* */

    /* SEMILLAS */
        //Función para consultar todas las semillas activas de la base de datos
        function consultarSemillasActivas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM semilla WHERE semEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todas las semillas de la base de datos
        function consultarTodasSemillas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM semilla
            INNER JOIN tipo_semilla ON semilla.idTipoSemilla=tipo_semilla.idTipoSemilla ORDER BY idSemilla";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la existencia de una semilla específica por su nombre
        function consultarExistenciaSemillaPorNombre($nombre){
            $conexion=abrirConexionDB();

            $query="SELECT semNombre FROM semilla WHERE semNombre='$nombre'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la existencia de una semilla por su id
        function consultarExistenciaSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT idSemilla FROM semilla WHERE idSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            if($resultado && mysqli_num_rows($resultado) > 0){
                return true;
            }else{
                return false;
            }
        }

        //Función para consultar los datos de una semilla activa específica, retorna un arreglo 
        function consultarDatosSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM semilla 
            INNER JOIN tipo_semilla ON semilla.idTipoSemilla=tipo_semilla.idTipoSemilla
            WHERE idSemilla='$id' AND semEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            $datos=arregloDatos($resultado);

            return $datos;
        }

        //Función para consultar los datos de una semilla específica
        function consultarDatosTodasSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM semilla 
            INNER JOIN tipo_semilla ON semilla.idTipoSemilla=tipo_semilla.idTipoSemilla
            WHERE idSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            $datos=arregloDatos($resultado);

            return $datos;
        }
        
        //Función para consultar las semillas que no tengan registrada una etapa de crecimiento
        function consultarSemillasSinEtapa(){
            $conexion=abrirConexionDB();

            $query="SELECT idSemilla, semNombre, idEtapaCrecimiento FROM semilla 
            WHERE idEtapaCrecimiento='0'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar las semillas que no tengan registrada una ficha tecnica
        function consultarSemillasSinFicha(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM semilla WHERE idFicha='0'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todas las semillas activas con información completa
        function consultarSemillasActivasConFichaYEtapa($conLimite){
            $conexion=abrirConexionDB();

            if($conLimite){
                $query="SELECT * FROM semilla WHERE semEstado='Activa' AND idFicha!='0' AND idEtapaCrecimiento!='0' LIMIT 8";
            }else{
                $query="SELECT * FROM semilla WHERE semEstado='Activa' AND idFicha!='0' AND idEtapaCrecimiento!='0'";
            }
        
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para agregar una nueva semilla
        function agregarSemilla($nombre, $rutaImagen, $observaciones, $tipoSemilla){
            $conexion=abrirConexionDB();

            $query="INSERT INTO semilla (semNombre, semImagen, semObservaciones, idTipoSemilla) VALUES('$nombre', '$rutaImagen', '$observaciones', '$tipoSemilla')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar una semilla
        function actualizarSemilla($id, $nombre, $rutaImagen, $observacion, $tipoSemilla){
            $conexion=abrirConexionDB();

            $query="UPDATE semilla SET semNombre ='$nombre', semImagen='$rutaImagen', semObservaciones='$observacion', idTipoSemilla='$tipoSemilla'
            WHERE idSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
        
        //Función para actualizar el estado de una semilla
        function actualizarEstadoSemilla($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE semilla SET semEstado ='$estado' WHERE idSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }  
    /**/

    /* TIPOS DE SEMILLA*/
        //Función para consultar el tipo de semilla por su id
        function consultarTipoSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT tipoSemDescripcion FROM tipo_semilla WHERE idTipoSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todos los tipos de semilla
        function consultarTiposSemillaActivas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_semilla WHERE tipoSemEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todos los tipos de semilla
        function consultarTodosTipoSemilla(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_semilla";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de un tipo de semilla, retorna un arreglo
        function consultarDatosTipoSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_semilla WHERE idTipoSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar si existe un tipo de semilla con esa descripción
        function consultarExistenciaTipoSemilla($descripcion){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_semilla WHERE tipoSemDescripcion='$descripcion'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para registrar un nuevo tipo de semilla
        function registrarTipoSemilla($descripcion){
            $conexion=abrirConexionDB();

            $query="INSERT INTO tipo_semilla (tipoSemDescripcion) VALUES ('$descripcion')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar un tipo de semilla
        function actualizarTipoSemilla($id, $descripcion){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_semilla SET tipoSemDescripcion='$descripcion' WHERE idTipoSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un tipo de semilla
        function actualizarEstadoTipoSemilla($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_semilla SET tipoSemEstado='$estado' WHERE idTipoSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* FICHA TÉCNICA */
        //Función para consultar la ficha técnica de una semilla en específico, retorna un arreglo
        function consultarFichaTecnicaSemilla($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM ficha_tecnica 
            INNER JOIN tipo_tierra ON ficha_tecnica.idTipoTierra=tipo_tierra.idTipoTierra
            INNER JOIN tipo_clima ON ficha_tecnica.idTipoClima=tipo_clima.idTipoClima
            INNER JOIN semilla ON ficha_tecnica.idSemilla=semilla.idSemilla WHERE ficha_tecnica.idSemilla='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar todas las fichas técnicas relacionadas con una semilla
        function consultarTodasFichasTecnicas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM ficha_tecnica 
            INNER JOIN tipo_tierra ON ficha_tecnica.idTipoTierra=tipo_tierra.idTipoTierra
            INNER JOIN tipo_clima ON ficha_tecnica.idTipoClima=tipo_clima.idTipoClima
            INNER JOIN semilla ON ficha_tecnica.idSemilla=semilla.idSemilla WHERE semilla.idFicha!='0' ORDER BY ficha_tecnica.idSemilla";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de una ficha técnica por su id, retorna un arreglo
        function consultarDatosFichaTecnica($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM ficha_tecnica 
            INNER JOIN tipo_tierra ON ficha_tecnica.idTipoTierra=tipo_tierra.idTipoTierra
            INNER JOIN tipo_clima ON ficha_tecnica.idTipoClima=tipo_clima.idTipoClima
            INNER JOIN semilla ON ficha_tecnica.idSemilla=semilla.idSemilla WHERE ficha_tecnica.idFicha='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para registrar una nueva ficha técnica
        function registrarFichaTecnica($datosFichaTecnica){
            $conexion=abrirConexionDB();

            $idSemilla = $datosFichaTecnica["idSemilla"];
            $idTipoClima = $datosFichaTecnica["idTipoClima"];
            $temperaturaMin = $datosFichaTecnica["temperaturaMin"];
            $temperaturaMax = $datosFichaTecnica["temperaturaMax"];
            $humedadMin = $datosFichaTecnica["humedadMin"];
            $humedadMax = $datosFichaTecnica["humedadMax"];
            $cantidadAguaMin = $datosFichaTecnica["cantidadAguaMin"];
            $cantidadAguaMax = $datosFichaTecnica["cantidadAguaMax"];
            $idTipoTierra = $datosFichaTecnica["idTipoTierra"];
            $cantidadTierraMin = $datosFichaTecnica["cantidadTierraMin"];
            $cantidadTierraMax = $datosFichaTecnica["cantidadTierraMax"];
            $espacio = $datosFichaTecnica["espacio"];

            $query="INSERT INTO ficha_tecnica (idSemilla, idTipoClima, fichaTemperaturaMin, fichaTemperaturaMax, fichaHumedadMin, fichaHumedadMax, fichaCantidadAguaMin, 
            fichaCantidadAguaMax, idTipoTierra, fichaCantidadTierraMin, fichaCantidadTierraMax, fichaEspacio)
            VALUES ('$idSemilla','$idTipoClima','$temperaturaMin','$temperaturaMax','$humedadMin','$humedadMax','$cantidadAguaMin','$cantidadAguaMax',
            '$idTipoTierra','$cantidadTierraMin','$cantidadTierraMax','$espacio')";
            $resultadoRegistro=mysqli_query($conexion, $query);

            if($resultadoRegistro){
                $idFichaTecnica=mysqli_insert_id($conexion);
                
                $query="UPDATE semilla SET idFicha='$idFichaTecnica' WHERE idSemilla='$idSemilla'";
                $resultadoActualizacion=mysqli_query($conexion, $query);

                if($resultadoActualizacion){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        //Función para actualizar una ficha técnica
        function actualizarFichaTecnica($id, $nuevosDatosFichaTecnica){
            $conexion=abrirConexionDB();

            $idTipoClima = $nuevosDatosFichaTecnica["idTipoClima"];
            $temperaturaMin = $nuevosDatosFichaTecnica["temperaturaMin"];
            $temperaturaMax = $nuevosDatosFichaTecnica["temperaturaMax"];
            $humedadMin = $nuevosDatosFichaTecnica["humedadMin"];
            $humedadMax = $nuevosDatosFichaTecnica["humedadMax"];
            $cantidadAguaMin = $nuevosDatosFichaTecnica["cantidadAguaMin"];
            $cantidadAguaMax = $nuevosDatosFichaTecnica["cantidadAguaMax"];
            $idTipoTierra = $nuevosDatosFichaTecnica["idTipoTierra"];
            $cantidadTierraMin = $nuevosDatosFichaTecnica["cantidadTierraMin"];
            $cantidadTierraMax = $nuevosDatosFichaTecnica["cantidadTierraMax"];
            $espacio = $nuevosDatosFichaTecnica["espacio"];

            $query="UPDATE ficha_tecnica SET idTipoClima='$idTipoClima', fichaTemperaturaMin='$temperaturaMin', fichaTemperaturaMax='$temperaturaMax', 
            fichaHumedadMin='$humedadMin', fichaHumedadMax='$humedadMax', fichaCantidadAguaMin='$cantidadAguaMin', fichaCantidadAguaMax='$cantidadAguaMax', idTipoTierra=' $idTipoTierra', 
            fichaCantidadTierraMin='$cantidadTierraMin', fichaCantidadTierraMax='$cantidadTierraMax', fichaEspacio='$espacio' WHERE idFicha='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* TIPO TIERRA */
        //Función para consultar el tipo de tierra por su id
        function consultarTipoTierra($id){
            $conexion=abrirConexionDB();

            $query="SELECT tipoTierraDescripcion FROM tipo_tierra WHERE idTipoTierra='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos del tipo de tierra por su id
        function consultarDatosTipoTierra($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_tierra WHERE idTipoTierra='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar todos los tipos de tierra activos
        function consultarTiposTierraActivos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_tierra WHERE tipoTierraEstado='Activo'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todos los tipos de tierra
        function consultarTiposTierra(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_tierra";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la existencia de un tipo de tierra por su descripción
        function consultarExistenciaTipoTierra($descripcion){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_tierra WHERE tipoTierraDescripcion='$descripcion'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para registrar un nuevo tipo de tierra
        function registrarTipoTierra($descripcion){
            $conexion=abrirConexionDB();

            $query="INSERT INTO tipo_tierra (tipoTierraDescripcion) VALUES ('$descripcion')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado; 
        }

        //Función para actualizar un tipo de tierra
        function actualizarTipoTierra($id, $descripcion){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_tierra SET tipoTierraDescripcion='$descripcion' WHERE idTipoTierra='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un tipo de tierra
        function actualizarEstadoTipoTierra($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_tierra SET tipoTierraEstado='$estado' WHERE idTipoTierra='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        } 
    /**/

    /* TIPO CLIMA */
        //Función para consultar el tipo de clima por su id
        function consultarTipoClima($id){
            $conexion=abrirConexionDB();

            $query="SELECT tipoClimaDescripcion FROM tipo_clima WHERE idTipoClima='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de un tipo de clima por su id, retorna un arreglo
        function consultarDatosTipoClima($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_clima WHERE idTipoClima='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para consultar todos los tipos de clima activos
        function consultarTiposClimaActivos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_clima WHERE tipoClimaEstado='Activo'";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para consultar todos los tipos de clima
        function consultarTiposClima(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_clima";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para consultar la existencia del tipo de clima
        function consultarExistenciaTipoClima($descripcion){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM tipo_clima WHERE tipoClimaDescripcion='$descripcion'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para registrar un nuevo tipo de clima
        function registrarTipoClima($descripcion){
            $conexion=abrirConexionDB();

            $query="INSERT INTO tipo_clima (tipoClimaDescripcion) VALUES('$descripcion')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar un tipo de clima
        function actualizarTipoClima($id, $descripcion){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_clima SET tipoClimaDescripcion='$descripcion' WHERE idTipoClima='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un tipo de clima
        function actualizarEstadoTipoClima($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE tipo_clima SET tipoClimaEstado='$estado' WHERE idTipoClima='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /*ETAPAS DE CRECIMIENTO */
        //Función para consultar la etapa de crecimiento por su id 
        function consultarDatosEtapaCrecimiento($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM etapas_crecimiento WHERE idEtapaCrecimiento='$id'";
            $resultado=mysqli_query($conexion, $query); 

            $datos=arregloDatos($resultado);

            return $datos;
        }

        //Función para consultar todas las etapas de crecimiento relacionadas a una semilla
        function consultarTodasEtapasCrecimientoSemilla(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM etapas_crecimiento 
            INNER JOIN semilla ON etapas_crecimiento.idEtapaCrecimiento=semilla.idEtapaCrecimiento";
            $resultado=mysqli_query($conexion, $query); 

            return $resultado;
        }

        //Función para registra una nueva etapa de crecimiento
        function registrarEtapaCrecimiento($datosEtapaCrecimiento){
            $conexion=abrirConexionDB();

            $idSemilla = $datosEtapaCrecimiento["idSemilla"];
            $germinacionMin = $datosEtapaCrecimiento["germinacionMin"];
            $germinacionMax = $datosEtapaCrecimiento["germinacionMax"];
            $desarrolloVegetativoMin = $datosEtapaCrecimiento["desarrolloVegetativoMin"];
            $desarrolloVegetativoMax = $datosEtapaCrecimiento["desarrolloVegetativoMax"];
            $floracionMin = $datosEtapaCrecimiento["floracionMin"];
            $floracionMax = $datosEtapaCrecimiento["floracionMax"];
            $llenadoGranosMin = $datosEtapaCrecimiento["llenadoGranosMin"];
            $llenadoGranosMax = $datosEtapaCrecimiento["llenadoGranosMax"];
            $cosechaMin = $datosEtapaCrecimiento["cosechaMin"];
            $cosechaMax = $datosEtapaCrecimiento["cosechaMax"];
            
            $query = "INSERT INTO etapas_crecimiento (etapaCreDiasGerminacionMin,etapaCreDiasGerminacionMax,etapaCreDiasDesarrolloVegetativoMin,etapaCreDiasDesarrolloVegetativoMax, etapaCreDiasFloracionMin,etapaCreDiasFloracionMax,
            etapaCreDiasLlenadoGranosMin,etapaCreDiasLlenadoGranosMax, etapaCreDiasCosechaMin,etapaCreDiasCosechaMax)
            VALUES('$germinacionMin','$germinacionMax','$desarrolloVegetativoMin','$desarrolloVegetativoMax','$floracionMin','$floracionMax','$llenadoGranosMin',
            '$llenadoGranosMax','$cosechaMin','$cosechaMax')";

            $resultadoRegistro = mysqli_query($conexion, $query);

            if($resultadoRegistro){
                $idEtapaCrecimiento= mysqli_insert_id($conexion);

                $queryActualizacion="UPDATE semilla SET idEtapaCrecimiento='$idEtapaCrecimiento' WHERE idSemilla='$idSemilla'";
                $resultadoActualizacion = mysqli_query($conexion, $queryActualizacion);

                if($resultadoActualizacion){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        //Función para actualizar una etapa de crecimiento
        function actualizarEtapaCrecimiento($id, $nuevosDatosEtapaCrecimiento){
            $conexion=abrirConexionDB();

            $germinacionMin=$nuevosDatosEtapaCrecimiento["germinacionMin"];
            $germinacionMax=$nuevosDatosEtapaCrecimiento["germinacionMax"];
            $desarrolloVegetativoMin=$nuevosDatosEtapaCrecimiento["desarrolloVegetativoMin"];
            $desarrolloVegetativoMax=$nuevosDatosEtapaCrecimiento["desarrolloVegetativoMax"];
            $floracionMin=$nuevosDatosEtapaCrecimiento["floracionMin"];
            $floracionMax=$nuevosDatosEtapaCrecimiento["floracionMax"];
            $llenadoGranosMin=$nuevosDatosEtapaCrecimiento["llenadoGranosMin"];
            $llenadoGranosMax=$nuevosDatosEtapaCrecimiento["llenadoGranosMax"];
            $cosechaMin=$nuevosDatosEtapaCrecimiento["cosechaMin"];
            $cosechaMax=$nuevosDatosEtapaCrecimiento["cosechaMax"];

            $query="UPDATE etapas_crecimiento SET etapaCreDiasGerminacionMin='$germinacionMin',etapaCreDiasGerminacionMax='$germinacionMax',etapaCreDiasDesarrolloVegetativoMin='$desarrolloVegetativoMin',
            etapaCreDiasDesarrolloVegetativoMax='$desarrolloVegetativoMax', etapaCreDiasFloracionMin='$floracionMin',etapaCreDiasFloracionMax='$floracionMax', etapaCreDiasLlenadoGranosMin='$llenadoGranosMin',
            etapaCreDiasLlenadoGranosMax='$llenadoGranosMax', etapaCreDiasCosechaMin='$cosechaMin',etapaCreDiasCosechaMax='$cosechaMax' WHERE idEtapaCrecimiento='$id'";
            $resultado = mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /*JARDINERAS */

        //Función para consultar la información la jardinera por el id del usuario
        function consultarJardineras($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM jardinera WHERE usuNumeroDocumento='$usuario'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todas las jardineras
        function consultarTodasJardineras(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM jardinera 
            INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla
            INNER JOIN fase ON jardinera.idFase=fase.idFase
            INNER JOIN usuario ON jardinera.usuNumeroDocumento=usuario.usuNumeroDocumento";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de la jardinera relacionadas a un usuario, retorna un arreglo
        function consultarDatosJardineras($usuario){
            $conexion=abrirConexionDB();

            $resultadoConsulta=consultarJardineras($usuario);

            $datosJardinera=arregloDatos($resultadoConsulta);

            $idSemilla=$datosJardinera["idSemilla"];
            $idFase=$datosJardinera["idFase"];

            $resultadoConsultarFase=consultarDatosFase($idFase);
            if(mysqli_num_rows($resultadoConsultarFase)>0){
                $datosFase=mysqli_fetch_assoc($resultadoConsultarFase);
                $datosJardinera["idFase"]=$datosFase["faseNombre"];
            }

            $datosSemilla=consultarDatosSemilla($idSemilla);

            $datosJardinera["idSemilla"]=$datosSemilla["semNombre"];

            return $datosJardinera;
        }

        //Función para consultar los datos de la jardinera por su id, retorna un arreglo
        function consultarDatosJardineraPorId($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM jardinera 
            INNER JOIN usuario ON jardinera.usuNumeroDocumento=usuario.usuNumeroDocumento
            INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla
            INNER JOIN fase ON jardinera.idFase=fase.idFase
            WHERE idJardinera='$id'";

            $resultado=mysqli_query($conexion, $query);

            $datos=arregloDatos($resultado);

            return $datos;
        }

        //Función para consultar todas las jardineras con sus factores externos y evolución relacionadas a un usuario
        function consultarJardinerasConDetalles($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT jardinera.*, semilla.semNombre AS semNombre, fase.faseNombre AS faseNombre FROM jardinera 
            INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla
            INNER JOIN fase ON jardinera.idFase=fase.idFase
            WHERE jardinera.usuNumeroDocumento='$usuario' AND jarEstado='Activa'";

            $resultado=mysqli_query($conexion, $query);
            $jardineras=[];

            if($resultado && mysqli_num_rows($resultado) > 0){
                while($jardinera=mysqli_fetch_assoc($resultado)){
                    $idJardinera=$jardinera['idJardinera'];
                    $factores=[];
                    $resultadoFactores=consultarFactoresExternosPorJardinera($idJardinera);

                    if($resultadoFactores && mysqli_num_rows($resultadoFactores) > 0){
                        while($factor=mysqli_fetch_assoc($resultadoFactores)){
                            $climaDescripcion='';
                            if(!empty($factor['idTipoClima'])){
                                $resultadoClima=consultarTipoClima($factor['idTipoClima']);
                                if($resultadoClima && mysqli_num_rows($resultadoClima) > 0){
                                    $climaDatos=mysqli_fetch_assoc($resultadoClima);
                                    $climaDescripcion=$climaDatos['tipoClimaDescripcion'] ?? '';
                                }
                            }

                            $factores[]=[
                                'humedad'=>$factor['factHumedad'] ?? '',
                                'temperatura'=>$factor['factTemperatura'] ?? '',
                                'cantidadAgua'=>$factor['factCantidadAgua'] ?? '',
                                'clima'=>$climaDescripcion
                            ];
                        }
                    }

                    $evoluciones=[];
                    $resultadoEvolucion=consultarEvolucionPorJardinera($idJardinera);
                    if($resultadoEvolucion && mysqli_num_rows($resultadoEvolucion) > 0){
                        while($evolucion=mysqli_fetch_assoc($resultadoEvolucion)){
                            $evoluciones[]=[
                                'fecha'=>$evolucion['segJardineraFecha'] ?? '',
                                'nota'=>$evolucion['segJardineraNota'] ?? '',
                                'imagen'=>$evolucion['segJardineraImagen'] ?? '',
                                'porcentaje'=>$evolucion['segJardineraPorcentaje'] ?? ''
                            ];
                        }
                    }

                    $jardinera['factoresExternos']=$factores;
                    $jardinera['evoluciones']=$evoluciones;
                    $jardineras[]=$jardinera;
                }
            }

            return $jardineras;
        }

        //Funcion para contar todas las jardineras activas
        function contarTodasJardinerasActivas(){
            $conexion=abrirConexionDB();

            $query="SELECT COUNT(idJardinera) AS cantidadJardineras FROM jardinera WHERE jarEstado='Activa'"; 
            $resultado=mysqli_query($conexion, $query);

            $fila=mysqli_fetch_assoc($resultado); 

            return $fila["cantidadJardineras"];
        }

        //Función para agregar una jardinera a la base de datos
        function agregarJardinera($nombre, $descripcion, $semilla, $usuario){
            $conexion=abrirConexionDB();

            $fechaActual=recuperarFechaActual();

            $query="INSERT INTO jardinera (jarNombre, jarDescripcion, jarFechaCreacion, idFase, jarPorcentajeEvolucion, idSemilla, usuNumeroDocumento) 
            VALUES('$nombre', '$descripcion', '$fechaActual', '1', '10', '$semilla', '$usuario' )";
            $resultado=mysqli_query($conexion, $query);

            return $resultado; 
        }

        //Función para actualizar los datos de una jardinera
        function actualizarJardinera($id, $nombre, $descripcion){
            $conexion=abrirConexionDB();

            $query="UPDATE jardinera SET jarNombre='$nombre', jarDescripcion='$descripcion' WHERE idJardinera='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar los datos de una jardinera por parte de un administrador
        function actualizarJardineraAdmin($id, $nombre, $descripcion, $fase){
            $conexion=abrirConexionDB();

            $query="UPDATE jardinera SET jarNombre='$nombre', jarDescripcion='$descripcion', idFase='$fase' WHERE idJardinera='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
        
        //Función para actualizar el estado de una jardinera
        function actualizarEstadoJardinera($id, $usuario, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE jardinera SET jarEstado='$estado' WHERE idJardinera='$id'";
            $resultadoActualizacionJardinera=mysqli_query($conexion, $query);

            if($resultadoActualizacionJardinera){
                $datosUsuario=consultarDatosUsuario($usuario);

                $cantidadJardineras=$datosUsuario["usuCantidadJardineras"];

                $queryActualizacionUsuario="UPDATE usuario SET usuCantidadJardineras='$cantidadJardineras' WHERE usuNumeroDocumento='$usuario'";
                $resultadoActualizacionUsuario=mysqli_query($conexion, $queryActualizacionUsuario);

                if($resultadoActualizacionUsuario){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        //Funcion para actualizar la evolucion de la jardinera (porcentaje y fase)
        function actualizarEvolucionJardinera($idJardinera, $porcentaje, $nuevaFase){
            $conexion=abrirConexionDB();

            $query="UPDATE jardinera SET jarPorcentajeEvolucion='$porcentaje', idFase='$nuevaFase' WHERE idJardinera='$idJardinera'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* ALERTAS */
        //Función para registrar la alerta generada por el sistema
        function registrarAlerta($fecha, $tipo, $descripcion, $recomendacion, $valorRegistrado, $rangoRecomendado, $id){
            $conexion=abrirConexionDB();

            $query="INSERT INTO alerta (alerFecha, alerTipo, alerDescripcion, alerRecomendacion, alerValorRegistrado, alerRangoRecomendado, idJardinera) 
            VALUES('$fecha','$tipo','$descripcion', '$recomendacion', '$valorRegistrado', '$rangoRecomendado', '$id')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar las alertas activas de un usuario por su id 
        function consultarAlertas($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM alerta 
            INNER JOIN jardinera ON alerta.idJardinera=jardinera.idJardinera
            INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla 
            INNER JOIN usuario ON jardinera.usuNumeroDocumento=usuario.usuNumeroDocumento 
            WHERE usuario.usuNumeroDocumento='$usuario' AND
            alerta.alerEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la existencia de una alerta activa de un tipo específico para una jardinera
        function existeAlertaPorTipo($tipo, $id){
            $conexion = abrirConexionDB();

            $query = "SELECT idAlerta FROM alerta WHERE alerTipo = '$tipo'  AND idJardinera = '$id' LIMIT 1";
            $resultado = mysqli_query($conexion, $query);

            return mysqli_num_rows($resultado) > 0;
        }

        //Función para consultar el tipo de alerta de una jardinera
        function consultarTiposAlertaJardinera($idJardinera){

            $conexion = abrirConexionDB();

            $query = "SELECT alerTipo
                    FROM alerta
                    WHERE idJardinera = '$idJardinera' AND alerEstado='Activa'";

            return mysqli_query($conexion, $query);
        }
    
        //Función para actualizar el estado de la alerta
        function actualizarEstadoAlerta($id){
            $conexion=abrirConexionDB();

            $query="UPDATE alerta SET alerEstado='Inactiva' WHERE idAlerta='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la cantidad de alertas de una usuario activo
        function consultarCantidadAlertasUsuario($usuario){
            $conexion=abrirConexionDB();

            $query="SELECT COUNT(idAlerta) AS cantidadAlertas FROM alerta 
            INNER JOIN jardinera ON alerta.idJardinera=jardinera.idJardinera
            WHERE jardinera.usuNumeroDocumento='$usuario'
            AND alerta.alerEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* FACTORES EXTERNOS */
        //Función para consultar los factores externos registrados por el id de la jardinera
        function consultarFactoresExternosPorJardinera($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM factores_externos WHERE idJardinera='$id' ORDER BY idFactoresExternos ASC LIMIT 3";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los factores externos registrados por el id de la jardinera y su estado sea registrado
        function consultarFactoresExternosPorJardineraAlertas($id){
            $conexion = abrirConexionDB();

            $query = "SELECT * FROM factores_externos WHERE idJardinera = '$id' AND factEstado = 'Registrado'";

            return mysqli_query($conexion,$query);
        }

        //Función para consultar todos los factores externos
        function consultarTodasFactoresExternos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM factores_externos
            INNER JOIN jardinera ON factores_externos.idJardinera=jardinera.idJardinera
            INNER JOIN tipo_clima ON factores_externos.idTipoClima=tipo_clima.idTipoClima";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar un factor externo por su id
        function consultarDatosFactorExterno($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM factores_externos
            INNER JOIN jardinera ON factores_externos.idJardinera=jardinera.idJardinera
            INNER JOIN tipo_clima ON factores_externos.idTipoClima=tipo_clima.idTipoClima WHERE factores_externos.idFactoresExternos='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Funcion para insertar el factor externo en la base de datos
        function agregarFactoresExternos($id, $humedad, $cantidadAgua, $temperatura, $clima){
            $conexion=abrirConexionDB();

            $query="INSERT INTO factores_externos (factHumedad,idTipoClima, factTemperatura, factCantidadAgua, idJardinera) VALUES('$humedad','$clima','$temperatura', '$cantidadAgua', '$id')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar un factor externo
        function actualizarFactorExterno($id, $humedad,  $tipoClima, $temperatura, $cantidadAgua){
            $conexion=abrirConexionDB();

            $query="UPDATE factores_externos SET factHumedad='$humedad', idTipoClima='$tipoClima', factTemperatura='$temperatura', factCantidadAgua='$cantidadAgua' WHERE idFactoresExternos='$id' ";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de un factor externo por el administrador
        function actualizarEstadoFactorExterno($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE factores_externos SET factEstado='$estado' WHERE idFactoresExternos='$id' ";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado del factor externo tras ser evaluado para generar una alerta
        function actualizarEstadoFactoresExternos($id){
            $conexion=abrirConexionDB();

            $query="UPDATE factores_externos SET factEstado='Evaluado' WHERE idFactoresExternos='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/

    /* SEGUIMIENTO - EVOLUCION JARDINERA */
        //Función para consultar el registro del seguimiento de una jardinera por su id
        function consultarEvolucionPorJardinera($idJardinera){
            $conexion=abrirConexionDB();

            $query="SELECT segJardineraFecha, segJardineraNota, segJardineraImagen, segJardineraPorcentaje FROM seguimiento_jardinera WHERE idJardinera='$idJardinera' ORDER BY segJardineraFecha ASC";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar todos los seguimientos registrados de todas las jardineras
        function consultarTodasSeguimientos(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM seguimiento_jardinera 
            INNER JOIN jardinera ON seguimiento_jardinera.idJardinera=jardinera.idJardinera";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar los datos de un monitoreo por su id, retorna un arreglo
        function consultarDatosMonitoreo($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM seguimiento_jardinera WHERE idSeguimiento='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para agregar la evolucion de la jardinera
        function agregarEvolucionJardinera($id, $nota, $imagen, $porcentaje){
            $conexion=abrirConexionDB();

            $fechaActual=recuperarFechaActual();

            $query="INSERT INTO seguimiento_jardinera (segJardineraFecha, segJardineraNota, segJardineraImagen, segJardineraPorcentaje, idJardinera) 
            VALUES('$fechaActual', '$nota', '$imagen', '$porcentaje', '$id')";
            $resultado=mysqli_query($conexion, $query);

            if($resultado==true){
                return $fechaActual;
            }else{
                return false;
            }   
        }

        //Función para actualizar un monitoreo por parte de un administrador
        function actualizarMonitoreoAdmin($id,$nota,$rutaImagen,$porcentaje ){
            $conexion=abrirConexionDB();

            $query="UPDATE seguimiento_jardinera SET segJardineraNota='$nota', segJardineraImagen='$rutaImagen', segJardineraPorcentaje='$porcentaje' WHERE idSeguimiento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado del monitoreo por parte de un administrador
        function actualizarEstadoMonitoreo($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE seguimiento_jardinera SET segJardineraEstado='$estado' WHERE idSeguimiento='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado del registro de una evolucion de una jardinera
        function actualizarEstadoEvolucionJardinera($idJardinera){
            $conexion=abrirConexionDB();

            $query="UPDATE seguimiento_jardinera SET segJardineraEstado='Inactiva' WHERE idJardinera='$idJardinera' AND segJardineraEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado; 
        }
    /**/

    /* FASES */
        //Función para consultar los datos de una fase por su id, retorna un arreglo
        function consultarDatosFase($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM fase WHERE idFase='$id' ";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=mysqli_fetch_assoc($resultado);
            }

            return $datos; 
        }

        //Función para consultar la fase por su id
        function consultarFaseJardinera($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM fase WHERE idFase='$id' ";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para consultar todas las fases activas del sistema
        function consultarTodasFasesActivas(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM fase WHERE faseEstado='Activa'";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para consultar todas las fases del sistema
        function consultarTodasFases(){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM fase";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para consultar la existencia de una fase por su nombre
        function consultarExistenciaFase($nombre){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM fase WHERE faseNombre='$nombre'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para registrar una nueva fase en el sistema
        function registrarFase($nombre, $descripcion, $porcentaje){
            $conexion=abrirConexionDB();

            $query="INSERT INTO fase (faseNombre, faseDescripcion, fasePorcentaje) VALUES('$nombre', '$descripcion', '$porcentaje')";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para actualizar una fase por su id 
        function actualizarFase($id, $nombre,$descripcion, $porcentaje ){
            $conexion=abrirConexionDB();

            $query="UPDATE fase SET faseNombre='$nombre', faseDescripcion='$descripcion', fasePorcentaje='$porcentaje' WHERE idFase='$id'";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }

        //Función para actualizar el estado de una fase 
        function actualizarEstadoFase($id, $estado){
            $conexion=abrirConexionDB();

            $query="UPDATE fase SET faseEstado='$estado' WHERE idFase='$id'";
            $resultado=mysqli_query($conexion, $query);
            
            return $resultado;
        }
    /**/

    /* PREGUNTAS FASE*/
        //Función para consultar las preguntas de una fase en específico
        function consultarPreguntasPorFase($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM pregunta
            INNER JOIN fase ON pregunta.idFase=fase.idFase
            WHERE pregunta.idFase='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para consultar la existencia de una fase por la descripción de su pregunta
        function consultarExistenciaPreguntaFase($pregunta){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM pregunta WHERE pregDescripcion='$pregunta'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                return true;
            }else{
                return false;
            }
        }

        //Función para consultar los datos de una pregunta de una fase por su id, retorna un arreglo
        function consultarDatosPreguntaFase($id){
            $conexion=abrirConexionDB();

            $query="SELECT * FROM pregunta
            INNER JOIN fase ON pregunta.idFase=fase.idFase
            WHERE pregunta.idPregunta='$id'";
            $resultado=mysqli_query($conexion, $query);

            if(mysqli_num_rows($resultado)>0){
                $datos=arregloDatos($resultado);
            }

            return $datos;
        }

        //Función para registrar una nueva pregunta
        function registrarPreguntaFase($pregunta, $porcentaje, $idFase){
            $conexion=abrirConexionDB();

            $query="INSERT INTO pregunta (pregDescripcion, pregPorcentaje, idFase) VALUES ('$pregunta', '$porcentaje', '$idFase')";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar una pregunta
        function actualizarPreguntaFase($id, $pregunta, $porcentaje){
            $conexion=abrirConexionDB();

            $query="UPDATE pregunta SET pregDescripcion='$pregunta', pregPorcentaje='$porcentaje' WHERE idPregunta='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }

        //Función para actualizar el estado de la pregunta
        function actualizarEstadoPreguntaFase($id,$estado){
            $conexion=abrirConexionDB();

            $query="UPDATE pregunta SET pregEstado='$estado' WHERE idPregunta='$id'";
            $resultado=mysqli_query($conexion, $query);

            return $resultado;
        }
    /**/    
?>