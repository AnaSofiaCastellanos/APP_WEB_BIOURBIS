<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Agregar Jardinera | BioUrbis</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php
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
    ?>
</body>
</html>