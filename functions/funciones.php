<?php 
    //Función para recuperar la fecha actual del equipo
    function recuperarFechaActual(){
        //Recuperar la fecha y hora actual del sistema
        date_default_timezone_set("America/Bogota");
        $fechaActual=date('Y-m-d');

        return $fechaActual;
    }

    //Función para abrir la conexion a la base de datos
    function abrirConexionDB(){
        $host="localhost";
        $username="root";
        $dbName="biourbis_db";
        $password="";
        
        $conexion_db=mysqli_connect($host,$username, $password,$dbName);

        if(!$conexion_db){
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }

        return $conexion_db;
    }

    //Función para cerrar la conexion a la base de datos
    function cerrarConexionDB($conexion){
        mysqli_close($conexion);
    }

    //Función para crear arreglos con todos los datos de una consulta
    function arregloDatos($resultadoConsulta){
        $datos=mysqli_fetch_array($resultadoConsulta);

        return $datos;
    }

    
    function calcularDiasEntreFechas($fechaMin, $fechaMax){
        $fecha1= new DateTime($fechaMin);
        $fecha2= new DateTime($fechaMax);

        $diferencia=$fecha1->diff($fecha2);

        return $diferencia->days;
    }

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
    /* SEMILLAS */

    //Función para consultar todas las semillas de la base de datos
    function consultarSemillas(){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM semilla";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar la existencia de una semilla específica por su nombre
    function consultarExistenciaSemillaPorNombre($semillaNombre){
        $conexion=abrirConexionDB();

        $query="SELECT semNombre FROM semilla WHERE semNombre='$semillaNombre'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar la existencia de una semilla por su id
    function consultarExistenciaSemilla($idSemilla){
        $conexion=abrirConexionDB();

        $query="SELECT idSemilla FROM semilla WHERE idSemilla='$idSemilla'";
        $resultado=mysqli_query($conexion, $query);

        if($resultado && mysqli_num_rows($resultado) > 0){
            return true;
        }else{
            return false;
        }
    }

    //Función para consultar la información de una semilla específica
    function consultarDatosSemilla($idSemilla){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM semilla 
        INNER JOIN tipo_semilla ON tipo_semilla.idTipoSemilla=semilla.idTipoSemilla
        WHERE idSemilla='$idSemilla'";
        $resultado=mysqli_query($conexion, $query);

        $datos=arregloDatos($resultado);

        return $datos;
    }

    //Función para consultar el tipo de semilla de cada semilla
    function consultarTipoSemilla($idTipoSemilla){
        $conexion=abrirConexionDB();

        $query="SELECT tipoSemDescripcion FROM tipo_semilla WHERE idTipoSemilla='$idTipoSemilla'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar la ficha técnica de una semilla específica
    function consultarFichaTecnicaSemilla($idSemilla){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM ficha_tecnica 
        INNER JOIN tipo_tierra ON tipo_tierra.idTipoTierra=ficha_tecnica.idTipoTierra
        INNER JOIN tipo_clima ON tipo_clima.idTipoClima=ficha_tecnica.idTipoClima
        WHERE idSemilla='$idSemilla'";
        $resultado=mysqli_query($conexion, $query);

        $datos=arregloDatos($resultado);

        return $datos;
    }

    //Función para consultar la etapa de crecimiento de una semilla específica
    function consultarEtapaCrecimientoSemilla($idEtapa){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM etapas_crecimiento WHERE idEtapaCrecimiento='$idEtapa'";
        $resultado=mysqli_query($conexion, $query); 

        $datos=arregloDatos($resultado);

        return $datos;
    }

    //Función para consultar el tipo de tierra de cada semilla
    function consultarTipoTierra($idTipoTierra){
        $conexion=abrirConexionDB();

        $query="SELECT tipoTierraDescripcion FROM tipo_tierra WHERE idTipoTierra='$idTipoTierra'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar todos los tipo de tierra
    function consultarTiposTierra(){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM tipo_tierra";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar el tipo de clima de cada semilla
    function consultarTipoClima($idTipoClima){
        $conexion=abrirConexionDB();

        $query="SELECT tipoClimaDescripcion FROM tipo_clima WHERE idTipoClima='$idTipoClima'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar los datos de un tipo de clima de cada semilla
    function consultarDatosTipoClima($idTipoClima){
        $conexion=abrirConexionDB();

        $query="SELECT tipoClimaDescripcion FROM tipo_clima WHERE idTipoClima='$idTipoClima'";
        $resultado=mysqli_query($conexion, $query);

        if(mysqli_num_rows($resultado)){
            $datos=arregloDatos($resultado);
        }

        return $datos;
    }

    //Función para consultar todos los tipos de clima
    function consultarTiposClima(){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM tipo_clima";
        $resultado=mysqli_query($conexion, $query);
        
        return $resultado;
    }

    /* USUARIOS */

    //Función para consultar el tipo de documento del usuario
    function consultarTipoDocumento($idTipoDocumento){
        $conexion=abrirConexionDB();

        $query="SELECT tipoDocDescripcion FROM tipo_documento WHERE idTipoDocumento='$idTipoDocumento'";
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

    //Función para consultar si un usuario existe en la base de datos
    function consultarUsuarioExistente($numeroDocumento){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM usuario WHERE usuNumeroDocumento='$numeroDocumento'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar los datos del usuario
    function consultarDatosUsuario($numeroDocumento){
        $conexion=abrirConexionDB();

        $resultadoConsulta=consultarUsuarioExistente($numeroDocumento);

        $datosUsuario=arregloDatos($resultadoConsulta);

        $idTipoDocumento=$datosUsuario["idTipoDocumento"];
        $resultadoConsultarTipoDocumento=consultarTipoDocumento($idTipoDocumento);

        if(mysqli_num_rows($resultadoConsultarTipoDocumento)){
            $datosTipoDocumento=mysqli_fetch_array($resultadoConsultarTipoDocumento);
            $datosUsuario["idTipoDocumento"]=$datosTipoDocumento["tipoDocDescripcion"];
        }

        return $datosUsuario;
    }

    //Función para actualizar la contraseña del usuario
    function actualizarContrasena($numeroDocumento, $contrasena){
        $conexion=abrirConexionDB();

        $contrasena=md5($contrasena);
        $query="UPDATE usuario SET usuContrasena='$contrasena' WHERE usuNumeroDocumento='$numeroDocumento'";
        $resultado=mysqli_query($conexion, $query);

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

    //Funcion para consultar todas las solicitudes asociadas a un usuario en estado pendiente
    function consultarSolicitudes($usuario){
        $conexion=abrirConexionDB();

        $query="SELECT soliFecha, soliAsunto, soliSemilla, soliDescripcion, soliEstado, usuNumeroDocumento FROM solicitud WHERE usuNumeroDocumento='$usuario' AND soliEstado='Pendiente'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para registrar una solicitud en la base de datos con el estado pendiente
    function registrarSolicitud($tipoSolicitud, $mensaje){

        $conexion=abrirConexionDB();
        $fechaActual=recuperarFechaActual();

        $query="INSERT INTO solicitud (soliFecha, soliAsunto, soliDescripcion, soliEstado) VALUES('$fechaActual', '$tipoSolicitud', '$mensaje', 'Pendiente')";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para agregar la ruta de la imagen de perfil del usuario a la base de datos
    function agregarImagenPerfil($usuario, $rutaImagen){
        $conexion=abrirConexionDB();

        $query="UPDATE usuario SET usuImagen='$rutaImagen' WHERE usuNumeroDocumento='$usuario'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para consultar la cantidad de alertas de una usuario activo
    function consultarCantidadAlertasUsuario($usuario){
        $conexion=abrirConexionDB();

        $query="SELECT COUNT(idAlerta) AS cantidadAlertas FROM alerta INNER JOIN jardinera 
        ON alerta.idJardinera=jardinera.idJardinera WHERE jardinera.usuNumeroDocumento='$usuario'
        AND alerta.alerEstado='Activa'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    /*JARDINERAS */

    //Función para agregar una jardinera a la base de datos
    function agregarJardinera($nombre, $descripcion, $semilla, $usuario){
        $conexion=abrirConexionDB();

        $fechaActual=recuperarFechaActual();

        $query="INSERT INTO jardinera (jarNombre, jarDescripcion, jarFechaCreacion, idFase, jarPorcentajeEvolucion, idSemilla, usuNumeroDocumento) 
        VALUES('$nombre', '$descripcion', '$fechaActual', '1', '10', '$semilla', '$usuario' )";
        $resultado=mysqli_query($conexion, $query);

        return $resultado; 
    }

    //Función para consultar la información básica de la jardinera por el usuario
    function consultarJardineras($usuario){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM jardinera WHERE usuNumeroDocumento='$usuario'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Función para consultar los datos de la tabla fase
    function consultarDatosFaseJardinera($fase){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM fase WHERE idFase='$fase' ";
        $resultado=mysqli_query($conexion, $query);

        if(mysqli_num_rows($resultado)){
            $datos=mysqli_fetch_array($resultado);
        }

        return $datos; 
    }

     //Función para la fase de la jardinera
    function consultarFaseJardinera($fase){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM fase WHERE idFase='$fase' ";
        $resultado=mysqli_query($conexion, $query);
        
        return $resultado;
    }

    //Función para consultar los datos de la jardinera por el usuario, retorna un arreglo
    function consultarDatosJardineras($usuario){
        $conexion=abrirConexionDB();

        $resultadoConsulta=consultarJardineras($usuario);

        $datosJardinera=arregloDatos($resultadoConsulta);

        $idSemilla=$datosJardinera["idSemilla"];
        $idFase=$datosJardinera["idFase"];

        $resultadoConsultarFase=consultarDatosFaseJardinera($idFase);
        if(mysqli_num_rows($resultadoConsultarFase)){
            $datosFase=mysqli_fetch_array($resultadoConsultarFase);
            $datosJardinera["idFase"]=$datosFase["faseNombre"];
        }

        $datosSemilla=consultarDatosSemilla($idSemilla);

        $datosJardinera["idSemilla"]=$datosSemilla["semNombre"];

        return $datosJardinera;
    }

    //Función para consultar los datos de la jardinera por su id, retorna un arreglo
    function consultarDatosJardineraPorId($idJardinera){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM jardinera 
        INNER JOIN usuario ON jardinera.usuNumeroDocumento=usuario.usuNumeroDocumento
        INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla
        INNER JOIN fase ON jardinera.idFase=fase.idFase
        WHERE idJardinera='$idJardinera'";

        $resultado=mysqli_query($conexion, $query);

        $datos=arregloDatos($resultado);

        return $datos;
    }

    //Función para consultar todas las jardineras con sus factores externos y evolución
    function consultarJardinerasConDetalles($usuario){
        $conexion=abrirConexionDB();

        $query="SELECT jardinera.*, semilla.semNombre AS semNombre, fase.faseNombre AS faseNombre FROM jardinera 
        INNER JOIN semilla ON jardinera.idSemilla=semilla.idSemilla
        INNER JOIN fase ON jardinera.idFase=fase.idFase
        WHERE jardinera.usuNumeroDocumento='$usuario'";

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

    //Función para actualizar los datos de una jardinera
    function actualizarJardinera($idJardinera, $nombre, $descripcion){
        $conexion=abrirConexionDB();

        $query="UPDATE jardinera SET jarNombre='$nombre', jarDescripcion='$descripcion' WHERE idJardinera='$idJardinera'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para actualizar la evolucion de la jardinera (porcentaje y fase)
    function actualizarEvolucionJardinera($idJardinera, $porcentaje, $nuevaFase){
        $conexion=abrirConexionDB();

        $query="UPDATE jardinera SET jarPorcentajeEvolucion='$porcentaje', idFase='$nuevaFase' WHERE idJardinera='$idJardinera'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    /* FACTORES EXTERNOS */

    //Funcion para insertar el factor externo en la base de datos
    function agregarFactoresExternos($idJardinera, $humedad, $cantidadAgua, $temperatura, $clima){
        $conexion=abrirConexionDB();

        $query="INSERT INTO factores_externos (factHumedad,idTipoClima, factTemperatura, factCantidadAgua, idJardinera) VALUES('$humedad','$clima','$temperatura', '$cantidadAgua', '$idJardinera')";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para consultar los factores externos registrados por el id de la jardinera
    function consultarFactoresExternosPorJardinera($idJardinera){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM factores_externos WHERE idJardinera='$idJardinera' ORDER BY idFactoresExternos ASC LIMIT 3";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }
    
    
    //Funcion para actualizar el estado del factor externo tras ser evaluado para generar una alerta
    function actualizarEstadoFactoresExternos($idFactoresExternos){
        $conexion=abrirConexionDB();

        $query="UPDATE factores_externos SET factEstado='Evaluado' WHERE idFactoresExternos='$idFactoresExternos'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    /* ALERTAS */

    //Funcion para registrar la alerta generada
    function registrarAlerta( $tipo, $descripcion, $recomendacion, $valorRegistrado, $rangoRecomendado, $idJardinera){
        $conexion=abrirConexionDB();

        $fechaActual=recuperarFechaActual();

        $query="INSERT INTO alerta (alerFecha, alerTipo, alerDescripcion, alerRecomendacion, alerValorRegistrado, alerRangoRecomendado, idJardinera) 
        VALUES('$fechaActual','$tipo','$descripcion', '$recomendacion', '$valorRegistrado', '$rangoRecomendado', '$idJardinera')";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para consultar las alertas activas de un usuario
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

    function existeAlertaPorTipo($tipo, $idJardinera){
        $conexion = abrirConexionDB();

        $query = "SELECT idAlerta FROM alerta 
                WHERE alerTipo = '$tipo' 
                AND idJardinera = '$idJardinera'
                LIMIT 1";

        $resultado = mysqli_query($conexion, $query);

        return mysqli_num_rows($resultado) > 0;
    }

   
    //Funcion para actualizar el estado de la alerta
    function actualizarEstadoAlerta($idAlerta){
        $conexion=abrirConexionDB();

        $query="UPDATE alerta SET alerEstado='Inactiva' WHERE idAlerta='$idAlerta'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    /* SEGUIMIENTO - EVOLUCION JARDINERA */

    //Funcion para consultar las preguntas de una fase en especifico
    function consultarPreguntasPorFase($idFase){
        $conexion=abrirConexionDB();

        $query="SELECT * FROM pregunta
        INNER JOIN fase ON pregunta.idFase=fase.idFase
        WHERE pregunta.idFase='$idFase'";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para agregar la evolucion de la jardinera
    function agregarEvolucionJardinera($idJardinera, $nota, $imagen, $porcentaje){
        $conexion=abrirConexionDB();

        $fechaActual=recuperarFechaActual();

        $query="INSERT INTO seguimiento_jardinera (segJardineraFecha, segJardineraNota, segJardineraImagen, segJardineraPorcentaje, idJardinera) 
        VALUES('$fechaActual', '$nota', '$imagen', '$porcentaje', $idJardinera)";
        $resultado=mysqli_query($conexion, $query);

        if($resultado==true){
            return $fechaActual;
        }else{
            return false;
        }   
    }

    //Funcion para actualizar el estado del registro de una evolucion de una jardinera
    function actualizarEstadoEvolucionJardinera($idJardinera){
        $conexion=abrirConexionDB();

        $query="UPDATE seguimiento_jardinera SET segJardineraEstado='Inactiva' WHERE idJardinera='$idJardinera' AND segJardineraEstado='Activa'";
        $resultado=mysqli_query($conexion, $query);

       return $resultado; 
    }

    //Funcion para consultar el registro del seguimiento de una jardinera
    function consultarEvolucionPorJardinera($idJardinera){
        $conexion=abrirConexionDB();

        $query="SELECT segJardineraFecha, segJardineraNota, segJardineraImagen, segJardineraPorcentaje FROM seguimiento_jardinera WHERE idJardinera='$idJardinera' ORDER BY segJardineraFecha ASC";
        $resultado=mysqli_query($conexion, $query);

        return $resultado;
    }

    //Funcion para calcular la tendencia de crecimiento de una jardinera
    function calcularTendencia($porcentajes){

        $tendencia = [];

        for($i = 1; $i < count($porcentajes); $i++){
            $tendencia[] = $porcentajes[$i] - $porcentajes[$i - 1];
        }

        return $tendencia;
    }
?>