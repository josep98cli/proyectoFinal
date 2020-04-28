<?php
    session_start();
    include '../configuration_server/propierties.php';
    include '../configuration_server/functionsAcces.php';

    isset($_POST['nombre']) ? $nomUsuario= $_POST['nombre']: $nomUsuario="";
    isset($_POST['contrasena']) ? $contrasena= $_POST['contrasena']: $contrasena="";
    isset($_POST['contrasena2']) ? $contrasena2= $_POST['contrasena2']: $contrasena2="";

    /* EXEMPLE PER A PASAR PARAMETRES A UNA FUNCIÓ
    // afegim les dades a variables o podem gastar les que ja tenim
    $value = $nomUsuario;

    //en el primer array posem el nom de la variable que anem a pasar i en el segon posem el nom de la variable a que es refereix en el odoo
    $value = $models->execute_kw($db, $uid, $password,
    'game.crea_jugador', 'crear_jugador',
    array("$value"), array('values'=> $value));
    */
    
    $nomUsuariRegistrat = false;
    foreach ($infoJug as $infoJug2){
        if (strcmp($nomUsuario, $infoJug2['name'])==0) {
            $nomUsuariRegistrat = true;
        }
    }
    
    if (!$nomUsuariRegistrat) {
        if ($contrasena == $contrasena2) {
            /* Consulta que crea un jugador en la base de datos */
            $models->execute_kw($db, $uid, $password,
            'res.partner', 'create',
            array(array('name'=>"$nomUsuario", 'password'=>"$contrasena", 'is_player'=>true, 'default_is_player'=>true)));
            
            header('Location: ../index.php');
            die();

        }else{
            header('Location: ../archivosPhp/formularioRegistro.php?falloRegistro=Las contraseñas no coinciden');
            die();
        }
    }else{
        header('Location: ../archivosPhp/formularioRegistro.php?falloRegistro=Nombre de usuario existente');
            die();
    }
    
    
    
?>