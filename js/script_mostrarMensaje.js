function mostrarMensaje(config){
    const scrollY=window.scrollY;
    
    Swal.fire({
        //Ubicación del mensaje emergente
        position: 'center',
        scrollbarPadding: false, 
        allowOutsideClick:false, //No permitir el cierre de la pantalla fuera de la misma

        //Botón de confirmación
        showConfirmButton: true, //Permitir que aparezca el botón de confirmar
        confirmButtonText: "Aceptar",// Cambiar el texto del botón de confirmar
        confirmButtonAriaLabel: "Aceptar",

        //Botón de cancelación
        showCancelButton: true, //Permitir que aparezca el botón para cancelar el mensaje
        cancelButtonText: "No, gracias", // Cambiar el texto del botón de cancelar
        cancelButtonAriaLabel:"No, gracias",

        //Botón para cerrar la alerta
        showCloseButton: true, //Boton para cerrar la alerta

        //Icono
        iconColor:"rgb(184, 98, 12)", //Modificar color del icono

        //Estilos personalizados
        customClass:{
            title:"tituloMensaje", //Estilos para el título del mensaje 
            confirmButton:"redireccion", //Color del botón para confirmar
        },

        ...config
    }).then (resultado=>{
        window.scrollTo(0,scrollY);

        if(resultado.isConfirmed && config.rutaTrue){
            window.location.replace(config.rutaTrue);

        }else if (resultado.isDismissed && config.rutaFalse){
        window.location.replace(config.rutaFalse);
        }
    });
}