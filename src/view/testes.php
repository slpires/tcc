<?php
/*
    /src/view/testes.php
    [VIEW]
    Interface simplificada do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades após simplificação:
    - Exibir apenas o filtro "Código do teste";
    - Exibir descrição detalhada do teste selecionado;
    - Permitir execução individual do teste;
    - Manter cabeçalho e rodapé institucionais padronizados.
*/

/* [INCLUSÃO] Verificação de permissão de acesso ao módulo */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos dinâmicos ($base_url, $action_base, $url_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Variáveis esperadas do controller */
$titulo_pagina     = isset($titulo_pagina)     ? (string) $titulo_pagina     : 'Módulo de Testes – Catálogo';
$mensagem_execucao = isset($mensagem_execucao) ? (string) $mensagem_execucao : '';
$erro              = isset($erro)              ? $erro                       : '';
$filtro            = isset($filtro) && is_array($filtro) ? $filtro           : [];
$codigos_teste     = isset($codigos_teste) && is_array($codigos_teste) ? $codigos_teste : [];
$testes            = isset($testes) && is_array($testes) ? $testes           : [];

// Pode ou não vir do controller; aqui garantimos um fallback seguro
$teste_selecionado = (isset($teste_selecionado) && is_array($teste_selecionado))
    ? $teste_selecionado
    : null;

/* Caminho base */
$action_base = isset($action_base) ? (string) $action_base : 'index.php';

/* Função auxiliar de escapamento */
if (!function_exists('e')) {
    function e(?string $v): string {
        return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/* [LÓGICA DE SELEÇÃO] Garante que sempre haja um teste selecionado se existir catálogo */
$codSel = $filtro['cod_teste'] ?? '';

if ($teste_selecionado === null && !empty($testes)) {
    // 1) tenta casar pelo cod_teste vindo do filtro
    if ($codSel !== '') {
        foreach ($testes as $t) {
            if (isset($t['cod_teste']) && $t['cod_teste'] === $codSel) {
                $teste_selecionado = $t;
                break;
            }
        }
    }

    // 2) fallback: primeiro da lista
    if ($teste_selecionado === null) {
        $teste_selecionado = $testes[0];
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
    /* [AJUSTE-FAVICON]
       Padronização da resolução de caminhos de assets (CSS/IMG) para compatibilidade
       entre DEV (XAMPP) e PRD (HostGator), mesmo se $base_url vier vazio. */
    if (isset($base_url) && $base_url !== '') {
        $assetBaseCss = rtrim($base_url, '/') . '/css';
        $assetBaseImg = rtrim($base_url, '/') . '/img';
    } else {
        $assetBaseCss = 'css';
        $assetBaseImg = 'img';
    }

    $cssHref       = htmlspecialchars($assetBaseCss . '/style.css', ENT_QUOTES, 'UTF-8');
    $favicon16Href = htmlspecialchars($assetBaseImg . '/favicon-16x16.png', ENT_QUOTES, 'UTF-8');
    $favicon32Href = htmlspecialchars($assetBaseImg . '/favicon-32x32.png', ENT_QUOTES, 'UTF-8');
    $faviconIco    = htmlspecialchars($assetBaseImg . '/favicon.ico', ENT_QUOTES, 'UTF-8');
    $appleTouch    = htmlspecialchars($assetBaseImg . '/apple-touch-icon.png', ENT_QUOTES, 'UTF-8');
    $android192    = htmlspecialchars($assetBaseImg . '/android-chrome-192x192.png', ENT_QUOTES, 'UTF-8');
    $android512    = htmlspecialchars($assetBaseImg . '/android-chrome-512x512.png', ENT_QUOTES, 'UTF-8');
    $manifestHref  = htmlspecialchars($assetBaseImg . '/site.webmanifest', ENT_QUOTES, 'UTF-8');
    ?>

    <!-- [INCLUSÃO] Favicon e Favibar padronizados -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $favicon32Href ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $favicon16Href ?>">
    <link rel="shortcut icon" href="<?= $faviconIco ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $appleTouch ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $android192 ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= $android512 ?>">
    <link rel="manifest" href="<?= $manifestHref ?>">

    <!-- [INCLUSÃO] CSS institucional -->
    <link rel="stylesheet" href="<?= $cssHref ?>">
</head>

<body class="sistema-bg">
<div class="sistema-container app">

  <!-- Mensagens institucionais -->
  <?php include __DIR__ . '/componentes/mensagens.php'; ?>

  <!-- Cabeçalho padrão -->
  <div class="app-logo">Slpires.COM</div>
  <div class="app-title">Módulo de Testes – Catálogo</div>
  <div class="app-desc">
    Escolha um dos testes listados abaixo.
  </div>

  <!-- Mensagens pós-execução -->
  <?php if (!empty($mensagem_execucao)): ?>
    <p class="app-info"><?= e($mensagem_execucao) ?></p>
  <?php endif; ?>

  <!-- Erros -->
  <?php if (!empty($erro)): ?>
    <div class="alert-erro" role="alert"><?= e($erro) ?></div>
  <?php endif; ?>

  <!-- FORMULÁRIO PRINCIPAL -->
  <form method="get" action="<?= e($action_base) ?>" class="app-form" style="margin-top:1.5em;">

    <input type="hidden" name="r" value="testes">

    <!-- ÚNICO FILTRO: CÓDIGO DO TESTE -->
    <!-- [AJUSTE] Comentário movido para fora do atributo para evitar HTML inválido -->
    <div class="form-row">
      <label for="cod_teste"><strong>Código do teste</strong></label><br>
      <select
        id="cod_teste"
        name="cod_teste"
        onchange="this.form.submit()"
      >
        <?php foreach ($codigos_teste as $codigo): ?>
          <option value="<?= e($codigo) ?>" <?= $codSel === $codigo ? 'selected' : '' ?>>
            <?= e($codigo) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <?php if (!empty($teste_selecionado) && !empty($teste_selecionado['id_teste'])): ?>

      <!-- LISTA AMIGÁVEL DE DETALHES DO TESTE -->
      <ul class="teste-detalhes" style="margin-top:1em; padding-left:1.2em; line-height:1.5; text-align:left;">
        <li><strong>Código do teste:</strong> <em><?= e($teste_selecionado['cod_teste'] ?? '') ?></em></li>
        <li><strong>Módulo:</strong> <em><?= e($teste_selecionado['modulo'] ?? '') ?></em></li>
        <li><strong>Cenário:</strong> <em><?= e($teste_selecionado['cenario'] ?? '') ?></em></li>
        <li><strong>Prioridade:</strong> <em><?= e($teste_selecionado['prioridade'] ?? '') ?></em></li>
        <li><strong>Descrição do teste:</strong> <em><?= e($teste_selecionado['descricao_teste'] ?? '') ?></em></li>
        <li><strong>Tipo do teste:</strong> <em><?= e($teste_selecionado['tipo_teste'] ?? '') ?></em></li>
        <li><strong>Status no catálogo:</strong> <em><?= e($teste_selecionado['status_teste'] ?? '') ?></em></li>
        <li><strong>Executado em:</strong> <em><?= e($teste_selecionado['executado_em'] ?? '') ?></em></li>
        <li><strong>Mensagem da última execução:</strong> <em><?= e($teste_selecionado['mensagem'] ?? '') ?></em></li>
      </ul>

      <!-- BOTÃO: EXECUTAR TESTE -->
      <div style="margin-top:1.5em; text-align:center;">
        <a
          class="btn"
          style="display:inline-block; width:100%;"
          href="<?= e($action_base) ?>?r=testes&amp;acao=run&amp;id_teste=<?= urlencode((string)$teste_selecionado['id_teste']) ?>"
        >
          Executar teste
        </a>
      </div>

    <?php else: ?>

      <p class="app-info" style="margin-top:1em; text-align:center;">
        Nenhum teste encontrado no catálogo.
      </p>

    <?php endif; ?>

  </form>

  <!-- Rodapé institucional -->
  <?php require_once __DIR__ . '/rodape_usuario.php'; ?>

</div>
</body>
</html>
