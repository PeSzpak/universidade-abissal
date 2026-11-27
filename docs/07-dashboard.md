# 7. Dashboard

Página `dashboard.php`, no menu do site. Todos os números são calculados no momento do acesso pela função `indicadoresDashboard()` em `includes/funcoes.php`. A mesma função responde ao endpoint `GET /api/index.php/dashboard`, então a tela e a API nunca divergem.

| Indicador | Pergunta que responde | Como é calculado |
|---|---|---|
| Alunos matriculados | Quantos alunos a universidade tem? | `COUNT(*)` em alunos |
| Cursos | Quantos cursos são oferecidos? | `COUNT(*)` em cursos |
| Ocupação das vagas | Quanto da capacidade está em uso? | alunos ÷ soma das vagas × 100 |
| Idade média | Qual o perfil de idade dos alunos? | `AVG(idade)` |
| Espécies diferentes | Quão diverso é o corpo discente? | `COUNT(DISTINCT especie)` |
| Ocupação por curso | Quais cursos estão mais cheios? | alunos ÷ vagas de cada curso (`LEFT JOIN` + `GROUP BY`) |
| Alunos por espécie | Quais espécies mais estudam aqui? | `GROUP BY especie` |
| Departamentos | Que profundidade concentra mais alunos? | `GROUP BY departamento` com cursos e alunos |
| Matrículas por mês | Como as matrículas evoluem? | `GROUP BY DATE_FORMAT(criado_em, '%Y-%m')` |

Cada barra de curso e de espécie é um link para a listagem de alunos já filtrada. Os gráficos são feitos em CSS, sem biblioteca externa, e cada um tem texto alternativo para leitores de tela.

---

← [API REST e Swagger](06-api.md) · [Índice](README.md) · [Identidade visual](08-identidade-visual.md) →
