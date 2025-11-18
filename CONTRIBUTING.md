# Contributing Guidelines

Este documento estabelece o fluxo de contribuição, práticas de versionamento e padrões adotados no projeto.

## Fluxo de Trabalho

- Utilize branches nomeadas por padrão:
  - `feat/<nome>`
  - `fix/<nome>`
  - `chore/<nome>`
  - `refactor/<nome>`
  - `hotfix/<nome>`

## Mensagens de Commit

Use prefixos padronizados:
- `feat:` nova funcionalidade
- `fix:` correções
- `chore:` ajustes gerais
- `refactor:` melhorias internas
- `hotfix:` correção emergencial

## Pull Requests

- Sempre abrir PR direcionada para `main`.
- Preencher o template de Pull Request.
- Atualizar `CHANGELOG.md` quando houver impacto funcional.

## Padrões Gerais

- Manter logs no padrão `[AAAAMMDD_hhmmss]: mensagem`.
- Não versionar dados sensíveis ou arquivos de diagnóstico.
- Respeitar a estrutura do MVP e os módulos existentes.

