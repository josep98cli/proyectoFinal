<?php
   
    foreach($inf as $inf2){ 
        $infoCiutat = $models->execute_kw($db, $uid, $password,
                            'game.recursos', 'read',
                            array($inf2['recursos']),
                            array('fields'=>array('name', 'cantidad', 'recurs')));
           
            echo '<table>';
            foreach($infoCiutat as $recursos){
                echo '<tr>';
                    echo '<th>'.$recursos['name'].'</th>';
                    echo '<td>'.$recursos['cantidad'].'</td>';
                echo '<br/>';
            }
            echo '</table>';
    
    }
?>