<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
$porPagina = 6;

// Ordenação: só aceita chaves conhecidas, porque ORDER BY não pode ser parâmetro do prepared statement
$ordens = [
    "nome_asc"  => ["Nome A-Z", "a.nome ASC"],
    "nome_desc" => ["Nome Z-A", "a.nome DESC"],
    "recentes"  => ["Mais recentes", "a.criado_em DESC, a.id DESC"],
    "antigos"   => ["Mais antigos", "a.criado_em ASC, a.id ASC"],
];

// 1. Lê pesquisa, filtros, ordem e página da URL
$busca = trim($_GET["busca"] ?? "");
$cursoFiltro = filter_var($_GET["curso"] ?? "", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$especieFiltro = in_array($_GET["especie"] ?? "", ESPECIES, true) ? $_GET["especie"] : "";
$ordem = array_key_exists($_GET["ordem"] ?? "", $ordens) ? $_GET["ordem"] : "nome_asc";
$pagina = filter_var($_GET["pagina"] ?? 1, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ?: 1;

// 2. Monta o WHERE só com trechos fixos; os valores vão em parâmetros nomeados
$condicoes = [];
$parametros = [];
if ($busca !== "") {
    // LIKE com % dos dois lados: encontra o texto em qualquer parte do nome ou do e-mail
    $termo = "%" . addcslashes($busca, "%_\\") . "%";
    $condicoes[] = "(a.nome LIKE :busca_nome OR a.email LIKE :busca_email)";
    $parametros[":busca_nome"] = $termo;
    $parametros[":busca_email"] = $termo;
}
if ($cursoFiltro !== false) {
    $condicoes[] = "a.curso_id = :curso";
    $parametros[":curso"] = $cursoFiltro;
}
if ($especieFiltro !== "") {
    $condicoes[] = "a.especie = :especie";
    $parametros[":especie"] = $especieFiltro;
}
$where = count($condicoes) > 0 ? "WHERE " . implode(" AND ", $condicoes) : "";

// 3. Conta o total para calcular as páginas
$contagem = $pdo->prepare("SELECT COUNT(*) FROM alunos a $where");
foreach ($parametros as $nome => $valor) {
    $contagem->bindValue($nome, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$contagem->execute();
$total = (int) $contagem->fetchColumn();

$totalPaginas = max(1, (int) ceil($total / $porPagina));
$pagina = min($pagina, $totalPaginas);
$offset = ($pagina - 1) * $porPagina;

// 4. Busca só a página atual com LIMIT e OFFSET
$consulta = $pdo->prepare(
    "SELECT a.id, a.nome, a.email, a.especie, a.idade, a.criado_em, c.nome AS curso
     FROM alunos a JOIN cursos c ON c.id = a.curso_id
     $where
     ORDER BY {$ordens[$ordem][1]}
     LIMIT :limite OFFSET :offset"
);
foreach ($parametros as $nome => $valor) {
    $consulta->bindValue($nome, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$consulta->bindValue(":limite", $porPagina, PDO::PARAM_INT);
$consulta->bindValue(":offset", $offset, PDO::PARAM_INT);
$consulta->execute();
$alunos = $consulta->fetchAll();

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
