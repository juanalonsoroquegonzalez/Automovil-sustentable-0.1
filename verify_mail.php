<?php
session_start();
require "conect.php";
$con = conecta();

$correo = $_REQUEST['correo'];
$pass = $_REQUEST['pass'];
$passw = md5($pass);

$sql = "SELECT id_usuario, contrasenia FROM usuario WHERE correo = '$correo'";

$res = $con->query($sql);

if ($res) {
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        // Comparar utilizando md5
        if ($row['contrasenia'] == $passw) {
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['validar'] = 1;
            echo "1"; // Autenticación exitosa
        } else {
            echo "2"; // Contraseña incorrecta
        }
    } else {
        echo "3"; // No se encontró el usuario
    }
} else {
    echo "4"; // Error en la consulta SQL
}

$con->close();
?>
