<footer class="bg-gray-800 text-white py-6 mt-12">
    <div class="max-w-7xl mx-auto text-center text-sm">
        &copy; <?php echo date("Y"); ?> Projeto de Extensão <a target="_blank" href="https://www.fatecaracatuba.edu.br/novo/">Fatec Araçatuba</a>. Todos os direitos reservados.
        
        <p><strong>Telefone: </strong>(18) 3625-9914</p>
        
        <?php
            $ehIndex = basename($_SERVER['SCRIPT_NAME']) === 'index.php';
            if($ehIndex == true){
                echo '<a id="admin" href="./modules/admin/pageLoginADM.php">ADMIN</a>';
            };
        ?>
    </div>
</footer>