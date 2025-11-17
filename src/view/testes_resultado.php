<?php
/*
    /src/view/testes_resultado.php
    [VIEW]
    Interface de RESULTADO do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades:
    - Exibir o resultado detalhado da execução de um caso de teste automatizado.
    - Apresentar código, objetivo, resumo da execução, itens verificados.
    - Organizar os dados em estrutura amigável para dispositivos móveis
      (tabelas transpostas para metadados e resumo).
*/

/* [INCLUSÃO] Verificação de permissão de acesso ao módulo (perfil administrador) */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos dinâmicos ($base_url, $action_base, $url_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Variáveis esperadas do controller (fallback seguro) */
$titulo_pagina = isset($titulo_pagina) ? (string) $titulo_pagina : 'Módulo de Testes – Resultado';
$resultado     = isset($resultado) && is_array($resultado) ? $resultado : [];
$erro          = isset($erro) ? (string) $erro : '';

/* Dados opcionais extras (se o controller enviar) */
$caso          = isset($caso) && is_array($caso) ? $caso : [];
$entrada_json  = isset($entrada_json) ? (string) $entrada_json : '';
$esperado_json = isset($esperado_json) ? (string) $esperado_json : '';

/* Helper simples para escapar saída em HTML */
if (!function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/* Helper simples SIM/NÃO */
if (!function_exists('e_sim_nao')) {
    function e_sim_nao($v): string
    {
        return ((int)$v === 1) ? 'Sim' : 'Não';
    }
}

/* Extração segura dos campos esperados no array de resultado */
$codigo_teste = $resultado['codigo_teste'] ?? '';
$rotulo       = $resultado['rotulo']       ?? $codigo_teste;
$titulo_teste = $resultado['titulo']       ?? '';
$objetivo     = $resultado['objetivo']     ?? '';
$ok           = array_key_exists('ok', $resultado) ? (bool) $resultado['ok'] : null;
$mensagem     = $resultado['mensagem']     ?? '';

$inputs   = isset($resultado['inputs'])   && is_array($resultado['inputs'])   ? $resultado['inputs']   : [];
$expected = isset($resultado['expected']) && is_array($resultado['expected']) ? $resultado['expected'] : [];
$items    = isset($resultado['items'])    && is_array($resultado['items'])    ? $resultado['items']    : [];

$summary         = $resultado['summary']         ?? '';
$status_execucao = $resultado['status_execucao'] ?? '';
$duracao_ms      = $resultado['duracao_ms']      ?? 0;
$dry_run         = $resultado['dry_run']         ?? 1;

/* Metadados adicionais a partir do caso (se enviados) */
$modulo     = $caso['modulo']          ?? '';
$cenario    = $caso['cenario']         ?? '';
$prioridade = $caso['prioridade']      ?? '';
$tipo_teste = $caso['tipo_teste']      ?? '';
$descricao  = $caso['descricao_teste'] ?? '';

/* Caminho base para ações (fallback simples) */
$action_base = isset($action_base) ? (string) $action_base : 'index.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= e($titulo_pagina) ?> | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
    // Monta caminho robusto para o CSS (mesma lógica da view principal)
    if (isset($base_url) && $base_url !== '') {
        $cssHref = rtrim($base_url, '/') . '/css/style.css';
    } else {
        $cssHref = 'css/style.css';
    }
    $cssHref = htmlspecialchars($cssHref, ENT_QUOTES, 'UTF-8');
  ?>

  <link rel="stylesheet" href="<?= $cssHref ?>">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">

    <!-- Cabeçalho padrão do módulo -->
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Módulo de Testes – Resultado da Execução</div>
    <div class="app-desc">
      Resultado detalhado do caso de teste escolhido.
    </div>

    <!-- Ação: voltar ao catálogo de testes -->
    <div style="margin-top:0.5em; margin-bottom:1em;">
      <?php $urlVoltar = $action_base . '?r=testes'; ?>
      <a class="btn" href="<?= e($urlVoltar) ?>">&larr; Voltar ao catálogo</a>
    </div>

    <!-- Mensagens institucionais (erro/sucesso/alerta) -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <?php if (!empty($erro)): ?>
      <div class="alert-erro" role="alert" style="margin-top:1em;">
        <?= e($erro) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($resultado)): ?>

      <!-- Resumo do status geral (alerta compacto) -->
      <?php if ($ok === true): ?>
        <p class="app-info" style="margin-top:0.8em;">
          Execução concluída com sucesso.
        </p>
      <?php elseif ($ok === false): ?>
        <div class="alert-erro" role="alert" style="margin-top:0.8em;">
          Falha na execução do teste.
          <?php if ($mensagem !== ''): ?>
            <?= ' ' . e($mensagem) ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- BLOCO 1: Informações do teste (tabela transposta) -->
      <section class="app-section" style="margin-top:1.2em;">
        <h2 class="app-subtitle">Informações do teste</h2>
        <table class="app-table app-table-compact" style="margin-top:0.8em;">
          <tbody>
            <tr>
              <th style="width:30%;">Código</th>
              <td><?= e($rotulo) ?></td>
            </tr>
            <tr>
              <th>Título</th>
              <td><?= e($titulo_teste) ?></td>
            </tr>
            <tr>
              <th>Objetivo</th>
              <td><?= e($objetivo) ?></td>
            </tr>
            <?php if ($modulo !== '' || $cenario !== '' || $prioridade !== '' || $tipo_teste !== ''): ?>
              <tr>
                <th>Módulo</th>
                <td><?= e($modulo) ?></td>
              </tr>
              <tr>
                <th>Cenário</th>
                <td><?= e($cenario) ?></td>
              </tr>
              <tr>
                <th>Prioridade</th>
                <td><?= e($prioridade) ?></td>
              </tr>
              <tr>
                <th>Tipo de teste</th>
                <td><?= e($tipo_teste) ?></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>

      <!-- BLOCO 2: Resumo da execução (tabela transposta, foco em mobile) -->
      <section class="app-section" style="margin-top:1.5em;">
        <h2 class="app-subtitle">Resumo da execução</h2>
        <table class="app-table app-table-compact" style="margin-top:0.8em;">
          <tbody>
            <tr>
              <th style="width:30%;">Status de execução</th>
              <td><?= e($status_execucao) ?></td>
            </tr>
            <tr>
              <th>Resultado geral</th>
              <td>
                <?php
                  if ($ok === true) {
                      echo 'Aprovado (PASS)';
                  } elseif ($ok === false) {
                      echo 'Reprovado/Erro';
                  } else {
                      echo 'Indeterminado';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <th>Tempo de execução (ms)</th>
              <td><?= e((string) $duracao_ms) ?></td>
            </tr>
            <tr>
              <th>Dry-run</th>
              <td><?= e_sim_nao($dry_run) ?></td>
            </tr>
            <?php if ($summary !== ''): ?>
              <tr>
                <th>Síntese</th>
                <td><?= e($summary) ?></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>

      <!-- BLOCO 3: Itens verificados pelo adapter -->
      <section class="app-section" style="margin-top:1.5em;">
        <h2 class="app-subtitle">Itens verificados</h2>

        <?php if (!empty($items)): ?>
          <table class="app-table" style="margin-top:0.8em;">
            <thead>
              <tr>
                <th style="width:8%;">#</th>
                <th>Descrição</th>
                <th style="width:18%;">Resultado</th>
                <th style="width:34%;">Detalhe</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $idx = 1;
              foreach ($items as $item):
                  $desc    = isset($item['descricao']) ? (string) $item['descricao'] : '';
                  $itemOk  = isset($item['ok']) ? (bool) $item['ok'] : null;
                  $detalhe = isset($item['mensagem'])
                      ? (string) $item['mensagem']
                      : (string)($item['detalhe'] ?? '');
            ?>
              <tr>
                <td><?= $idx ?></td>
                <td><?= e($desc) ?></td>
                <td>
                  <?php if ($itemOk === true): ?>
                    OK
                  <?php elseif ($itemOk === false): ?>
                    FALHA
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td><?= e($detalhe) ?></td>
              </tr>
            <?php
              $idx++;
              endforeach;
            ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="margin-top:0.8em;">
            Não há itens individuais registrados para este teste.
          </p>
        <?php endif; ?>
      </section>

      <!-- BLOCO 4: Entradas e saídas esperadas (visão técnica) -->
      <section class="app-section" style="margin-top:1.5em; margin-bottom:1.5em;">
        <h2 class="app-subtitle">Entradas e saídas esperadas</h2>

        <!-- Entradas -->
        <h3 class="app-subtitle" style="font-size:0.95em; margin-top:0.8em;">Entradas utilizadas</h3>
        <?php if (!empty($inputs)): ?>
          <pre class="app-code-block" style="margin-top:0.4em;"><?php
              echo e(print_r($inputs, true));
          ?></pre>
        <?php else: ?>
          <p style="margin-top:0.4em;">
            Não foram registradas entradas específicas para este teste.
          </p>
        <?php endif; ?>

        <!-- Saídas esperadas -->
        <h3 class="app-subtitle" style="font-size:0.95em; margin-top:1em;">Saídas esperadas</h3>
        <?php if (!empty($expected)): ?>
          <pre class="app-code-block" style="margin-top:0.4em;"><?php
              echo e(print_r($expected, true));
          ?></pre>
        <?php else: ?>
          <p style="margin-top:0.4em;">
            Não há saídas esperadas detalhadas registradas para este teste.
          </p>
        <?php endif; ?>

      </section>

    <?php else: ?>
      <!-- Caso extremo: view chamada sem $resultado válido -->
      <div class="alert-erro" role="alert" style="margin-top:1.5em;">
        Não foram encontrados dados de resultado para o teste informado.
      </div>
    <?php endif; ?>

    <!-- Rodapé institucional do usuário logado (inclui botão Sair do Sistema) -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>

  </div>
</body>
</html>
