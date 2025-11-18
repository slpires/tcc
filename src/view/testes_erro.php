<?php
/*
    /src/view/testes_erro.php
    [VIEW]
    Interface de ERRO do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades:
    - Exibir falhas na execução de um caso de teste automatizado.
    - Informar mensagem amigável e motivo técnico (quando disponível).
    - Oferecer retorno simples ao catálogo de testes.
    - Manter identidade visual e rodapé padronizados com os demais módulos.
*/

/* [INCLUSÃO] Verificação de permissão de acesso ao módulo (perfil administrador) */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos dinâmicos ($base_url, $action_base, $url_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Variáveis esperadas do controller (fallback seguro) */
$titulo_pagina  = isset($titulo_pagina) ? (string) $titulo_pagina : 'Módulo de Testes – Erro na Execução';
$erro_execucao  = isset($erro_execucao) && is_array($erro_execucao) ? $erro_execucao : [];
$erro           = isset($erro) ? $erro : '';

/* Helper simples para escapar saída em HTML */
if (!function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/* Extração segura dos campos esperados no array de erro */
$codigo_teste = $erro_execucao['codigo_teste'] ?? '';
$mensagem     = $erro_execucao['mensagem']     ?? 'Não foi possível executar o teste selecionado.';
$detalhe      = $erro_execucao['detalhe']      ?? '';

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

    <!-- Mensagens institucionais (erro/sucesso/alerta) -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <!-- Cabeçalho padrão do módulo -->
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Módulo de Testes – Erro na Execução</div>
    <div class="app-desc">
      Ocorreu um problema ao tentar executar o caso de teste automatizado.
    </div>

    <?php if (!empty($erro)): ?>
      <div class="alert-erro" role="alert" style="margin-top:1em;">
        <?= e($erro) ?>
      </div>
    <?php endif; ?>

    <!-- Mensagem amigável -->
    <div class="alert-erro" role="alert" style="margin-top:1.5em;">
      <?= e($mensagem) ?>
      <?php if ($codigo_teste !== ''): ?>
        <br><small>Código do teste: <?= e($codigo_teste) ?></small>
      <?php endif; ?>
    </div>

    <!-- Motivo técnico (quando disponível) -->
    <?php if ($detalhe !== ''): ?>
      <section class="app-section" style="margin-top:1.5em; margin-bottom:1.5em;">
        <h2 class="app-subtitle">Detalhes técnicos</h2>
        <pre class="app-code-block" style="margin-top:0.8em;"><?php
            echo e($detalhe);
        ?></pre>
      </section>
    <?php else: ?>
      <p style="margin-top:1.5em; margin-bottom:1.5em;">
        Nenhum detalhe técnico adicional foi fornecido para esta execução.
      </p>
    <?php endif; ?>

    <!-- Ação: voltar ao catálogo de testes -->
    <div class="app-actions" style="margin-bottom:1.5em;">
      <?php $urlVoltar = $action_base . '?r=testes'; ?>
      <a class="btn" href="<?= e($urlVoltar) ?>">OK, voltar ao catálogo</a>
    </div>

    <!-- Rodapé institucional do usuário logado (inclui botão Sair do Sistema) -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>

  </div>
</body>
</html>
