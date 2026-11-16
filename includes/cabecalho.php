<?php
// Espera $titulo (texto da aba) e $base ("" na raiz, "../" nas subpastas).
$base = $base ?? "";
$flash = pegarFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($titulo ?? "Universidade Abissal") ?> · Universidade Abissal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
    <link rel="icon" href="<?= $base ?>assets/img/logo.svg" type="image/svg+xml">
</head>
<body>
<div class="bolhas" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>
<header class="topo">
    <a class="marca" href="<?= $base ?>index.php">
        <img src="<?= $base ?>assets/img/logo.svg" alt="" width="40" height="40">
        <span>Universidade <strong>Abissal</strong></span>
    </a>
    <nav>
        <a href="<?= $base ?>index.php">Início</a>
        <a href="<?= $base ?>institucional.php">A Universidade</a>
        <a href="<?= $base ?>cursos/listar.php">Cursos</a>
        <a href="<?= $base ?>alunos/listar.php">Alunos</a>
    </nav>
</header>
<main class="conteudo">
<?php if ($flash !== null): ?>
    <p class="alerta <?= h($flash["tipo"]) ?>" role="status"><?= h($flash["texto"]) ?></p>
<?php endif; ?>
