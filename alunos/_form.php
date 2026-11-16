<?php // Formulário compartilhado por cadastrar.php e editar.php. Espera $aluno, $cursos, $erros e $textoBotao. ?>
<?php if (count($erros) > 0): ?>
    <div class="alerta erro" role="alert">
        <strong>Corrija os dados abaixo:</strong>
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= h($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="formulario" method="POST" novalidate>
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" maxlength="100" value="<?= h($aluno["nome"]) ?>">

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" maxlength="120" value="<?= h($aluno["email"]) ?>">

    <label for="especie">Espécie</label>
    <select id="especie" name="especie">
        <option value="">Selecione</option>
        <?php foreach (ESPECIES as $especie): ?>
            <option value="<?= h($especie) ?>" <?= $aluno["especie"] === $especie ? "selected" : "" ?>><?= h($especie) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="idade">Idade</label>
    <input type="number" id="idade" name="idade" min="1" max="500" value="<?= h($aluno["idade"]) ?>">

    <label for="curso_id">Curso</label>
    <select id="curso_id" name="curso_id">
        <option value="">Selecione</option>
        <?php foreach ($cursos as $curso): ?>
            <option value="<?= (int) $curso["id"] ?>" <?= (string) $aluno["curso_id"] === (string) $curso["id"] ? "selected" : "" ?>><?= h($curso["nome"]) ?></option>
        <?php endforeach; ?>
    </select>

    <div class="acoes">
        <button class="botao" type="submit"><?= h($textoBotao) ?></button>
        <a class="botao secundario" href="listar.php">Cancelar</a>
    </div>
</form>
