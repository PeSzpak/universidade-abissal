<?php
require_once __DIR__ . "/includes/funcoes.php";

$pdo = conectar();
$cursos = $pdo->query("SELECT c.nome, c.departamento, c.descricao, COUNT(a.id) AS total_alunos
                       FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
                       GROUP BY c.id ORDER BY total_alunos DESC, c.nome LIMIT 3")->fetchAll();
$totais = $pdo->query("SELECT (SELECT COUNT(*) FROM cursos) AS cursos, (SELECT COUNT(*) FROM alunos) AS alunos")->fetch();

$titulo = "Início";
include __DIR__ . "/includes/cabecalho.php";
?>
<section class="hero">
    <p class="rotulo">Tema: universidade no fundo do oceano</p>
    <h1>Universidade <span class="brilho">Abissal</span></h1>
    <p class="slogan">Onde o conhecimento brilha mesmo a 4.000 metros de profundidade.</p>
    <div class="acoes">
        <a class="botao" href="alunos/listar.php">Ver alunos (<?= (int) $totais["alunos"] ?>)</a>
        <a class="botao secundario" href="cursos/listar.php">Conhecer os <?= (int) $totais["cursos"] ?> cursos</a>
        <a class="botao secundario" href="institucional.php">Nossa história</a>
    </div>
</section>

<section class="secao">
    <h2>Principais cursos</h2>
    <div class="grade">
        <?php foreach ($cursos as $curso): ?>
            <article class="cartao">
                <span class="selo"><?= h($curso["departamento"]) ?></span>
                <h3><?= h($curso["nome"]) ?></h3>
                <p><?= h($curso["descricao"]) ?></p>
                <p class="pequeno"><?= (int) $curso["total_alunos"] ?> alunos matriculados</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="secao">
    <h2>Diferenciais</h2>
    <div class="grade">
        <article class="cartao">
            <h3>Campus iluminado por peixes-lanterna</h3>
            <p>Zero conta de luz: as salas são iluminadas por bolsistas de Bioluminescência Aplicada.</p>
        </article>
        <article class="cartao">
            <h3>Aulas para oito tentáculos</h3>
            <p>Carteiras adaptadas para polvos, lulas e quem mais precisar escrever com vários braços ao mesmo tempo.</p>
        </article>
        <article class="cartao">
            <h3>Intercâmbio com a superfície</h3>
            <p>Convênio com navios de pesquisa e um farol inteiro para quem quer estudar lá em cima.</p>
        </article>
        <article class="cartao">
            <h3>Pressão só de 400 atmosferas</h3>
            <p>A única universidade onde a pressão da prova é literalmente a menor das preocupações.</p>
        </article>
    </div>
</section>
<?php include __DIR__ . "/includes/rodape.php"; ?>
