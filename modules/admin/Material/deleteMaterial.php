<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id']) || !isset($_GET['sala'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

$id = intval($_GET['id']);
$id_sala = intval($_GET['sala']);

// Buscar material para excluir arquivo físico
$sql = "SELECT arquivo FROM materiais WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();

if ($material) {
    // Buscar nome da sala
    $sqlSala = "SELECT nome FROM sala WHERE id = ?";
    $stmtSala = $conn->prepare($sqlSala);
    $stmtSala->bind_param('i', $id_sala);
    $stmtSala->execute();
    $resultSala = $stmtSala->get_result();
    $rowSala = $resultSala->fetch_assoc();
    $nomeSala = $rowSala['nome'];

    $caminho = "../../../assets/arquivos/" . $nomeSala . "/materiais/" . $material['arquivo'];
    if (file_exists($caminho)) {
        unlink($caminho);
    }
    // Excluir do banco
    $sqlDel = "DELETE FROM materiais WHERE id = ?";
    $stmtDel = $conn->prepare($sqlDel);
    $stmtDel->bind_param('i', $id);
    $stmtDel->execute();
    $_SESSION['msg_material'] = "Material excluído com sucesso!";
}

header("Location: ../paginaAdmin.php?sala=$id_sala");
exit;
?>