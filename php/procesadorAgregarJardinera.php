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
        $queryConsultarCantidadJardineras="SELECT usuCantidadJardineras FROM usuario WHERE usuNumeroDocumento='$usuarioActivo'";
        $resultadoConsultarCantidadJardineras=mysqli_query($conexion_db, $queryConsultarCantidadJardineras);

        //Evalua si encuentra un registro del usuario activo para verificar la cantidad de jardineras que tiene
        if(mysqli_num_rows($resultadoConsultarCantidadJardineras)){
            $datosConsulta=arregloDatos($resultadoConsultarCantidadJardineras);

            //Evalua si el usuario activo ha alcanzado el limite de jardineras permitidas para registrar
            if($datosConsulta["usuCantidadJardineras"]>=5){
                $_SESSION["alerta"]="limiteJardineras";
            }else{
                //El sistema procede a recuperar los datos y registrar una nueva jardinera para el usuario activo
                
                $nombreJardinera=$_POST["gardenName"];
                $descripcionJardinera=$_POST["gardenDescription"];
                $semillaJardinera=$_POST["gardenSeed"];

                //El sistema convierte el nombre de la jardinera a formato título para mantener una presentación uniforme en los nombres de las jardineras
                $nombreJardinera=ucwords(strtolower($nombreJardinera));

                //Funcion para agregar una nueva jardinera a la base de datos, recibe como parámetros el nombre, descripción, semilla y el número de documento del usuario activo
                $resultadoAgregarJardinera=agregarJardinera($nombreJardinera, $descripcionJardinera, $semillaJardinera, $usuarioActivo);

                //Si el resultado de la consulta es exitoso
                if($resultadoAgregarJardinera==true){
                    //Consulta para recuperar la cantidad de jardineras que tiene el usuario activo
                    $datosUsuario=consultarDatosUsuario($usuarioActivo);
                    //Recuperar la cantidad de jardineras del usuario
                    $cantidadJardineras=$datosUsuario["usuCantidadJardineras"];

                    //Incrementar la cantidad de jardineras que tiene el usuario
                    $cantidadJardineras=$cantidadJardineras+1;

                    //Actualizar la cantidad de jardineras del usuario activo en la base de datos
                    $queryActualizarUsuario="UPDATE usuario SET usuCantidadJardineras='$cantidadJardineras' WHERE usuNumeroDocumento='$usuarioActivo'";
                    $resultadoActualizarUsuario=mysqli_query($conexion_db, $queryActualizarUsuario);

                    //Si el resultado de la actualizacion es exitosa
                    if($resultadoActualizarUsuario==true){
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