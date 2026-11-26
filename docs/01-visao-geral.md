# 1. Visão geral

## Identificação

- **Universidade:** Universidade Abissal
- **Tema:** uma universidade no fundo do oceano, com campus a 4.000 metros de profundidade, cursos para criaturas marinhas e professores polvos, lulas e baleias.
- **Disciplina:** Tecnologias para Internet, Senac São José dos Pinhais (ADS).

## O que é o sistema

O site apresenta a universidade (página inicial e página institucional) e oferece à secretaria acadêmica dois cadastros ligados ao MySQL: **Cursos** e **Alunos**. Cada aluno pertence a um curso.

Além das telas, o sistema oferece um **dashboard** com os indicadores da universidade e uma **API REST** documentada com Swagger, que expõe os mesmos dados para outros sistemas.

## Problema que o sistema resolve

A secretaria da universidade controlava matrículas em placas de coral riscadas a tentáculo. O sistema centraliza cursos e alunos num banco de dados, impede dados inválidos (e-mail sem formato, idade negativa, curso inexistente) e permite localizar rapidamente qualquer aluno por nome, e-mail, curso ou espécie.

## Público

| Quem | O que faz no sistema |
|---|---|
| Visitante | Conhece a universidade (página inicial e institucional) |
| Secretaria acadêmica | Cadastra e mantém cursos e alunos |
| Direção | Acompanha os indicadores no dashboard |
| Outros sistemas | Consultam e alteram dados pela API REST |

---

[Índice](README.md) · [Requisitos](02-requisitos.md) →
