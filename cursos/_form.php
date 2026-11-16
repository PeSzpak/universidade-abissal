<?php // Formulário compartilhado por cadastrar.php e editar.php. Espera $curso, $erros e $textoBotao. ?>
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
    <label for="nome">Nome do curso</label>
    <input type="text" id="nome" name="nome" maxlength="100" value="<?= h($curso["nome"]) ?>">

    <label for="departamento">Departamento</label>
    <select id="departamento" name="departamento">
        <option value="">Selecione</option>
        <?php foreach (DEPARTAMENTOS as $departamento): ?>
            <option value="<?= h($departamento) ?>" <?= $curso["departamento"] === $departamento ? "selected" : "" ?>><?= h($departamento) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="duracao_semestres">Duração (semestres)</label>
    <input type="number" id="duracao_semestres" name="duracao_semestres" min="1" max="12" value="<?= h($curso["duracao_semestres"]) ?>">

    <label for="vagas">Vagas</label>
    <input type="number" id="vagas" name="vagas" min="1" value="<?= h($curso["vagas"]) ?>">

    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao"><?= h($curso["descricao"]) ?></textarea>

    <div class="acoes">
        <button class="botao" type="submit"><?= h($textoBotao) ?></button>
        <a class="botao secundario" href="listar.php">Cancelar</a>
    </div>
</form>
