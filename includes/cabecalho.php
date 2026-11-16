<?php
// Espera $titulo e $base ("" na raiz, "../" nas subpastas).
// $vitrine = true nas páginas institucionais (largura total + profundímetro); as telas de CRUD usam o layout de trabalho.
$base = $base ?? "";
$vitrine = $vitrine ?? false;
$secaoAtual = $secaoAtual ?? "";
$flash = pegarFlash();

$menu = [
    "inicio" => ["index.php", "Início"],
    "universidade" => ["institucional.php", "A Universidade"],
    "cursos" => ["cursos/listar.php", "Cursos"],
    "alunos" => ["alunos/listar.php", "Alunos"],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($titulo ?? "Universidade Abissal") ?> · Universidade Abissal</title>
    <meta name="description" content="Universidade Abissal: uma universidade fictícia a 4.000 metros de profundidade.">
    <script>document.documentElement.classList.add("js");</script>
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=roundo@400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
    <link rel="icon" href="<?= $base ?>assets/img/logo.svg" type="image/svg+xml">
</head>
<body<?= $vitrine ? ' class="vitrine"' : "" ?>>
<header class="topo">
    <div class="marca-grupo">
        <a class="marca" href="<?= $base ?>index.php">
            <img src="<?= $base ?>assets/img/logo.svg" alt="" width="44" height="44">
            <span>Universidade Abissal</span>
        </a>
        <?php if ($vitrine): ?>
            <p class="profundimetro" title="Profundidade da sua descida pela página"><strong>0 m</strong><span>zona da luz do sol</span></p>
        <?php endif; ?>
    </div>
    <nav aria-label="Principal">
        <?php foreach ($menu as $chave => [$link, $rotulo]): ?>
            <a href="<?= $base . $link ?>" <?= $secaoAtual === $chave ? 'aria-current="page"' : "" ?>><?= $rotulo ?></a>
        <?php endforeach; ?>
        <a class="botao pequeno" href="<?= $base ?>alunos/cadastrar.php">Matricular</a>
    </nav>
</header>
<main class="conteudo<?= $vitrine ? "" : " app" ?>">
<?php if ($flash !== null): ?>
    <p class="alerta <?= h($flash["tipo"]) ?>" role="status"><?= h($flash["texto"]) ?></p>
<?php endif; ?>
