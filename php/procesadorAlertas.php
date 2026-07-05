<?php
    //Consulta para validar si el usuario tiene jardineras registradas, si no tiene, no se ejecuta el proceso de alertas
    $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);

    //Obtener la fecha actual del equipo y su zona horaria
    $fechaActual=recuperarFechaActual();

    //Mientras el usuario tenga jardineras registradas y activas
    while($datosJardineras=mysqli_fetch_assoc($resultadoConsultarJardineras)){

        //Recuperar el id de la jardinera
        $idJardinera=$datosJardineras["idJardinera"];
 
        //Recuperar el id de la semilla
        $idSemilla=$datosJardineras["idSemilla"];

        //Arreglo con los datos basicos de la semilla
        $datosSemilla=consultarDatosSemilla($idSemilla);

        $alertasExistentes = [];

        $resultadoAlertas = consultarTiposAlertaJardinera($idJardinera);

        while($alerta = mysqli_fetch_assoc($resultadoAlertas)){
            $alertasExistentes[$alerta["alerTipo"]] = true;
        }

        //Consulta para validar si la jardinera tiene factores externos registrados, si no tiene, no se ejecuta el proceso de alertas
        $resultadoConsultarFactoresExternos=consultarFactoresExternosPorJardineraAlertas($idJardinera);
 
        if(mysqli_num_rows($resultadoConsultarFactoresExternos)>0){

            //Arreglo con los datos de la ficha técnica de la semilla
            $datosFichaTecnica=consultarFichaTecnicaSemilla($idSemilla);

            $humedadMin = $datosFichaTecnica["fichaHumedadMin"];
            $humedadMax = $datosFichaTecnica["fichaHumedadMax"];

            $tempMin = $datosFichaTecnica["fichaTemperaturaMin"];
            $tempMax = $datosFichaTecnica["fichaTemperaturaMax"];

            $aguaMin = $datosFichaTecnica["fichaCantidadAguaMin"];
            $aguaMax = $datosFichaTecnica["fichaCantidadAguaMax"];

            $climaIdeal = $datosFichaTecnica["idTipoClima"];

            $climaFicha = consultarDatosTipoClima($datosFichaTecnica["idTipoClima"]);

            $descripcionClimaRecomendado = $climaFicha["tipoClimaDescripcion"];

            //Mientras la jardinera tenga factores externos registrados
            while($datosFactoresExternos=mysqli_fetch_assoc($resultadoConsultarFactoresExternos)){
                //Consultar el tipo de clima seleccionado por el usuario en el registro del factor externo
                $datosTipoClima=consultarDatosTipoClima($datosFactoresExternos["idTipoClima"]);

                //Actualizar el valor del id por la descripcion obtendida de la consulta
                $descripcionTipoClima=$datosTipoClima["tipoClimaDescripcion"];

                //Evalua si la humedad registrada es menor humedad minima permitida para el crecimiento de la planta
                if($datosFactoresExternos["factHumedad"]<$humedadMin){  

                    //Establecer el tipo de la alerta
                    $tipo="bajaHumedad";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "El nivel de humedad es inferior al recomendado, lo que puede afectar el desarrollo de la planta";
                        $recomendacionAlerta="Se recomienda aumentar ligeramente la frecuencia de riego o ubicar la planta en un ambiente con mayor humedad";
                        $valorRegistrado=$datosFactoresExternos["factHumedad"];
                        $rangoRecomendado=$humedadMin."º - ".$humedadMax."º";
                            
                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        //Registrar la actividad del usuario
                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);
                        
                        $alertasExistentes[$tipo] = true;
                    }
                    
                //Evalua si la humedad registrada es mayor humedad maxima permitida para el crecimiento de la planta
                }elseif($datosFactoresExternos["factHumedad"]>$humedadMax){

                    //Establecer el tipo de la alerta
                    $tipo="altaHumedad";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "El nivel de humedad es superior al recomendado y podría impactar negativamente la salud de la planta";
                        $recomendacionAlerta="Se recomienda reducir la frecuencia de riego y asegurar una adecuada ventilación para evitar exceso de humedad";
                        $valorRegistrado=$datosFactoresExternos["factHumedad"];
                        $rangoRecomendado=$humedadMin."º - ".$humedadMax."º";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                }

                //Evalua si la temperatura registrada es menor a la temperatura minima permitida para el crecimiento de la planta
                if($datosFactoresExternos["factTemperatura"]<$tempMin){

                    //Establecer el tipo de la alerta
                    $tipo="bajaTemperatura";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La temperatura se encuentra por debajo del rango recomendado, lo que puede afectar el crecimiento de la planta.";
                        $recomendacionAlerta="Se recomienda ubicar la planta en un lugar más cálido o con mayor exposición a la luz solar";
                        $valorRegistrado=$datosFactoresExternos["factTemperatura"];
                        $rangoRecomendado=$tempMin."º - ".$tempMax."º";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Evalua si la temperatura registrada es mayor a la temperatura maxima permitida para el crecimiento de la planta
                }elseif($datosFactoresExternos["factTemperatura"]>$tempMax){

                    //Establecer el tipo de la alerta
                    $tipo="altaTemperatura";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La temperatura supera el rango recomendado y podría generar estrés en la planta.";
                        $recomendacionAlerta="Se recomienda trasladar la planta a un lugar más fresco o con sombra, evitando la exposición directa al sol";
                        $valorRegistrado=$datosFactoresExternos["factTemperatura"];
                        $rangoRecomendado=$tempMin."º - ".$tempMax."º";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }  
                }

                //Evalua si la cantidad de agua registrada es menor a cantidad minima de agua permitida para el crecimiento de la planta
                if($datosFactoresExternos["factCantidadAgua"]<$aguaMin){

                    //Establecer el tipo de la alerta
                    $tipo="bajaCantidadAgua";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La cantidad de agua es inferior a la recomendada, lo que puede afectar el crecimiento de la planta.";
                        $recomendacionAlerta="Se recomienda aumentar ligeramente la cantidad o frecuencia de riego, asegurando que el suelo se mantenga húmedo sin encharcarse";
                        $valorRegistrado=$datosFactoresExternos["factCantidadAgua"];
                        $rangoRecomendado=$aguaMin."ml - ".$aguaMax."ml";
                            
                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Evalua si la cantidad de agua registrada es mayor a cantidad maxima de agua permitida para el crecimiento de la planta
                }elseif($datosFactoresExternos["factCantidadAgua"]>$aguaMax){

                    //Establecer el tipo de la alerta
                    $tipo="altaCantidadAgua";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La cantidad de agua es superior a la recomendada y podría afectar la salud de la planta.";
                        $recomendacionAlerta="Se recomienda reducir la frecuencia de riego y verificar que el drenaje sea adecuado para evitar acumulación de agua";
                        $valorRegistrado=$datosFactoresExternos["factCantidadAgua"];
                        $rangoRecomendado=$datosFichaTecnica["fichaCantidadAguaMin"]."ml - ".$datosFichaTecnica["fichaCantidadAguaMax"]."ml";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                }

                //Evalua si el tipo de clima registrado es adecuado para el crecimiento de la planta
                if($datosFactoresExternos["idTipoClima"]!=$climaIdeal){

                    //Establecer el tipo de la alerta
                    $tipo="climaInadecuado";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="Las condiciones climáticas actuales no son las más adecuadas para el desarrollo de la planta";
                        $recomendacionAlerta="Se recomienda ubicar la planta en un entorno más acorde a sus necesidades, ajustando factores como la exposición al sol, la ventilación o la protección frente a cambios bruscos del clima";
                        $valorRegistrado=$descripcionTipoClima;

                        $rangoRecomendado=$descripcionClimaRecomendado;;

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($fechaActual, $tipo, $mensajeAlerta, $recomendacionAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                }

                //Actualizar el estado del factor externo a "Evaluado" para evitar que se generen alertas repetidas por el mismo registro
                actualizarEstadoFactoresExternos($datosFactoresExternos["idFactoresExternos"]);        
            }
        } 

        //Arreglo con los datos de la etapa de crecimiento de la semilla
        $datosEtapaCrecimiento=consultarDatosEtapaCrecimiento($datosSemilla["idEtapaCrecimiento"]);

        //Calcular la cantidad de dias que han pasado desde la creacion de la jardinera hasta la fecha actual
        $diasCreacion=calcularDiasEntreFechas($datosJardineras["jarFechaCreacion"], $fechaActual );

        //Evaluar la fase en que se encuentra la jardinera
        switch($datosJardineras["idFase"]){
            
            //Fase Germinacion
            case 1:
                //Obtener los dias minimos y maximos de la fase actual (Germinacion)
                $diasMin=$datosEtapaCrecimiento["etapaCreDiasGerminacionMin"];
                $diasMax=$datosEtapaCrecimiento["etapaCreDiasGerminacionMax"];

                //Obtener los dias minimos y maximos de la proxima fase (Desarrollo Vegetativo)
                $diasMinProximaFase=$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"];
                $diasMaxProximaFase=$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"];

                //Si se encuentra proximo a iniciar la proxima etapa (Desarrollo Vegetativo)
                if($diasCreacion>=($diasMinProximaFase-5) && $diasCreacion<= $diasMaxProximaFase){
                    //Establecer el tipo de la alerta
                    $tipo="proximoDesarrolloVegetativo";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="Su jardinera está próxima a entrar en la fase de desarrollo vegetativo, prepárase para un crecimiento más activo";
                        $recomendacionAlerta="Asegúrase de mantener condiciones estables de riego, luz y temperatura para favorecer el inicio de esta nueva etapa";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra en la etapa (Germinacion)
                }else if($diasCreacion >= $diasMin && $diasCreacion <= ($diasMax - 5)){ 

                    //Establecer el tipo de la alerta
                    $tipo="enGerminacion";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La jardinera se encuentra en la fase de germinación, etapa inicial en la que comienza el desarrollo de la planta";
                        $recomendacionAlerta="Se recomienda mantener una humedad constante en el sustrato y evitar la exposición directa a condiciones extremas para favorecer la germinación";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra culminando la etapa (Germinacion)
                }else if($diasCreacion > ($diasMax - 5) && $diasCreacion <= $diasMax){

                    //Establecer el tipo de la alerta
                    $tipo="terminandoGerminacion";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="La jardinera está próxima a finalizar la fase de germinación y avanzar a la siguiente etapa de crecimiento";
                        $recomendacionAlerta="Se recomienda mantener condiciones estables de humedad y luz, preparando el entorno para el desarrollo inicial de la planta";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                }
            break;

            //Fase Desarrollo Vegetativo
            case 2:
                //Obtener los dias minimos y maximos de la fase actual (Desarrollo Vegetativo)
                $diasMin=$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMin"];
                $diasMax=$datosEtapaCrecimiento["etapaCreDiasDesarrolloVegetativoMax"];

                //Obtener los dias minimos y maximos de la proxima fase (Floración)
                $diasMinProximaFase=$datosEtapaCrecimiento["etapaCreDiasFloracionMin"];
                $diasMaxProximaFase=$datosEtapaCrecimiento["etapaCreDiasFloracionMax"];

                //Si se encuentra proximo a iniciar la etapa (Floración)
                if($diasCreacion>=($diasMinProximaFase-5) && $diasCreacion<= $diasMaxProximaFase){

                    //Establecer el tipo de la alerta
                    $tipo="proximoFloracion";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="Su jardinera está próxima a entrar en la fase de floración, prepárase para un crecimiento más activo";
                        $recomendacionAlerta="Asegúrase de mantener condiciones estables de riego, luz y temperatura para favorecer el inicio de esta nueva etapa";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra en la etapa (Desarrollo Vegetativo)
                }else if($diasCreacion >= $diasMin && $diasCreacion <= ($diasMax - 5)){

                    //Establecer el tipo de la alerta
                    $tipo="enDesarrolloVegetativo";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="Su jardinera se encuentra en la fase de desarrollo vegetativo, donde el crecimiento es constante y visible";
                        $recomendacionAlerta="Manténga un riego constante y buena exposición a la luz, ya que esta es una etapa clave para el crecimiento de la planta";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                    //Si se encuentra culminando la etapa (Desarrollo Vegetativo)
                }else if($diasCreacion > ($diasMax - 5) && $diasCreacion <= $diasMax){

                    //Establecer el tipo de la alerta
                    $tipo="terminandoDesarrolloVegetativo";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta="Su jardinera está próxima a culminar la fase de desarrollo vegetativo y pasar a la siguiente etapa";
                        $recomendacionAlerta="Continue con los cuidados actuales y prepárase para ajustar las condiciones según la siguiente fase de crecimiento";
                       
                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                }
            break;

            //Fase Floracion
            case 3: 
                //Obtener los dias minimos y maximos de la fase actual (Floración)
                $diasMin=$datosEtapaCrecimiento["etapaCreDiasFloracionMin"];
                $diasMax=$datosEtapaCrecimiento["etapaCreDiasFloracionMax"];

                //Obtener los dias minimos y maximos de la proxima fase (Llenado de Granos)
                $diasMinProximaFase=$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"];
                $diasMaxProximaFase=$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"];

                //Si se encuentra proximo a iniciar la etapa (Llenado de Granos)
                if($diasCreacion>=($diasMinProximaFase-5) && $diasCreacion<= $diasMaxProximaFase){

                    //Establecer el tipo de la alerta
                    $tipo="proximoLlenadoGranos";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera está próxima a iniciar la fase de llenado de granos";
                        $recomendacionAlerta = "Se recomienda mantener condiciones adecuadas de luz y riego para favorecer el inicio de esta etapa";       

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                //Si se encuentra en la etapa (Floración)
                }else if($diasCreacion >= $diasMin && $diasCreacion <= ($diasMax - 5)){

                    //Establecer el tipo de la alerta
                    $tipo="enFloracion";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera se encuentra en la fase de floración";
                        $recomendacionAlerta = "Se recomienda mantener un riego equilibrado y una adecuada exposición a la luz para favorecer el desarrollo de las flores";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }
                                    
                //Si se encuentra culminando la etapa (Floración)
                }else if($diasCreacion > ($diasMax - 5) && $diasCreacion <= $diasMax){

                    //Establecer el tipo de la alerta
                    $tipo="terminandoFloracion";
                
                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera está próxima a culminar la fase de floración";
                        $recomendacionAlerta = "Se recomienda continuar con los cuidados actuales y preparar las condiciones necesarias para la siguiente etapa del cultivo";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }                   
                }
            break;

            //Fase Llenado de Granos
            case 4: 
                //Obtener los dias minimos y maximos de la fase actual (Llenado de Granos)
                $diasMin=$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMin"];
                $diasMax=$datosEtapaCrecimiento["etapaCreDiasLlenadoGranosMax"];

                //Obtener los dias minimos y maximos de la proxima fase (Cosecha)
                $diasMinProximaFase=$datosEtapaCrecimiento["etapaCreDiasCosechaMin"];
                $diasMaxProximaFase=$datosEtapaCrecimiento["etapaCreDiasCosechaMax"];

                //Si se encuentra proximo a iniciar la etapa (Cosecha)
                if($diasCreacion>=($diasMinProximaFase-5) && $diasCreacion<= $diasMaxProximaFase){

                    //Establecer el tipo de la alerta
                    $tipo="proximoCosecha";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera está próxima a iniciar la fase de cosecha";
                        $recomendacionAlerta = "Se recomienda mantener condiciones adecuadas de riego y nutrientes para favorecer el desarrollo inicial de los granos";
                       
                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra en la etapa (Llenado de Granos)
                }else if($diasCreacion >= $diasMin && $diasCreacion <= ($diasMax - 5)){

                    //Establecer el tipo de la alerta
                    $tipo="enLlenadoGranos";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera se encuentra en la fase de llenado de granos";
                        $recomendacionAlerta = "Se recomienda asegurar un riego adecuado y condiciones estables que favorezcan el desarrollo óptimo de los granos";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra culminando la etapa (Llenado de Granos)
                }else if($diasCreacion > ($diasMax - 5) && $diasCreacion <= $diasMax){
                    
                    //Establecer el tipo de la alerta
                    $tipo="terminandoLlenadoGranos";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera está próxima a finalizar la fase de llenado de granos";
                        $recomendacionAlerta = "Se recomienda mantener los cuidados actuales y preparar la transición hacia la siguiente etapa del cultivo";
                       
                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }                   
                }
            break;

            //Fase Cosecha
            case 5: 
                //Obtener los dias minimos y maximos de la fase actual (Cosecha)
                $diasMin=$datosEtapaCrecimiento["etapaCreDiasCosechaMin"];
                $diasMax=$datosEtapaCrecimiento["etapaCreDiasCosechaMax"];

                //Si se encuentra proxima a terminar su ciclo de crecimiento
                if($diasCreacion>=($diasMin-5) && $diasCreacion<= $diasMax){

                    //Establecer el tipo de la alerta
                    $tipo="proximoCulminarCiclo";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera ha completado su ciclo de crecimiento";
                        $recomendacionAlerta = "Se recomienda finalizar la cosecha y preparar la jardinera para un nuevo ciclo de cultivo";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra en la etapa (Cosecha)
                }else if($diasCreacion >= $diasMin && $diasCreacion <= ($diasMax - 5)){

                    //Establecer el tipo de la alerta
                    $tipo="enCosecha";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera se encuentra en la fase de cosecha";
                        $recomendacionAlerta = "Se recomienda realizar la recolección en el momento oportuno para asegurar la calidad del cultivo";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }

                //Si se encuentra culminando la etapa (Cosecha)
                }else if($diasCreacion > ($diasMax - 5) && $diasCreacion <= $diasMax){

                    //Establecer el tipo de la alerta
                    $tipo="terminandoCosecha";

                    if(!isset($alertasExistentes[$tipo])){
                        $mensajeAlerta = "La jardinera está próxima a finalizar la fase de cosecha";
                        $recomendacionAlerta = "Se recomienda completar la recolección y cerrar adecuadamente el ciclo del cultivo";

                        registrarAlerta($fechaActual, $tipo,$mensajeAlerta,$recomendacionAlerta,null,null,$idJardinera);

                        registrarActividadUsuario("Alerta","Generar", "El sisteama generó una alerta activa  para la jardinera Nº{$idJardinera}", $usuarioActivo);

                        $alertasExistentes[$tipo] = true;
                    }                   
                }
            break;
        }   
    }      
?>