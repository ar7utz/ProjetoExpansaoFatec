<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $descricao = trim($_POST['descricao']);
    $data = date('Y-m-d H:i:s');

    if ($descricao) {
        $sql = "UPDATE avisos SET descricao = ?, data = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $descricao, $data, $id);
        $stmt->execute();
        $_SESSION['msg_aviso'] = "Aviso editado com sucesso!";
    } else {
        $_SESSION['msg_aviso'] = "Preencha o campo do aviso!";
    }
    header("Location: ../paginaAdmin.php");
    exit;
}

// GET: mostrar formulário
$id = intval($_GET['id']);
$sql = "SELECT * FROM avisos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$aviso = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aviso</title>
    <link rel="stylesheet" href="../../../src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <form class="bg-white p-8 rounded shadow-md w-full max-w-md" method="POST">
        <h2 class="text-xl font-bold mb-4">Editar Aviso</h2>
        <input type="hidden" name="id" value="<?php echo $aviso['id']; ?>">
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Descrição</label>
            <textarea name="descricao" class="w-full border px-3 py-2 rounded" required><?php echo htmlspecialchars($aviso['descricao']); ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Última modificação</label>
            <span class="block"><?php echo date('d/m/Y H:i', strtotime($aviso['data'])); ?></span>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Salvar Alterações</button>
        </div>
    </form>
</body>
</html>