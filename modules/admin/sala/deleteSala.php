<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

$id = intval($_GET['id']);

$sqlNome = "SELECT nome FROM sala WHERE id = ?";
$stmtNome = $conn->prepare($sqlNome);
$stmtNome->bind_param('i', $id);
$stmtNome->execute();
$resultNome = $stmtNome->get_result();
$sala = $resultNome->fetch_assoc();

if ($sala) {
    $nomeArquivo = strtolower(str_replace([' ', 'ç', 'ã', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'á', 'õ'], ['','c','a','e','i','o','u','a','e','o','a','o'], $sala['nome'])) . '.php';
    $caminhoArquivo = '../../../modules/public/salas/' . $nomeArquivo;

    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }
}

// Exclui a sala do banco
$sql = "DELETE FROM sala WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

$_SESSION['msg_sala'] = "Sala excluída com sucesso!";
header("Location: ../paginaAdmin.php");
exit;
?>