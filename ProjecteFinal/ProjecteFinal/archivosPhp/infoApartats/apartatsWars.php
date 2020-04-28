<?php
     foreach($inf as $inf2){ 
        $infoCiutat = $models->execute_kw($db, $uid, $password,
                            'game.wars', 'read',
                            array($inf2['wars']),
                            array('fields'=>array('atacante')));
        
            var_dump($infoCiutat);
    
    }

?>