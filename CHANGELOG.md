# Changelog
Este arquivo registra a evolução real do projeto, seguindo o padrão
[Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/) e o
versionamento semântico (SemVer).

O projeto passou a adotar SemVer oficialmente a partir da versão
*v0.9.0*. Antes disso, o desenvolvimento seguiu ciclos naturais de
prototipação, correção, adaptação e sobrevivência — comuns em MVPs
críticos com pouco tempo, poucos recursos e complexidade crescente.

---

## [1.0.0] – 2025-11-18
### Adicionado
- Consolidação dos ajustes visuais finais do MVP.
- Preparação da interface para apresentação e demonstração à banca.

### Alterado
- Atualização da versão pública exibida na landing page e no README para `v1.0.0`.
- Polimento de textos institucionais e rótulos de botões nas views.

### Corrigido
- Pequenas inconsistências de alinhamento e espaçamento em páginas internas.

---

## [0.9.0] – 2025-11-18
### Adicionado
- Implementação integral do **Módulo Testes**, incluindo:
  - Criação de rotinas de teste,
  - Execução de cenários definidos no plano emergencial,
  - Registro estruturado em arquivos de log.
- Padronização interna de logs com o formato:
  `[AAAAMMDD_hhmmss]: mensagem`.
- Inclusão do padrão `campo=valor;campo=valor;...` para monitoramento.
- Padronização da identidade visual:
  - Cabeçalho institucional (favicon + CSS unificado) incluído nas views.
- Atualização das diretrizes e templates oficiais do projeto.

### Alterado
- Views revisadas para manter identidade visual consistente.
- Fluxos internos ajustados para garantir estabilidade mínima em DEV e PRD.

### Corrigido
- Problemas de sincronização entre DEV e PRD detectados nas últimas
  semanas de desenvolvimento sob forte pressão de prazo.
- Inconsistências de exibição e carregamento que surgiram ao integrar
  módulos independentes escritos em momentos diferentes do projeto.

### Observações
Esta versão marca:
- a estabilização do MVP,
- o início do uso oficial de SemVer,
- e o fechamento de uma das fases mais desafiadoras do projeto.

Desenvolver este sistema exigiu muito mais que código:
exigiu resiliência, aprendizado acelerado, reconstrução de decisões,
refatorações necessárias e disciplina para transformar caos em algo
funcional — mesmo com tempo quase inexistente.

---

## Histórico anterior ao SemVer
Antes da versão *0.9.0*, o processo de desenvolvimento acompanhou
a realidade de um projeto acadêmico complexo, construído de forma
incremental, com vários ciclos de tentativa e erro. Alguns marcos:

- Integração inicial entre **SIMULACAO_FOLHA** e **CONTROLE_CREDITO**;
- Ajustes estruturais para garantir funcionamento mínimo em ambiente
  compartilhado;
- Correções emergenciais para unificar DEV/PRD;
- Ajustes de deploy, htaccess e front-controller;
- Refino incremental de funcionalidades críticas.

Não houve versionamento formal até aqui. Houve algo mais importante:
**evolução real**.

---

## [Unreleased]
### Planejado
- Finalização da integração entre SIMULACAO_FOLHA ↔ CONTROLE_CREDITO.
- Ajustes de UX e polimento final das interfaces.
- Revisão final de consistência antes da versão *1.0.0*.

---

