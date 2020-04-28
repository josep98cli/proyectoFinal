<?php
//incluyo la configuracion del servidor en los archivos los cuales van a usar datos 
//de la base de datos para procesar operaciones
session_start();

include '../configuration_server/propierties.php';
include '../configuration_server/functionsAcces.php';

//con estos dos issets se comprueba el usuario y la contraseña si existen
isset($_POST['nombre'])? $nomLogin=$_POST['nombre'] : $nomLogin="empty";
isset($_POST['contrasena'])? $contrasena=$_POST['contrasena'] : $contrasena="empty";
 

if(isset($_POST['registrar'])){
    header('Location: ../archivosPhp/formularioRegistro.php');
    die();
}else{
    //inicializo la variable error que si al acabar el bucle no encuentra al usuario devuelve false
    //si lo encuentra devuelve true
    $errorNotFoundPlayer = false;
    //esta comprobacion hace que si el nombre de usuario no es introducida rediriga al login y salte un error 
    if (strcmp($nomLogin, "")!==0) {
        //esta comprobacion hace que si el nombre de usuario introducido no existe en la base de datos da el error notfoundplayer

        /*La variable infoJug esta declarada en el fichero functionsAcces que son funciones estandares que vamos a 
        utilizar en más de una ocasión,  así ahorramos codigo*/
        foreach ($infoJug as $inf2){
            //comparo todos los jugadores hasta que encuentro el que se esta logeando, en caso de no encontrarlo suelta error de notFoundPlayer
            if (strcasecmp($inf2['name'], $nomLogin)==0) {
                $errorNotFoundPlayer = true;
                
                //comparo la contraseña con empty para asegurarme que no esta vacia y a la vez la comparo con la del user que esta intentando logearse
                //en caso de que falle alguna comprobacion de las dos devuelve error de contraseña
                if(strcmp($contrasena,"empty")!==0 && strcmp($contrasena,"")!==0 && strcmp($inf2['password'], $contrasena)==0){
                    //redireccion a archivo donde se muestran las ciudades del jugador

                    //guardo la información del usuario logeado en una variable de session para poder acceder en diferentes ficheros php 
                    $_SESSION['usuarioLogeado']= $inf2;
                    //una vez almacenado la información del usuario redirigo a la pagina principal del juego
                    header('Location: ../archivosPhp/pagUsuarioLogin.php');
                    die();
                }else{
                   header('Location: ../index.php?error=Contraseña incorrecta');
                   die();
                }
            }
        }
        //redirige al login si no ha encontrado ningun jugador con este nombre de usuario
        if(!$errorNotFoundPlayer){
            header('Location: ../index.php?error=Usuario no encontrado');
            die();
        }
    }else{
        header('Location: ../index.php?error=Usuario no introducido');
        die();   
    }
}
?>
