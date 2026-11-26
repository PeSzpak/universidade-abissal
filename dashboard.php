<?php
require_once __DIR__ . "/includes/funcoes.php";

// Mesmos números do endpoint GET api/index.php/dashboard
$d = indicadoresDashboard(conectar());
$t = $d["totais"];

$maxEspecie = max(array_merge([1], array_column($d["por_especie"], "alunos")));
$maxDepartamento = max(array_merge([1], array_column($d["por_departamento"], "alunos")));
$maxMes = max(array_merge([1], array_column($d["matriculas_por_mes"], "matriculas")));
$mesesNome = ["01" => "jan", "02" => "fev", "03" => "mar", "04" => "abr", "05" => "mai", "06" => "jun",
    "07" => "jul", "08" => "ago", "09" => "set", "10" => "out", "11" => "nov", "12" => "dez"];
$numero = fn($n, $casas = 0) => number_format($n, $casas, ",", ".");

$titulo = "Dashboard";
$secaoAtual = "dashboard";
include __DIR__ . "/includes/cabecalho.php";
?>
<div class="cabecalho-pagina">
    <div>
        <h1>Dashboard</h1>
        <p class="resumo-lista">Indicadores calculados agora, direto do banco. Os mesmos dados estão na <a href="api/docs.html">API (Swagger)</a>.</p>
    </div>
    <a class="botao secundario" href="api/index.php/dashboard">Ver JSON</a>
</div>

<section class="kpis" aria-label="Indicadores gerais">
    <div class="kpi"><span>Alunos matriculados</span><strong><?= $numero($t["alunos"]) ?></strong></div>
    <div class="kpi"><span>Cursos</span><strong><?= $numero($t["cursos"]) ?></strong></div>
    <div class="kpi"><span>Ocupação das vagas</span><strong><?= $numero($t["ocupacao_percentual"], 1) ?>%</strong><small><?= $numero($t["alunos"]) ?> de <?= $numero($t["vagas"]) ?> vagas</small></div>
    <div class="kpi"><span>Idade média</span><strong><?= $numero($t["idade_media"], 1) ?></strong><small>anos</small></div>
    <div class="kpi"><span>Espécies diferentes</span><strong><?= $numero($t["especies"]) ?></strong></div>
</section>

<div class="painel-grade">
    <section class="bloco">
        <h2>Ocupação por curso</h2>
        <ul class="barras">
            <?php foreach ($d["por_curso"] as $c): ?>
                <li>
                    <div class="barra-rotulo"><a href="alunos/listar.php?curso=<?= $c["id"] ?>"><?= h($c["nome"]) ?></a><span><?= $c["alunos"] ?> / <?= $c["vagas"] ?> · <?= $numero($c["ocupacao_percentual"], 1) ?>%</span></div>
                    <div class="barra" role="img" aria-label="<?= $numero($c["ocupacao_percentual"], 1) ?>% das vagas ocupadas"><i style="width: <?= min(100, $c["ocupacao_percentual"]) ?>%"></i></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="bloco">
        <h2>Alunos por espécie</h2>
        <ul class="barras">
            <?php foreach ($d["por_especie"] as $e): ?>
                <li>
                    <div class="barra-rotulo"><a href="alunos/listar.php?especie=<?= urlencode($e["especie"]) ?>"><?= h($e["especie"]) ?></a><span><?= $e["alunos"] ?></span></div>
                    <div class="barra" role="img" aria-label="<?= $e["alunos"] ?> alunos"><i style="width: <?= round($e["alunos"] / $maxEspecie * 100, 1) ?>%"></i></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="bloco">
        <h2>Departamentos</h2>
        <ul class="barras">
            <?php foreach ($d["por_departamento"] as $dep): ?>
                <li>
                    <div class="barra-rotulo"><span class="forte"><?= h($dep["departamento"]) ?></span><span><?= $dep["alunos"] ?> alunos · <?= $dep["cursos"] ?> curso(s)</span></div>
                    <div class="barra" role="img" aria-label="<?= $dep["alunos"] ?> alunos"><i style="width: <?= round($dep["alunos"] / $maxDepartamento * 100, 1) ?>%"></i></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="bloco">
        <h2>Matrículas por mês</h2>
        <?php if (count($d["matriculas_por_mes"]) === 0): ?>
            <p class="vazio">Nenhuma matrícula ainda.</p>
        <?php else: ?>
            <div class="colunas">
                <?php foreach ($d["matriculas_por_mes"] as $m): [$ano, $mes] = explode("-", $m["mes"]); ?>
                    <div class="coluna">
                        <strong><?= $m["matriculas"] ?></strong>
                        <div class="coluna-trilho" role="img" aria-label="<?= $m["matriculas"] ?> matrículas"><i style="height: <?= round($m["matriculas"] / $maxMes * 100, 1) ?>%"></i></div>
                        <span><?= $mesesNome[$mes] ?>/<?= substr($ano, 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . "/includes/rodape.php"; ?>
