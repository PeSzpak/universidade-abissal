<?php
require_once __DIR__ . "/includes/funcoes.php";

$professores = [
    ["Profa. Dra. Octavia Ventosa", "Polvo", "Reitora. Leciona Tinta de Polvo e Artes Visuais.", "assets/fotos/octopusvulgaris.jpg"],
    ["Prof. Me. Anselmo Isca", "Peixe-pescador", "Coordena Bioluminescência Aplicada.", "assets/fotos/ceratias-uranoscopus1.jpg"],
    ["Prof. Dr. Kraken Sênior", "Lula-gigante", "Navegação por Sonar e Cartografia Submarina.", "assets/fotos/haeckel-gamochonia-noframe.jpg"],
    ["Prof. Franjo Serpentino", "Tubarão-cobra", "Engenharia de Correntes e Relações com a Superfície.", "assets/fotos/chlamydoselachus-anguineus1.jpg"],
];

$unidades = [
    ["0 a 200 m", "Superfície", "Centro de intercâmbio, farol-biblioteca e sala de diplomacia com os navios."],
    ["200 a 1.000 m", "Recifes de Coral", "Ateliês de arte, laboratórios de arquitetura viva e o restaurante universitário."],
    ["1.000 a 4.000 m", "Zona Abissal", "Prédio central, reitoria e os laboratórios de luz e de sonar."],
    ["até 11.000 m", "Fossas Oceânicas", "Engenharia, câmaras de pressão e o navio naufragado onde tudo começou."],
];

$titulo = "A Universidade";
$vitrine = true;
$secaoAtual = "universidade";
include __DIR__ . "/includes/cabecalho.php";
?>
<section class="hero curto">
    <div class="hero-texto">
        <h1>Nossa história</h1>
        <p class="lead">Desde 1872, quando uma polva cansou de aprender sozinha no escuro.</p>
        <div class="acoes">
            <a class="botao" href="cursos/listar.php">Ver os cursos</a>
            <a class="link-seta" href="#unidades">Conhecer o campus <span aria-hidden="true">→</span></a>
        </div>
    </div>
    <div class="hero-luz">
        <img src="assets/fotos/melanocetus-johnsonii4.jpg" width="1300" height="907"
             alt="Gravura de um peixe-pescador nadando sobre o fundo do mar, com outros dois ao longe">
    </div>
</section>

<section class="secao" aria-labelledby="titulo-origem">
    <h2 id="titulo-origem">Uma sala de aula num naufrágio</h2>
    <div class="texto-corrido" data-entra>
        <p>A Universidade Abissal nasceu dentro de um navio naufragado na Fossa das Marianas. A professora Octavia
            Ventosa juntou os vizinhos da fossa, secou os livros encharcados do porão e começou a dar aulas no escuro.</p>
        <p>Pouco mais de um século depois, a sala improvisada virou o maior centro de pesquisa submarina do planeta,
            com quatro unidades ligadas por correntes marítimas que funcionam como um ônibus circular gratuito.</p>
    </div>
</section>

<section class="secao" aria-labelledby="titulo-principios">
    <h2 id="titulo-principios">O que nos guia</h2>
    <ul class="trio" data-entra>
        <li><h3>Missão</h3><p>Levar educação de qualidade a todas as criaturas do oceano, da superfície ao ponto mais fundo.</p></li>
        <li><h3>Visão</h3><p>Ser, até 2050, a universidade mais brilhante (literalmente) dos sete mares.</p></li>
        <li><h3>Valores</h3><p>Curiosidade abissal, respeito a todas as espécies, recifes preservados e nenhum anzol no campus.</p></li>
    </ul>
</section>

<section class="campo-areia" id="unidades" aria-labelledby="titulo-unidades">
    <div class="secao-titulo">
        <h2 id="titulo-unidades">Quatro unidades</h2>
        <p>Do farol ao fundo da fossa</p>
    </div>
    <ul class="profundidades" data-entra>
        <?php foreach ($unidades as [$metros, $nome, $descricao]): ?>
            <li>
                <span class="metros"><?= $metros ?></span>
                <h3><?= $nome ?></h3>
                <p><?= $descricao ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<section class="secao" aria-labelledby="titulo-docentes">
    <h2 id="titulo-docentes">Corpo docente</h2>
    <ul class="professores" data-entra>
        <?php foreach ($professores as [$nome, $especie, $area, $imagem]): ?>
            <li>
                <div class="retrato"><img src="<?= $imagem ?>" alt="Gravura de <?= h(mb_strtolower($especie)) ?>" width="300" height="300" loading="lazy"></div>
                <h3><?= h($nome) ?></h3>
                <p class="especie"><?= h($especie) ?></p>
                <p><?= h($area) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<section class="secao" aria-labelledby="titulo-curiosidades">
    <div class="texto-corrido">
        <h2 id="titulo-curiosidades">Curiosidades</h2>
        <ul class="lista-luz" data-entra>
            <li>A formatura acontece durante a migração das baleias, que cantam o hino da universidade.</li>
            <li>O trote é proibido desde 1931, quando um calouro foi confundido com isca.</li>
            <li>Os diplomas são impressos em tinta de polvo, resistente a 400 atmosferas.</li>
        </ul>
    </div>
</section>

<a class="faixa" href="cursos/listar.php">
    <span class="faixa-topo">
        <span class="faixa-frase"><strong>Seis cursos, quatro profundidades.</strong> Veja qual combina com a sua espécie.</span>
        <svg class="faixa-seta" viewBox="0 0 48 48" width="48" height="48" aria-hidden="true"><path d="M14 34 34 14M17 14h17v17" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </span>
    <span class="palavra">Cursos</span>
</a>
<?php include __DIR__ . "/includes/rodape.php"; ?>
