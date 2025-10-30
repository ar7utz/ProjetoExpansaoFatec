<?php
session_start();
require_once('../../../assets/bd/conexao.php');

function sanitize_filename($str) {
    $map = [
        'Á'=>'A','À'=>'A','Ã'=>'A','Â'=>'A','Ä'=>'A',
        'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','ä'=>'a',
        'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
        'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I',
        'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
        'Ó'=>'O','Ò'=>'O','Õ'=>'O','Ô'=>'O','Ö'=>'O',
        'ó'=>'o','ò'=>'o','õ'=>'o','ô'=>'o','ö'=>'o',
        'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U',
        'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
        'Ç'=>'C','ç'=>'c','Ñ'=>'N','ñ'=>'n'
    ];
    // translitera acentos
    $str = strtr($str, $map);
    // remove caracteres inválidos (mantém letras, números, espaços e hífens)
    $str = preg_replace('/[^A-Za-z0-9\s\-]/', '', $str);
    // remove TODOS os espaços (requisito: retirar espaços no nome do arquivo)
    $str = preg_replace('/\s+/', '', $str);
    // consolida hífens (caso existam) e transforma para minúsculas
    $str = preg_replace('/-+/', '-', $str);
    return strtolower($str);
}

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: ../paginaAdmin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // busca dados atuais
    $stmtGet = $conn->prepare("SELECT * FROM sala WHERE id = ?");
    $stmtGet->bind_param('i', $id);
    $stmtGet->execute();
    $resGet = $stmtGet->get_result();
    $salaAtual = $resGet->fetch_assoc();
    $stmtGet->close();

    if (!$salaAtual) {
        $_SESSION['msg_sala'] = "Sala não encontrada.";
        header("Location: ../paginaAdmin.php");
        exit;
    }

    // novos valores (preserva antigos se não enviados)
    $nome = isset($_POST['nome']) && $_POST['nome'] !== '' ? trim($_POST['nome']) : $salaAtual['nome'];
    $descricao_sala = isset($_POST['descricao_sala']) && $_POST['descricao_sala'] !== '' ? trim($_POST['descricao_sala']) : $salaAtual['descricao_sala'];
    $professor = isset($_POST['professor']) && $_POST['professor'] !== '' ? trim($_POST['professor']) : $salaAtual['professor'];
    $descricao_professor = isset($_POST['descricao_professor']) && $_POST['descricao_professor'] !== '' ? trim($_POST['descricao_professor']) : $salaAtual['descricao_professor'];
    $foto_professor = isset($_POST['foto_professor_atual']) ? $_POST['foto_professor_atual'] : $salaAtual['foto_professor'];
    $img_sala = isset($_POST['img_sala_atual']) ? $_POST['img_sala_atual'] : (isset($salaAtual['img_sala']) ? $salaAtual['img_sala'] : '');

    // upload foto professor (se enviada)
    if (isset($_FILES['foto_professor']) && $_FILES['foto_professor']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['foto_professor']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $foto_nome = uniqid() . '_' . preg_replace('/[^a-z0-9\.\-_]/i', '', basename($_FILES['foto_professor']['name']));
            $diretorio = "../../../assets/imgs/professores/";
            if (!is_dir($diretorio)) mkdir($diretorio, 0777, true);
            $caminho_foto = $diretorio . $foto_nome;
            if (move_uploaded_file($_FILES['foto_professor']['tmp_name'], $caminho_foto)) {
                if (!empty($salaAtual['foto_professor']) && file_exists("../../../assets/imgs/professores/{$salaAtual['foto_professor']}") && $salaAtual['foto_professor'] !== $foto_nome) {
                    @unlink("../../../assets/imgs/professores/{$salaAtual['foto_professor']}");
                }
                $foto_professor = $foto_nome;
            }
        }
    }

    // upload imagem da sala (se enviada)
    if (isset($_FILES['img_sala']) && $_FILES['img_sala']['error'] === UPLOAD_ERR_OK) {
        $allowed2 = ['jpg','jpeg','png','webp'];
        $ext2 = strtolower(pathinfo($_FILES['img_sala']['name'], PATHINFO_EXTENSION));
        if (in_array($ext2, $allowed2)) {
            $img_sala_nome = uniqid() . '_' . preg_replace('/[^a-z0-9\.\-_]/i', '', basename($_FILES['img_sala']['name']));
            $diretorioSala = "../../../assets/imgs/salas/";
            if (!is_dir($diretorioSala)) mkdir($diretorioSala, 0777, true);
            $caminho_img_sala = $diretorioSala . $img_sala_nome;
            if (move_uploaded_file($_FILES['img_sala']['tmp_name'], $caminho_img_sala)) {
                if (!empty($salaAtual['img_sala']) && file_exists("../../../assets/imgs/salas/{$salaAtual['img_sala']}") && $salaAtual['img_sala'] !== $img_sala_nome) {
                    @unlink("../../../assets/imgs/salas/{$salaAtual['img_sala']}");
                }
                $img_sala = $img_sala_nome;
            }
        }
    }

    // atualizar banco (não altera estrutura de materiais/links)
    $sql = "UPDATE sala SET nome = ?, descricao_sala = ?, professor = ?, descricao_professor = ?, foto_professor = ?, img_sala = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $nome, $descricao_sala, $professor, $descricao_professor, $foto_professor, $img_sala, $id);
    $stmt->execute();
    $stmt->close();

    // RENOMEAR pasta de materiais/arquivos para manter correspondência (usa NOME COM ESPAÇOS)
    $oldFolder = "../../../assets/arquivos/" . $salaAtual['nome'];
    $newFolder = "../../../assets/arquivos/" . $nome;
    if ($salaAtual['nome'] !== $nome) {
        if (is_dir($oldFolder) && !is_dir($newFolder)) {
            @rename($oldFolder, $newFolder);
        }
        // se já existe newFolder, não sobrescreve (mantém materiais)
    }

    // Atualizar/RENOMEAR arquivo público da sala (usa SLUG com hífens para o nome do arquivo)
    $dirPublic = '../../../modules/public/salas/';
    if (!is_dir($dirPublic)) mkdir($dirPublic, 0777, true);

    $slugOld = sanitize_filename($salaAtual['nome']);
    $slugNew = sanitize_filename($nome);
    $oldFile = $dirPublic . $slugOld . '.php';
    $newFile = $dirPublic . $slugNew . '.php';

    // monta conteúdo atualizado (mantém lógica dinâmica de materiais e links)
    // usa nowdoc + placeholder para evitar problemas de interpolação de variáveis
    $template = <<<'HTML'
<?php
require_once('../../../assets/bd/conexao.php');
// usa o id da sala embutido no arquivo público para buscar materiais/links
$id_sala = PLACEHOLDER_ID;

// busca os dados completos da sala (nome, descrição, professor, fotos)
$stmtSala = $conn->prepare("SELECT nome, descricao_sala, professor, descricao_professor, foto_professor, img_sala FROM sala WHERE id = ?");
$stmtSala->bind_param('i', $id_sala);
$stmtSala->execute();
$resSala = $stmtSala->get_result();
$rowSala = $resSala->fetch_assoc();

// define variáveis usadas no template para evitar "undefined variable"
$nome = $rowSala['nome'] ?? '';
$descricao_sala = $rowSala['descricao_sala'] ?? '';
$professor = $rowSala['professor'] ?? '';
$descricao_professor = $rowSala['descricao_professor'] ?? '';
$foto_professor = $rowSala['foto_professor'] ?? '';
$img_sala = $rowSala['img_sala'] ?? '';

// Materiais - paginação
$itensPorPaginaMat = 10;
$paginaAtualMat = isset($_GET['pagina_materiais']) ? max(1, intval($_GET['pagina_materiais'])) : 1;
$offsetMat = ($paginaAtualMat - 1) * $itensPorPaginaMat;
$totalPaginasMat = 1;
$materiais = [];
if ($id_sala) {
    $sqlTotalMat = "SELECT COUNT(*) as total FROM materiais WHERE id_sala = ?";
    $stmtTotalMat = $conn->prepare($sqlTotalMat);
    $stmtTotalMat->bind_param('i', $id_sala);
    $stmtTotalMat->execute();
    $resultTotalMat = $stmtTotalMat->get_result();
    $totalMateriais = $resultTotalMat->fetch_assoc()['total'] ?? 0;
    $totalPaginasMat = $totalMateriais > 0 ? ceil($totalMateriais / $itensPorPaginaMat) : 1;

    $sqlMat = "SELECT * FROM materiais WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
    $stmtMat = $conn->prepare($sqlMat);
    $stmtMat->bind_param('iii', $id_sala, $itensPorPaginaMat, $offsetMat);
    $stmtMat->execute();
    $resMat = $stmtMat->get_result();
    while ($row = $resMat->fetch_assoc()) {
        $materiais[] = $row;
    }
}

// Links - paginação
$itensPorPaginaLinks = 10;
$paginaAtualLinks = isset($_GET['pagina_links']) ? max(1, intval($_GET['pagina_links'])) : 1;
$offsetLinks = ($paginaAtualLinks - 1) * $itensPorPaginaLinks;
$totalPaginasLinks = 1;
$links = [];
if ($id_sala) {
    $sqlTotalLinks = "SELECT COUNT(*) as total FROM links WHERE id_sala = ?";
    $stmtTotalLinks = $conn->prepare($sqlTotalLinks);
    $stmtTotalLinks->bind_param('i', $id_sala);
    $stmtTotalLinks->execute();
    $resultTotalLinks = $stmtTotalLinks->get_result();
    $totalLinks = $resultTotalLinks->fetch_assoc()['total'] ?? 0;
    $totalPaginasLinks = $totalLinks > 0 ? ceil($totalLinks / $itensPorPaginaLinks) : 1;

    $sqlLinks = "SELECT * FROM links WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
    $stmtLinks = $conn->prepare($sqlLinks);
    $stmtLinks->bind_param('iii', $id_sala, $itensPorPaginaLinks, $offsetLinks);
    $stmtLinks->execute();
    $resLinks = $stmtLinks->get_result();
    while ($row = $resLinks->fetch_assoc()) {
        $links[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../src/output.css">
    <title><?php echo htmlspecialchars($nome); ?></title>
</head>
<body class="bg-gray-50 font-sans">
    <header class="bg-white shadow-sm">
        <?php require_once ('../../template/navbar.php'); ?>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-6">
        <div class="flex flex-col">
            <div class="flex flex-row w-full mb-12">
                <div class="flex-[7] pr-8">
                    <h1 class="text-4xl font-extrabold text-gray-800 mb-6"><?php echo htmlspecialchars($nome); ?></h1>
                    <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mb-12">
                        <?php echo nl2br(htmlspecialchars($descricao_sala)); ?>
                    </p>
                    <?php if (!empty($img_sala)): ?>
                        <img src="../../../assets/imgs/salas/<?php echo htmlspecialchars($img_sala); ?>" alt="Imagem da sala" class="w-full max-w-md h-48 object-cover rounded mb-4">
                    <?php endif; ?>
                </div>
                <div class="flex-[3] flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold mb-2">Professor</h2>
                    <?php if (!empty($foto_professor)): ?>
                        <img class="w-40 h-40 rounded-lg object-contain mb-2" src="../../../assets/imgs/professores/<?php echo htmlspecialchars($foto_professor); ?>" alt="Foto do professor">
                    <?php endif; ?>
                    <p class="text-md text-gray-700 font-semibold mb-2"><?php echo htmlspecialchars($professor); ?></p>
                    <p class="text-gray-600 text-sm text-center"><?php echo nl2br(htmlspecialchars($descricao_professor)); ?></p>
                </div>
            </div>
        </div>

        <!-- Materiais Dinâmicos (tabela e paginação) -->
        <section class="mt-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Materiais da Sala</h2>
            <div class="bg-white rounded shadow mb-8">
                <div class="overflow-x-auto">
                    <?php if (empty($materiais)): ?>
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
                                <?php foreach ($materiais as $mat): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-4"><?php echo htmlspecialchars($mat['nome']); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="../../../assets/arquivos/<?php echo rawurlencode($nome); ?>/materiais/<?php echo rawurlencode($mat['arquivo']); ?>"
                                               download="<?php echo htmlspecialchars($mat['arquivo']); ?>"
                                               class="text-blue-600 underline">
                                                <?php echo htmlspecialchars($mat['arquivo']); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($mat['data'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ($totalPaginasMat > 1): ?>
                            <div class="flex justify-center mt-4 space-x-2">
                                <?php for ($i = 1; $i <= $totalPaginasMat; $i++): ?>
                                    <a href="?pagina_materiais=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo $i == $paginaAtualMat ? 'bg-yellow-300' : 'bg-gray-200'; ?>">
                                        <?php echo $i; ?>
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
                    <?php if (empty($links)): ?>
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
                                <?php foreach ($links as $link): ?>
                                    <tr class="border-b">
                                        <td class="px-4 py-4"><?php echo htmlspecialchars($link['nome']); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="text-blue-600 underline">
                                                <?php echo htmlspecialchars($link['url']); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($link['data'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ($totalPaginasLinks > 1): ?>
                            <div class="flex justify-center mt-4 space-x-2">
                                <?php for ($i = 1; $i <= $totalPaginasLinks; $i++): ?>
                                    <a href="?pagina_links=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo $i == $paginaAtualLinks ? 'bg-yellow-300' : 'bg-gray-200'; ?>">
                                        <?php echo $i; ?>
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

    // substitui placeholder pelo id numérico e grava arquivo
    $conteudo = str_replace('PLACEHOLDER_ID', (int)$id, $template);
    file_put_contents($newFile, $conteudo);

    // se existia arquivo antigo e é diferente do novo, remove antigo
    if (file_exists($oldFile) && $oldFile !== $newFile) {
        @unlink($oldFile);
    }

    $_SESSION['msg_sala'] = "Sala editada com sucesso!";
    // redireciona para a página da sala editada
    header("Location: ../paginaAdmin.php?sala=" . urlencode($nome));
    exit;
}

// GET: exibir formulário (quando acessado por ?id=)
$id = intval($_GET['id']);
$sql = "SELECT * FROM sala WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$sala = $result->fetch_assoc();
$stmt->close();
?>