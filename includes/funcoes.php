<?php
// Carregado no topo de toda página: abre a sessão, a conexão e as funções compartilhadas.
require_once __DIR__ . "/../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DEPARTAMENTOS = ["Zona Abissal", "Fossas Oceânicas", "Recifes de Coral", "Superfície"];
const ESPECIES = ["Polvo", "Lula-gigante", "Peixe-lanterna", "Tubarão", "Água-viva", "Cavalo-marinho", "Baleia", "Caranguejo"];

// Ordenações aceitas na listagem de alunos: rótulo da tela e trecho SQL fixo.
// ORDER BY não pode ser parâmetro do prepared statement, por isso só chaves conhecidas entram.
const ORDENS_ALUNOS = [
    "nome_asc"  => ["Nome A-Z", "a.nome ASC"],
    "nome_desc" => ["Nome Z-A", "a.nome DESC"],
    "recentes"  => ["Mais recentes", "a.criado_em DESC, a.id DESC"],
    "antigos"   => ["Mais antigos", "a.criado_em ASC, a.id ASC"],
];

// Escapa qualquer valor antes de ir para o HTML (proteção contra XSS).
function h($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, "UTF-8");
}

// Mensagem que sobrevive a um redirecionamento e aparece uma única vez.
function definirFlash(string $tipo, string $texto): void
{
    $_SESSION["flash"] = ["tipo" => $tipo, "texto" => $texto];
}

function pegarFlash(): ?array
{
    $flash = $_SESSION["flash"] ?? null;
    unset($_SESSION["flash"]);
    return $flash;
}

function redirecionar(string $url): void
{
    header("Location: $url");
    exit;
}

// Lê um id inteiro positivo da URL ou do formulário; devolve null se inválido.
function lerId(array $origem): ?int
{
    $id = filter_var($origem["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    return $id === false ? null : $id;
}

// Monta a URL atual trocando só alguns parâmetros: preserva busca, filtros e ordem na paginação.
function urlCom(array $mudancas): string
{
    return "?" . http_build_query(array_merge($_GET, $mudancas));
}

function validarCurso(array $curso): array
{
    $erros = [];

    if ($curso["nome"] === "") {
        $erros[] = "O nome do curso é obrigatório.";
    } elseif (mb_strlen($curso["nome"]) > 100) {
        $erros[] = "O nome do curso deve ter no máximo 100 caracteres.";
    }
    if (!in_array($curso["departamento"], DEPARTAMENTOS, true)) {
        $erros[] = "Selecione um departamento válido.";
    }
    if (filter_var($curso["duracao_semestres"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 12]]) === false) {
        $erros[] = "A duração deve ser um número inteiro entre 1 e 12 semestres.";
    }
    if (filter_var($curso["vagas"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) === false) {
        $erros[] = "O número de vagas deve ser um inteiro maior que zero.";
    }

    return $erros;
}

function validarAluno(array $aluno, PDO $pdo): array
{
    $erros = [];

    if ($aluno["nome"] === "") {
        $erros[] = "O nome do aluno é obrigatório.";
    } elseif (mb_strlen($aluno["nome"]) > 100) {
        $erros[] = "O nome deve ter no máximo 100 caracteres.";
    }
    if (filter_var($aluno["email"], FILTER_VALIDATE_EMAIL) === false) {
        $erros[] = "Informe um e-mail válido.";
    }
    if (!in_array($aluno["especie"], ESPECIES, true)) {
        $erros[] = "Selecione uma espécie válida.";
    }
    if (filter_var($aluno["idade"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 500]]) === false) {
        $erros[] = "A idade deve ser um número inteiro entre 1 e 500.";
    }

    $cursoId = filter_var($aluno["curso_id"], FILTER_VALIDATE_INT);
    $existe = false;
    if ($cursoId !== false) {
        $comando = $pdo->prepare("SELECT COUNT(*) FROM cursos WHERE id = :id");
        $comando->bindValue(":id", $cursoId, PDO::PARAM_INT);
        $comando->execute();
        $existe = $comando->fetchColumn() > 0;
    }
    if (!$existe) {
        $erros[] = "Selecione um curso existente.";
    }

    return $erros;
}

// Código de erro do MySQL para valor duplicado em coluna UNIQUE.
function ehDuplicado(PDOException $e): bool
{
    return ($e->errorInfo[1] ?? null) === 1062;
}

/**
 * Listagem de alunos com pesquisa (LIKE), filtros, ordenação e paginação (LIMIT/OFFSET).
 * $params vem de $_GET na página e da query string na API: tudo é validado aqui.
 */
function consultarAlunos(PDO $pdo, array $params, int $porPagina): array
{
    $busca = trim((string) ($params["busca"] ?? ""));
    $curso = filter_var($params["curso"] ?? "", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $especie = in_array($params["especie"] ?? "", ESPECIES, true) ? $params["especie"] : "";
    $ordem = array_key_exists($params["ordem"] ?? "", ORDENS_ALUNOS) ? $params["ordem"] : "nome_asc";
    $pagina = filter_var($params["pagina"] ?? 1, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ?: 1;

    // O WHERE só tem trechos fixos; os valores vão em parâmetros nomeados
    $condicoes = [];
    $valores = [];
    if ($busca !== "") {
        // LIKE com % dos dois lados: encontra o texto em qualquer parte do nome ou do e-mail
        $termo = "%" . addcslashes($busca, "%_\\") . "%";
        $condicoes[] = "(a.nome LIKE :busca_nome OR a.email LIKE :busca_email)";
        $valores[":busca_nome"] = $termo;
        $valores[":busca_email"] = $termo;
    }
    if ($curso !== false) {
        $condicoes[] = "a.curso_id = :curso";
        $valores[":curso"] = $curso;
    }
    if ($especie !== "") {
        $condicoes[] = "a.especie = :especie";
        $valores[":especie"] = $especie;
    }
    $where = count($condicoes) > 0 ? "WHERE " . implode(" AND ", $condicoes) : "";

    $contagem = $pdo->prepare("SELECT COUNT(*) FROM alunos a $where");
    foreach ($valores as $nome => $valor) {
        $contagem->bindValue($nome, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $contagem->execute();
    $total = (int) $contagem->fetchColumn();

    $totalPaginas = max(1, (int) ceil($total / $porPagina));
    $pagina = min($pagina, $totalPaginas);

    $consulta = $pdo->prepare(
        "SELECT a.id, a.nome, a.email, a.especie, a.idade, a.curso_id, a.criado_em, c.nome AS curso
         FROM alunos a JOIN cursos c ON c.id = a.curso_id
         $where
         ORDER BY " . ORDENS_ALUNOS[$ordem][1] . "
         LIMIT :limite OFFSET :offset"
    );
    foreach ($valores as $nome => $valor) {
        $consulta->bindValue($nome, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $consulta->bindValue(":limite", $porPagina, PDO::PARAM_INT);
    $consulta->bindValue(":offset", ($pagina - 1) * $porPagina, PDO::PARAM_INT);
    $consulta->execute();

    return [
        "alunos" => $consulta->fetchAll(),
        "total" => $total,
        "pagina" => $pagina,
        "totalPaginas" => $totalPaginas,
        "busca" => $busca,
        "curso" => $curso,
        "especie" => $especie,
        "ordem" => $ordem,
    ];
}

/**
 * Indicadores do painel. Usada pela página dashboard.php e pelo endpoint GET /api/dashboard,
 * para que a tela e a API mostrem sempre os mesmos números.
 */
function indicadoresDashboard(PDO $pdo): array
{
    $t = $pdo->query(
        "SELECT (SELECT COUNT(*) FROM alunos) AS alunos,
                (SELECT COUNT(*) FROM cursos) AS cursos,
                (SELECT COALESCE(SUM(vagas), 0) FROM cursos) AS vagas,
                (SELECT COALESCE(ROUND(AVG(idade), 1), 0) FROM alunos) AS idade_media,
                (SELECT COUNT(DISTINCT especie) FROM alunos) AS especies"
    )->fetch();

    $porCurso = [];
    foreach ($pdo->query(
        "SELECT c.id, c.nome, c.departamento, c.vagas, COUNT(a.id) AS alunos
         FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
         GROUP BY c.id, c.nome, c.departamento, c.vagas
         ORDER BY alunos DESC, c.nome"
    ) as $linha) {
        $porCurso[] = [
            "id" => (int) $linha["id"],
            "nome" => $linha["nome"],
            "departamento" => $linha["departamento"],
            "vagas" => (int) $linha["vagas"],
            "alunos" => (int) $linha["alunos"],
            "ocupacao_percentual" => $linha["vagas"] > 0 ? round($linha["alunos"] / $linha["vagas"] * 100, 1) : 0.0,
        ];
    }

    $porEspecie = [];
    foreach ($pdo->query("SELECT especie, COUNT(*) AS alunos FROM alunos GROUP BY especie ORDER BY alunos DESC, especie") as $linha) {
        $porEspecie[] = ["especie" => $linha["especie"], "alunos" => (int) $linha["alunos"]];
    }

    $porDepartamento = [];
    foreach ($pdo->query(
        "SELECT c.departamento, COUNT(DISTINCT c.id) AS cursos, COUNT(a.id) AS alunos
         FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
         GROUP BY c.departamento ORDER BY alunos DESC, c.departamento"
    ) as $linha) {
        $porDepartamento[] = ["departamento" => $linha["departamento"], "cursos" => (int) $linha["cursos"], "alunos" => (int) $linha["alunos"]];
    }

    $porMes = [];
    foreach ($pdo->query("SELECT DATE_FORMAT(criado_em, '%Y-%m') AS mes, COUNT(*) AS matriculas FROM alunos GROUP BY mes ORDER BY mes") as $linha) {
        $porMes[] = ["mes" => $linha["mes"], "matriculas" => (int) $linha["matriculas"]];
    }

    $alunos = (int) $t["alunos"];
    $vagas = (int) $t["vagas"];
    return [
        "totais" => [
            "alunos" => $alunos,
            "cursos" => (int) $t["cursos"],
            "vagas" => $vagas,
            "ocupacao_percentual" => $vagas > 0 ? round($alunos / $vagas * 100, 1) : 0.0,
            "idade_media" => (float) $t["idade_media"],
            "especies" => (int) $t["especies"],
        ],
        "por_curso" => $porCurso,
        "por_especie" => $porEspecie,
        "por_departamento" => $porDepartamento,
        "matriculas_por_mes" => $porMes,
    ];
}
