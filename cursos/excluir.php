<?php
require_once __DIR__ . "/../includes/funcoes.php";

$pdo = conectar();
// GET mostra a confirmação; POST (botão "Sim, excluir") executa o DELETE
$id = lerId($_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET);

$curso = false;
if ($id !== null) {
    $busca = $pdo->prepare("SELECT c.nome, c.departamento, COUNT(a.id) AS total_alunos
                            FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
                            WHERE c.id = :id GROUP BY c.id");
    $busca->bindValue(":id", $id, PDO::PARAM_INT);
    $busca->execute();
    $curso = $busca->fetch();
}
if ($curso === false) {
    definirFlash("erro", "Curso não encontrado.");
    redirecionar("listar.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // A chave estrangeira impede apagar um curso com alunos; avisamos antes de tentar
    if ($curso["total_alunos"] > 0) {
        definirFlash("erro", "Não é possível excluir \"{$curso["nome"]}\": há {$curso["total_alunos"]} aluno(s) matriculado(s).");
        redirecionar("listar.php");
    }

    $comando = $pdo->prepare("DELETE FROM cursos WHERE id = :id");
    $comando->bindValue(":id", $id, PDO::PARAM_INT);
    $comando->execute();

    definirFlash("sucesso", "Curso \"{$curso["nome"]}\" excluído.");
    redirecionar("listar.php");
}

$titulo = "Excluir curso";
$base = "../";
$secaoAtual = "cursos";
include __DIR__ . "/../includes/cabecalho.php";
?>
<section class="formulario confirmacao">
    <h1>Excluir curso?</h1>
    <dl class="detalhes">
        <dt>Curso</dt><dd><?= h($curso["nome"]) ?></dd>
        <dt>Departamento</dt><dd><?= h($curso["departamento"]) ?></dd>
        <dt>Alunos</dt><dd><?= (int) $curso["total_alunos"] ?></dd>
    </dl>
    <?php if ($curso["total_alunos"] > 0): ?>
        <p class="alerta erro">Este curso tem alunos matriculados. Transfira ou exclua os alunos antes.</p>
    <?php else: ?>
        <p>Essa ação não pode ser desfeita.</p>
    <?php endif; ?>
    <form method="POST" class="acoes">
        <input type="hidden" name="id" value="<?= (int) $id ?>">
        <?php if ($curso["total_alunos"] == 0): ?>
            <button class="botao perigo" type="submit">Sim, excluir</button>
        <?php endif; ?>
        <a class="botao secundario" href="listar.php">Cancelar</a>
    </form>
</section>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
