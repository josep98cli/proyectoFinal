<div id="login">
        
        <form action="./procesaFormularios/processaLogin.php" method="POST">
            <h3>Login</h3>
            <img src="./images/imagesLogin/loginImage.jpg" width="80px">
            <br/>
            
            <?php //muestra el error en caso de que el processador de formulario detecte algun fallo
            if($error !== "empty"){echo '<h3 class="error">'.$error.'</h3>';}?>

            <input type="text" id="nombre" class="inputLogin" placeholder="Usuario" name="nombre"/>
            <br/>
            <input type="password" id="contrasena" class="inputLogin" placeholder="Contraseña" name="contrasena" minlength="7"/>

            <br/>
            <input name="logear" type="submit" class="buttonLogin" value="Login"/>
            <input name="registrar" type="submit" class="buttonLogin" value="Registro"/>
        </form>
            
    </div>