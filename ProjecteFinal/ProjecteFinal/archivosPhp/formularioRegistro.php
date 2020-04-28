<DOCTYPE html>

<html>
  
    <head>
    
    <link rel="stylesheet" type="text/css" href="../css/estilsFormulariRegistro.css">
    </head>

    </body>
        <div id="registro">
            <?php
                isset($_GET['falloRegistro'])? $error=$_GET['falloRegistro'] : $error="";
                
            ?>
           
            <form action="../procesaFormularios/procesaRegistro.php" method="POST">
                <h3>Registro</h3>
                <?php echo '<p class="error">'.$error.'</p>'; ?>
                <br/>
                <input type="text" id="nombre" class="inputLogin" placeholder="Nombre de usuario" name="nombre" required/>
                <br/>
                <input type="password" id="contrasena" class="inputLogin" placeholder="Contraseña" name="contrasena" minlength="7" required/>
                <br/>
                <input type="password" id="contrasena" class="inputLogin" placeholder="Repita la contraseña" name="contrasena2" minlength="7" required/>
                <br/>
                <input type="text" id="correo" class="inputLogin" placeholder="Correo electronico" name="correo"/>
                <br/>
                <input name="registrar" type="submit" class="buttonLogin" value="Registro"/>
            </form>
        </div>
    <body>
</html>