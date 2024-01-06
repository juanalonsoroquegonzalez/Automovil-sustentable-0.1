<!DOCTYPE html>
<html>
<?php
  session_start();

  $ses = 0; 

  if (isset($_SESSION['validar']) && $_SESSION['validar'] == 1) {
      $ses = 1;
  }
?>
    <head>

    <link rel="stylesheet", href="sesion.css">
        <title>Pagina de inicio</title>
        <script src="jquery-3.3.1.min.js"></script>
        <script>
            function verificarCliente(){
                var correo = $('.correo').val();
                var pass = $('.pass').val();
                var mensajes = document.getElementById('message');
                if(correo=="" || pass==""){
                    alert('Campos incompletos');
                }else{
                    $.ajax({
                    url      : 'verify_mail.php',
                    type     : 'post',
                    dataType : 'text',
                    data     : 'correo='+correo + "&pass="+pass,
                    success  : function(res) {
                        if(res == 4){
                            alert('Datos incorrectos.');
                        }if(res == 2){
                            alert('Contraseña incorrecta.');
                        }if(res == 3){
                            alert('_No se encontro el usuario.');
                        }if(res == 1){
                            window.location.href = 'index.php';
                        }
                    },error: function() {
                        alert('Error, archivo no encontrado...');
                    }
                    });
                }
            }

        </script>
    </head>
    <header>
      <nav>
        <a href="index.php">Inicio</a>
        
        <?php
            if(!$ses){
                echo "<a href='sesion.php'>Iniciar Sesion</a>";
                echo "<a href='register.php'>Registrarse</a>";
            }
            else{
                echo "<a href='index.php'>Perfil</a>";
                echo "<a href='mapa.php'>Mapa</a>";
                echo "<a href='close_sesion.php'>Cerrar sesion</a>";
            }
        ?>
        <div class="dropdown">
          <button class="dropbtn">Contacto</button>
          <div class="dropdown-content">
            <a href="#email">Correo Electrónico</a>
            <a href="#phone">Teléfono</a>
          </div>
        </div>
      </nav>
    </header>

    <body>
        <form name="Forma01" action="verify_mail.php" method="POST" enctype="multipart/form-data" ></form>
            <div class="table-wrapper">
                <table class="table" width="400px">
                    <tr class='incmpletos'>
                        <td style="text-align:center; padding-top: 20px;" class='celdaTitulo' colspan="2">Iniciar sesion</td>
                    </tr>
                    <tr style="text-align:center;" class='row'>
                        <td class='cell' style="text-align:center">Correo:</td>
                    <tr>
                        <td class='cell' width="300px" ><input id="correo" type="text" class="correo" placeholder="Escribe tu correo" style="width: 100%;" required></td>
                    </tr>
                    <tr style="text-align:center;" class='row'>
                        <td class='cell' style="text-align:center">Contrasenia:</td>
                    </tr>
                    <tr>
                        <td class='cell'><input type="password" class="pass" placeholder="Escribe una contrasenia" style="width: 100%;"></td>
                    </tr>
                    <tr>
                        <td class='cell'style="text-align:center"><div id="message"></div></td>
                    </tr>
                    <tr style="text-align:center;" class='rowBtn'>
                        <td class='cell' style="padding-top: 30px;"><button class="btn" onClick="verificarCliente();" type="submit" >Iniciar sesion</button></td>
                    </tr>
                    <br>
                </table>
            </div>
        </body>
    </form>
</html>