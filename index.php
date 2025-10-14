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
        <div class="relative bg-bg-cinza-fatec text-white overflow-hidden">
            <div class="absolute inset-0 opacity-30">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200" alt="Students" class="w-full h-full object-cover">
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="max-w-2xl">
                    <h1 class="text-5xl font-bold mb-6 text-vermLetra-fatec">PROJETO DE EXTENSÃO FATEC ARAÇATUBA</h1>
                    <p class="text-lg leading-relaxed text-cinza-fatec">
                        Conectando conhecimento acadêmico com a prática do mercado, Projeto de extensão universitária gratuita para impulsionar seu futuro.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-vermLetra-fatec">Bem-vindo ao Portal de Extensão da Fatec Araçatuba!</h2>
            <p class="text-gray-600 mt-2">
                Explore nossas salas temáticas e descubra recursos, projetos e atividades para expandir seus conhecimentos e habilidades.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-2xl font-semibold text-vermLetra-fatec">Fatec Aberta Araçatuba</h1>
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
        <h2 class="text-3xl font-bold text-center text-vermLetra-fatec mb-12">
            SELECIONE SUA ÁREA DE INTERESSE
        </h2>

        <!-- Colocar a lógica dinâmica das salas -->
        <?php
        // ...código anterior...
        $sqlSalas = "SELECT * FROM sala";
        $resultSalas = $conn->query($sqlSalas);
        ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            <?php while ($sala = $resultSalas->fetch_assoc()): ?>
                <div class="bg-vermelho-fatec rounded-lg p-12 text-center shadow-lg hover:shadow-xl transition flex flex-col md:flex-row items-center">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-6"><?php echo htmlspecialchars($sala['nome']); ?></h3>
                        <a href="./modules/public/salas/<?php echo strtolower(str_replace([' ', 'ç', 'ã', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'á', 'õ'], ['','c','a','e','i','o','u','a','e','o','a','o'], $sala['nome'])); ?>.php">
                            <button class="bg-cinza-fatec text-white px-6 py-2 rounded font-semibold hover:bg-blue-800 transition cursor-pointer">
                                ACESSAR SALA
                            </button>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Avisos Recentes //  bloco while bd -->
        <div class="bg-gray-100 rounded-lg p-8 shadow">
            <h3 class="text-2xl font-bold text-vermLetra-fatec mb-6">AVISOS RECENTES</h3>
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