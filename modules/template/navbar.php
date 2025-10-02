<link rel="stylesheet" href="../../src/output.css">
<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        
        <div class="flex items-center space-x-2">
            <?php $ehIndex = basename($_SERVER['SCRIPT_NAME']) === 'index.php';?>
            <a href="<?php echo $ehIndex ? 'javascript:location.reload();' : '../../../index.php'; ?>">
                <img src="../../assets/imgs/fatec_ra_aracatuba_aracatuba_cor.png" alt="Logo Fatec" class="w-16 h-10"> 
            </a>
            <span class="font-bold text-xl text-gray-800 tracking-tight whitespace-nowrap">
                Projeto de Extensão - Fatec
            </span>
        </div>
        
        <div class="hidden md:flex space-x-8 text-sm font-medium h-full items-center">
            <?php
            $ehIndex = basename($_SERVER['SCRIPT_NAME']) === 'index.php';
            ?>
            <a 
                href="<?php echo $ehIndex ? 'javascript:location.reload();' : '../../../index.php'; ?>" 
                class="text-gray-600 hover:text-blue-900 transition hover:underline decoration-4 hover:decoration-vermelho-fatec hover:transition"
            >
                Início
            </a>
            <a href="#Sobre" class="text-gray-600 hover:text-blue-900 transition hover:underline decoration-4 hover:decoration-vermelho-fatec hover:transition">Sobre</a>
            <a href="#Cursos" class="text-gray-600 hover:text-blue-900 transition hover:underline decoration-4 hover:decoration-vermelho-fatec hover:transition">Cursos</a>
            <a href="#Footer" class="text-gray-600 hover:text-blue-900 transition hover:underline decoration-4 hover:decoration-vermelho-fatec hover:transition">Contato</a>
        </div>
    </div>
</nav>