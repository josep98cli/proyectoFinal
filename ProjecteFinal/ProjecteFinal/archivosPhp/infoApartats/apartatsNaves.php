
<?php
foreach($inf as $inf2){ 
       
                            
        $infoNaves = $models->execute_kw($db, $uid, $password,
            'game.naves', 'read',
            array($inf2['naves']),
            array('fields'=>array('name', 'cant_tropas', 'cantidad')));

        echo '<div id="navesDiv">';
            foreach($infoNaves as $naves){
                echo "<div id='nave'>";
                    echo $naves['name'];
                        echo "<br/>";
                    echo "Cantidad de tropas: ". $naves['cant_tropas'];
                        echo "<br/>";
                    echo "Coste compra: ".$naves['cantidad'];
                        echo "<br/>";
                    echo "<a class='buySoldados' href='./ciudad.php?idCiudad=".$ciudad."&numImg=".$numImg."&seleccion=$seleccion&comprarNaves=".True."&naves=".$naves['id']."'>Comprar</a>";
                
                echo '</div>';
                echo "<hr/>";

            }
        echo '</div>';
       
    
    
    }

?>