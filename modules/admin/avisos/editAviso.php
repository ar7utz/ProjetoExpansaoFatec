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
        <h2 class="text-xl font-bold mb-4 text-center">Editar Aviso</h2>
        <input type="hidden" name="id" value="<?php echo $aviso['id']; ?>">

        <div class=""> <!--div principal-->
            <div class="mb-2">
                <label class="block mb-1 font-semibold">Descrição:</label>
                <textarea name="descricao" class="w-full border px-3 py-2 rounded" required><?php echo htmlspecialchars($aviso['descricao']); ?></textarea>
            </div>
            <div class="mb-4">
                <div class="relative group mb-2" tabindex="0" aria-label="Informação sobre a modificação do aviso">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"> 
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /> 
                    </svg> 
                    <div class="absolute left-1/2 transform -translate-x-1/2 top-full mt-2 w-64 bg-gray-800 text-white text-sm p-2 rounded shadow-lg opacity-0 pointer-events-none group-hover:opacity-100 group-focus-within:opacity-100 group-hover:pointer-events-auto transition-opacity z-20" role="tooltip"> 
                        Ao salvar uma alteração no aviso, a data é atualizada para o dia atual. <strong>Atente-se.</strong>
                    </div> 
                </div>

                <div class="">
                    <span class="font-semibold text-gray-700">Última modificação:</span> 
                    <span class="ml-auto text-sm text-gray-700"><?php echo date('d/m/Y H:i', strtotime($aviso['data'])); ?></span> 
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 cursor-pointer">Salvar Alterações</button>
            <button type="button" onclick="window.location.href='../paginaAdmin.php'" class="ml-2 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">X</button>
        </div>
    </form>
</body>
</html>