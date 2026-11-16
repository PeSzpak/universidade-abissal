<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
// GET mostra a confirmação; POST (botão "Sim, excluir") executa o DELETE
$id = lerId($_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET);

$aluno = false;
if ($id !== null) {
    $busca = $pdo->prepare("SELECT a.nome, a.email, c.nome AS curso
                            FROM alunos a JOIN cursos c ON c.id = a.curso_id WHERE a.id = :id");
    $busca->bindValue(":id", $id, PDO::PARAM_INT);
    $busca->execute();
    $aluno = $busca->fetch();
}
if ($aluno === false) {
    definirFlash("erro", "Aluno não encontrado.");
    redirecionar("listar.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comando = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
    $comando->bindValue(":id", $id, PDO::PARAM_INT);
    $comando->execute();

    definirFlash("sucesso", "Aluno \"{$aluno["nome"]}\" excluído.");
    redirecionar("listar.php");
}

$titulo = "Excluir aluno";
$base = "../";
$secaoAtual = "alunos";
include __DIR__ . "/../includes/cabecalho.php";
?>
<section class="formulario confirmacao">
    <h1>Excluir aluno?</h1>
    <dl class="detalhes">
        <dt>Nome</dt><dd><?= h($aluno["nome"]) ?></dd>
        <dt>E-mail</dt><dd><?= h($aluno["email"]) ?></dd>
        <dt>Curso</dt><dd><?= h($aluno["curso"]) ?></dd>
    </dl>
    <p>Essa ação não pode ser desfeita.</p>
    <form method="POST" class="acoes">
        <input type="hidden" name="id" value="<?= (int) $id ?>">
        <button class="botao perigo" type="submit">Sim, excluir</button>
        <a class="botao secundario" href="listar.php">Cancelar</a>
    </form>
</section>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
