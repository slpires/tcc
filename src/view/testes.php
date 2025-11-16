<?php
/*
    /src/view/testes.php
    [VIEW]
    Interface do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades:
    - Exibir o catálogo de casos de teste automatizados.
    - Permitir filtro por módulo, tipo de teste, prioridade, status e ativo/inativo.
    - Oferecer ações em lote: execução (runAll) e replay de reprovados.
    - Manter identidade visual e rodapé padronizados com os demais módulos.
*/

/* [INCLUSÃO] Verificação de permissão de acesso ao módulo (perfil administrador) */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos dinâmicos ($base_url, $action_base, $url_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Variáveis esperadas do controller (fallback seguro) */
$titulo_pagina     = isset($titulo_pagina) ? (string) $titulo_pagina : 'Módulo de Testes – Catálogo';
$mensagem_execucao = isset($mensagem_execucao) ? (string) $mensagem_execucao : '';
$erro              = isset($erro) ? $erro : '';
$filtro            = isset($filtro) && is_array($filtro) ? $filtro : [];
$testes            = isset($testes) && is_array($testes) ? $testes : [];

/* Helper simples para escapar saída em HTML */
if (!function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= e($titulo_pagina) ?> | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
    // [AJUSTE] Monta caminho robusto para o CSS
    // Preferência: $base_url vindo de paths.php
    if (isset($base_url) && $base_url !== '') {
        $cssHref = rtrim($base_url, '/') . '/css/style.css';
    } else {
        // Fallback relativo a /public/index.php
        $cssHref = 'css/style.css';
    }
    $cssHref = htmlspecialchars($cssHref, ENT_QUOTES, 'UTF-8');
  ?>

  <!-- [INCLUSÃO] CSS institucional unificado -->
  <link rel="stylesheet" href="<?= $cssHref ?>">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">

    <!-- Mensagens institucionais (erro/sucesso/alerta) -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <!-- Cabeçalho padrão do módulo -->
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Módulo de Testes – Catálogo</div>
    <div class="app-desc">
      Catálogo de casos de teste automatizados do sistema.<br>
      Utilize os filtros abaixo para localizar e executar os testes desejados.
    </div>

    <?php if (!empty($mensagem_execucao)): ?>
      <p class="app-info"><?= e($mensagem_execucao) ?></p>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
      <div class="alert-erro" role="alert">
        <?= e($erro) ?>
      </div>
    <?php endif; ?>

    <!-- FORMULÁRIO DE FILTROS -->
    <form method="get" action="<?= $action_base ?>" class="app-form" style="margin-top:1.5em; margin-bottom:1.5em;">
      <!-- Rota fixa do módulo TESTES -->
      <input type="hidden" name="r" value="testes">

      <!-- MÓDULO (domínio fechado) -->
      <div class="form-row">
        <label for="modulo"><strong>Módulo</strong></label><br>
        <?php $modSel = $filtro['modulo'] ?? ''; ?>
        <select id="modulo" name="modulo">
          <option value=""  <?= $modSel === ''  ? 'selected' : '' ?>>(Todos)</option>
          <option value="FP" <?= $modSel === 'FP' ? 'selected' : '' ?>>FP – Simulação da Folha</option>
          <option value="CC" <?= $modSel === 'CC' ? 'selected' : '' ?>>CC – Controle de Créditos</option>
          <option value="RE" <?= $modSel === 'RE' ? 'selected' : '' ?>>RE – Relatórios</option>
          <option value="TE" <?= $modSel === 'TE' ? 'selected' : '' ?>>TE – Testes (internos)</option>
        </select>
      </div>

      <!-- TIPO DE TESTE (unitario / integrado / exibicao) -->
      <div class="form-row">
        <label for="tipo_teste"><strong>Tipo de teste</strong></label><br>
        <?php $tipoSel = $filtro['tipo_teste'] ?? ''; ?>
        <select id="tipo_teste" name="tipo_teste">
          <option value=""           <?= $tipoSel === ''           ? 'selected' : '' ?>>(Todos)</option>
          <option value="unitario"   <?= $tipoSel === 'unitario'   ? 'selected' : '' ?>>Unitário</option>
          <option value="integrado"  <?= $tipoSel === 'integrado'  ? 'selected' : '' ?>>Integrado</option>
          <option value="exibicao"   <?= $tipoSel === 'exibicao'   ? 'selected' : '' ?>>Exibição</option>
        </select>
      </div>

      <div class="form-row">
        <label for="prioridade"><strong>Prioridade</strong></label><br>
        <select id="prioridade" name="prioridade">
          <option value="">(Todas)</option>
          <option value="alta"  <?= isset($filtro['prioridade']) && $filtro['prioridade'] === 'alta'  ? 'selected' : '' ?>>Alta</option>
          <option value="media" <?= isset($filtro['prioridade']) && $filtro['prioridade'] === 'media' ? 'selected' : '' ?>>Média</option>
          <option value="baixa" <?= isset($filtro['prioridade']) && $filtro['prioridade'] === 'baixa' ? 'selected' : '' ?>>Baixa</option>
        </select>
      </div>

      <div class="form-row">
        <label for="status_teste"><strong>Status catálogo</strong></label><br>
        <select id="status_teste" name="status_teste">
          <option value="">(Todos)</option>
          <option value="nao_exec"   <?= isset($filtro['status_teste']) && $filtro['status_teste'] === 'nao_exec'   ? 'selected' : '' ?>>Não executado</option>
          <option value="aprovado"   <?= isset($filtro['status_teste']) && $filtro['status_teste'] === 'aprovado'   ? 'selected' : '' ?>>Aprovado</option>
          <option value="reprovado"  <?= isset($filtro['status_teste']) && $filtro['status_teste'] === 'reprovado'  ? 'selected' : '' ?>>Reprovado</option>
          <option value="indefinido" <?= isset($filtro['status_teste']) && $filtro['status_teste'] === 'indefinido' ? 'selected' : '' ?>>Indefinido</option>
        </select>
      </div>

      <div class="form-row">
        <label for="ativo"><strong>Ativo</strong></label><br>
        <select id="ativo" name="ativo">
          <?php $ativo = $filtro['ativo'] ?? ''; ?>
          <option value=""  <?= $ativo === ''  ? 'selected' : '' ?>>(Todos)</option>
          <option value="1" <?= $ativo === '1' ? 'selected' : '' ?>>Somente ativos</option>
          <option value="0" <?= $ativo === '0' ? 'selected' : '' ?>>Somente inativos</option>
        </select>
      </div>

      <div class="form-row" style="margin-top:1.1em;">
        <button type="submit" class="btn">Aplicar filtros</button>
        <a class="btn" href="<?= $action_base ?>?r=testes">Limpar</a>
      </div>
    </form>

    <!-- AÇÕES EM LOTE (runAll / replay) -->
    <div class="app-btn-group" style="margin-bottom:1.5em;">
      <?php
        $modulo     = trim($filtro['modulo']     ?? '');
        $tipo_teste = trim($filtro['tipo_teste'] ?? '');
        $disabled   = ($modulo === '' || $tipo_teste === '');
      ?>
      <a
        class="btn<?= $disabled ? ' btn-disabled' : '' ?>"
        href="<?= $disabled ? '#' : $action_base . '?r=testes&acao=runAll&modulo=' . urlencode($modulo) . '&tipo_teste=' . urlencode($tipo_teste) ?>"
        <?= $disabled ? 'aria-disabled="true"' : '' ?>
      >
        Executar lote (máx. 50)
      </a>

      <a
        class="btn<?= $disabled ? ' btn-disabled' : '' ?>"
        href="<?= $disabled ? '#' : $action_base . '?r=testes&acao=replay&modulo=' . urlencode($modulo) . '&tipo_teste=' . urlencode($tipo_teste) ?>"
        <?= $disabled ? 'aria-disabled="true"' : '' ?>
      >
        Replay de reprovados
      </a>
    </div>

    <?php if (!empty($testes)): ?>
      <!-- TABELA DE RESULTADOS -->
      <table class="app-table" style="margin-top:1em;">
        <thead>
          <tr>
            <th>ID</th>
            <th>Módulo</th>
            <th>Cenário</th>
            <th>Tipo</th>
            <th>Prioridade</th>
            <th>Status</th>
            <th>Ativo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($testes as $t): ?>
          <tr>
            <td><?= e((string)($t['id_teste'] ?? '')) ?></td>
            <td><?= e($t['modulo']       ?? '') ?></td>
            <td><?= e($t['cenario']      ?? '') ?></td>
            <td><?= e($t['tipo_teste']   ?? '') ?></td>
            <td><?= e($t['prioridade']   ?? '') ?></td>
            <td><?= e($t['status_teste'] ?? '') ?></td>
            <td><?= isset($t['ativo']) && (int)$t['ativo'] === 1 ? 'Sim' : 'Não' ?></td>
            <td>
              <a
                class="btn"
                href="<?= $action_base ?>?r=testes&acao=run&id_teste=<?= urlencode((string)($t['id_teste'] ?? '')) ?>"
              >
                Executar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="margin-top:1.5em;">
        Nenhum caso de teste encontrado para os critérios informados.
      </p>
    <?php endif; ?>

    <!-- Rodapé institucional do usuário logado (inclui botão Sair do Sistema) -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>

  </div>
</body>
</html>
