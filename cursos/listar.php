<?php
require_once __DIR__ . "/../includes/funcoes.php";

$cursos = conectar()->query("SELECT c.*, COUNT(a.id) AS total_alunos
                             FROM cursos c LEFT JOIN alunos a ON a.curso_id = c.id
                             GROUP BY c.id ORDER BY c.nome")->fetchAll();

$titulo = "Cursos";
$base = "../";
$secaoAtual = "cursos";
include __DIR__ . "/../includes/cabecalho.php";
?>
<div class="cabecalho-pagina">
    <h1>Cursos</h1>
    <a class="botao" href="cadastrar.php">+ Novo curso</a>
</div>

<div class="tabela-envolve">
    <table>
        <thead>
            <tr><th>Curso</th><th>Departamento</th><th>Duração</th><th class="num">Vagas</th><th class="num">Alunos</th><th>Ações</th></tr>
        </thead>
        <tbody>
        <?php foreach ($cursos as $curso): ?>
            <tr>
                <td><?= h($curso["nome"]) ?></td>
                <td><?= h($curso["departamento"]) ?></td>
                <td><?= (int) $curso["duracao_semestres"] ?> semestres</td>
                <td class="num"><?= (int) $curso["vagas"] ?></td>
                <td class="num"><a href="../alunos/listar.php?curso=<?= (int) $curso["id"] ?>"><?= (int) $curso["total_alunos"] ?></a></td>
                <td>
                    <div class="acoes">
                        <a class="botao pequeno secundario" href="editar.php?id=<?= (int) $curso["id"] ?>">Editar</a>
                        <a class="botao pequeno perigo" href="excluir.php?id=<?= (int) $curso["id"] ?>">Excluir</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($cursos) === 0): ?>
            <tr><td colspan="6" class="vazio">Nenhum curso cadastrado ainda.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
