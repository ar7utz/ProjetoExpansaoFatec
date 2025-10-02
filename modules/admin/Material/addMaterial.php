<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $id_sala = intval($_POST['id_sala']);
    $data = date('Y-m-d H:i:s');

    // Upload do arquivo
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $arquivo_nome = basename($_FILES['arquivo']['name']);
        $diretorio = "../../../assets/arquivos/";
        // Buscar nome da sala pelo id
        $sqlSala = "SELECT nome FROM sala WHERE id = ?";
        $stmtSala = $conn->prepare($sqlSala);
        $stmtSala->bind_param('i', $id_sala);
        $stmtSala->execute();
        $resultSala = $stmtSala->get_result();
        $rowSala = $resultSala->fetch_assoc();
        $nomeSala = $rowSala['nome'];
        $pastaSala = $diretorio . $nomeSala . "/materiais/";
        if (!is_dir($pastaSala)) {
            mkdir($pastaSala, 0777, true);
        }
        $caminho_arquivo = $pastaSala . $arquivo_nome;
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo);

        // Salvar no banco
        $sql = "INSERT INTO materiais (id_sala, nome, arquivo, data) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $id_sala, $nome, $arquivo_nome, $data);
        $stmt->execute();
        $_SESSION['msg_material'] = "Material adicionado com sucesso!";
    } else {
        $_SESSION['msg_material'] = "Erro ao fazer upload do arquivo!";
    }
    header("Location: ../paginaAdmin.php?sala=$id_sala");
    exit;
}
?>