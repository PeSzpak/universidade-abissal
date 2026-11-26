# 5. Funcionalidades

| Funcionalidade | Onde |
|---|---|
| Página inicial com nome, slogan, tema, principais cursos (vindos do banco) e diferenciais | `index.php` |
| Página institucional: história, missão, visão, valores, unidades, professores e curiosidades | `institucional.php` |
| CRUD de cursos: cadastrar, listar, editar e excluir (com confirmação) | `cursos/` |
| CRUD de alunos: cadastrar, listar, editar e excluir (com confirmação) | `alunos/` |
| Pesquisa com `LIKE` por parte do nome ou do e-mail | `alunos/listar.php` |
| Filtros por curso e por espécie | `alunos/listar.php` |
| Ordenação: nome A-Z, nome Z-A, mais recentes, mais antigos | `alunos/listar.php` |
| Paginação com `LIMIT` e `OFFSET` (6 por página) preservando pesquisa, filtros e ordem | `alunos/listar.php` |
| Mensagens de sucesso/erro que aparecem uma única vez após o redirecionamento | `includes/funcoes.php` |
| Dashboard de indicadores | `dashboard.php` |
| API REST com documentação Swagger | `api/` |

## CRUDs

| Operação | Cursos | Alunos |
|---|---|---|
| Cadastrar (INSERT) | `cursos/cadastrar.php` | `alunos/cadastrar.php` |
| Consultar (SELECT) | `cursos/listar.php` | `alunos/listar.php` (com pesquisa, filtros, ordem e paginação) |
| Editar (SELECT + UPDATE) | `cursos/editar.php?id=` | `alunos/editar.php?id=` |
| Excluir (DELETE) | `cursos/excluir.php?id=` | `alunos/excluir.php?id=` |

Edição: o ID vem da URL e é validado como inteiro; o registro é buscado no banco e preenche o formulário; as alterações são validadas de novo antes do `UPDATE`.

Exclusão: o link abre uma tela de confirmação (GET, não apaga nada). Só o botão "Sim, excluir" envia um POST que executa o `DELETE`.

---

← [Banco de dados](04-banco-de-dados.md) · [Índice](README.md) · [API REST e Swagger](06-api.md) →
