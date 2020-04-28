<DOCTYPE html>

<html>
    <?php
        session_start();
        
        isset($_GET['error'])? $error=$_GET['error'] : $error="empty";
        isset($_SESSION['usuarioLogeado'])? $usuarioLogeado=$_SESSION['usuarioLogeado'] : $usuarioLogeado = "";
       
    ?>
    <head>
    <link rel="stylesheet" type="text/css" href="./css/estilsIndex.css">
    </head>

    </body>
       <?php
            if (!empty($usuarioLogeado)) {
                header('Location: ./archivosPhp/pagUsuarioLogin.php');
                die();
            }else{
                include './archivosPhp/login.php';
            }
       ?>  
    <body>
</html>