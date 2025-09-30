<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../src/output.css">
    <link rel="shortcut icon" href="../../assets/icon/fatec-logo-nobackground.ico" type="image/x-icon">
    <title>Administração Geral</title>

</head>
<body class="bg-gray-50 font-sans">

    <header class="bg-white shadow-sm">
        <?php require_once ('../../template/navbar.php'); ?>
    </header>
        
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-6">

        <h1 class="text-4xl font-extrabold text-gray-800 mb-6">ADMINISTRAÇÃO GERAL</h1>
        <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mb-12">
            Bem-vindo à sala de Empreendedorismo! Aqui você encontrará recursos, projetos e
            atividades para desenvolver suas habilidades inovadoras e transformar ideias em realidade.
            Explore materiais e participe!
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mb-8 border-b-2 pb-2">PROJETOS E ATIVIDADES</h2>

        <div class="space-y-8">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Ideação e Modelagem de Negócios</h3>
                <p class="text-gray-700 mb-4">
                    Aprenda a gerar e validar suas primeiras ideias, transformando-as em modelos de negócio sólidos.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="material-ideacao.pdf" class="btn-green-light text-gray-900 font-semibold py-2 px-4 rounded-md text-sm hover:opacity-90 transition shadow-sm">
                        BAIXAR MATERIAL (PDF)
                    </a>
                    <a href="formulario-ideacao.php" class="bg-gray-800 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-gray-700 transition shadow-sm">
                        ACESSAR FORMULÁRIO
                    </a>
                    <a href="formulario-feedback.php" class="bg-gray-800 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-gray-700 transition shadow-sm">
                        ACESSAR FORMULÁRIO
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pitch Deck Vencedor</h3>
                <p class="text-gray-700 mb-4">
                    Crie apresentações impactantes para investidores e aprenda a comunicar sua ideia de forma persuasiva.
                </p>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pitch Deck Vencedor</h3> 
                <a href="registro-presenca-pitch.php" class="btn-blue-dark text-white font-bold py-3 px-6 rounded-md text-base hover:opacity-90 transition shadow-md w-full sm:w-auto">
                    REGISTRAR PRESENÇA / VALIDAR PARTICIPAÇÃO
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Inovação e Modelagem de Negócios</h3>
                <p class="text-gray-700 mb-4">
                    Aprofunde-se em métodos de inovação e técnicas avançadas de modelagem para novos empreendimentos.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="material-inovacao.pdf" class="btn-green-light text-gray-900 font-semibold py-2 px-4 rounded-md text-sm hover:opacity-90 transition shadow-sm">
                        BAIXAR MATERIAL (PDF)
                    </a>
                    <a href="formulario-inovacao.php" class="bg-gray-800 text-white font-semibold py-2 px-4 rounded-md text-sm hover:bg-gray-700 transition shadow-sm">
                        ACESSAR FORMULÁRIO
                    </a>
                </div>
            </div>

        </div>

    </main>

    <footer>
        <?php require_once ('../../template/footer.php'); ?>
    </footer>

</body>
</html>