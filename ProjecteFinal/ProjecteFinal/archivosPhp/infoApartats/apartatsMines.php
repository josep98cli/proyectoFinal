
<?php

    foreach($inf as $inf2){ 
        $infoCiutat = $models->execute_kw($db, $uid, $password,
                            'game.mines', 'read',
                            array($inf2['mines']),
                            array('fields'=>array('name', 'produccion', 'nivel','coste', 'minutos', 'const_percent', 'mejora')));
                            
        foreach($infoCiutat as $infCiudad2){
                echo "<div id='divMina'>";
                    echo $infCiudad2['name'];
                    echo '<br>';
                    echo "Nivel: ".$infCiudad2['nivel'];
                    echo '<br>';
                    echo "Produccion: ".$infCiudad2['produccion'];

                    echo "<a class='upgradeMina' href='./ciudad.php?idCiudad=".$ciudad."&numImg=".$numImg."&seleccion=minas&upgrade=".True."&infoUpgrade=".$infCiudad2['id']."'>Upgrade</a>";
                echo "</div>";
                echo "<hr/>";
        }
    
    }
?>


