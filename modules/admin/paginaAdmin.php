<?php

// ...código PHP para autenticação/admin...
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
                <button id="btnSalas" type="button" class="w-full flex justify-between items-center px-4 py-2 rounded bg-yellow-200 text-gray-800 font-semibold focus:outline-none">
                    Salas
                    <i id="iconSalas" class="fa fa-chevron-down transition-transform"></i>
                </button>
                <ul id="submenuSalas" class="mt-2 space-y-2 hidden">
                    <li>
                        <button type="button" onclick="selectSala('EMPREENDEDORISMO')" class="w-full flex justify-between items-center px-4 py-2 rounded bg-gray-100 hover:bg-yellow-100 text-gray-800 font-semibold focus:outline-none">
                            Empreendedorismo
                        </button>
                    </li>
                    <li>
                        <button type="button" onclick="selectSala('METODOLOGIA CIENTÍFICA')" class="w-full flex justify-between items-center px-4 py-2 rounded bg-gray-100 hover:bg-yellow-100 text-gray-800 font-semibold focus:outline-none">
                            Metodologia Científica
                        </button>
                    </li>
                    <li>
                        <button type="button" onclick="selectSala('Sociedade, Tecnologia e Inovação')" class="w-full flex justify-between items-center px-4 py-2 rounded bg-gray-100 hover:bg-yellow-100 text-gray-800 font-semibold focus:outline-none">
                            Sociedade, Tecnologia e Inovação
                        </button>
                    </li>
                    <li>
                        <button type="button" onclick="selectSala('Administração Geral')" class="w-full flex justify-between items-center px-4 py-2 rounded bg-gray-100 hover:bg-yellow-100 text-gray-800 font-semibold focus:outline-none">
                            Administração Geral
                        </button>
                    </li>
                </ul>
            </li>
            <!-- Avisos fora das salas -->
            <li>
                <a id="btnAvisos" class="block px-4 py-2 rounded hover:bg-yellow-100 cursor-pointer" href="javascript:void(0);">Avisos</a>
            </li>
        </ul>
    </nav>
    
    <!-- Main -->
    <main class="flex-1 p-8 ml-64 transition-all" id="mainContent">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold">Painel de Gerenciamento</h2>
            <div class="flex items-center space-x-2">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-10 h-10 rounded-full" alt="Admin">
                <span class="font-semibold">Admin</span>
            </div>
        </div>
        <!-- Conteúdo dinâmico das salas -->
        <div id="salaTabs" class="hidden">
            <div class="mb-4">
                <span class="text-lg font-semibold" id="salaSelecionada"></span>
            </div>
            <div class="flex gap-2 mb-6">
                <button id="tabMateriais" class="tab-btn bg-yellow-200 text-gray-800 px-4 py-2 rounded font-semibold" onclick="showTab('materiais')">Materiais</button>
                <button id="tabFormularios" class="tab-btn bg-gray-200 text-gray-800 px-4 py-2 rounded font-semibold" onclick="showTab('formularios')">Formulários</button>
                <button id="tabRelatorios" class="tab-btn bg-gray-200 text-gray-800 px-4 py-2 rounded font-semibold" onclick="showTab('relatorios')">Relatórios</button>
            </div>
            <!-- Materiais -->
            <div id="tabContentMateriais" class="tab-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Materiais</h3>
                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" onclick="openModal('modalAddMaterial')"><i class="fa fa-plus"></i> Importar Material</button>
                </div>
                <div class="bg-white rounded shadow mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Material</th>
                                    <th class="px-6 py-3">Quantidade</th>
                                    <th class="px-6 py-3">Responsável</th>
                                    <th class="px-6 py-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-6 py-4">Projetor</td>
                                    <td class="px-6 py-4">2</td>
                                    <td class="px-6 py-4">João Silva</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fa fa-edit"></i></button>
                                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- ...outras linhas dinâmicas via PHP... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Formulários -->
            <div id="tabContentFormularios" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Formulários</h3>
                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"><i class="fa fa-plus"></i> Novo Formulário</button>
                </div>
                <div class="bg-white rounded shadow mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Nome do Aluno</th>
                                    <th class="px-6 py-3">Data</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-6 py-4">Maria Oliveira</td>
                                    <td class="px-6 py-4">29/09/2025</td>
                                    <td class="px-6 py-4"><span class="bg-green-200 text-green-800 px-3 py-1 rounded">Respondido</span></td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fa fa-edit"></i></button>
                                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- ...outras linhas dinâmicas via PHP... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Relatórios -->
            <div id="tabContentRelatorios" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Relatórios</h3>
                    <div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mr-2"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                    </div>
                </div>
                <div class="bg-white rounded shadow mb-8 p-6">
                    <p>Visualização dos relatórios da sala selecionada.</p>
                    <!-- ...relatórios dinâmicos via PHP... -->
                </div>
            </div>
        </div>
        <!-- Mensagem inicial -->
        <div id="mensagemInicial" class="text-gray-500 text-lg text-center mt-20">
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
<div id="modalAddMaterial" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
    <form class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h5 class="text-xl font-bold">Adicionar Material</h5>
        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal('modalAddMaterial')">&times;</button>
      </div>
      <div class="mb-4">
        <label for="sala" class="block mb-1 font-semibold">Sala</label>
        <input type="text" class="w-full border px-3 py-2 rounded" id="sala" name="sala">
      </div>
      <div class="mb-4">
        <label for="material" class="block mb-1 font-semibold">Material</label>
        <input type="text" class="w-full border px-3 py-2 rounded" id="material" name="material">
      </div>
      <div class="mb-4">
        <label for="quantidade" class="block mb-1 font-semibold">Quantidade</label>
        <input type="number" class="w-full border px-3 py-2 rounded" id="quantidade" name="quantidade">
      </div>
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

// Selecionar sala e mostrar abas
function selectSala(nomeSala) {
    document.getElementById('mensagemInicial').classList.add('hidden');
    document.getElementById('salaTabs').classList.remove('hidden');
    document.getElementById('salaSelecionada').textContent = nomeSala;
    showTab('materiais');
}

// Troca de abas
function showTab(tab) {
    // Reset abas
    document.getElementById('tabMateriais').classList.remove('bg-yellow-200');
    document.getElementById('tabMateriais').classList.add('bg-gray-200');
    document.getElementById('tabFormularios').classList.remove('bg-yellow-200');
    document.getElementById('tabFormularios').classList.add('bg-gray-200');
    document.getElementById('tabRelatorios').classList.remove('bg-yellow-200');
    document.getElementById('tabRelatorios').classList.add('bg-gray-200');
    // Esconde todos
    document.getElementById('tabContentMateriais').classList.add('hidden');
    document.getElementById('tabContentFormularios').classList.add('hidden');
    document.getElementById('tabContentRelatorios').classList.add('hidden');
    // Mostra selecionado
    if(tab === 'materiais') {
        document.getElementById('tabMateriais').classList.add('bg-yellow-200');
        document.getElementById('tabMateriais').classList.remove('bg-gray-200');
        document.getElementById('tabContentMateriais').classList.remove('hidden');
    } else if(tab === 'formularios') {
        document.getElementById('tabFormularios').classList.add('bg-yellow-200');
        document.getElementById('tabFormularios').classList.remove('bg-gray-200');
        document.getElementById('tabContentFormularios').classList.remove('hidden');
    } else if(tab === 'relatorios') {
        document.getElementById('tabRelatorios').classList.add('bg-yellow-200');
        document.getElementById('tabRelatorios').classList.remove('bg-gray-200');
        document.getElementById('tabContentRelatorios').classList.remove('hidden');
    }
}

// Avisos: mostrar conteúdo de avisos ao clicar
document.getElementById('btnAvisos').addEventListener('click', function() {
    document.getElementById('mensagemInicial').classList.add('hidden');
    document.getElementById('salaTabs').classList.add('hidden');
    document.getElementById('avisosContent').classList.remove('hidden');
});

// Sempre que selecionar sala, esconder avisos
function selectSala(nomeSala) {
    document.getElementById('mensagemInicial').classList.add('hidden');
    document.getElementById('avisosContent').classList.add('hidden');
    document.getElementById('salaTabs').classList.remove('hidden');
    document.getElementById('salaSelecionada').textContent = nomeSala;
    showTab('materiais');
}

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
</body>
</html>