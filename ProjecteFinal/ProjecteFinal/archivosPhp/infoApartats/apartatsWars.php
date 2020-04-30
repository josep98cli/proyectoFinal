<?php
     foreach($inf as $inf2){ 
       
        $info = $models->execute_kw($db, $uid, $password,
                            'game.ciutat', 'busqueda_points',
                            array('values'),
                            array('values'=>$inf2['id']));
        var_dump($info); 
    }

?>