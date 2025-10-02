<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao']);
    $data = date('Y-m-d H:i:s');

    if ($descricao) {
        $sql = "INSERT INTO avisos (descricao, data) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $descricao, $data);
        $stmt->execute();
        $_SESSION['msg_aviso'] = "Aviso adicionado com sucesso!";
    } else {
        $_SESSION['msg_aviso'] = "Preencha o campo do aviso!";
    }
    header("Location: ../paginaAdmin.php");
    exit;
}
?>