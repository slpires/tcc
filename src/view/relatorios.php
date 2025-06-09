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
