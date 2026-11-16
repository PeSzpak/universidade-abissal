<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
$id = lerId($_GET);

// 1. Identifica o registro pelo ID e busca os dados no banco
$curso = false;
if ($id !== null) {
    $busca = $pdo->prepare("SELECT nome, departamento, duracao_semestres, vagas, descricao FROM cursos WHERE id = :id");
    $busca->bindValue(":id", $id, PDO::PARAM_INT);
    $busca->execute();
    $curso = $busca->fetch();
}
if ($curso === false) {
    definirFlash("erro", "Curso não encontrado.");
    redirecionar("listar.php");
}
$curso["descricao"] = $curso["descricao"] ?? "";
$erros = [];

// 2. Recebe as alterações, valida e executa o UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($curso as $campo => $valor) {
        $curso[$campo] = trim($_POST[$campo] ?? "");
    }

    $erros = validarCurso($curso);

    if (count($erros) === 0) {
        try {
            $comando = $pdo->prepare(
                "UPDATE cursos SET nome = :nome, departamento = :departamento, duracao_semestres = :duracao,
                        vagas = :vagas, descricao = :descricao
                 WHERE id = :id"
            );
            $comando->bindValue(":nome", $curso["nome"]);
            $comando->bindValue(":departamento", $curso["departamento"]);
            $comando->bindValue(":duracao", (int) $curso["duracao_semestres"], PDO::PARAM_INT);
            $comando->bindValue(":vagas", (int) $curso["vagas"], PDO::PARAM_INT);
            $comando->bindValue(":descricao", $curso["descricao"]);
            $comando->bindValue(":id", $id, PDO::PARAM_INT);
            $comando->execute();

            definirFlash("sucesso", "Curso \"{$curso["nome"]}\" atualizado.");
            redirecionar("listar.php");
        } catch (PDOException $e) {
            if (!ehDuplicado($e)) {
                throw $e;
            }
            $erros[] = "Já existe um curso com esse nome.";
        }
    }
}

$titulo = "Editar curso";
$base = "../";
$secaoAtual = "cursos";
$textoBotao = "Salvar alterações";
include __DIR__ . "/../includes/cabecalho.php";
?>
<h1>Editar curso</h1>
<?php include __DIR__ . "/_form.php"; ?>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
