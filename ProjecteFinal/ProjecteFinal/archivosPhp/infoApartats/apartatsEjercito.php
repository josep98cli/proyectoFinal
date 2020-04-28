<?php
     foreach($inf as $inf2){ 
        $infoSoldado = $models->execute_kw($db, $uid, $password,
                            'game.soldado', 'read',
                            array($inf2['soldado']),
                            array('fields'=>array('name', 'cant_tropas', 'cantidad')));
                            
        $infoNaves = $models->execute_kw($db, $uid, $password,
            'game.naves', 'read',
            array($inf2['naves']),
            array('fields'=>array('name')));

        echo '<div id="soldadoDiv">';
            foreach($infoSoldado as $soldado){
                echo "<div id='soldado'>";
                    echo $soldado['name'];
                        echo "<br/>";
                    echo "Cantidad de tropas: ". $soldado['cant_tropas'];
                        echo "<br/>";
                    echo "Coste compra: ".$soldado['cantidad'];
                        echo "<br/>";
                    echo "<a class='buySoldados' href='./ciudad.php?idCiudad=".$ciudad."&numImg=".$numImg."&seleccion=$seleccion&comprarSoldado=".True."&soldado=".$soldado['id']."'>Comprar</a>";
                
                echo '</div>';
                echo "<hr/>";

            }
        echo '</div>';
       
    
    
    }
?>