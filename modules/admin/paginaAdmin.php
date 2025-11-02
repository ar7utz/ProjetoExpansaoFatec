<?php
session_start();

include('../../assets/bd/conexao.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: ../pageLoginADM.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Função para buscar arquivos de materiais (mantida para arquivos físicos, se necessário)
function listarMateriais($sala) {
    $diretorio = "../../assets/arquivos/" . $sala . "/materiais/";
    if (!is_dir($diretorio)) {
        return [];
    }
    $arquivos = array_diff(scandir($diretorio), ['.', '..']);
    return $arquivos;
}

// Função para buscar links do banco de dados
function listarLinks($conn, $id_sala) {
    $links = [];
    $sql = "SELECT * FROM links WHERE id_sala = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_sala);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $links[] = $row;
    }
    return $links;
}

// Buscar salas do banco
$salas = [];
$sqlSalas = "SELECT * FROM sala";
$resultSalas = $conn->query($sqlSalas);
while ($row = $resultSalas->fetch_assoc()) {
    $salas[] = $row;
}


// Determina a sala selecionada (aceita id numérico ou nome com espaços)
// resultado: $salaSelecionada (id) e $nomeSalaSelecionada (nome)
$salaSelecionada = null;
$nomeSalaSelecionada = '';

if (isset($_GET['sala']) && $_GET['sala'] !== '') {
    $salaParam = $_GET['sala'];

    if (ctype_digit(strval($salaParam))) {
        // recebeu ID
        $salaSelecionada = intval($salaParam);
    } else {
        // recebeu NOME da sala -> buscar ID
        $stmt = $conn->prepare("SELECT id, nome FROM sala WHERE nome = ?");
        $stmt->bind_param('s', $salaParam);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $salaSelecionada = intval($row['id']);
            $nomeSalaSelecionada = $row['nome'];
        }
        $stmt->close();
    }

    // se temos apenas ID, buscar nome
    if ($salaSelecionada && $nomeSalaSelecionada === '') {
        $stmt2 = $conn->prepare("SELECT nome FROM sala WHERE id = ?");
        $stmt2->bind_param('i', $salaSelecionada);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        if ($r = $res2->fetch_assoc()) {
            $nomeSalaSelecionada = $r['nome'];
        }
        $stmt2->close();
    }
}

// Carregar materiais e links dinamicamente para a sala selecionada
$materiais = [];
$links = [];
$totalPaginasMat = 1;
$totalPaginasLinks = 1;

$id_sala = null;
// se paginaAdmin já definiu $salaSelecionada como id ou nome, ajusta aqui
if (isset($salaSelecionada) && $salaSelecionada) {
    $id_sala = intval($salaSelecionada);
} elseif (!empty($nomeSalaSelecionada)) {
    $stmtTemp = $conn->prepare("SELECT id FROM sala WHERE nome = ?");
    $stmtTemp->bind_param('s', $nomeSalaSelecionada);
    $stmtTemp->execute();
    $resTemp = $stmtTemp->get_result();
    $rowTemp = $resTemp->fetch_assoc();
    if ($rowTemp) $id_sala = intval($rowTemp['id']);
    $stmtTemp->close();
}

// Caso só tenha id, busca o nome (útil para links/path)
if ($id_sala && empty($nomeSalaSelecionada)) {
    $stmtN = $conn->prepare("SELECT nome FROM sala WHERE id = ?");
    $stmtN->bind_param('i', $id_sala);
    $stmtN->execute();
    $resN = $stmtN->get_result();
    if ($r = $resN->fetch_assoc()) $nomeSalaSelecionada = $r['nome'];
    $stmtN->close();
}

// Materiais - paginação e carregamento
$itensPorPaginaMat = 10;
$paginaAtualMat = isset($_GET['pagina_materiais']) ? max(1, intval($_GET['pagina_materiais'])) : 1;
$offsetMat = ($paginaAtualMat - 1) * $itensPorPaginaMat;

if ($id_sala) {
    $sqlTotalMat = "SELECT COUNT(*) as total FROM materiais WHERE id_sala = ?";
    $stmtTotalMat = $conn->prepare($sqlTotalMat);
    $stmtTotalMat->bind_param('i', $id_sala);
    $stmtTotalMat->execute();
    $resultTotalMat = $stmtTotalMat->get_result();
    $totalMateriais = $resultTotalMat->fetch_assoc()['total'] ?? 0;
    $totalPaginasMat = $totalMateriais > 0 ? ceil($totalMateriais / $itensPorPaginaMat) : 1;
    $stmtTotalMat->close();

    $sqlMat = "SELECT * FROM materiais WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
    $stmtMat = $conn->prepare($sqlMat);
    $stmtMat->bind_param('iii', $id_sala, $itensPorPaginaMat, $offsetMat);
    $stmtMat->execute();
    $resMat = $stmtMat->get_result();
    while ($row = $resMat->fetch_assoc()) $materiais[] = $row;
    $stmtMat->close();
}

// Links - paginação e carregamento
$itensPorPaginaLinks = 10;
$paginaAtualLinks = isset($_GET['pagina_links']) ? max(1, intval($_GET['pagina_links'])) : 1;
$offsetLinks = ($paginaAtualLinks - 1) * $itensPorPaginaLinks;

if ($id_sala) {
    $sqlTotalLinks = "SELECT COUNT(*) as total FROM links WHERE id_sala = ?";
    $stmtTotalLinks = $conn->prepare($sqlTotalLinks);
    $stmtTotalLinks->bind_param('i', $id_sala);
    $stmtTotalLinks->execute();
    $resultTotalLinks = $stmtTotalLinks->get_result();
    $totalLinks = $resultTotalLinks->fetch_assoc()['total'] ?? 0;
    $totalPaginasLinks = $totalLinks > 0 ? ceil($totalLinks / $itensPorPaginaLinks) : 1;
    $stmtTotalLinks->close();

    $sqlLinks = "SELECT * FROM links WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
    $stmtLinks = $conn->prepare($sqlLinks);
    $stmtLinks->bind_param('iii', $id_sala, $itensPorPaginaLinks, $offsetLinks);
    $stmtLinks->execute();
    $resLinks = $stmtLinks->get_result();
    while ($row = $resLinks->fetch_assoc()) $links[] = $row;
    $stmtLinks->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../src/output.css">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    <!-- Sidebar fixa -->
    <nav class="w-64 bg-white shadow-md min-h-screen p-6 fixed left-0 top-0 h-full z-10">
        <h4 class="text-2xl font-bold mb-8">Projeto Expansão</h4>
        <ul class="space-y-2">

            <!-- Salas com submenu -->
            <li>
                <button id="btnSalas" type="button" class="w-full flex justify-between items-center px-4 py-2 rounded bg-cinza-fatec text-white font-semibold focus:outline-none">
                    Salas
                    <i id="iconSalas" class="fa fa-chevron-down transition-transform"></i>
                </button>
                <ul id="submenuSalas" class="mt-2 space-y-2 hidden ml-2">
                    <?php foreach ($salas as $sala): ?>
                        <li>
                            <a href="?sala=<?php echo $sala['id']; ?>"
                               class="w-full flex justify-between items-center px-4 py-2 rounded bg-gray-100 hover:bg-gray-400 text-gray-800 font-semibold focus:outline-none cursor-pointer
                               <?php if ($salaSelecionada == $sala['id']) echo 'ring-2 ring-yellow-400'; ?>">
                                <?php echo htmlspecialchars($sala['nome']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <!-- Avisos fora das salas -->
            <li>
                <a id="btnAvisos" class="block px-4 py-2 rounded hover:bg-cinza-fatec cursor-pointer" href="javascript:void(0);">Avisos</a>
            </li>

            <li>
                <a id="AddSala" class="block px-4 py-2 rounded hover:bg-cinza-fatec cursor-pointer" href="javascript:void(0);">Adicionar sala</a>
            </li>
        </ul>
    </nav>
    
    <!-- Main -->
    <main class="flex-1 p-8 ml-64 transition-all" id="mainContent">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold">Painel de Gerenciamento</h2>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none" aria-expanded="false" aria-haspopup="true">
                        <img src="https://i0.wp.com/www.if.ufrgs.br/if/wp-content/uploads/2018/04/default-profile.png?w=300&ssl=1" class="w-10 h-10 rounded-full" alt="Admin">
                        <span class="font-semibold">Admin</span>
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50">
                        <a href="./autenticação/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo dinâmico das salas -->
        <div id="salaTabs" class="<?php echo $salaSelecionada ? '' : 'hidden'; ?>">
            <div class="flex p-4 ml-2">
                <div class="flex flex-col">
                    <div class="flex gap-2 mb-4 mt-2">
                        <span class="text-lg font-semibold" id="salaSelecionada">
                            <?php echo htmlspecialchars($nomeSalaSelecionada); ?>
                        </span>
                        <a href="?editSala=<?php echo $salaSelecionada; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center gap-2">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="abrirModalExcluir('sala', <?php echo $salaSelecionada; ?>);" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-2">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    
                    <?php
                    // Exibe imagem da sala, se existir
                    foreach ($salas as $sala) {
                        if ($sala['id'] == $salaSelecionada) {
                            // Imagem da sala
                            if (!empty($sala['img_sala'])) {
                                echo '<img src="../../assets/imgs/salas/' . htmlspecialchars($sala['img_sala']) . '" alt="Imagem da Sala" class="w-32 h-32 object-cover rounded mb-2 mt-2">';
                            }
                            // Imagem do professor
                            if (!empty($sala['foto_professor'])) {
                                echo '<img src="../../assets/imgs/professores/' . htmlspecialchars($sala['foto_professor']) . '" alt="Foto do Professor" class="w-20 h-20 object-cover rounded mb-2">';
                            }
                            // Nome do professor
                            if (!empty($sala['professor'])) {
                                echo '<span class="font-bold text-gray-700 text-md mb-1">' . htmlspecialchars($sala['professor']) . '</span>';
                            }
                            // Descrição do professor
                            if (!empty($sala['descricao_professor'])) {
                                echo '<span class="text-gray-500 text-sm mb-2">' . htmlspecialchars($sala['descricao_professor']) . '</span>';
                            }
                            break;
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="flex gap-2 mb-6">
                <a id="tabMateriais" class="bg-cinza-fatec text-gray-800 px-4 py-2 rounded font-semibold cursor-pointer" onclick="showTab('materiais')">Materiais</a>
                <a id="tabLinks" class="bg-cinza-fatec text-gray-800 px-4 py-2 rounded font-semibold cursor-pointer" onclick="showTab('links')">Links</a>
            </div>

            <!-- Materiais -->
            <div id="tabContentMateriais" class="tab-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Materiais</h3>
                    <a href="#" onclick="openModal('modalAddMaterial')">
                        <button type="button" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 cursor-pointer" >
                            <i class="fa fa-plus"></i> Importar Material
                        </button>
                    </a>

                </div>
                <div class="bg-white rounded shadow mb-8">
                    <div class="overflow-x-auto">
                        <?php if ($salaSelecionada): ?>
                            <?php if (empty($materiais)): ?>
                                <div class="p-6 text-gray-500">Nenhum arquivo encontrado.</div>
                            <?php else: ?>
                                <table class="min-w-full text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3">Arquivo</th>
                                            <th class="px-6 py-3">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materiais as $mat): ?>
                                        <?php $arquivoNome = $mat['arquivo'] ?? ''; ?>
                                        <?php $materialId = $mat['id'] ?? 0; ?>
                                        <tr class="border-b">
                                            <td class="px-6 py-4">
                                                <a href="<?php echo "../../assets/arquivos/" . rawurlencode($nomeSalaSelecionada) . "/materiais/" . rawurlencode($arquivoNome); ?>" 
                                                   download 
                                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 flex items-center gap-2">
                                                    <i class="fa fa-download"></i> <?php echo htmlspecialchars($arquivoNome); ?>
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 flex gap-2">
                                                <!-- passa o ID do material e o ID da sala -->
                                                <a href="javascript:void(0);" 
                                                   onclick="abrirModalExcluir('material', <?php echo (int)$materialId; ?>, <?php echo (int)$salaSelecionada; ?>);" 
                                                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="p-6 text-gray-500">Selecione uma sala para visualizar os materiais.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Links -->
            <div id="tabContentLinks" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Materiais</h3>
                    <button type="button" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 cursor-pointer" onclick="openModal('modalAddLink')">
                        <i class="fa fa-plus"></i> Inserir link
                    </button>
                </div>
            <?php
                if ($salaSelecionada) {
                    // Paginação
                    $itensPorPagina = 10;
                    $paginaAtual = isset($_GET['pagina_links']) ? max(1, intval($_GET['pagina_links'])) : 1;
                    $offset = ($paginaAtual - 1) * $itensPorPagina;
                
                    // Conta total de links
                    $sqlTotal = "SELECT COUNT(*) as total FROM links WHERE id_sala = ?";
                    $stmtTotal = $conn->prepare($sqlTotal);
                    $stmtTotal->bind_param('i', $salaSelecionada);
                    $stmtTotal->execute();
                    $resultTotal = $stmtTotal->get_result();
                    $totalLinks = $resultTotal->fetch_assoc()['total'];
                    $totalPaginas = ceil($totalLinks / $itensPorPagina);
                
                    // Busca os links da página atual
                    $sql = "SELECT * FROM links WHERE id_sala = ? ORDER BY data DESC LIMIT ? OFFSET ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iii', $salaSelecionada, $itensPorPagina, $offset);
                    $stmt->execute();
                    $resultado = $stmt->get_result();
                
                    if ($resultado->num_rows > 0) {
                        echo '<table class="min-w-full text-left">';
                        echo '<thead class="bg-gray-50">';
                        echo '<tr>';
                        echo '<th class="px-4 py-3">Nome</th>';
                        echo '<th class="px-6 py-3">Link</th>';
                        echo '<th class="px-6 py-3">Data</th>';
                        echo '<th class="px-6 py-3">Ações</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        while ($link = $resultado->fetch_assoc()) {
                            $data_formatada = date('d/m/Y', strtotime($link['data']));
                            echo '<tr class="border-b">';
                            echo '<td class="px-4 py-4">' . htmlspecialchars($link['nome']) . '</td>';
                            echo '<td class="px-6 py-4"><a href="' . htmlspecialchars($link['url']) . '" target="_blank" class="text-blue-600 underline">' . htmlspecialchars($link['url']) . '</a></td>';
                            echo '<td class="px-6 py-4">' . $data_formatada . '</td>';
                            echo '<td class="px-6 py-4 flex gap-2">';
                            echo '<a href="./Links/editLink.php?id=' . $link['id'] . '&sala=' . $salaSelecionada . '" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fa fa-edit"></i></a>';
                            echo '<a href="javascript:void(0);" onclick="abrirModalExcluir(\'link\', ' . $link['id'] . ', ' . $salaSelecionada . ');" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"><i class="fa fa-trash"></i></a>';                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    
                        // Paginação
                        if ($totalPaginas > 1) {
                            echo '<div class="flex justify-center mt-4 space-x-2">';
                            for ($i = 1; $i <= $totalPaginas; $i++) {
                                $active = $i == $paginaAtual ? 'bg-yellow-300' : 'bg-gray-200';
                                echo '<a href="?sala=' . $salaSelecionada . '&pagina_links=' . $i . '" class="px-3 py-1 rounded ' . $active . '">' . $i . '</a>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="p-6 text-gray-500">Nenhum link encontrado.</div>';
                    }
                } else {
                    echo '<div class="p-6 text-gray-500">Selecione uma sala para visualizar os links.</div>';
                }
            ?>
            </div>
        </div>
        

        <!-- Mensagem inicial -->
        <div id="mensagemInicial" class="text-gray-500 text-lg text-center mt-20 <?php echo $salaSelecionada ? 'hidden' : ''; ?>">
            Selecione uma sala para gerenciar materiais, formulários e relatórios.
        </div>

        <!-- Conteúdo de Avisos -->
        <div id="avisosContent" class="hidden">
            <div class="mb-4">
                <span class="text-lg font-semibold">Gerenciar Avisos</span>
            </div>
            <div class="bg-white rounded shadow mb-8 p-6">
                <form method="POST" action="./avisos/addAviso.php" enctype="multipart/form-data">
                    <label for="novoAviso" class="block mb-2 font-semibold">Novo aviso:</label>
                    <textarea id="descricao" name="descricao" class="w-full border px-3 py-2 rounded mb-4" rows="3"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-yellow-400 text-gray-800 px-4 py-2 rounded hover:bg-yellow-500">Adicionar Aviso</button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Avisos recentes:</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        <?php
                        // Exibir avisos do mais novo para o mais antigo
                        $sqlAvisos = "SELECT * FROM avisos ORDER BY data DESC";
                        $resultAvisos = $conn->query($sqlAvisos);
                        ?>
                        <?php if ($resultAvisos->num_rows > 0): ?>
                            <?php while ($aviso = $resultAvisos->fetch_assoc()): ?>
                                <li class="flex justify-between items-center">
                                    <span>
                                        <?php echo htmlspecialchars($aviso['descricao']); ?>
                                        <span class="text-xs text-gray-400 ml-2">(<?php echo date('d/m/Y', strtotime($aviso['data'])); ?>)</span>
                                    </span>
                                    <span>
                                        <a href="./avisos/editAviso.php?id=<?php echo $aviso['id']; ?>" class="text-blue-500 hover:underline mr-2"><i class="fa fa-edit"></i></a>
                                        <!-- agora usa o modal de confirmação (mesmo comportamento dos materiais) -->
                                        <a href="javascript:void(0);" onclick="abrirModalExcluir('aviso', <?php echo (int)$aviso['id']; ?>);" class="text-red-500 hover:underline"><i class="fa fa-trash"></i></a>
                                    </span>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>Nenhum aviso encontrado.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Conteúdo de adição de sala -->
        <div id="addSalaContent" class="hidden"></div>

        <!-- Conteúdo de edição de sala (container único que comporta ADD e EDIT) -->
        <div id="editSalaContent" class="hidden w-full">
            <!-- ADD SALA: agora ocupa todo o espaço do container (mesmo estilo do edit form) -->
            <div id="addSalaInner" class="hidden w-full">
                <div class="bg-white rounded shadow p-6 w-full">
                    <h2 class="text-2xl font-bold mb-4">Adicionar Nova Sala</h2>
                    <form method="POST" action="./sala/addSala.php" enctype="multipart/form-data" class="w-full">
                        <div class="flex flex-row w-full mb-6">
                            <div class="flex-[7] pr-8">
                                <div class="mb-4">
                                    <label class="block mb-1 font-semibold">Título da Sala</label>
                                    <input type="text" name="nome" class="w-full border px-3 py-2 rounded" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1 font-semibold">Descrição</label>
                                    <textarea name="descricao_sala" class="w-full border px-3 py-2 rounded" rows="6" required></textarea>
                                </div>
                                <!-- <div class="mb-4">
                                    <label class="block mb-1 font-semibold">Imagem da Sala (opcional)</label>
                                    <input type="file" name="img_sala" class="w-full border px-3 py-2 rounded">
                                </div> -->
                            </div>

                            <div class="flex-[3] flex flex-col items-center justify-start">
                                <h3 class="text-lg font-semibold mb-4">Professor Responsável</h3>

                                <div class="mb-4 w-full flex flex-col items-center">
                                    <label class="block mb-1 font-semibold">Foto</label>
                                    <input type="file" name="foto_professor" class="w-full border px-3 py-2 rounded mb-3">
                                </div>

                                <div class="mb-4 w-full">
                                    <label class="block mb-1 font-semibold">Nome</label>
                                    <input type="text" name="professor" class="w-full border px-3 py-2 rounded">
                                </div>

                                <div class="mb-4 w-full">
                                    <label class="block mb-1 font-semibold">Descrição breve</label>
                                    <textarea name="descricao_professor" class="w-full border px-3 py-2 rounded" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="w-full flex justify-center">
                            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 cursor-pointer">Salvar Sala</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- EDIT FORM WRAPPER (mantém PHP existente, agora controlável via JS) -->
            <div id="editFormInner" class="w-full">
            <?php
             // Só exibe se houver sala selecionada para edição
             if (isset($_GET['editSala']) && $_GET['editSala']) {
                 $id = intval($_GET['editSala']);
                 $sql = "SELECT * FROM sala WHERE id = ?";
                 $stmt = $conn->prepare($sql);
                 $stmt->bind_param('i', $id);
                 $stmt->execute();
                 $result = $stmt->get_result();
                 $sala = $result->fetch_assoc();
             ?>
             <form method="POST" action="./sala/editSala.php" enctype="multipart/form-data" class="bg-white rounded shadow p-6 w-full">
                 <input type="hidden" name="id" value="<?php echo $sala['id']; ?>">
                 <div class="flex flex-row w-full mb-6">
                     <!-- Esquerda: título, descrição e imagem da sala (70%) -->
                     <div class="flex-[7] pr-8">
                         <h2 class="text-2xl font-bold mb-4">Editar Sala: <?php echo htmlspecialchars($sala['nome']); ?></h2>

                         <div class="mb-4">
                             <label class="block mb-1 font-semibold">Título da Sala</label>
                             <input type="text" name="nome" class="w-full border px-3 py-2 rounded" value="<?php echo htmlspecialchars($sala['nome']); ?>" required>
                         </div>

                         <div class="mb-4">
                             <label class="block mb-1 font-semibold">Descrição</label>
                             <textarea name="descricao_sala" class="w-full border px-3 py-2 rounded" rows="6" required><?php echo htmlspecialchars($sala['descricao_sala']); ?></textarea>
                         </div>

                         <!-- <div class="mb-4">
                             <label class="block mb-1 font-semibold">Imagem da Sala</label>
                             <?php
                                 $imgSalaPath = "../../assets/imgs/salas/" . ($sala['img_sala'] ?: '');
                                 $defaultSala = "../../assets/imgs/salas/sala_default.png";
                                 $exibirSala = (!empty($sala['img_sala']) && file_exists(__DIR__ . "/../../assets/imgs/salas/" . $sala['img_sala'])) ? $imgSalaPath : $defaultSala;
                             ?>
                             <img src="<?php echo $exibirSala; ?>" alt="Imagem da Sala" class="w-full max-w-md h-48 object-cover rounded mb-2">
                             <input type="file" name="img_sala" class="w-full border px-3 py-2 rounded">
                             <input type="hidden" name="img_sala_atual" value="<?php echo htmlspecialchars($sala['img_sala']); ?>">
                         </div> -->
                     </div>

                     <!-- Direita: professor (30%) -->
                     <div class="flex-[3] flex flex-col items-center justify-start">
                         <h3 class="text-lg font-semibold mb-4">Professor Responsável</h3>

                         <div class="mb-4 w-full flex flex-col items-center">
                             <?php
                                 $imgProfPath = "../../assets/imgs/professores/" . ($sala['foto_professor'] ?: '');
                                 $defaultProf = "../../assets/imgs/professores/user_default.png";
                                 $exibirProf = (!empty($sala['foto_professor']) && file_exists(__DIR__ . "/../../assets/imgs/professores/" . $sala['foto_professor'])) ? $imgProfPath : $defaultProf;
                             ?>
                             <img src="<?php echo $exibirProf; ?>" alt="Foto do Professor" class="w-40 h-40 object-cover rounded mb-2">
                             <input type="file" name="foto_professor" class="w-full border px-3 py-2 rounded mb-3">
                             <input type="hidden" name="foto_professor_atual" value="<?php echo htmlspecialchars($sala['foto_professor']); ?>">
                         </div>

                         <div class="mb-4 w-full">
                             <label class="block mb-1 font-semibold">Nome</label>
                             <input type="text" name="professor" class="w-full border px-3 py-2 rounded" value="<?php echo htmlspecialchars($sala['professor']); ?>">
                         </div>

                         <div class="mb-4 w-full">
                             <label class="block mb-1 font-semibold">Descrição breve</label>
                             <textarea name="descricao_professor" class="w-full border px-3 py-2 rounded" rows="4"><?php echo htmlspecialchars($sala['descricao_professor']); ?></textarea>
                         </div>
                     </div>
                 </div>
                <div class="w-full flex justify-center">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 cursor-pointer">Salvar Alterações</button>
                </div>
             </form>
             <?php } ?>
            </div> <!-- fim editFormInner -->

        </div> <!-- fim editSalaContent -->

    </main>
</div>

<div id="modalAddMaterial" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-40 hidden" aria-hidden="true">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 z-50 relative">
         <div class="flex justify-between items-center mb-4">
             <h5 class="text-xl font-bold text-cinza-fatec">Adicionar Material</h5>
             <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl font-bold cursor-pointer" onclick="closeModal('modalAddMaterial')">&times;</button>
         </div>
         <form action="./Material/addMaterial.php" method="POST" enctype="multipart/form-data">
             <div class="mb-4">
                 <label for="material_nome" class="block mb-1 font-semibold">Nome do Material</label>
                 <input type="text" class="w-full border px-3 py-2 rounded" id="material_nome" name="nome" required>
             </div>
             <div class="mb-4">
                 <label for="material_arquivo" class="block mb-1 font-semibold">Arquivo</label>
                 <input type="file" class="w-full border px-3 py-2 rounded" id="material_arquivo" name="arquivo" required>
             </div>
             <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($salaSelecionada); ?>">
             <div class="flex justify-end gap-2 mt-6">
                 <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 font-semibold cursor-pointer" onclick="closeModal('modalAddMaterial')">Cancelar</button>
                 <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 font-semibold cursor-pointer">Salvar</button>
             </div>
         </form>
     </div>
 </div>

<!-- Modal Adicionar Link (padrão do modal de adicionar transação) -->
<div id="modalAddLink" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-40 hidden" aria-hidden="true">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 z-50 relative">
         <div class="flex justify-between items-center mb-4">
             <h5 class="text-xl font-bold text-cinza-fatec">Adicionar Link</h5>
             <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl font-bold cursor-pointer" onclick="closeModal('modalAddLink')">&times;</button>
         </div>
         <form action="./Links/addLink.php" method="POST">
             <div class="mb-4">
                 <label for="link_nome" class="block mb-1 font-semibold">Nome do Link</label>
                 <input type="text" class="w-full border px-3 py-2 rounded" id="link_nome" name="nome" required>
             </div>
             <div class="mb-4">
                 <label for="link_url" class="block mb-1 font-semibold">URL</label>
                 <input type="url" class="w-full border px-3 py-2 rounded" id="link_url" name="url" required>
             </div>
             <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($salaSelecionada); ?>">
             <div class="flex justify-end gap-2 mt-6">
                 <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 font-semibold cursor-pointer" onclick="closeModal('modalAddLink')">Cancelar</button>
                 <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 font-semibold cursor-pointer">Salvar</button>
             </div>
         </form>
     </div>
 </div>

<!-- Modal de confirmação de exclusão (padrão do dashboard.php) -->
<div id="modalConfirmarExclusao" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-40 hidden" aria-hidden="true">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-center z-50 relative">
         <h3 class="text-xl font-bold mb-4 text-red-600">Confirmar Exclusão</h3>
         <p class="mb-6 text-gray-700" id="textoConfirmacaoExclusao">Tem certeza que deseja excluir este item?</p>
         <div class="flex justify-center gap-4">
             <button id="btnConfirmarExclusao" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold cursor-pointer">Excluir</button>
             <button id="btnCancelarExclusao" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 font-semibold cursor-pointer">Cancelar</button>
         </div>
     </div>
 </div>

<script>
// Sidebar: toggle submenu de salas
document.getElementById('btnSalas').addEventListener('click', function() {
    const submenu = document.getElementById('submenuSalas');
    const icon = document.getElementById('iconSalas');
    submenu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
});

// Função para alternar abas de materiais/links
function showTab(tab) {
    // Abas
    const tabMateriais = document.getElementById('tabMateriais');
    const tabLinks = document.getElementById('tabLinks');
    // Conteúdos
    const contentMateriais = document.getElementById('tabContentMateriais');
    const contentLinks = document.getElementById('tabContentLinks');

    // Reset abas
    tabMateriais.classList.remove('bg-yellow-200');
    tabMateriais.classList.add('bg-cinza-fatec');
    tabLinks.classList.remove('bg-yellow-200');
    tabLinks.classList.add('bg-cinza-fatec');

    // Esconde todos
    contentMateriais.classList.add('hidden');
    contentLinks.classList.add('hidden');

    // Mostra selecionado
    if(tab === 'materiais') {
        tabMateriais.classList.add('bg-yellow-200');
        tabMateriais.classList.remove('bg-cinza-fatec');
        contentMateriais.classList.remove('hidden');
    } else if(tab === 'links') {
        tabLinks.classList.add('bg-yellow-200');
        tabLinks.classList.remove('bg-cinza-fatec');
        contentLinks.classList.remove('hidden');
    }
}


function hideAllMainSections() {
    const ids = ['salaTabs', 'avisosContent', 'mensagemInicial', 'addSalaContent', 'editSalaContent'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
    // também esconder sub-inners dentro do container de edição
    const addInner = document.getElementById('addSalaInner');
    const editInner = document.getElementById('editFormInner');
    if (addInner) addInner.classList.add('hidden');
    if (editInner) editInner.classList.add('hidden');
}

/* mostra apenas a área de sala (quando navega para ?sala=ID) */
function showSalaArea() {
    hideAllMainSections();
    const salaTabs = document.getElementById('salaTabs');
    if (salaTabs) salaTabs.classList.remove('hidden');
}

/* Botão Avisos */
document.getElementById('btnAvisos').addEventListener('click', function() {
    hideAllMainSections();
    const avisos = document.getElementById('avisosContent');
    if (avisos) avisos.classList.remove('hidden');
});

/* Botão Adicionar Sala */
document.getElementById('AddSala').addEventListener('click', function() {
    hideAllMainSections();
    const editContainer = document.getElementById('editSalaContent');
    if (editContainer) editContainer.classList.remove('hidden');

    const addInner = document.getElementById('addSalaInner');
    const editInner = document.getElementById('editFormInner');
    if (addInner) addInner.classList.remove('hidden');
    if (editInner) editInner.classList.add('hidden');

    editContainer.scrollIntoView({behavior: 'smooth'});
});

/* Garante que ao clicar em qualquer link de sala do submenu, apenas a sala selecionada seja mostrada.
   Não previne navegação (o href continua funcionando); esto previne que componentes extras fiquem visíveis
   em single-page interactions. */
document.querySelectorAll('#submenuSalas a').forEach(link => {
    link.addEventListener('click', function() {
        // antes de navegar, esconder tudo (evita que o formulário de adicionar permaneça visível em SPA flows)
        hideAllMainSections();
        // permitir que o link siga normalmente (recarregamento)
    });
});

/* Ao carregar a página, decide qual área mostrar com base na query string */
(function initViewFromUrl(){
    const params = new URLSearchParams(window.location.search);
    if (params.has('editSala')) {
        // mostrar apenas o container de edição (ou o formulário de edição se presente)
        hideAllMainSections();
        const editContainer = document.getElementById('editSalaContent');
        if (editContainer) editContainer.classList.remove('hidden');
        const editInner = document.getElementById('editFormInner');
        if (editInner) editInner.classList.remove('hidden');
        // rolar
        editContainer && editContainer.scrollIntoView({behavior: 'smooth'});
        return;
    }
    if (params.has('sala')) {
        showSalaArea();
        return;
    }
    // padrão: mostrar mensagem inicial
    hideAllMainSections();
    const msg = document.getElementById('mensagemInicial');
    if (msg) msg.classList.remove('hidden');
})();
</script>

<script>
(function(){
    const modal = document.getElementById('modalConfirmarExclusao');
    const texto = document.getElementById('textoConfirmacaoExclusao');
    const btnConfirm = document.getElementById('btnConfirmarExclusao');
    const btnCancel = document.getElementById('btnCancelarExclusao');

    if (!modal || !btnConfirm || !btnCancel || !texto) return;

    // Função pública para abrir o modal de exclusão
    window.abrirModalExcluir = function(tipo, id, salaId = null, arquivo = null) {
        let url = '#';
        let msg = 'Tem certeza que deseja excluir este item?';

        switch (tipo) {
            case 'sala':
                msg = 'Tem certeza que deseja excluir esta sala?';
                url = `./sala/deleteSala.php?id=${encodeURIComponent(id)}`;
                break;
            case 'link':
                msg = 'Tem certeza que deseja excluir este link?';
                url = `./Links/deleteLink.php?id=${encodeURIComponent(id)}&sala=${encodeURIComponent(salaId)}`;
                break;
            case 'material':
                msg = 'Tem certeza que deseja excluir este material?';
                // id e salaId já são números passados ao chamar abrirModalExcluir
                url = `./Material/deleteMaterial.php?id=${encodeURIComponent(id)}&sala=${encodeURIComponent(salaId)}`;
                break;
            case 'aviso':
                msg = 'Tem certeza que deseja excluir este aviso?';
                url = `./avisos/deleteAviso.php?id=${encodeURIComponent(id)}`;
                break;
            default:
                msg = 'Tem certeza que deseja excluir este item?';
        }

        texto.textContent = msg;

        // Define ação de confirmação (substitui handler anterior)
        btnConfirm.onclick = function() {
            // fechar modal antes de navegar (opcional)
            modal.classList.add('hidden');
            window.location.href = url;
        };

        // mostra modal
        modal.classList.remove('hidden');
    };

    // fechar ao clicar no botão cancelar
    btnCancel.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // fechar ao clicar fora da caixa do modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.classList.add('hidden');
    });

    // proteger contra funções duplicadas (se houver outras definições em cache)
    // sobrescreve qualquer abrirModalExcluir anterior
    window.openModal = window.openModal || function(id){ document.getElementById(id).classList.remove('hidden'); };
    window.closeModal = window.closeModal || function(id){ document.getElementById(id).classList.add('hidden'); };
})();
</script>

<script>
(function(){
    const btn = document.getElementById('userMenuButton');
    const menu = document.getElementById('userDropdown');
    if (!btn || !menu) return;

    btn.addEventListener('click', function(e){
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden ? true : false);
        btn.setAttribute('aria-expanded', String(isHidden));
    });

    // fechar ao clicar fora
    document.addEventListener('click', function(e){
        if (!menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    });

    // fechar com Escape
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>

</body>
</html>