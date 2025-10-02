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
    $id_sala = intval($_POST['id_sala']);
    $data = date('Y-m-d H:i:s');

    // Buscar material atual
    $sqlMat = "SELECT * FROM materiais WHERE id = ?";
    $stmtMat = $conn->prepare($sqlMat);
    $stmtMat->bind_param('i', $id);
    $stmtMat->execute();
    $resultMat = $stmtMat->get_result();
    $material = $resultMat->fetch_assoc();

    $arquivo_nome = $material['arquivo'];
    // Se enviou novo arquivo, faz upload e atualiza nome
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        // Buscar nome da sala pelo id
        $sqlSala = "SELECT nome FROM sala WHERE id = ?";
        $stmtSala = $conn->prepare($sqlSala);
        $stmtSala->bind_param('i', $id_sala);
        $stmtSala->execute();
        $resultSala = $stmtSala->get_result();
        $rowSala = $resultSala->fetch_assoc();
        $nomeSala = $rowSala['nome'];
        $diretorio = "../../../assets/arquivos/" . $nomeSala . "/materiais/";
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        $arquivo_nome = basename($_FILES['arquivo']['name']);
        $caminho_arquivo = $diretorio . $arquivo_nome;
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo);
    }

    // Atualiza no banco
    $sql = "UPDATE materiais SET nome = ?, arquivo = ?, data = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $nome, $arquivo_nome, $data, $id);
    $stmt->execute();

    $_SESSION['msg_material'] = "Material editado com sucesso!";
    header("Location: ../paginaAdmin.php?sala=$id_sala");
    exit;
}

// GET: mostrar formulário
$id = intval($_GET['id']);
$sql = "SELECT * FROM materiais WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Material</title>
    <link rel="stylesheet" href="../../../src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <form class="bg-white p-8 rounded shadow-md w-full max-w-md" method="POST" enctype="multipart/form-data">
        <h2 class="text-xl font-bold mb-4">Editar Material</h2>
        <input type="hidden" name="id" value="<?php echo $material['id']; ?>">
        <input type="hidden" name="id_sala" value="<?php echo $material['id_sala']; ?>">
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nome</label>
            <input type="text" name="nome" class="w-full border px-3 py-2 rounded" value="<?php echo htmlspecialchars($material['nome']); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arquivo atual</label>
            <span class="block mb-2"><?php echo htmlspecialchars($material['arquivo']); ?></span>
            <input type="file" name="arquivo" class="w-full border px-3 py-2 rounded">
            <small class="text-gray-500">Deixe em branco para manter o arquivo atual.</small>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Última modificação</label>
            <span class="block"><?php echo date('d/m/Y H:i', strtotime($material['data'])); ?></span>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar Alterações</button>
        </div>
    </form>
</body>
</html>