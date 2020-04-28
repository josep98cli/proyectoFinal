<?php
    session_start();
    //variable de session que guarda la información de el usuario registrado
    $usuarioLogeado = $_SESSION['usuarioLogeado'];
    
    include '../configuration_server/propierties.php';
    include '../configuration_server/functionsAcces.php';

    //consulta per a accedir a les ciutats del jugador logejat
    //consulta para llamar a la informacion de las ciudades

    $infoCiutats = $models->execute_kw($db, $uid, $password,
        'game.ciutat', 'read',
    array($usuarioLogeado['ciutat']),
    array('fields'=>array('name', 'vida', 'soldado', 'mines', 'recursos')));

    ?>

<DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="../css/estilsPagPrincipal.css">
    </head>

    </body>

    <header>
        <ul id="navCiudades">
            <?php
            //utilizo este contador para seleccionar la imagen de la ciudad que va numerada
            $cont = 1;
                //el if es para que no se pase del numero de imagenes que hay
                if ($cont>6) {
                    $cont = 0;
                }else{
                     //recorro las ciudades del jugador logeado y las muestro por pantalla
                     foreach($infoCiutats as $ciutats){
                        echo '<li class="liCiudades"><a  href="./ciudad.php?idCiudad='.$ciutats['id'].'&numImg='.$cont.'" class="ciudades">'.$ciutats['name'];
                            //Consulta para acceder a los datos de las ciudades que estan relacionados con otras tablas como recursos en este caso
                            $infoRecursos = $models->execute_kw($db, $uid, $password,
                            'game.recursos', 'read',
                            array($ciutats['recursos']),
                            array('fields'=>array('name', 'cantidad', 'recurs')));
                            
                            echo "<div id='recursos'>";
                            foreach($infoRecursos as $recursos){
                                echo "<div id='recurs'>";
                                    echo $recursos['name'];
                                    echo "<p class='cantidadRecurs'>".$recursos['cantidad']."</p>";
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "<img src='../images/imagesCiudad/ciudad$cont.jpeg' alt='ciudad' height='185px' width='300px'/>";
                            $cont++;
                        echo '</a>';
                        echo '</li>';
                    }
                }
                
            ?>
        </ul>
        <form action="../procesaFormularios/procesapagUsuarioLogin.php" method="POST">
            <input type="submit" name="deslogearse" value="Log on"/>
        </form>
    </header>
        
    <body>
</html>
