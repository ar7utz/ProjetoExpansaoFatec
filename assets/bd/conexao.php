<?php
$host = 'localhost';
$dbname = 'fatecextensao';
$usuario = 'root';
$senha = '';

$conn = new mysqli($host, $usuario, $senha, $dbname);

if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
    exit();
}
?>