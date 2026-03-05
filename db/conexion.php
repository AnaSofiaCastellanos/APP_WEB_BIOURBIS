<?php 
    $host="localhost";
    $username="root";
    $dbName="biourbis_db";
    $password="";

    $conexion_db=mysqli_connect($host,$username, $password,$dbName);

    if(!$conexion_db){
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }
?>