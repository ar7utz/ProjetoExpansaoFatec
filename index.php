<?php
    require_once('./assets/bd/conexao.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./src/output.css">

    <link rel="shortcut icon" href="./assets/icon/fatec-ico -branco.ico" type="image/x-icon">

    <title>Fatec Extensão Araçatuba</title>
</head>

<body class="min-h-screen bg-white">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <?php require_once('./modules/template/navbar.php'); ?>
    </header>

    <div id="Sobre" class="">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200" alt="Students" class="w-full h-full object-cover">
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="max-w-2xl">
                    <h1 class="text-5xl font-bold mb-6">PROJETO DE EXTENSÃO FATEC ARAÇATUBA</h1>
                    <p class="text-lg leading-relaxed">
                        Conectando conhecimento acadêmico com a prática do mercado, Projeto de extensão universitária gratuita para impulsionar seu futuro.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Bem-vindo ao Portal de Extensão da Fatec Araçatuba!</h2>
            <p class="text-gray-600 mt-2">
                Explore nossas salas temáticas e descubra recursos, projetos e atividades para expandir seus conhecimentos e habilidades.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-2xl font-semibold text-gray-800">Fatec Aberta Araçatuba</h1>
            <p class="mt-2 mb-8">
                A Fatec Aberta Araçatuba é um espaço criado para aproximar a universidade das escolas públicas e da comunidade.
                Por meio de projetos de extensão, promove-se a troca de conhecimentos em uma via de mão dupla: os estudantes compartilham
                saberes acadêmicos e, ao mesmo tempo, aprendem com a realidade social em que estão inseridos.
            </p>

            <p class="mt-2 mb-8">
                O objetivo é integrar ensino, pesquisa e extensão em ações práticas que contribuam para a formação dos alunos,
                a valorização da cidadania e o fortalecimento do vínculo entre universidade e sociedade.
            </p>

            <p class="mt-2 mb-8">
                Cada projeto está organizado em quatro grandes áreas — Empreendedorismo, Metodologia Científica,
                Sociedade, Tecnologia e Inovação, e Administração Geral —, oferecendo atividades dinâmicas e
                inovadoras que atendem às necessidades reais das escolas e da comunidade local.
            </p>

            <p class="mt-2 mb-8">
                Assim, a Fatec Aberta Araçatuba reafirma seu compromisso com a educação de qualidade e com o 
                desenvolvimento social da região, tornando-se um canal de transformação e impacto positivo.
            </p>
        </div>
    </div>


    <!-- Main Content -->
    <div id="Cursos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-12">
            SELECIONE SUA ÁREA DE INTERESSE
        </h2>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            <!-- Card Empreendedorismo -->
            <div class="bg-gradient-to-br from-green-300 to-green-400 rounded-lg p-12 text-center shadow-lg hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-6">EMPREENDEDORISMO</h3>
                <a href="./modules/public/salas/empreendedorismo.php">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded font-semibold hover:bg-blue-800 transition cursor-pointer">
                        ACESSAR SALA
                    </button>
                </a>
                
            </div>

            <!-- Card Metologia Científica -->
            <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg p-12 text-center shadow-lg hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-6">METODOLOGIA CIENTÍFICA</h3>
                <a href="./modules/public/salas/metodologiaCientifica.php">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded font-semibold hover:bg-blue-800 transition cursor-pointer">
                        ACESSAR SALA
                    </button>
                </a>
                
            </div>

            <!-- Card Sociedade, Technologia e Inovação -->
            <div class="bg-gradient-to-br from-orange-300 to-orange-400 rounded-lg p-12 text-center shadow-lg hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-6">
                    SOCIEDADE, TECNOLOGIA E INOVAÇÃO
                </h3>
                <a href="./modules/public/salas/STechI.php">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded font-semibold hover:bg-blue-800 transition cursor-pointer">
                        ACESSAR SALA
                    </button>
                </a>
                
            </div>

            <!-- Card Adminsticação Geral -->
            <div class="bg-gradient-to-br from-purple-300 to-purple-400 rounded-lg p-12 text-center shadow-lg hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-white mb-6">ADMINISTRAÇÃO GERAL</h3>
                <a href="./modules/public/salas/admGeral.php">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded font-semibold hover:bg-blue-800 transition cursor-pointer">
                        ACESSAR SALA    
                    </button>
                </a>
                
            </div>
        </div>

        <!-- Avisos Recentes //  bloco while bd -->
        <div class="bg-gray-100 rounded-lg p-8 shadow">
            <h3 class="text-2xl font-bold text-blue-900 mb-6">AVISOS RECENTES</h3>
            <ul class="space-y-3">
                    <?php
                    $sqlAvisos = "SELECT * FROM avisos ORDER BY data DESC";
                    $resultAvisos = $conn->query($sqlAvisos);
                    ?>
                    <?php if ($resultAvisos->num_rows > 0): ?>
                        <?php while ($aviso = $resultAvisos->fetch_assoc()): ?>
                            <li class="flex justify-between items-center">
                                <span>
                                    <span class="text-xs text-gray-400 ml-2">(<?php echo date('d/m/Y H:i', strtotime($aviso['data'])); ?>)</span>
                                    <?php echo htmlspecialchars($aviso['descricao']); ?>
                                </span>
                                <span>
                                    <a href="./avisos/editAviso.php?id=<?php echo $aviso['id']; ?>" class="text-blue-500 hover:underline mr-2"><i class="fa fa-edit"></i></a>
                                    <a href="./avisos/deleteAviso.php?id=<?php echo $aviso['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Tem certeza que deseja excluir este aviso?')"><i class="fa fa-trash"></i></a>
                                </span>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>Nenhum aviso encontrado.</li>
                    <?php endif; ?>
            </ul>
        </div>
    </div>

    <footer id="Footer">
        <?php require_once ('./modules/template/footer.php'); ?>
    </footer>
</body>

</html>