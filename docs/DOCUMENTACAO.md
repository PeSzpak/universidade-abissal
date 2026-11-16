# Documentação do sistema — Universidade Abissal

## 1. Identificação

- **Universidade:** Universidade Abissal
- **Tema:** uma universidade no fundo do oceano, com campus a 4.000 metros de profundidade, cursos para criaturas marinhas e professores polvos, lulas e baleias.
- **Disciplina:** Tecnologias para Internet, Senac São José dos Pinhais (ADS).

## 2. Visão geral

O site apresenta a universidade (página inicial e página institucional) e oferece à secretaria acadêmica dois cadastros ligados ao MySQL: **Cursos** e **Alunos**. Cada aluno pertence a um curso.

## 3. Problema que o sistema resolve

A secretaria da universidade controlava matrículas em placas de coral riscadas a tentáculo. O sistema centraliza cursos e alunos num banco de dados, impede dados inválidos (e-mail sem formato, idade negativa, curso inexistente) e permite localizar rapidamente qualquer aluno por nome, e-mail, curso ou espécie.

## 4. Funcionalidades

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

## 5. Estrutura dos arquivos

```text
universidade-abissal/
├── index.php                 página inicial
├── institucional.php         página institucional
├── config/
│   └── conexao.php           conexão PDO (lê DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS)
├── includes/
│   ├── funcoes.php           sessão, h(), mensagens flash, validações, listas fixas
│   ├── cabecalho.php         <head>, menu e mensagem flash
│   └── rodape.php            rodapé e fechamento do HTML
├── cursos/
│   ├── listar.php            SELECT com contagem de alunos
│   ├── cadastrar.php         INSERT
│   ├── editar.php            SELECT por ID + UPDATE
│   ├── excluir.php           confirmação + DELETE
│   └── _form.php             formulário reaproveitado em cadastrar/editar
├── alunos/
│   ├── listar.php            pesquisa, filtros, ordenação e paginação
│   ├── cadastrar.php         INSERT
│   ├── editar.php            SELECT por ID + UPDATE
│   ├── excluir.php           confirmação + DELETE
│   └── _form.php             formulário reaproveitado em cadastrar/editar
├── assets/
│   ├── css/style.css         identidade visual e animações
│   ├── js/movimento.js       entradas por seção, profundímetro e galeria
│   ├── img/logo.svg          logotipo (peixe-pescador com a isca acesa)
│   └── fotos/                gravuras e vídeo em domínio público (+ credits.json)
├── database/
│   └── banco.sql             criação do banco, tabelas e dados de exemplo
└── docs/
    └── DOCUMENTACAO.md       este arquivo
```

## 6. Banco de dados

Banco `universidade_abissal`, charset `utf8mb4`.

### Tabela `cursos`

| Campo | Tipo | Regra |
|---|---|---|
| `id` | INT, AUTO_INCREMENT, PK | gerado pelo banco |
| `nome` | VARCHAR(100), UNIQUE | obrigatório, sem repetição |
| `departamento` | VARCHAR(40) | um dos 4 departamentos |
| `duracao_semestres` | INT | inteiro de 1 a 12 |
| `vagas` | INT | inteiro maior que zero |
| `descricao` | TEXT | opcional |
| `criado_em` | TIMESTAMP | preenchido pelo banco |

### Tabela `alunos`

| Campo | Tipo | Regra |
|---|---|---|
| `id` | INT, AUTO_INCREMENT, PK | gerado pelo banco |
| `nome` | VARCHAR(100) | obrigatório |
| `email` | VARCHAR(120), UNIQUE | formato válido, sem repetição |
| `especie` | VARCHAR(30) | uma das espécies da lista |
| `idade` | INT | inteiro de 1 a 500 (polvos vivem pouco, baleias muito) |
| `curso_id` | INT, FK → `cursos.id` | precisa existir |
| `criado_em` | TIMESTAMP | data da matrícula, usada na ordenação |

Relacionamento: **um curso tem vários alunos** (1:N). A chave estrangeira usa `ON DELETE RESTRICT`, então um curso com alunos não pode ser apagado; a tela de exclusão avisa isso antes.

## 7. CRUDs

| Operação | Cursos | Alunos |
|---|---|---|
| Cadastrar (INSERT) | `cursos/cadastrar.php` | `alunos/cadastrar.php` |
| Consultar (SELECT) | `cursos/listar.php` | `alunos/listar.php` (com pesquisa, filtros, ordem e paginação) |
| Editar (SELECT + UPDATE) | `cursos/editar.php?id=` | `alunos/editar.php?id=` |
| Excluir (DELETE) | `cursos/excluir.php?id=` | `alunos/excluir.php?id=` |

Edição: o ID vem da URL e é validado como inteiro; o registro é buscado no banco e preenche o formulário; as alterações são validadas de novo antes do `UPDATE`.

Exclusão: o link abre uma tela de confirmação (GET, não apaga nada). Só o botão "Sim, excluir" envia um POST que executa o `DELETE`.

## 8. Identidade visual

- **Ideia:** a única luz a 4.000 metros é a isca do peixe-pescador. Por isso o site é escuro e tudo que é importante aparece dentro de um **círculo de luz**: as imagens, os botões, os marcadores das listas e a moldura da galeria.
- **Cores:** água profunda (`#06121c`), luz bioluminescente verde-limão (`#dcf06a`) e a areia do leito do mar (`#e6dbc0`) na seção de cursos.
- **Tipografia:** Roundo, uma única família.
- **Imagens reais em domínio público:** gravuras do relatório da expedição H.M.S. Challenger (1872-1876, publicadas em 1887), que foi a primeira grande expedição científica ao fundo do mar; pranchas de Ernst Haeckel (*Kunstformen der Natur*, 1904); e um vídeo de água-viva filmada pela NOAA na Fossa das Marianas (2016). Os créditos estão no rodapé e em `assets/fotos/credits.json`.
- **Movimento:** a luz da página inicial se acende abrindo do centro; os blocos de cada seção sobem em sequência ao entrar na tela; a gravura flutua; a galeria troca de aluno sozinha (ou ao tocar); o vídeo roda em loop; as letras do rodapé sobem como um perfil do fundo do mar; as linhas das tabelas entram em sequência. Tudo é desligado para quem pede movimento reduzido no sistema.
- **Profundímetro:** na barra do topo, um medidor mostra a profundidade da "descida" conforme a página rola, de 0 a 4.000 m, com o nome de cada zona do oceano.

## 9. Recursos de segurança

- **Prepared statements em toda consulta que recebe dado externo:** `prepare()`, `bindValue()` com parâmetros nomeados e `execute()`. Nenhum valor do usuário é concatenado no SQL.
- **Ordenação por lista branca:** `ORDER BY` não aceita parâmetro, então o usuário escolhe uma chave (`nome_asc`, `recentes`...) e o PHP troca pelo trecho SQL fixo correspondente.
- **`LIMIT`/`OFFSET` como inteiros** (`PDO::PARAM_INT`), calculados no servidor.
- **Escape na saída:** todo dado exibido passa por `h()`, que usa `htmlspecialchars(..., ENT_QUOTES, "UTF-8")`. Um nome `<script>` aparece como texto.
- **Validação no servidor** antes de gravar (`trim`, `filter_var` com `FILTER_VALIDATE_INT` e `FILTER_VALIDATE_EMAIL`, listas fixas de departamentos e espécies, curso existente). O `novalidate` nos formulários deixa claro que a regra está no PHP, não no navegador.
- **Restrições no banco** como segunda barreira: `UNIQUE` em e-mail e nome do curso, `NOT NULL`, chave estrangeira.
- **Exclusão só por POST**, para que um link aberto por engano não apague dados.
- **Erros de banco tratados:** duplicidade vira mensagem amigável; outros erros não são escondidos.

## 10. Fluxo da aplicação

```text
Usuário preenche o formulário
        │  POST
        ▼
PHP recebe ($_POST) e aplica trim()
        │
        ▼
Validação (validarAluno / validarCurso)
        │
   ┌────┴─────┐
 inválido    válido
   │            │
   ▼            ▼
Mostra erros   prepare() → bindValue() → execute()
e mantém os        │
valores no         ▼
formulário     MySQL grava (INSERT/UPDATE/DELETE)
                   │
                   ▼
            Mensagem flash na sessão + redirecionamento
                   │
                   ▼
            Listagem exibe a mensagem uma vez
```

Na consulta, o caminho é: parâmetros da URL (`busca`, `curso`, `especie`, `ordem`, `pagina`) → validação de cada um → montagem do `WHERE` só com trechos fixos → `COUNT(*)` para saber o total de páginas → `SELECT ... LIMIT :limite OFFSET :offset` → tabela e links de paginação que repetem os mesmos parâmetros.
