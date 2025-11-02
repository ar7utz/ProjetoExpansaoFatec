<?php
session_start();

$_SESSION = array();

//Destruir a sessão
session_destroy();

//Redirecionar para o index
header("Location: ../../../index.php");
exit;
?>