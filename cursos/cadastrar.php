<?php
require_once __DIR__ . "/../includes/funcoes.php";

$curso = ["nome" => "", "departamento" => "", "duracao_semestres" => "", "vagas" => "", "descricao" => ""];
$erros = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($curso as $campo => $valor) {
        $curso[$campo] = trim($_POST[$campo] ?? "");
    }

    $erros = validarCurso($curso);

    if (count($erros) === 0) {
        try {
            $comando = conectar()->prepare(
                "INSERT INTO cursos (nome, departamento, duracao_semestres, vagas, descricao)
                 VALUES (:nome, :departamento, :duracao, :vagas, :descricao)"
            );
            $comando->bindValue(":nome", $curso["nome"]);
            $comando->bindValue(":departamento", $curso["departamento"]);
            $comando->bindValue(":duracao", (int) $curso["duracao_semestres"], PDO::PARAM_INT);
            $comando->bindValue(":vagas", (int) $curso["vagas"], PDO::PARAM_INT);
            $comando->bindValue(":descricao", $curso["descricao"]);
            $comando->execute();

            definirFlash("sucesso", "Curso \"{$curso["nome"]}\" cadastrado com sucesso!");
            redirecionar("listar.php");
        } catch (PDOException $e) {
            if (!ehDuplicado($e)) {
                throw $e;
            }
            $erros[] = "Já existe um curso com esse nome.";
        }
    }
}

$titulo = "Novo curso";
$base = "../";
$textoBotao = "Cadastrar curso";
include __DIR__ . "/../includes/cabecalho.php";
?>
<h1>Novo curso</h1>
<?php include __DIR__ . "/_form.php"; ?>
<?php include __DIR__ . "/../includes/rodape.php"; ?>
