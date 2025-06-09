<?php
/*
    /src/view/simulacao_folha.php
    [INCLUSÃO]
    View do módulo "Simulação da Folha de Pagamento" – Sistema SLPIRES.COM (TCC UFF).
    Inclui validação de permissão, paths dinâmicos e layout institucional.
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
  <title>Simulação da Folha | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- [INCLUSÃO] CSS institucional unificado -->
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <!-- [INCLUSÃO] Logotipo institucional -->
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Simulação da Folha de Pagamento</div>
    <div class="app-desc" style="margin-bottom: 2.5em;">
      Você está na página do módulo <strong>Simulação da Folha de Pagamento</strong>.<br>
      (Conteúdo do módulo será implementado aqui)
    </div>
    <!-- [INCLUSÃO] Rodapé dinâmico institucional -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
