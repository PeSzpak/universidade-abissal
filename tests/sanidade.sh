#!/bin/bash
# Testes de sanidade da Universidade Abissal contra o servidor em $BASE.
# Pesquisa e filtros esperam os dados de database/banco.sql; o resto vale para qualquer banco.
# Precisa de curl e jq. Uso: bash tests/sanidade.sh  (ou BASE=http://localhost/universidade-abissal bash tests/sanidade.sh)
BASE=${BASE:-http://localhost:8000}
API=$BASE/api/index.php
ok=0; falha=0
check() { # nome, esperado, obtido
  if [ "$2" = "$3" ]; then ok=$((ok+1)); echo "OK    $1 ($3)"; else falha=$((falha+1)); echo "FALHA $1 (esperado $2, veio $3)"; fi
}
code() { curl -s -o /dev/null -w '%{http_code}' "$@"; }

for p in index.php institucional.php cursos/listar.php alunos/listar.php alunos/cadastrar.php dashboard.php api/docs.html api/openapi.yaml; do
  check "página $p" 200 "$(code "$BASE/$p")"
done
check "pesquisa 'polv'" 1 "$(curl -s "$API/alunos?busca=polv" | jq '.paginacao.total')"
check "filtro espécie Polvo" 3 "$(curl -s "$API/alunos?especie=Polvo" | jq '.paginacao.total')"
check "paginação 2 por página" 2 "$(curl -s "$API/alunos?por_pagina=2" | jq '.dados | length')"
antes=$(curl -s "$API/alunos" | jq '.paginacao.total')
check "dashboard bate com a listagem" "$antes" "$(curl -s "$API/dashboard" | jq '.totais.alunos')"
check "curso inexistente" 404 "$(code "$API/cursos/99")"
check "JSON quebrado" 400 "$(code -X POST -H 'Content-Type: application/json' -d '{oops' "$API/cursos")"
check "curso inválido" 422 "$(code -X POST -H 'Content-Type: application/json' -d '{}' "$API/cursos")"
check "método não permitido" 405 "$(code -X PATCH "$API/cursos/1")"
check "excluir curso com alunos" 409 "$(code -X DELETE "$API/cursos/1")"
check "SQL injection na busca" 0 "$(curl -s -G "$API/alunos" --data-urlencode "busca=' OR 1=1 --" | jq '.paginacao.total')"
check "ordem maliciosa ignorada" 200 "$(code -G "$API/alunos" --data-urlencode "ordem=nome; DROP TABLE alunos")"
check "XSS escapado na tela" 0 "$(curl -s -G "$BASE/alunos/listar.php" --data-urlencode 'busca=<script>x</script>' | grep -c '<script>x')"

id=$(curl -s -X POST -H 'Content-Type: application/json' -d '{"nome":"Curso Sanidade","departamento":"Zona Abissal","duracao_semestres":2,"vagas":5,"descricao":"teste"}' "$API/cursos" | jq '.id')
check "criar curso" true "$([ "$id" != null ] && echo true)"
check "curso duplicado" 409 "$(code -X POST -H 'Content-Type: application/json' -d '{"nome":"Curso Sanidade","departamento":"Zona Abissal","duracao_semestres":2,"vagas":5}' "$API/cursos")"
check "excluir curso criado" 204 "$(code -X DELETE "$API/cursos/$id")"
check "banco intacto depois" "$antes" "$(curl -s "$API/dashboard" | jq '.totais.alunos')"

echo; echo "$ok passaram, $falha falharam"; [ $falha -eq 0 ]
