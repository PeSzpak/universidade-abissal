<?php
require_once __DIR__ . "/includes/funcoes.php";

$pdo = conectar();
$cursos = $pdo->query("SELECT c.nome, c.departamento, c.descricao, c.duracao_semestres, c.vagas, COUNT(a.id) AS total_alunos
                       FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
                       GROUP BY c.id ORDER BY total_alunos DESC, c.nome LIMIT 6")->fetchAll();
$totais = $pdo->query("SELECT (SELECT COUNT(*) FROM cursos) AS cursos,
                              (SELECT COUNT(*) FROM alunos) AS alunos,
                              (SELECT COUNT(DISTINCT especie) FROM alunos) AS especies,
                              (SELECT MIN(idade) FROM alunos) AS mais_novo,
                              (SELECT MAX(idade) FROM alunos) AS mais_velho")->fetch();

// Uma gravura por curso, como marcador redondo da lista
$gravuras = [
    "assets/fotos/melanocetus-murrayi-murrays-abyssal-anglerfish.jpg",
    "assets/fotos/chlamydoselachus-anguineus1.jpg",
    "assets/fotos/haeckel-actiniae.jpg",
    "assets/fotos/octopusvulgaris.jpg",
    "assets/fotos/ceratias-uranoscopus1.jpg",
    "assets/fotos/haeckel-discomedusae-8.jpg",
];

$galeria = [
    ["assets/fotos/octopusvulgaris.jpg", "Polvo, aluno de Tinta de Polvo e Artes Visuais"],
    ["assets/fotos/haeckel-discomedusae-8.jpg", "Água-viva, aluna de Bioluminescência Aplicada"],
    ["assets/fotos/haeckel-gamochonia-noframe.jpg", "Lula-gigante, aluna de Navegação por Sonar"],
    ["assets/fotos/melanocetus-johnsonii4.jpg", "Peixe-pescador, monitor do laboratório de luz"],
    ["assets/fotos/haeckel-actiniae.jpg", "Anêmonas, alunas de Arquitetura de Recifes"],
];

$titulo = "Início";
$vitrine = true;
$secaoAtual = "inicio";
include __DIR__ . "/includes/cabecalho.php";
?>
<section class="hero">
    <div class="hero-texto">
        <h1>Universidade Abissal</h1>
        <p class="lead">O conhecimento brilha até no escuro: <?= (int) $totais["cursos"] ?> cursos a 4.000 metros de profundidade, para polvos, lulas e peixes-lanterna.</p>
        <div class="acoes">
            <a class="botao" href="alunos/cadastrar.php">Fazer matrícula</a>
            <a class="link-seta" href="#cursos">Ver os cursos <span aria-hidden="true">→</span></a>
        </div>
    </div>
    <div class="hero-luz">
        <img src="assets/fotos/melanocetus-murrayi-murrays-abyssal-anglerfish.jpg" width="1483" height="887"
             alt="Gravura do peixe-pescador Melanocetus murrayi, com a isca luminosa sobre a cabeça">
    </div>
</section>

<section class="secao declaracao" aria-labelledby="desde-1872">
    <h2 id="desde-1872">Desde 1872</h2>
    <p>Fundada num navio afundado, a Abissal ensina polvos
        <span class="mini"><img src="assets/fotos/octopusvulgaris.jpg" alt="" width="60" height="60" loading="lazy"></span>,
        lulas <span class="mini"><img src="assets/fotos/haeckel-gamochonia-noframe.jpg" alt="" width="60" height="60" loading="lazy"></span>
        e águas-vivas <span class="mini"><img src="assets/fotos/haeckel-discomedusae-8.jpg" alt="" width="60" height="60" loading="lazy"></span>
        a estudar onde o sol não chega.</p>
</section>

<section class="campo-areia" id="cursos" aria-labelledby="titulo-cursos">
    <div class="secao-titulo">
        <h2 id="titulo-cursos">Principais cursos</h2>
        <p><?= (int) $totais["cursos"] ?> cursos, 4 departamentos</p>
    </div>
    <ul class="cardapio">
        <?php foreach ($cursos as $i => $curso): ?>
            <li>
                <span class="bola"><img src="<?= $gravuras[$i % count($gravuras)] ?>" alt="" width="76" height="76" loading="lazy"></span>
                <div>
                    <div class="linha-nome">
                        <h3><?= h($curso["nome"]) ?></h3>
                        <span><?= (int) $curso["duracao_semestres"] ?> sem.</span>
                    </div>
                    <p><?= h($curso["descricao"]) ?></p>
                    <p class="nota"><?= h($curso["departamento"]) ?> · <?= (int) $curso["vagas"] ?> vagas · <?= (int) $curso["total_alunos"] ?> matriculados</p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="acoes">
        <a class="botao" href="cursos/listar.php">Todos os cursos</a>
    </div>
</section>

<section class="secao" aria-labelledby="titulo-diferenciais">
    <div class="painel">
        <div data-entra>
            <h2 id="titulo-diferenciais">Aprender onde a luz não chega</h2>
            <div class="painel-video">
                <video autoplay muted loop playsinline preload="metadata"
                       poster="assets/fotos/agua-viva-marianas.jpg"
                       aria-label="Água-viva nadando na Fossa das Marianas, filmada pela NOAA em 2016">
                    <source src="assets/fotos/agua-viva-marianas.mp4" type="video/mp4">
                </video>
            </div>
        </div>
        <div>
            <div class="diferenciais" data-entra>
                <div>
                    <h3>Campus iluminado por alunos</h3>
                    <p>Zero conta de luz: as salas são acesas pelos bolsistas de Bioluminescência Aplicada.</p>
                </div>
                <div>
                    <h3>Carteiras para oito braços</h3>
                    <p>Polvos e lulas escrevem com quantos tentáculos quiserem, inclusive na prova.</p>
                </div>
                <div>
                    <h3>Intercâmbio com a superfície</h3>
                    <p>Convênio com navios de pesquisa e um farol inteiro para quem quer estudar lá em cima.</p>
                </div>
                <div>
                    <h3>Pressão de 400 atmosferas</h3>
                    <p>A única universidade onde a pressão da prova é, de longe, a menor das preocupações.</p>
                </div>
            </div>
            <div class="acoes" style="margin-top: 48px">
                <a class="botao" href="institucional.php">Nossa história</a>
            </div>
        </div>
    </div>
</section>

<section class="secao galeria" aria-labelledby="titulo-alunos">
    <div class="galeria-texto" data-entra>
        <h2 id="titulo-alunos">Quem estuda aqui</h2>
        <p class="numero"><?= (int) $totais["alunos"] ?></p>
        <p>alunos de <?= (int) $totais["especies"] ?> espécies diferentes, com idades de <?= (int) $totais["mais_novo"] ?> a <?= (int) $totais["mais_velho"] ?> anos.</p>
        <a class="link-seta" href="alunos/listar.php">Ver todos os alunos <span aria-hidden="true">→</span></a>
    </div>
    <div class="moldura" role="button" tabindex="0" aria-label="Mostrar o próximo aluno">
        <?php foreach ($galeria as $i => [$imagem, $legenda]): ?>
            <figure class="<?= $i === 0 ? "ativa" : "" ?>">
                <div class="disco"><img src="<?= $imagem ?>" alt="<?= h($legenda) ?>" width="560" height="560" loading="lazy"></div>
                <figcaption><?= h($legenda) ?></figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>

<a class="faixa" href="alunos/cadastrar.php">
    <span class="faixa-topo">
        <span class="faixa-frase"><strong>Escolha um curso e faça sua matrícula.</strong> Leva dois minutos e não precisa saber nadar.</span>
        <svg class="faixa-seta" viewBox="0 0 48 48" width="48" height="48" aria-hidden="true"><path d="M14 34 34 14M17 14h17v17" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </span>
    <span class="palavra">Mergulhe</span>
</a>
<?php include __DIR__ . "/includes/rodape.php"; ?>
