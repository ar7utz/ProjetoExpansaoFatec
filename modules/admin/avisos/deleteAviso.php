<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

$id = intval($_GET['id']);
$sql = "DELETE FROM avisos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

$_SESSION['msg_aviso'] = "Aviso excluído com sucesso!";
header("Location: ../paginaAdmin.php");
exit;
?>
