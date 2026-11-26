# 9. Segurança

- **Prepared statements em toda consulta que recebe dado externo:** `prepare()`, `bindValue()` com parâmetros nomeados e `execute()`. Nenhum valor do usuário é concatenado no SQL.
- **Ordenação por lista branca:** `ORDER BY` não aceita parâmetro, então o usuário escolhe uma chave (`nome_asc`, `recentes`...) e o PHP troca pelo trecho SQL fixo correspondente.
- **`LIMIT`/`OFFSET` como inteiros** (`PDO::PARAM_INT`), calculados no servidor.
- **Escape na saída:** todo dado exibido passa por `h()`, que usa `htmlspecialchars(..., ENT_QUOTES, "UTF-8")`. Um nome `<script>` aparece como texto.
- **Validação no servidor** antes de gravar (`trim`, `filter_var` com `FILTER_VALIDATE_INT` e `FILTER_VALIDATE_EMAIL`, listas fixas de departamentos e espécies, curso existente). O `novalidate` nos formulários deixa claro que a regra está no PHP, não no navegador.
- **Restrições no banco** como segunda barreira: `UNIQUE` em e-mail e nome do curso, `NOT NULL`, chave estrangeira.
- **Exclusão só por POST**, para que um link aberto por engano não apague dados.
- **Erros de banco tratados:** duplicidade vira mensagem amigável; outros erros não são escondidos.
- **API com as mesmas proteções:** prepared statements, validação no servidor e respostas de erro que não expõem detalhes internos (o erro técnico vai para o log).
- **Swagger UI com Subresource Integrity:** os arquivos do CDN são carregados com `integrity` (SHA-384); se forem adulterados, o navegador não executa.

**Limitação conhecida:** o sistema não tem login, como nas telas do projeto. Em produção, a API e os CRUDs precisariam de autenticação (sessão para as telas e token para a API).

---

← [Identidade visual](08-identidade-visual.md) · [Índice](README.md) · [Testes](10-testes.md) →
