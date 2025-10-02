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
    $url = trim($_POST['url']);
    $id_sala = intval($_POST['id_sala']);
    $data = date('Y-m-d H:i:s');

    // Atualiza no banco
    $sql = "UPDATE links SET nome = ?, url = ?, data = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $nome, $url, $data, $id);
    $stmt->execute();

    $_SESSION['msg_link'] = "Link editado com sucesso!";
    header("Location: ../paginaAdmin.php?sala=$id_sala");
    exit;
}

// GET: mostrar formulário
$id = intval($_GET['id']);
$sql = "SELECT * FROM links WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$link = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Link</title>
    <link rel="stylesheet" href="../../../src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <form class="bg-white p-8 rounded shadow-md w-full max-w-md" method="POST">
        <h2 class="text-xl font-bold mb-4">Editar Link</h2>
        <input type="hidden" name="id" value="<?php echo $link['id']; ?>">
        <input type="hidden" name="id_sala" value="<?php echo $link['id_sala']; ?>">
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nome</label>
            <input type="text" name="nome" class="w-full border px-3 py-2 rounded" value="<?php echo htmlspecialchars($link['nome']); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Link (URL)</label>
            <input type="url" name="url" class="w-full border px-3 py-2 rounded" value="<?php echo htmlspecialchars($link['url']); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Última modificação</label>
            <span class="block"><?php echo date('d/m/Y H:i', strtotime($link['data'])); ?></span>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar Alterações</button>
        </div>
    </form>
</body>
</html>