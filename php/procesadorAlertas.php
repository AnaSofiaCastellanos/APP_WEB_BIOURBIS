<?php
    //Consulta para validar si el usuario tiene jardineras registradas, si no tiene, no se ejecuta el proceso de alertas
    $resultadoConsultarJardineras=consultarJardineras($usuarioActivo);

    //Mientras el usuario tenga jardineras registradas y activas
    while($datosJardineras=mysqli_fetch_array($resultadoConsultarJardineras)){

        //Recuperar el id de la jardinera
        $idJardinera=$datosJardineras["idJardinera"];

        //Consulta para validar si la jardinera tiene factores externos registrados, si no tiene, no se ejecuta el proceso de alertas
        $resultadoConsultarFactoresExternos=consultarFactoresExternosPorJardinera($idJardinera);
 
        if(mysqli_num_rows($resultadoConsultarFactoresExternos)){
        
            //Recuperar el id de la semilla
            $idSemilla=$datosJardineras["idSemilla"];

            //Mientras la jardinera tenga factores externos registrados
            while($datosFactoresExternos=mysqli_fetch_array($resultadoConsultarFactoresExternos)){

                //Evalua si el estado del factor externo es "Registrado", si el estado es diferente, no se ejecuta el proceso de alertas para evitar generar alertas repetidas por el mismo registro
                if($datosFactoresExternos["factEstado"]=="Registrado"){

                    //Arreglo con los datos basicos de la semilla
                    $datosSemilla=consultarDatosSemilla($idSemilla);

                    //Arreglo con los datos de la ficha técnica de la semilla
                    $datosFichaTecnica=consultarFichaTecnicaSemilla($idSemilla);

                    //Consultar el tipo de clima seleccionado por el usuario en el registro del factor externo
                    $datosTipoClima=consultarDatosTipoClima($datosFactoresExternos["idTipoClima"]);

                    //Actualizar el valor del id por la descripcion obtendida de la consulta
                    $descripcionTipoClima=$datosTipoClima["tipoClimaDescripcion"];

                    //Evalua si la humedad registrada se encuentra dentro del rango recomendado para el crecimiento de la planta
                    if($datosFactoresExternos["factHumedad"]<$datosFichaTecnica["fichaHumedadMin"] || $datosFactoresExternos["factHumedad"]>$datosFichaTecnica["fichaHumedadMax"]){  
                        $mensajeAlerta="La húmedad registrada no es adecuada para el crecimiento de la planta";
                        $valorRegistrado=$datosFactoresExternos["factHumedad"];
                        $rangoRecomendado=$datosFichaTecnica["fichaHumedadMin"]."º - ".$datosFichaTecnica["fichaHumedadMax"]."º";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($mensajeAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);
                    }

                    //Evalua si la temperatura registrada se encuentra dentro del rango recomendado para el crecimiento de la planta
                    if($datosFactoresExternos["factTemperatura"]<$datosFichaTecnica["fichaTemperaturaMin"] || $datosFactoresExternos["factTemperatura"]>$datosFichaTecnica["fichaTemperaturaMax"]){
                        $mensajeAlerta="La temperatura registrada no es adecuada para el crecimiento de la planta";
                        $valorRegistrado=$datosFactoresExternos["factTemperatura"];
                        $rangoRecomendado=$datosFichaTecnica["fichaTemperaturaMin"]."º - ".$datosFichaTecnica["fichaTemperaturaMax"]."º";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($mensajeAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);
                    }

                    //Evalua si la cantidad de agua registrada se encuentra dentro del rango recomendado para el crecimiento de la planta
                    if($datosFactoresExternos["factCantidadAgua"]<$datosFichaTecnica["fichaCantidadAguaMin"] || $datosFactoresExternos["factCantidadAgua"]>$datosFichaTecnica["fichaCantidadAguaMax"]){
                        $mensajeAlerta="La cantidad de agua registrada no es adecuada para el crecimiento de la planta";
                        $valorRegistrado=$datosFactoresExternos["factCantidadAgua"];
                        $rangoRecomendado=$datosFichaTecnica["fichaCantidadAguaMin"]."ml - ".$datosFichaTecnica["fichaCantidadAguaMax"]."ml";

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($mensajeAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);
                    }

                    //Evalua si el tipo de clima registrado es adecuado para el crecimiento de la planta
                    if($datosFactoresExternos["idTipoClima"]!=$datosFichaTecnica["idTipoClima"]){
                        $mensajeAlerta="El tipo de clima registrado no es adecuado para el crecimiento de la planta";
                        $valorRegistrado=$descripcionTipoClima;

                        $datosTipoClimaFicha=consultarDatosTipoClima($datosFichaTecnica["idTipoClima"]);
                        $rangoRecomendado=$datosTipoClimaFicha["tipoClimaDescripcion"];

                        //Registra la alerta generada en la tabla de alertas de la base de datos
                        registrarAlerta($mensajeAlerta, $valorRegistrado, $rangoRecomendado,$idJardinera);
                    }

                    //Actualizar el estado del factor externo a "Evaluado" para evitar que se generen alertas repetidas por el mismo registro
                    actualizarEstadoFactoresExternos($datosFactoresExternos["idFactoresExternos"]);      
                }    
            }
        }      
    }      
?>