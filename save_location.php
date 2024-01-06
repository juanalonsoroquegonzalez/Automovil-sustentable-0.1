<?php
session_start();
require "conect.php";
$con = conecta();

$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$descripcion = $_POST['description'];
$id = $_SESSION['id_usuario'];

$sql = "INSERT INTO favoritos (id_favorito, id_usuario, descripcion, latitud, longitud) VALUES (default, '$id', '$descripcion', $latitud, $longitud)";

$res = $con->query($sql);

if ($res) {
    echo 'success';
} else {
    echo 'error';
}
?>