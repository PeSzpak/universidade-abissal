# 10. Testes

Testes executados manualmente em 26/11/2026, com MySQL 8.4 e `php -S`, a partir do `database/banco.sql`.

## Telas

| Cenário | Resultado |
|---|---|
| Listagem de alunos sem filtro | 24 alunos, página 1 de 4 |
| Pesquisa "polv" | 1 aluno |
| Filtro espécie Polvo + curso 4 | 3 alunos |
| Ordem Z-A | Primeiro: Tubarina Martelo |
| Página 99 | Mostra a última página (4 de 4) |
| Pesquisa com `%` | 0 alunos (o `%` é tratado como texto) |
| Link da página 2 com pesquisa e ordem | Mantém `busca` e `ordem` na URL |
| Dashboard | 24 alunos, 6 cursos, 13,3% de ocupação (24 de 180 vagas), idade média 23,6, 8 espécies |

## API

| Requisição | Resultado |
|---|---|
| `GET /dashboard` | 200, mesmos números do dashboard |
| `GET /cursos/1` | 200 |
| `GET /cursos/99` | 404 "Curso 99 não encontrado." |
| `GET /alunos?especie=Polvo&ordem=nome_desc&por_pagina=2` | 200, 2 alunos e paginação |
| `POST /cursos` com campos inválidos | 422 com as 4 mensagens em `campos` |
| `POST /cursos` com JSON quebrado | 400 |
| `POST /cursos` válido | 201 com o curso criado |
| `POST /cursos` com nome repetido | 409 |
| `PUT /cursos/{id}` | 200 com os dados novos |
| `POST /alunos` válido (e-mail em maiúsculas) | 201, e-mail gravado em minúsculas |
| `POST /alunos` com e-mail repetido | 409 |
| `DELETE /cursos/{id}` com aluno | 409, nada excluído |
| `DELETE /alunos/{id}` e depois o curso | 204 e 204 |
| `PATCH /cursos/1` | 405 |
| `GET /professores` | 404 |
| Pesquisa `' OR 1=1 --` e ordem `nome; DROP TABLE alunos` | 200 sem resultados; tabela intacta (24 alunos) |

## Testes de sanidade automáticos

O script `tests/sanidade.sh` repete os principais cenários acima com `curl` e `jq`: abre as 8 páginas, confere pesquisa, filtros e paginação, provoca os erros 400, 404, 405, 409 e 422, tenta SQL injection e XSS e faz um ciclo de criar, duplicar e excluir um curso. No fim confere que o banco terminou com o mesmo número de alunos.

```bash
bash tests/sanidade.sh                                            # php -S localhost:8000
BASE=http://localhost/universidade-abissal bash tests/sanidade.sh # XAMPP
```

Resultado em 26/11/2026: **24 passaram, 0 falharam**. Os testes de pesquisa e filtro esperam os dados de exemplo de `database/banco.sql`.

## Swagger

A página `api/docs.html` carrega a especificação, lista as 11 operações em 3 grupos (Dashboard, Cursos, Alunos) e executa as requisições pelo **Try it out**.

---

← [Segurança](09-seguranca.md) · [Índice](README.md)
