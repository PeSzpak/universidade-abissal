<?php
// API REST da Universidade Abissal (JSON). Documentação interativa: api/docs.html (Swagger UI).
//
// Rotas (o recurso vem depois de index.php, no PATH_INFO):
//   GET                 /dashboard
//   GET, POST           /cursos            GET, PUT, DELETE /cursos/{id}
//   GET, POST           /alunos            GET, PUT, DELETE /alunos/{id}
//
// As regras são as mesmas das telas: validarCurso(), validarAluno() e consultarAlunos()
// em includes/funcoes.php, e toda consulta com valor externo usa prepared statement.

require_once __DIR__ . "/../includes/funcoes.php";

header("Content-Type: application/json; charset=utf-8");

function responder(int $status, $dados = null): void
{
    http_response_code($status);
    if ($dados !== null) {
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
    }
    exit;
}

function erro(int $status, string $mensagem, array $campos = []): void
{
    responder($status, ["erro" => $mensagem, "campos" => $campos]);
}

// Corpo JSON de POST e PUT, já com trim nos textos (como nos formulários)
function lerCorpo(array $campos): array
{
    $corpo = json_decode(file_get_contents("php://input"), true);
    if (!is_array($corpo)) {
        erro(400, "O corpo da requisição precisa ser um JSON válido.");
    }
    $dados = [];
    foreach ($campos as $campo) {
        $dados[$campo] = trim((string) ($corpo[$campo] ?? ""));
    }
    return $dados;
}

function curso(array $linha): array
{
    return [
        "id" => (int) $linha["id"],
        "nome" => $linha["nome"],
        "departamento" => $linha["departamento"],
        "duracao_semestres" => (int) $linha["duracao_semestres"],
        "vagas" => (int) $linha["vagas"],
        "descricao" => $linha["descricao"],
        "total_alunos" => (int) $linha["total_alunos"],
    ];
}

function aluno(array $linha): array
{
    return [
        "id" => (int) $linha["id"],
        "nome" => $linha["nome"],
        "email" => $linha["email"],
        "especie" => $linha["especie"],
        "idade" => (int) $linha["idade"],
        "curso_id" => (int) $linha["curso_id"],
        "curso" => $linha["curso"],
        "criado_em" => $linha["criado_em"],
    ];
}

function buscarCurso(PDO $pdo, int $id): ?array
{
    $consulta = $pdo->prepare(
        "SELECT c.*, COUNT(a.id) AS total_alunos FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
         WHERE c.id = :id GROUP BY c.id"
    );
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $linha = $consulta->fetch();
    return $linha ? curso($linha) : null;
}

function buscarAluno(PDO $pdo, int $id): ?array
{
    $consulta = $pdo->prepare(
        "SELECT a.id, a.nome, a.email, a.especie, a.idade, a.curso_id, a.criado_em, c.nome AS curso
         FROM alunos a JOIN cursos c ON c.id = a.curso_id WHERE a.id = :id"
    );
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $linha = $consulta->fetch();
    return $linha ? aluno($linha) : null;
}

function gravarCurso(PDO $pdo, array $c, ?int $id): int
{
    $sql = $id === null
        ? "INSERT INTO cursos (nome, departamento, duracao_semestres, vagas, descricao) VALUES (:nome, :departamento, :duracao, :vagas, :descricao)"
        : "UPDATE cursos SET nome = :nome, departamento = :departamento, duracao_semestres = :duracao, vagas = :vagas, descricao = :descricao WHERE id = :id";
    $comando = $pdo->prepare($sql);
    $comando->bindValue(":nome", $c["nome"]);
    $comando->bindValue(":departamento", $c["departamento"]);
    $comando->bindValue(":duracao", (int) $c["duracao_semestres"], PDO::PARAM_INT);
    $comando->bindValue(":vagas", (int) $c["vagas"], PDO::PARAM_INT);
    $comando->bindValue(":descricao", $c["descricao"]);
    if ($id !== null) {
        $comando->bindValue(":id", $id, PDO::PARAM_INT);
    }
    $comando->execute();
    return $id ?? (int) $pdo->lastInsertId();
}

function gravarAluno(PDO $pdo, array $a, ?int $id): int
{
    $sql = $id === null
        ? "INSERT INTO alunos (nome, email, especie, idade, curso_id) VALUES (:nome, :email, :especie, :idade, :curso_id)"
        : "UPDATE alunos SET nome = :nome, email = :email, especie = :especie, idade = :idade, curso_id = :curso_id WHERE id = :id";
    $comando = $pdo->prepare($sql);
    $comando->bindValue(":nome", $a["nome"]);
    $comando->bindValue(":email", $a["email"]);
    $comando->bindValue(":especie", $a["especie"]);
    $comando->bindValue(":idade", (int) $a["idade"], PDO::PARAM_INT);
    $comando->bindValue(":curso_id", (int) $a["curso_id"], PDO::PARAM_INT);
    if ($id !== null) {
        $comando->bindValue(":id", $id, PDO::PARAM_INT);
    }
    $comando->execute();
    return $id ?? (int) $pdo->lastInsertId();
}

// ---------------------------------------------------------------------------------------------
// Roteamento
// ---------------------------------------------------------------------------------------------
$metodo = $_SERVER["REQUEST_METHOD"];
$partes = array_values(array_filter(explode("/", $_SERVER["PATH_INFO"] ?? "")));
$recurso = $partes[0] ?? "";
$id = null;
if (isset($partes[1])) {
    $id = filter_var($partes[1], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($id === false || isset($partes[2])) {
        erro(404, "Rota não encontrada.");
    }
}

try {
    $pdo = conectar();

    if ($recurso === "dashboard" && $id === null && $metodo === "GET") {
        responder(200, indicadoresDashboard($pdo));
    }

    if ($recurso === "cursos") {
        if ($id === null && $metodo === "GET") {
            $linhas = $pdo->query(
                "SELECT c.*, COUNT(a.id) AS total_alunos FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
                 GROUP BY c.id ORDER BY c.nome"
            )->fetchAll();
            responder(200, array_map("curso", $linhas));
        }
        if ($id === null && $metodo === "POST") {
            $dados = lerCorpo(["nome", "departamento", "duracao_semestres", "vagas", "descricao"]);
            $erros = validarCurso($dados);
            if ($erros) {
                erro(422, "Dados inválidos.", $erros);
            }
            responder(201, buscarCurso($pdo, gravarCurso($pdo, $dados, null)));
        }
        if ($id !== null) {
            if (buscarCurso($pdo, $id) === null) {
                erro(404, "Curso $id não encontrado.");
            }
            if ($metodo === "GET") {
                responder(200, buscarCurso($pdo, $id));
            }
            if ($metodo === "PUT") {
                $dados = lerCorpo(["nome", "departamento", "duracao_semestres", "vagas", "descricao"]);
                $erros = validarCurso($dados);
                if ($erros) {
                    erro(422, "Dados inválidos.", $erros);
                }
                gravarCurso($pdo, $dados, $id);
                responder(200, buscarCurso($pdo, $id));
            }
            if ($metodo === "DELETE") {
                $curso = buscarCurso($pdo, $id);
                if ($curso["total_alunos"] > 0) {
                    erro(409, "Não é possível excluir \"{$curso["nome"]}\": há {$curso["total_alunos"]} aluno(s) matriculado(s).");
                }
                $comando = $pdo->prepare("DELETE FROM cursos WHERE id = :id");
                $comando->bindValue(":id", $id, PDO::PARAM_INT);
                $comando->execute();
                responder(204);
            }
        }
    }

    if ($recurso === "alunos") {
        if ($id === null && $metodo === "GET") {
            $porPagina = filter_var($_GET["por_pagina"] ?? 10, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 100]]) ?: 10;
            $r = consultarAlunos($pdo, $_GET, $porPagina);
            responder(200, [
                "dados" => array_map("aluno", $r["alunos"]),
                "paginacao" => ["pagina" => $r["pagina"], "por_pagina" => $porPagina, "total" => $r["total"], "total_paginas" => $r["totalPaginas"]],
                "filtros" => ["busca" => $r["busca"], "curso" => $r["curso"] ?: null, "especie" => $r["especie"] ?: null, "ordem" => $r["ordem"]],
            ]);
        }
        if ($id === null && $metodo === "POST") {
            $dados = lerCorpo(["nome", "email", "especie", "idade", "curso_id"]);
            $dados["email"] = mb_strtolower($dados["email"]);
            $erros = validarAluno($dados, $pdo);
            if ($erros) {
                erro(422, "Dados inválidos.", $erros);
            }
            responder(201, buscarAluno($pdo, gravarAluno($pdo, $dados, null)));
        }
        if ($id !== null) {
            if (buscarAluno($pdo, $id) === null) {
                erro(404, "Aluno $id não encontrado.");
            }
            if ($metodo === "GET") {
                responder(200, buscarAluno($pdo, $id));
            }
            if ($metodo === "PUT") {
                $dados = lerCorpo(["nome", "email", "especie", "idade", "curso_id"]);
                $dados["email"] = mb_strtolower($dados["email"]);
                $erros = validarAluno($dados, $pdo);
                if ($erros) {
                    erro(422, "Dados inválidos.", $erros);
                }
                gravarAluno($pdo, $dados, $id);
                responder(200, buscarAluno($pdo, $id));
            }
            if ($metodo === "DELETE") {
                $comando = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
                $comando->bindValue(":id", $id, PDO::PARAM_INT);
                $comando->execute();
                responder(204);
            }
        }
    }

    $existe = in_array($recurso, ["dashboard", "cursos", "alunos"], true);
    $existe ? erro(405, "Método $metodo não permitido nesta rota.") : erro(404, "Rota não encontrada.");
} catch (PDOException $e) {
    if (ehDuplicado($e)) {
        erro(409, $recurso === "cursos" ? "Já existe um curso com esse nome." : "Esse e-mail já está em uso por outro aluno.");
    }
    error_log($e->getMessage());
    erro(500, "Erro interno ao acessar o banco de dados.");
}
