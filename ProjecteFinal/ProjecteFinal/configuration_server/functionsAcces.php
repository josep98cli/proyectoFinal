<?php

//consulta de listado de todos los jugadores
$idJugadores = $models->execute_kw(
    $db,
    $uid,
    $password,
    'res.partner',
    'search',
    array(array(array('is_player', '=', true))));

//consulta de listado del nombre y las ciudades de los jugadores
$infoJug = $models->execute_kw($db, $uid, $password,
    'res.partner', 'read',
    array($idJugadores),
    array('fields'=>array('name', 'ciutat', 'password', 'fecha_creacion', 'fecha_muerte')));





?>