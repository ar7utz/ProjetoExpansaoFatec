<?php

session_start();
require_once('../../../assets/bd/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $url = trim($_POST['url']);
    $id_sala = intval($_POST['id_sala']);
    $data = date('Y-m-d H:i:s');

    if ($nome && $url && $id_sala) {
        $sql = "INSERT INTO links (id_sala, nome, url, data) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $id_sala, $nome, $url, $data);
        $stmt->execute();
        $_SESSION['msg_link'] = "Link adicionado com sucesso!";
    } else {
        $_SESSION['msg_link'] = "Preencha todos os campos!";
    }
    header("Location: ../paginaAdmin.php?sala=$id_sala");
    exit;
}
?>