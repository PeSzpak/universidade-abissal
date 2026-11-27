<?php
// A marca fecha a página como um perfil do leito do mar: cada letra num bloco de profundidade diferente.
$letras = ["A" => 64, "B" => 82, "I" => 100, "S" => 88, "S " => 70, "A " => 94, "L" => 58];
?>
</main>
<footer class="rodape<?= $vitrine ? "" : " compacto" ?>">
    <?php if ($vitrine): ?>
        <div class="marca-grande" aria-hidden="true">
            <?php foreach ($letras as $letra => $altura): ?>
                <span style="--h: <?= $altura ?>%"><?= trim($letra) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="rodape-colunas">
        <div>
            <h2>Universidade</h2>
            <ul>
                <li><a href="<?= $base ?>index.php">Início</a></li>
                <li><a href="<?= $base ?>institucional.php">Nossa história</a></li>
            </ul>
        </div>
        <div>
            <h2>Secretaria</h2>
            <ul>
                <li><a href="<?= $base ?>cursos/listar.php">Cursos</a></li>
                <li><a href="<?= $base ?>alunos/listar.php">Alunos</a></li>
                <li><a href="<?= $base ?>alunos/cadastrar.php">Matricular aluno</a></li>
                <li><a href="<?= $base ?>dashboard.php">Dashboard</a></li>
                <li><a href="<?= $base ?>api/docs.html">API (Swagger)</a></li>
            </ul>
        </div>
        <div>
            <h2>Campus</h2>
            <p>Fossa das Marianas, 4.000 m abaixo do nível do mar. Aberto de dia e de noite, que aqui é a mesma coisa.</p>
        </div>
        <p class="creditos">
            Gravuras do relatório da expedição H.M.S. Challenger (Robert Mintern, 1887; A. L. Clément), pranchas de
            Ernst Haeckel, <i>Kunstformen der Natur</i> (1904), e vídeo da NOAA Ocean Exploration, Fossa das Marianas (2016).
            Todas em domínio público, via Wikimedia Commons.
            Projeto final de Tecnologias para Internet, Senac São José dos Pinhais.
        </p>
    </div>
</footer>
<script src="<?= $base ?>assets/js/movimento.js" defer></script>
</body>
</html>
