<html>
<?php
    require "conect.php";

    $con = conecta();

    $nombre = $_REQUEST['nombre'];
    $apellido = $_REQUEST['apellido'];
    $correo = $_REQUEST['correo'];
    $pass = $_REQUEST['pass'];
    $passEnc = md5($pass);
    $activo = 1;

$sql    =   "INSERT INTO `usuario`(`id_usuario`, `nombre`, `apellido`, `correo`, `contrasenia`, `activo`) VALUES (default,'$nombre','$apellido','$correo','$passEnc','$activo')";
echo"<center>";
if ($con->query($sql) === TRUE) {
    echo "REGISTRO GUARDADO CORRECTAMENTE";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}
echo "</center>";
?>
    <center>
        <form name="regreso" action="index.php" method="POST">
            <input onClick="index();" type="submit" value="Volver al inicio">
        </form>
    </center>

</html>