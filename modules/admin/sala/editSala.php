<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $descricao_sala = trim($_POST['descricao_sala']);
    $professor = trim($_POST['professor']);
    $descricao_professor = trim($_POST['descricao_professor']);
    $foto_professor = isset($_POST['foto_professor_atual']) ? $_POST['foto_professor_atual'] : '';

    // Upload nova foto
    if (isset($_FILES['foto_professor']) && $_FILES['foto_professor']['error'] === UPLOAD_ERR_OK) {
        $foto_nome = uniqid() . '_' . basename($_FILES['foto_professor']['name']);
        $diretorio = "../../../assets/imgs/professores/";
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        $caminho_foto = $diretorio . $foto_nome;
        move_uploaded_file($_FILES['foto_professor']['tmp_name'], $caminho_foto);
        $foto_professor = $foto_nome;
    }

    $sql = "UPDATE sala SET nome = ?, descricao_sala = ?, professor = ?, descricao_professor = ?, foto_professor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $nome, $descricao_sala, $professor, $descricao_professor, $foto_professor, $id);
    $stmt->execute();

    $_SESSION['msg_sala'] = "Sala editada com sucesso!";
    header("Location: ../paginaAdmin.php");
    exit;
}

// GET: mostrar formulário
$id = intval($_GET['id']);
$sql = "SELECT * FROM sala WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$sala = $result->fetch_assoc();
?>
