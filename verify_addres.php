<?php
require "conect.php";
$con = conecta();

$correo = $_REQUEST['correo'];
$sql = "SELECT * FROM usuario WHERE correo = '$correo';";


$res        =$con->query($sql);
$filas      =$res->num_rows;

if ($filas) {
    echo "alert('Correo ya  registrado. Intenta con otro...')";
} else {
    echo "";
}
?>