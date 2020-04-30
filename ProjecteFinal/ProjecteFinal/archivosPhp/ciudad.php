<?php

isset($_GET['idCiudad'])? $ciudad=$_GET['idCiudad'] : $ciudad="empty";
isset($_GET['numImg'])? $numImg=$_GET['numImg'] : $numImg="empty";
isset($_GET['upgrade'])? $upgradear=$_GET['upgrade'] : $upgradear=false;
isset($_GET['infoUpgrade'])? $infoUpgrade=$_GET['infoUpgrade'] : $infoUpgrade="";
isset($_GET['comprarSoldado'])? $comprarSoldado=$_GET['comprarSoldado'] : $comprarSoldado=false;
isset($_GET['soldado'])? $idSoldado=$_GET['soldado'] : $idSoldado="empty";
isset($_GET['comprarNaves'])? $comprarNave=$_GET['comprarNaves'] : $comprarNave=false;
isset($_GET['naves'])? $idNave=$_GET['naves'] : $idNave="empty";

?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="../css/estilosCiudad.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
        <?php
         
            include '../configuration_server/propierties.php';
            include '../configuration_server/functionsAcces.php';
        ?>

        <div id="bloqueCiudad">
            <div id="navOpciones">
                <div id="apartatNav1" class="parte1">
                    <a class ="classNav" href="./ciudad.php?idCiudad=<?php echo $ciudad?>&numImg=<?php echo $numImg?>&seleccion=minas">Minas</a>
                </div>
                <div id="apartatNav2" class="parte1">
                    <a class ="classNav" href="./ciudad.php?idCiudad=<?php echo $ciudad?>&numImg=<?php echo $numImg?>&seleccion=recursos">Recursos</a>
                </div>
                <div id="apartatNav3" class="parte1">
                    <a class ="classNav" href="./ciudad.php?idCiudad=<?php echo $ciudad?>&numImg=<?php echo $numImg?>&seleccion=soldados">Soldados</a>
                </div>
                <div id="apartatNav4" class="parte1">
                    <a class ="classNav" href="./ciudad.php?idCiudad=<?php echo $ciudad?>&numImg=<?php echo $numImg?>&seleccion=naves">Naves</a>
                </div>
                <div id="apartatNav5" class="parte1">
                    <a class ="classNav" href="./ciudad.php?idCiudad=<?php echo $ciudad?>&numImg=<?php echo $numImg?>&seleccion=wars">Guerras</a>
                </div>
            </div>

            <div id="fondoCiudad" style="background: url('../images/imagesCiudad/ciudad<?php echo $numImg?>.jpeg'); background-repeat:no-repeat; ">

            </div>
            
            <div id="inferiorBloque">
                    <?php
                       isset($_GET['seleccion'])? $seleccion=$_GET['seleccion'] : $seleccion="empty";

                       $inf = $models->execute_kw($db, $uid, $password,
                       'game.ciutat', 'search_read',
                       array(array(array('id', '=', $ciudad))),
                       array('fields'=>array('name', 'recursos', 'mines', 'soldado', 'naves', 'wars')));
                       switch ($seleccion) {
                           case 'minas':

                            include './infoApartats/apartatsMines.php';

                            if($upgradear==1){
                                echo "ENtra";
                                $inf = $models->execute_kw($db, $uid, $password,
                                'game.mines', 'llamar_calc_cantidad',
                                array(array($infoUpgrade)));
                                header("Location: ./ciudad.php?idCiudad=$ciudad&numImg=$numImg&seleccion=$seleccion");
                                die();
                            }
                            

                            break;
                            
                            case 'recursos':
                                    include './infoApartats/apartatsRecursos.php';
                            break;
                            
                            case 'soldados':
                                    include './infoApartats/apartatsSoldado.php';
                                 if($comprarSoldado==1){
                                    
                                    $soldadoComprado = $models->execute_kw($db, $uid, $password,
                                    'game.soldado', 'comprar_soldado',
                                    array(array($idSoldado)));
                                    
                                    header("Location: ./ciudad.php?idCiudad=$ciudad&numImg=$numImg&seleccion=$seleccion");
                                    die();
                                 }   
                                    

                            break;

                            case 'naves':
                                
                                    include './infoApartats/apartatsNaves.php';  
                                    
                                    if($comprarNave==1){
                                        $naveComprada = $models->execute_kw($db, $uid, $password,
                                        'game.naves', 'comprar_naves',
                                        array(array($idNave)));
                                        
                                        header("Location: ./ciudad.php?idCiudad=$ciudad&numImg=$numImg&seleccion=$seleccion");
                                        die();
                                     }   
                            break;
                            
                            case 'wars':
                                    include './infoApartats/apartatsWars.php';
                            break;
                       }
                             
                            
                    ?>
                     
            </div>

        </div>
        <form action="../procesaFormularios/procesapagUsuarioLogin.php" method="POST">
                        <input type="submit" name="deslogearse" value="Log on"/>
                    </form>
    </body>
</html>