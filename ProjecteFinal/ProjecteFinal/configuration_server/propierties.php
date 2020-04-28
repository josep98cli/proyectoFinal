<?php
        
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    
        $url = 'http://192.168.2.109:8069';
        $db = "game";
        $username = "admin";
        $password = "admin";

        require_once('../ripcord-master/ripcord.php');
        $common = ripcord::xmlrpcClient($url.'/xmlrpc/2/common');
        $uid = $common->authenticate($db, $username, $password, array());
        $models = ripcord::xmlrpcClient("$url/xmlrpc/2/object");
?>