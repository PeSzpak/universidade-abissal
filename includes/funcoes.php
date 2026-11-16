<?php
// Carregado no topo de toda página: abre a sessão, a conexão e as funções compartilhadas.
require_once __DIR__ . "/../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DEPARTAMENTOS = ["Zona Abissal", "Fossas Oceânicas", "Recifes de Coral", "Superfície"];
const ESPECIES = ["Polvo", "Lula-gigante", "Peixe-lanterna", "Tubarão", "Água-viva", "Cavalo-marinho", "Baleia", "Caranguejo"];

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
