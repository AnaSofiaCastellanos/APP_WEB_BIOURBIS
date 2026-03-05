<?php
    //Incluir la conexión a la base de datos
    include("../db/conexion.php");
    //Incluir las funciones de la app
    include("../functions/funciones.php");

    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!--Logotipo pestaña-->
    <link rel="shortcut icon" href="../images/img_logotipo.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script_mostrarMensaje.js"></script>
</head>
<body>
    <?php 
        //Recuperar los datos usuario en el formulario
        $numDocumento=$_POST["numDocumento"]; 
        $contrasena=$_POST["contrasena"];

        //Encriptar la contraseña del usuario
        $contrasena=md5($contrasena);

        //Consulta para validar si existe un registro en la tabla usuarios con esos datos
        $queryValidarDatos="SELECT * FROM usuario WHERE usuNumeroDocumento='$numDocumento' AND usuContrasena='$contrasena'";
        $resultadoValidarDatos=mysqli_query($conexion_db, $queryValidarDatos);

        //Encriptar la contraseña del usuario
        $contrasena=password_hash($contrasena,PASSWORD_BCRYPT);

        if(mysqli_num_rows($resultadoValidarDatos)){

            $datosUsuario=arregloDatos($resultadoValidarDatos);
            //Validar el estado de la cuenta del usuario
            if($datosUsuario["usuEstado"]==="Activo"){ ?>
                <!--Redirección al perfil del usuario-->
                <script> window.location.replace("../php/homeUsuario.php");</script>
                <?php
                $_SESSION["numeroDocumento"]=$numDocumento;
            }else{
                $_SESSION["alerta"]="cuentaInactiva";
            }
            
        }else{ 
            $_SESSION["alerta"]="credencialesIncorrectas";
        }

        //Ejecutar mensajes emergentes
        if(isset($_SESSION["alerta"])){
            switch ($_SESSION["alerta"]) {
                case 'credencialesIncorrectas': ?>
                    <script>
                        //Mensaje cuando la cuenta del usuario ingresa un número de identificación o contraseña incorrectos
                        mostrarMensaje({
                            title:"¡Los datos ingresados no son válidos!",
                            text:"Revise su información y vuelva a intentarlo",
                            icon:"error",
                                                            
                            //Si el usuario acepta volver a ingresar sus credenciales
                            rutaTrue:"../forms/formAcceso.php",

                            //Si el usuario no acepta volver a ingresar sus credenciales
                            rutaFalse:"../forms/formAcceso.php"
                        })
                    </script>
                    <?php
                break;
                case "cuentaInactiva": ?>
                    <script>
                        //Mensaje cuando la cuenta del usuario se encuentra inactiva
                        mostrarMensaje({
                            title:"¡Su cuenta se encuentra inactiva!",
                            text:"Lo invitamos a comunicarse con un administrador para comenzar su proceso de reactivación",
                            icon:"error",
                                                            
                            //Si el usuario acepta enviar la solicitud
                            rutaTrue:"../index.php",

                            //Si el usuario no acepta enviar la solicitud
                            rutaFalse:"../forms/formAcceso.php"
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