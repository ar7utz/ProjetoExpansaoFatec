<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../src/output.css">
    <link rel="shortcut icon" href="../../assets/icon/fatec-logo-nobackground.ico" type="image/x-icon">
    <title>Futebol</title>
</head>
<body class="bg-gray-50 font-sans">
    <header class="bg-white shadow-sm">
        <?php require_once ('../../template/navbar.php'); ?>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-6">
        <div class="flex flex-col">
            <div class="flex flex-row w-full mb-12">
                <div class="flex-[7] pr-8">
                    <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Futebol</h1>
                    <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mb-12">
                        Futebol né
                    </p>
                </div>
                <div class="flex-[3] flex flex-col items-center justify-center">
                    <h2 class="text-xl font-bold mb-2">Professor</h2><img class="w-40 h-40 rounded-lg object-cover mb-2" src="../../../assets/imgs/professores/68eeafac0d0af_piso esprudeu.jpeg" alt="Foto do professor">                    <p class="text-md text-gray-700 font-semibold mb-2">Artur</p>
                    <p class="text-gray-600 text-sm text-center">Ótimo professor</p>
                </div>
            </div>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-8 border-b-2 pb-2">PROJETOS E ATIVIDADES</h2>
        <?php
            require_once('../../../assets/bd/conexao.php');
            $nomeSala = "Futebol";
            $sqlSala = "SELECT id FROM sala WHERE nome = ?";
            $stmtSala = $conn->prepare($sqlSala);
            $stmtSala->bind_param('s', $nomeSala);
            $stmtSala->execute();
            $resSala = $stmtSala->get_result();
            $sala = $resSala->fetch_assoc();
            $id_sala = $sala ? $sala['id'] : null;

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
                $totalMateriais = $resultTotalMat->fetch_assoc()['total'];
                $totalPaginasMat = ceil($totalMateriais / $itensPorPaginaMat);

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
                $totalLinks = $resultTotalLinks->fetch_assoc()['total'];
                $totalPaginasLinks = ceil($totalLinks / $itensPorPaginaLinks);

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
                                            <a href="../../../assets/arquivos/<?php echo rawurlencode($nomeSala); ?>/materiais/<?php echo urlencode($mat['arquivo']); ?>"
                                               target="_blank" class="text-blue-600 underline">
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