<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();

// Pesquisa, filtros, ordenação e paginação ficam em consultarAlunos() (includes/funcoes.php),
// a mesma função usada pela API em api/index.php.
[
    "alunos" => $alunos,
    "total" => $total,
    "pagina" => $pagina,
    "totalPaginas" => $totalPaginas,
    "busca" => $busca,
    "curso" => $cursoFiltro,
    "especie" => $especieFiltro,
    "ordem" => $ordem,
] = consultarAlunos($pdo, $_GET, 6);
$ordens = ORDENS_ALUNOS;

$cursos = $pdo->query("SELECT id, nome FROM cursos ORDER BY nome")->fetchAll();

$titulo = "Alunos";
$base = "../";
$secaoAtual = "alunos";
include __DIR__ . "/../includes/cabecalho.php";
?>
<div class="cabecalho-pagina">
    <h1>Alunos</h1>
    <a class="botao" href="cadastrar.php">+ Matricular aluno</a>
</div>

<form class="filtros" method="GET">
    <div class="campo-busca">
        <label for="busca">Pesquisar por nome ou e-mail</label>
        <input type="search" id="busca" name="busca" placeholder="Ex.: polv, lanterna, @abissal" value="<?= h($busca) ?>">
    </div>
    <div>
        <label for="curso">Curso</label>
        <select id="curso" name="curso">
            <option value="">Todos</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= (int) $curso["id"] ?>" <?= $cursoFiltro === (int) $curso["id"] ? "selected" : "" ?>><?= h($curso["nome"]) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="especie">Espécie</label>
        <select id="especie" name="especie">
            <option value="">Todas</option>
            <?php foreach (ESPECIES as $especie): ?>
                <option value="<?= h($especie) ?>" <?= $especieFiltro === $especie ? "selected" : "" ?>><?= h($especie) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="ordem">Ordenar por</label>
        <select id="ordem" name="ordem">
            <?php foreach ($ordens as $chave => [$rotulo]): ?>
                <option value="<?= $chave ?>" <?= $ordem === $chave ? "selected" : "" ?>><?= $rotulo ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="acoes">
        <button class="botao" type="submit">Filtrar</button>
        <a class="botao secundario" href="listar.php">Limpar</a>
    </div>
</form>

<p class="resumo-lista"><?= $total ?> aluno(s) encontrado(s) · página <?= $pagina ?> de <?= $totalPaginas ?></p>

<div class="tabela-envolve">
    <table>
        <thead>
            <tr><th>Nome</th><th>E-mail</th><th>Espécie</th><th class="num">Idade</th><th>Curso</th><th>Matrícula</th><th>Ações</th></tr>
        </thead>
        <tbody>
        <?php foreach ($alunos as $aluno): ?>
            <tr>
                <td><?= h($aluno["nome"]) ?></td>
                <td><?= h($aluno["email"]) ?></td>
                <td><?= h($aluno["especie"]) ?></td>
                <td class="num"><?= (int) $aluno["idade"] ?></td>
                <td><?= h($aluno["curso"]) ?></td>
                <td><?= date("d/m/Y", strtotime($aluno["criado_em"])) ?></td>
                <td>
                    <div class="acoes">
                        <a class="botao pequeno secundario" href="editar.php?id=<?= (int) $aluno["id"] ?>">Editar</a>
                        <a class="botao pequeno perigo" href="excluir.php?id=<?= (int) $aluno["id"] ?>">Excluir</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($alunos) === 0): ?>
            <tr><td colspan="7" class="vazio">Nenhum aluno encontrado com esses filtros.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPaginas > 1): ?>
    <nav class="paginacao" aria-label="Paginação">
        <?php if ($pagina > 1): ?>
            <a href="<?= h(urlCom(["pagina" => $pagina - 1])) ?>">‹ Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <?php if ($i === $pagina): ?>
                <span class="atual" aria-current="page"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= h(urlCom(["pagina" => $i])) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($pagina < $totalPaginas): ?>
            <a href="<?= h(urlCom(["pagina" => $pagina + 1])) ?>">Próxima ›</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
