# 6. API REST e Swagger

A API devolve e recebe JSON e aplica as mesmas regras das telas (as validações de `includes/funcoes.php`).

- **Swagger UI (documentação interativa):** `http://localhost/universidade-abissal/api/docs.html`
- **Especificação OpenAPI 3:** [`api/openapi.yaml`](../api/openapi.yaml)
- **URL base:** `api/index.php` (no Apache do XAMPP também funciona `api/`, graças ao `.htaccess`)

No Swagger dá para testar cada operação pelo botão **Try it out**, contra o banco de verdade.

## Endpoints

| Método | Rota | O que faz | Respostas |
|---|---|---|---|
| GET | `/dashboard` | Indicadores do painel | 200 |
| GET | `/cursos` | Lista os cursos com o total de alunos | 200 |
| POST | `/cursos` | Cadastra um curso | 201, 400, 409, 422 |
| GET | `/cursos/{id}` | Consulta um curso | 200, 404 |
| PUT | `/cursos/{id}` | Edita um curso | 200, 400, 404, 409, 422 |
| DELETE | `/cursos/{id}` | Exclui um curso sem alunos | 204, 404, 409 |
| GET | `/alunos` | Lista alunos (`busca`, `curso`, `especie`, `ordem`, `pagina`, `por_pagina`) | 200 |
| POST | `/alunos` | Matricula um aluno | 201, 400, 409, 422 |
| GET | `/alunos/{id}` | Consulta um aluno | 200, 404 |
| PUT | `/alunos/{id}` | Edita um aluno | 200, 400, 404, 409, 422 |
| DELETE | `/alunos/{id}` | Exclui um aluno | 204, 404 |

## Códigos de resposta

| Código | Quando |
|---|---|
| 200 / 201 / 204 | Sucesso (consulta, criação, exclusão) |
| 400 | O corpo não é um JSON válido |
| 404 | Registro ou rota inexistente |
| 405 | Método não permitido na rota |
| 409 | Nome de curso ou e-mail repetido; curso com alunos não pode ser excluído |
| 422 | Algum campo não passou na validação (a lista vem em `campos`) |
| 500 | Falha inesperada no banco (o detalhe fica no log, não na resposta) |

Todo erro volta no formato:

```json
{ "erro": "Dados inválidos.", "campos": ["O nome do curso é obrigatório."] }
```

## Exemplos com curl

```bash
# Alunos da espécie Polvo, ordem Z-A, 2 por página
curl "http://localhost/universidade-abissal/api/index.php/alunos?especie=Polvo&ordem=nome_desc&por_pagina=2"

# Matricular um aluno
curl -X POST http://localhost/universidade-abissal/api/index.php/alunos \
     -H "Content-Type: application/json" \
     -d '{"nome":"Nemo Coralino","email":"nemo@abissal.edu","especie":"Polvo","idade":19,"curso_id":4}'
```

---

← [Funcionalidades](05-funcionalidades.md) · [Índice](README.md) · [Dashboard](07-dashboard.md) →
