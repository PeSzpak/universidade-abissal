<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
$id = lerId($_GET);

// 1. Identifica o registro pelo ID e busca os dados no banco
$aluno = false;
if ($id !== null) {
    $busca = $pdo->prepare("SELECT nome, email, especie, idade, curso_id FROM alunos WHERE id = :id");
    $busca->bindValue(":id", $id, PDO::PARAM_INT);
    $busca->execute();
    $aluno = $busca->fetch();
}
if ($aluno === false) {
    definirFlash("erro", "Aluno não encontrado.");
    redirecionar("listar.php");
}
$erros = [];

// 2. Recebe as alterações, valida e executa o UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($aluno as $campo => $valor) {
        $aluno[$campo] = trim($_POST[$campo] ?? "");
    }

    $erros = validarAluno($aluno, $pdo);

    if (count($erros) === 0) {
        try {
            $comando = $pdo->prepare(
                "UPDATE alunos SET nome = :nome, email = :email, especie = :especie, idade = :idade, curso_id = :curso_id
                 WHERE id = :id"
            );
            $comando->bindValue(":nome", $aluno["nome"]);
            $comando->bindValue(":email", $aluno["email"]);
            $comando->bindValue(":especie", $aluno["especie"]);
            $comando->bindValue(":idade", (int) $aluno["idade"], PDO::PARAM_INT);
            $comando->bindValue(":curso_id", (int) $aluno["curso_id"], PDO::PARAM_INT);
            $comando->bindValue(":id", $id, PDO::PARAM_INT);
            $comando->execute();

            definirFlash("sucesso", "Dados de \"{$aluno["nome"]}\" atualizados.");
            redirecionar("listar.php");
        } catch (PDOException $e) {
            if (!ehDuplicado($e)) {
                throw $e;
            }
            $erros[] = "Esse e-mail já está em uso por outro aluno.";
        }
    }
}

$cursos = $pdo->query("SELECT id, nome FROM cursos ORDER BY nome")->fetchAll();

$titulo = "Editar aluno";
$base = "../";
$secaoAtual = "alunos";
$textoBotao = "Salvar alterações";
include __DIR__ . "/../includes/cabecalho.php";
?>
<h1>Editar aluno</h1>
<?php include __DIR__ . "/_form.php"; ?>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
