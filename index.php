<!DOCTYPE html>
    <?php
    session_start();

    $ses = 0; 

    if (isset($_SESSION['validar']) && $_SESSION['validar'] == 1) {
        $ses = 1;
    }
    ?>
  
<html>

 <head>
     <title>Registrarse</title>
     <link rel="stylesheet" href="register.css">
     <script src="jquery-3.3.1.min.js"></script>
     <script>
     function camposLlenos(){
         var nombre = document.Forma01.nombre.value;
         var correo = document.Forma01.correo.value;
         var password = document.Forma01.pass.value;
         
         if(nombre!='' && password!='' && correo!=''){
                document.Forma01.method = 'post';
                document.Forma01.action = 'salvaCliente.php';
                document.Forma01.submit();
            }
            else{
                $('#campos').html("Campos incompletos.");
                setTimeout("$('#campos').html('');", 3000);
            }
     }
     function verificarCorreo(){
         var correo = $('#correo').val();
         $.ajax({
            url      : '_verificarCorreo.php',
            type     : 'post',
            dataType : 'text',
            data     : 'correo='+correo,
            success  : function(res) {
                if(res == 0){
                    $('#mensajeCorreo').html(res);
                    setTimeout("$('#mensajeCorreo').html('');", 3000);
                    $('#correo').css({"border-color":"green"});
                }else{
                    $('#correo').css({"border-color":"red"});
                    $('#mensajeCorreo').html(res);
                    setTimeout("$('#mensajeCorreo').html('');", 3000);
                }
            },error: function() {
                alert('Error, archivo no encontrado...');
            }
        });
     }
     </script>
  
 </head>

 <body>
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
	<form name="Forma01" action="salva_alta.php" method="POST" enctype="multipart/form-data" >
        <div class="table-wrapper">
            <table class="table" width="400px">
                <tr class='incmpletos'>
                    <td style="text-align:center; padding-top: 20px;" class='celdaTitulo' colspan="2">Registrarse<div style="color:red;" id="campos" ></div></td>
                </tr>
                <tr style="text-align:center;" class='row'>
                    <td class='cell' style="text-align:right">Nombre:</td>
                    <td class='cell' width="300px" ><input id="nombre" type="text" name="nombre" placeholder="Escribe tu nombre" required></td>
                </tr>
                <tr style="text-align:center;" class='row'>
                  <td class='cell' style="text-align:right">Apellido:</td>
                  <td class='cell' width="300px" ><input id="apellido" type="text" name="apellido" placeholder="Escribe tu apellido" required></td>
              </tr>
                <tr style="text-align:center;" class='row'>
                    <td class='cell' style="text-align:right">Correo:</td>
                    <td class='cell'><input onblur="verificarCorreo();" type="email" class="correo" name="correo" id="correo" placeholder="Escribe tu correo"><br><div style="color:red;" id="mensajeCorreo"></div></td>
                </tr>
                <tr style="text-align:center;" class='row'>
                    <td class='cell' style="text-align:right">Contrasenia:</td>
                    <td class='cell'><input type="password" name="pass" placeholder="Escribe una contraseña"></td>
                </tr>
                <tr style="text-align:center;" class='rowBtn'>
                    <td class='cell' style="padding-top: 30px;"><button class="btn" onClick="window.location.href = 'index.php';" type="submit">Inicio</button></td>
                    <td class='cell' style="padding-top: 30px;"><button class="btn" onClick="camposLlenos(); return false;" type="submit">Registrar</button></td>
                </tr>
                <br>
            </table>
        </div>
	</form>
	
 </body>

</html>