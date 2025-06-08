<?php
// /src/view/relatorios.php
require_once __DIR__ . '/../controller/verificar_permissao.php';
require_once __DIR__ . '/../../config/paths.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Relatórios | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Relatórios</div>
    <div class="app-desc" style="margin-bottom: 2.5em;">
      Você está na página do módulo <strong>Relatórios</strong>.<br>
      (Conteúdo do módulo será implementado aqui)
    </div>
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
