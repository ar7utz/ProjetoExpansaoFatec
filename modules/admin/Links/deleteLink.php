<?php

session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id']) || !isset($_GET['sala'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

$id = intval($_GET['id']);
$id_sala = intval($_GET['sala']);

// Excluir do banco
$sqlDel = "DELETE FROM links WHERE id = ?";
$stmtDel = $conn->prepare($sqlDel);
$stmtDel->bind_param('i', $id);
$stmtDel->execute();

$_SESSION['msg_link'] = "Link excluído com sucesso!";
header("Location: ../paginaAdmin.php?sala=$id_sala");
exit;
?>