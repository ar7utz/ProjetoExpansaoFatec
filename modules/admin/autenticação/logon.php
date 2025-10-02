<?php

session_start();
require_once('../../../assets/bd/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        $_SESSION['erro_login'] = "Login e senha são obrigatórios!";
        header('Location: ../pageLoginADM.php');
        exit;
    }

    // Busca pelo nome de usuário
    $sql = "SELECT id, usuario, senha FROM administrador WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifica a senha (texto puro)
        if ($senha === $user['senha']) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['id_admin'] = $user['id'];
            header('Location: ../paginaAdmin.php');
            exit;
        } else {
            $_SESSION['erro_login'] = "Usuário ou senha incorretos!";
            header('Location: ../pageLoginADM.php?mensagem=ErroLogin');
            exit;
        }
    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado!";
        header('Location: ../pageLoginADM.php?mensagem=UserNotFound');
        exit;
    }

    $stmt->close();
    $conn->close();
}