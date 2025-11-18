<?php
/*
    /src/view/relatorios.php
    [INCLUSÃO]
    View do módulo de Relatórios.
    Realiza verificação de permissão e inclui paths dinâmicos para assets institucionais.
*/

/* [INCLUSÃO] Verifica permissão de acesso ao módulo */
require_once __DIR__ . '/../controller/verificar_permissao.php';
/* [INCLUSÃO] Caminho base dinâmico para assets e controllers */
require_once __DIR__ . '/../../config/paths.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Relatórios | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- [INCLUSÃO] Favicon e Favibar -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base_url ?>/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $base_url ?>/img/favicon-16x16.png">
  <link rel="shortcut icon" href="<?= $base_url ?>/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $base_url ?>/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $base_url ?>/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= $base_url ?>/img/android-chrome-512x512.png">
  <link rel="manifest" href="<?= $base_url ?>/img/site.webmanifest">
  
  <!-- [INCLUSÃO] CSS institucional unificado -->
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <!-- [INCLUSÃO] Exibição padronizada de mensagens institucionais -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Relatórios</div>
    <div class="app-desc" style="margin-bottom: 2.5em;">
      Você está na página do módulo <strong>Relatórios</strong>.<br>
      (Conteúdo do módulo será implementado aqui)
    </div>
    <!-- [INCLUSÃO] Rodapé institucional do usuário logado -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
