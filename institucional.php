<?php
require_once __DIR__ . "/includes/funcoes.php";

$professores = [
    ["nome" => "Profa. Dra. Octavia Ventosa", "especie" => "Polvo", "area" => "Reitora e docente de Tinta de Polvo e Artes Visuais"],
    ["nome" => "Prof. Me. Anselmo Lanterna", "especie" => "Peixe-lanterna", "area" => "Coordenador de Bioluminescência Aplicada"],
    ["nome" => "Prof. Dr. Kraken Sênior", "especie" => "Lula-gigante", "area" => "Navegação por Sonar e Cartografia Submarina"],
    ["nome" => "Profa. Ma. Jubarte Melodia", "especie" => "Baleia", "area" => "Relações Exteriores e Comunicação de Longa Distância"],
];

$titulo = "A Universidade";
include __DIR__ . "/includes/cabecalho.php";
?>
<section class="hero">
    <p class="rotulo">Institucional</p>
    <h1>Nossa história</h1>
    <p class="slogan">Fundada em 1872 por uma polva que cansou de aprender sozinha no escuro.</p>
</section>

<section>
    <p>A Universidade Abissal nasceu dentro de um navio naufragado na Fossa das Marianas. A professora Octavia Ventosa
        juntou os vizinhos da fossa, organizou aulas com os livros encharcados do navio e, em pouco mais de um século,
        transformou a sala improvisada no maior centro de pesquisa submarina do planeta.</p>
    <p>Hoje o campus ocupa quatro departamentos, um em cada faixa de profundidade, ligados por correntes marítimas
        que funcionam como um ônibus circular gratuito.</p>
</section>

<section class="secao">
    <div class="grade">
        <article class="cartao">
            <h3>Missão</h3>
            <p>Levar educação de qualidade a todas as criaturas do oceano, da superfície ao ponto mais fundo.</p>
        </article>
        <article class="cartao">
            <h3>Visão</h3>
            <p>Ser reconhecida até 2050 como a universidade mais brilhante (literalmente) dos sete mares.</p>
        </article>
        <article class="cartao">
            <h3>Valores</h3>
            <p>Curiosidade abissal, respeito a todas as espécies, sustentabilidade dos recifes e nenhum anzol no campus.</p>
        </article>
    </div>
</section>

<section class="secao">
    <h2>Estrutura e unidades</h2>
    <div class="grade">
        <article class="cartao"><span class="selo">0 a 200 m</span><h3>Superfície</h3><p>Centro de intercâmbio, farol-biblioteca e sala de diplomacia.</p></article>
        <article class="cartao"><span class="selo">200 a 1.000 m</span><h3>Recifes de Coral</h3><p>Ateliês de arte, laboratórios de arquitetura viva e o restaurante universitário.</p></article>
        <article class="cartao"><span class="selo">1.000 a 4.000 m</span><h3>Zona Abissal</h3><p>Prédio central, reitoria e os laboratórios de luz e sonar.</p></article>
        <article class="cartao"><span class="selo">até 11.000 m</span><h3>Fossas Oceânicas</h3><p>Engenharia, câmaras de pressão e o navio naufragado onde tudo começou.</p></article>
    </div>
</section>

<section class="secao">
    <h2>Corpo docente</h2>
    <div class="grade">
        <?php foreach ($professores as $professor): ?>
            <article class="cartao">
                <span class="selo"><?= h($professor["especie"]) ?></span>
                <h3><?= h($professor["nome"]) ?></h3>
                <p><?= h($professor["area"]) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="secao">
    <h2>Curiosidades</h2>
    <ul>
        <li>A formatura acontece durante a migração das baleias, que cantam o hino da universidade.</li>
        <li>O trote é proibido desde 1931, quando um calouro foi confundido com isca.</li>
        <li>Os diplomas são impressos em tinta de polvo, resistente a 400 atmosferas.</li>
    </ul>
    <p><a class="botao" href="cursos/listar.php">Ver todos os cursos</a></p>
</section>
<?php include __DIR__ . "/includes/rodape.php"; ?>
