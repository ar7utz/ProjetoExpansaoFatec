<?php
session_start();
require_once('../../../assets/bd/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao_sala = trim($_POST['descricao_sala']);
    $professor = trim($_POST['professor']);
    $descricao_professor = trim($_POST['descricao_professor']);
    $foto_professor = '';

    // Upload da foto do professor
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

    if ($nome && $descricao_sala) {
        $sql = "INSERT INTO sala (nome, descricao_sala, professor, descricao_professor, foto_professor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $nome, $descricao_sala, $professor, $descricao_professor, $foto_professor);
        $stmt->execute();

        // Gera o nome do arquivo PHP da sala
        $nomeArquivo = strtolower(str_replace([' ', 'ç', 'ã', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'á', 'õ'], ['','c','a','e','i','o','u','a','e','o','a','o'], $nome)) . '.php';
        $caminhoArquivo = '../../../modules/public/salas/' . $nomeArquivo;

        // Conteúdo do arquivo gerado
        $conteudo = <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../src/output.css">
    <link rel="shortcut icon" href="../../assets/icon/fatec-logo-nobackground.ico" type="image/x-icon">
    <title>{$nome}</title>
</head>
<body class="bg-gray-50 font-sans">
    <header class="bg-white shadow-sm">
        <?php require_once ('../../template/navbar.php'); ?>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-6">
        <div class="flex flex-col">
            <div class="flex flex-row w-full mb-12">
                <div class="flex-[7] pr-8">
                    <h1 class="text-4xl font-extrabold text-gray-800 mb-6">{$nome}</h1>
                    <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mb-12">
                        {$descricao_sala}
                    </p>
                </div>
                <div class="flex-[3] flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold mb-2">Professor</h2>
HTML;

        if (!empty($foto_professor)) {
            $conteudo .= '<img class="w-40 h-40 rounded-lg object-contain mb-2" src="../../../assets/imgs/professores/' . htmlspecialchars($foto_professor) . '" alt="Foto do professor">';
        }

        $conteudo .= <<<HTML
                    <p class="text-md text-gray-700 font-semibold mb-2">{$professor}</p>
                    <p class="text-gray-600 text-sm text-center">{$descricao_professor}</p>
                </div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-8 border-b-2 pb-2">PROJETOS E ATIVIDADES</h2>
        <?php
            require_once('../../../assets/bd/conexao.php');
            \$nomeSala = "{$nome}";
            \$sqlSala = "SELECT id FROM sala WHERE nome = ?";
            \$stmtSala = \$conn->prepare(\$sqlSala);
            \$stmtSala->bind_param('s', \$nomeSala);
            \$stmtSala->execute();
            \$resSala = \$stmtSala->get_result();
            \$sala = \$resSala->fetch_assoc();
            \$id_sala = \$sala ? \$sala['id'] : null;

            // Materiais - paginação
            \$itensPorPaginaMat = 10;
            \$paginaAtualMat = isset(\$_GET['pagina_materiais']) ? max(1, intval(\$_GET['pagina_materiais'])) : 1;
            \$offsetMat = (\$paginaAtualMat - 1) * \$itensPorPaginaMat;
            \$totalPaginasMat = 1;
            \$materiais = [];
            if (\$id_sala) {
                \$sqlTotalMat = "SELECT COUNT(*) as total FROM materiais WHERE id_sala = ?";
                \$stmtTotalMat = \$conn->prepare(\$sqlTotalMat);
                \$stmtTotalMat->bind_param('i', \$id_sala);
                \$stmtTotalMat->execute();
                \$resultTotalMat = \$stmtTotalMat->get_result();
                \$totalMateriais = \$resultTotalMat->fetch_assoc()['total'];
                \$totalPaginasMat = ceil(\$totalMateriais / \$itensPorPaginaMat);

                \$sqlMat = "SELECT * FROM materiais WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
                \$stmtMat = \$conn->prepare(\$sqlMat);
                \$stmtMat->bind_param('iii', \$id_sala, \$itensPorPaginaMat, \$offsetMat);
                \$stmtMat->execute();
                \$resMat = \$stmtMat->get_result();
                while (\$row = \$resMat->fetch_assoc()) {
                    \$materiais[] = \$row;
                }
            }

            // Links - paginação
            \$itensPorPaginaLinks = 10;
            \$paginaAtualLinks = isset(\$_GET['pagina_links']) ? max(1, intval(\$_GET['pagina_links'])) : 1;
            \$offsetLinks = (\$paginaAtualLinks - 1) * \$itensPorPaginaLinks;
            \$totalPaginasLinks = 1;
            \$links = [];
            if (\$id_sala) {
                \$sqlTotalLinks = "SELECT COUNT(*) as total FROM links WHERE id_sala = ?";
                \$stmtTotalLinks = \$conn->prepare(\$sqlTotalLinks);
                \$stmtTotalLinks->bind_param('i', \$id_sala);
                \$stmtTotalLinks->execute();
                \$resultTotalLinks = \$stmtTotalLinks->get_result();
                \$totalLinks = \$resultTotalLinks->fetch_assoc()['total'];
                \$totalPaginasLinks = ceil(\$totalLinks / \$itensPorPaginaLinks);

                \$sqlLinks = "SELECT * FROM links WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
                \$stmtLinks = \$conn->prepare(\$sqlLinks);
                \$stmtLinks->bind_param('iii', \$id_sala, \$itensPorPaginaLinks, \$offsetLinks);
                \$stmtLinks->execute();
                \$resLinks = \$stmtLinks->get_result();
                while (\$row = \$resLinks->fetch_assoc()) {
                    \$links[] = \$row;
                }
            }
        ?>
        <!-- Materiais Dinâmicos (tabela e paginação) -->
        <section class="mt-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Materiais da Sala</h2>
            <div class="bg-white rounded shadow mb-8">
                <div class="overflow-x-auto">
                    <?php if (empty(\$materiais)): ?>
                        <div class="p-6 text-gray-500">Nenhum material encontrado.</div>
                    <?php else: ?>
                        <table class="min-w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Nome</th>
                                    <th class="px-6 py-3">Arquivo</th>
                                    <th class="px-6 py-3">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (\$materiais as \$mat): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-4"><?php echo htmlspecialchars(\$mat['nome']); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="../../../assets/arquivos/<?php echo rawurlencode(\$nomeSala); ?>/materiais/<?php echo urlencode(\$mat['arquivo']); ?>"
                                               target="_blank" class="text-blue-600 underline">
                                                <?php echo htmlspecialchars(\$mat['arquivo']); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime(\$mat['data'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (\$totalPaginasMat > 1): ?>
                            <div class="flex justify-center mt-4 space-x-2">
                                <?php for (\$i = 1; \$i <= \$totalPaginasMat; \$i++): ?>
                                    <a href="?pagina_materiais=<?php echo \$i; ?>" class="px-3 py-1 rounded <?php echo \$i == \$paginaAtualMat ? 'bg-yellow-300' : 'bg-gray-200'; ?>">
                                        <?php echo \$i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- Links Dinâmicos (tabela e paginação) -->
        <section class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Links Úteis</h2>
            <div class="bg-white rounded shadow mb-8">
                <div class="overflow-x-auto">
                    <?php if (empty(\$links)): ?>
                        <div class="p-6 text-gray-500">Nenhum link encontrado.</div>
                    <?php else: ?>
                        <table class="min-w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Nome</th>
                                    <th class="px-6 py-3">Link</th>
                                    <th class="px-6 py-3">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (\$links as \$link): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-4"><?php echo htmlspecialchars(\$link['nome']); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="<?php echo htmlspecialchars(\$link['url']); ?>" target="_blank" class="text-blue-600 underline">
                                                <?php echo htmlspecialchars(\$link['url']); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime(\$link['data'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (\$totalPaginasLinks > 1): ?>
                            <div class="flex justify-center mt-4 space-x-2">
                                <?php for (\$i = 1; \$i <= \$totalPaginasLinks; \$i++): ?>
                                    <a href="?pagina_links=<?php echo \$i; ?>" class="px-3 py-1 rounded <?php echo \$i == \$paginaAtualLinks ? 'bg-yellow-300' : 'bg-gray-200'; ?>">
                                        <?php echo \$i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <?php require_once ('../../template/footer.php'); ?>
    </footer>
</body>
</html>
HTML;

        // Cria o arquivo da sala
        file_put_contents($caminhoArquivo, $conteudo);

        $_SESSION['msg_sala'] = "Sala adicionada com sucesso!";
    } else {
        $_SESSION['msg_sala'] = "Preencha todos os campos obrigatórios!";
    }
    header("Location: ../paginaAdmin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Sala</title>
    <link rel="stylesheet" href="../../../src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <form class="bg-white p-8 rounded shadow-md w-full max-w-2xl flex gap-8" method="POST" enctype="multipart/form-data">
        <div class="flex-1">
            <h2 class="text-xl font-bold mb-4">Nova Sala</h2>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Título da Sala</label>
                <input type="text" name="nome" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Descrição</label>
                <textarea name="descricao_sala" class="w-full border px-3 py-2 rounded" rows="4" required></textarea>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-semibold mb-2">Professor Responsável</h3>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Foto</label>
                <input type="file" name="foto_professor" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nome</label>
                <input type="text" name="professor" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Descrição breve</label>
                <textarea name="descricao_professor" class="w-full border px-3 py-2 rounded" rows="2"></textarea>
            </div>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Salvar Sala</button>
        </div>
    </form>
</body>
</html>