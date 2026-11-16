<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
$aluno = ["nome" => "", "email" => "", "especie" => "", "idade" => "", "curso_id" => ""];
$erros = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($aluno as $campo => $valor) {
        $aluno[$campo] = trim($_POST[$campo] ?? "");
    }

    // Primeiro validar, depois gravar
    $erros = validarAluno($aluno, $pdo);

    if (count($erros) === 0) {
        try {
            $comando = $pdo->prepare(
                "INSERT INTO alunos (nome, email, especie, idade, curso_id)
                 VALUES (:nome, :email, :especie, :idade, :curso_id)"
            );
            $comando->bindValue(":nome", $aluno["nome"]);
            $comando->bindValue(":email", $aluno["email"]);
            $comando->bindValue(":especie", $aluno["especie"]);
            $comando->bindValue(":idade", (int) $aluno["idade"], PDO::PARAM_INT);
            $comando->bindValue(":curso_id", (int) $aluno["curso_id"], PDO::PARAM_INT);
            $comando->execute();

            definirFlash("sucesso", "Aluno \"{$aluno["nome"]}\" matriculado com sucesso!");
            redirecionar("listar.php?ordem=recentes");
        } catch (PDOException $e) {
            if (!ehDuplicado($e)) {
                throw $e;
            }
            $erros[] = "Esse e-mail já está em uso por outro aluno.";
        }
    }
}

$cursos = $pdo->query("SELECT id, nome FROM cursos ORDER BY nome")->fetchAll();

$titulo = "Matricular aluno";
$base = "../";
$secaoAtual = "alunos";
$textoBotao = "Matricular";
include __DIR__ . "/../includes/cabecalho.php";
?>
<h1>Matricular aluno</h1>
<?php if (count($cursos) === 0): ?>
    <p class="alerta erro">Cadastre um curso antes de matricular alunos. <a href="../cursos/cadastrar.php">Cadastrar curso</a></p>
<?php else: ?>
    <?php include __DIR__ . "/_form.php"; ?>
<?php endif; ?>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
