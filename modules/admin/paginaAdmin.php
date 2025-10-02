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

$salaSelecionada = isset($_GET['sala']) ? $_GET['sala'] : null;
$materiais = [];
$links = [];
$nomeSalaSelecionada = '';
if ($salaSelecionada) {
    // Busca nome da sala
    foreach ($salas as $sala) {
        if ($sala['id'] == $salaSelecionada) {
            $nomeSalaSelecionada = $sala['nome'];
            break;
        }
    }
    $materiais = listarMateriais($nomeSalaSelecionada);
    $links = listarLinks($conn, $salaSelecionada);
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
        </ul>
    </nav>
    
    <!-- Main -->
    <main class="flex-1 p-8 ml-64 transition-all" id="mainContent">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold">Painel de Gerenciamento</h2>
            <div class="flex items-center space-x-2">
                <img src="https://i0.wp.com/www.if.ufrgs.br/if/wp-content/uploads/2018/04/default-profile.png?w=300&ssl=1" class="w-10 h-10 rounded-full" alt="Admin">
                <span class="font-semibold">Admin</span>
            </div>
        </div>

        <!-- Conteúdo dinâmico das salas -->
        <div id="salaTabs" class="<?php echo $salaSelecionada ? '' : 'hidden'; ?>">
            <div class="mb-4">
                <span class="text-lg font-semibold" id="salaSelecionada"><?php echo htmlspecialchars($nomeSalaSelecionada); ?></span>
            </div>

            <div class="flex gap-2 mb-6">
                <a id="tabMateriais" class="bg-cinza-fatec text-gray-800 px-4 py-2 rounded font-semibold cursor-pointer" onclick="showTab('materiais')">Materiais</a>
                <a id="tabLinks" class="bg-cinza-fatec text-gray-800 px-4 py-2 rounded font-semibold cursor-pointer" onclick="showTab('links')">Links</a>
            </div>

            <!-- Materiais -->
            <div id="tabContentMateriais" class="tab-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Materiais</h3>
                    <button type="button" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" onclick="openModal('modalAddMaterial')">
                        <i class="fa fa-plus"></i> Importar Material
                    </button>
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
                                        <?php foreach ($materiais as $arquivo): ?>
                                            <tr class="border-b">
                                                <td class="px-6 py-4"><?php echo htmlspecialchars($arquivo); ?></td>
                                                <td class="px-6 py-4 flex gap-2">
                                                    <a href="<?php echo "../../assets/arquivos/" . rawurlencode($nomeSalaSelecionada) . "/materiais/" . urlencode($arquivo); ?>" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Ver</a>
                                                    <a href="./Material/deleteMaterial.php?php echo urlencode($nomeSalaSelecionada); ?>&arquivo=<?php echo urlencode($arquivo); ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Excluir</a>
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
                    <button type="button" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" onclick="openModal('modalAddLink')">
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
                            $data_formatada = date('d/m/Y H:i', strtotime($link['data']));
                            echo '<tr class="border-b">';
                            echo '<td class="px-4 py-4">' . htmlspecialchars($link['nome']) . '</td>';
                            echo '<td class="px-6 py-4"><a href="' . htmlspecialchars($link['url']) . '" target="_blank" class="text-blue-600 underline">' . htmlspecialchars($link['url']) . '</a></td>';
                            echo '<td class="px-6 py-4">' . $data_formatada . '</td>';
                            echo '<td class="px-6 py-4 flex gap-2">';
                            echo '<a href="./Links/editLink.php?id=' . $link['id'] . '&sala=' . $salaSelecionada . '" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fa fa-edit"></i></a>';
                            echo '<a href="./Links/deleteLink.php?id=' . $link['id'] . '&sala=' . $salaSelecionada . '" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm(\'Tem certeza que deseja excluir este link?\')"><i class="fa fa-trash"></i></a>';
                            echo '</td>';
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
                <form>
                    <label for="novoAviso" class="block mb-2 font-semibold">Novo aviso:</label>
                    <textarea id="novoAviso" class="w-full border px-3 py-2 rounded mb-4" rows="3"></textarea>
                    <button type="button" class="bg-yellow-400 text-gray-800 px-4 py-2 rounded hover:bg-yellow-500">Adicionar Aviso</button>
                </form>
                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Avisos recentes:</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Exemplo de aviso já cadastrado.</li>
                        <!-- ...avisos dinâmicos via PHP... -->
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Adicionar Material -->
<div id="modalAddMaterial" class="fixed inset-0 bg-black bg-opacity-100 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
    <form class="p-6" action="./Material/addMaterial.php" method="POST" enctype="multipart/form-data">
      <div class="flex justify-between items-center mb-4">
        <h5 class="text-xl font-bold">Adicionar Material</h5>
        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal('modalAddMaterial')">&times;</button>
      </div>
      <div class="mb-4">
        <label for="material_nome" class="block mb-1 font-semibold">Nome do Material</label>
        <input type="text" class="w-full border px-3 py-2 rounded" id="material_nome" name="nome" required>
      </div>
      <div class="mb-4">
        <label for="material_arquivo" class="block mb-1 font-semibold">Arquivo</label>
        <input type="file" class="w-full border px-3 py-2 rounded" id="material_arquivo" name="arquivo" required>
      </div>
      <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($salaSelecionada); ?>">
      <div class="flex justify-end">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Adicionar Link -->
<div id="modalAddLink" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
    <form class="p-6" action="./Links/addLink.php" method="POST">
      <div class="flex justify-between items-center mb-4">
        <h5 class="text-xl font-bold">Adicionar Link</h5>
        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal('modalAddLink')">&times;</button>
      </div>
      <div class="mb-4">
        <label for="link_nome" class="block mb-1 font-semibold">Nome do Link</label>
        <input type="text" class="w-full border px-3 py-2 rounded" id="link_nome" name="nome" required>
      </div>
      <div class="mb-4">
        <label for="link_url" class="block mb-1 font-semibold">URL</label>
        <input type="url" class="w-full border px-3 py-2 rounded" id="link_url" name="url" required>
      </div>
      <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($salaSelecionada); ?>">
      <div class="flex justify-end">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Salvar</button>
      </div>
    </form>
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

// Avisos: mostrar conteúdo de avisos ao clicar
document.getElementById('btnAvisos').addEventListener('click', function() {
    document.getElementById('salaTabs').classList.add('hidden');
    document.getElementById('avisosContent').classList.remove('hidden');
    document.getElementById('mensagemInicial').classList.add('hidden');
});

// Modal funções
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
document.querySelectorAll('.fixed.inset-0').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if(e.target === modal) modal.classList.add('hidden');
    });
});
document.querySelectorAll('[data-modal-target]').forEach(btn => {
    btn.addEventListener('click', function() {
        const modal = document.getElementById(this.getAttribute('data-modal-target'));
        if(modal) modal.classList.remove('hidden');
    });
});
</script>

    <script>//Fechar modais clicando fora da caixa
        window.addEventListener('click', function(event) {
            const modais = ['AddTransacaoModal', 'modalEditarTransacao', 'modalConfirmarExclusao'];
            modais.forEach(function(modalId) {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>

        <script> //Funções para abrir e fechar o modal de adicionar transação
        document.getElementById('abrirModalAddTransacao').addEventListener('click', function() {
            document.getElementById('AddTransacaoModal').classList.remove('hidden');
        });

        document.getElementById('fecharModalAdd').addEventListener('click', function() {
            document.getElementById('AddTransacaoModal').classList.add('hidden');
        });
    </script>

    <script>//Funções para abrir e fechar o modal de confirmação de exclusão 
        function abrirModalExcluir(id) {
            document.getElementById('confirmarExcluirNota').onclick = function() {
                window.location.href = `../transacoes/excluir_transacao.php?id=${id}`;
            };
            document.getElementById('modalConfirmarExclusao').classList.remove('hidden');
        }
        //Função para cancelar a exclusão e fechar o modal
        document.getElementById('cancelarExcluirNota').addEventListener('click', function() {
            document.getElementById('modalConfirmarExclusao').classList.add('hidden');
        });
    </script>

</body>
</html>