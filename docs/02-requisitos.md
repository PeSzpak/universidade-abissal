# 2. Requisitos

## Requisitos funcionais

| Código | Requisito | Onde é atendido |
|---|---|---|
| RF01 | Apresentar a universidade: nome, slogan, tema, principais cursos e diferenciais | `index.php` |
| RF02 | Apresentar a história, missão, visão, valores, unidades e corpo docente | `institucional.php` |
| RF03 | Cadastrar, consultar, editar e excluir cursos | `cursos/` |
| RF04 | Cadastrar, consultar, editar e excluir alunos | `alunos/` |
| RF05 | Pesquisar alunos por parte do nome ou do e-mail | `alunos/listar.php` (LIKE) |
| RF06 | Filtrar alunos por curso e por espécie | `alunos/listar.php` |
| RF07 | Ordenar alunos por nome (A-Z, Z-A) e por data de matrícula | `alunos/listar.php` |
| RF08 | Paginar a listagem mantendo pesquisa, filtros e ordem | `alunos/listar.php` (LIMIT/OFFSET) |
| RF09 | Pedir confirmação antes de excluir e impedir a exclusão de curso com alunos | `*/excluir.php` |
| RF10 | Exibir um dashboard com totais, ocupação por curso, espécies, departamentos e matrículas por mês | `dashboard.php` |
| RF11 | Disponibilizar uma API REST em JSON com as mesmas operações e regras das telas | `api/index.php` |
| RF12 | Documentar a API de forma interativa (Swagger / OpenAPI 3) | `api/docs.html`, `api/openapi.yaml` |

## Requisitos não funcionais

| Código | Requisito | Como é atendido |
|---|---|---|
| RNF01 | Segurança contra SQL injection | Prepared statements em toda consulta com valor externo; ORDER BY por lista branca |
| RNF02 | Segurança contra XSS | Todo dado exibido passa por `htmlspecialchars` (função `h()`) |
| RNF03 | Integridade dos dados | Validação no PHP e restrições no banco (UNIQUE, NOT NULL, chave estrangeira) |
| RNF04 | Usabilidade | Mensagens de erro claras por campo; valores digitados mantidos após erro |
| RNF05 | Portabilidade | Roda no XAMPP ou no `php -S`; conexão configurável por variáveis de ambiente |
| RNF06 | Acessibilidade | Rótulos em todos os campos, foco visível, gráficos com texto alternativo e animações desligadas para quem pede movimento reduzido |
| RNF07 | Consistência | Dashboard e API usam a mesma função de indicadores; tela e API usam as mesmas validações |

---

← [Visão geral](01-visao-geral.md) · [Índice](README.md) · [Arquitetura](03-arquitetura.md) →
