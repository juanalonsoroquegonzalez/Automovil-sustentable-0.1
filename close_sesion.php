<?php
session_start();
$_SESSION['validar'] = 0;
session_destroy();
header("Location: index.php");
?>