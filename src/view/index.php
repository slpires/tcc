<?php
/*
    /src/view/index.php
    [INCLUSÃO]
    View de seleção de perfil – ponto de entrada interno do sistema após landing page.
    Inclui paths dinâmicos, mensagens institucionais e formulário de escolha de perfil,
    conforme padrões definidos no template institucional do projeto.
    Recebe o array $perfis_validos preparado pelo controller.
*/

// [INCLUSÃO] Carrega caminhos institucionais para assets e controllers
require_once __DIR__ . '/../../config/paths.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>SLPIRES.COM – Sistema de Recuperação de Créditos | Seleção de Perfil de Acesso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Ambiente de demonstração do sistema – Escolha de perfil de acesso | Sistema TCC SLPIRES.COM">
  <meta name="author" content="Sérgio Luís de Oliveira Pires">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#45763f">

  <!-- [INCLUSÃO] Todos os caminhos de assets usam o $base_url dinâmico -->
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
    <div class="app-title">Bem-vindo ao Sistema</div>
    <div class="app-desc">
      Selecione abaixo o perfil desejado para testar as funcionalidades:
    </div>

    <!-- [INCLUSÃO] Formulário de seleção de perfil -->
    <form method="post" action="<?= $controller_url ?>/selecao_perfil.php" class="app-btn-group" style="margin-top:1.5em;">
      <?php foreach ($perfis_validos as $perfil): ?>
        <button class="app-btn" name="perfil" value="<?= htmlspecialchars($perfil) ?>" type="submit">
          <?= htmlspecialchars($perfil) ?>
        </button>
      <?php endforeach; ?>
    </form>
    <!-- Fim do bloco de seleção de perfil -->

    <!-- Rodapé institucional dentro do container -->
    <footer class="footer-version app-footer">
      <small>
        © 2025 – Sistema desenvolvido como Prova de Conceito no âmbito do TCC do curso de Tecnologia em Sistemas de Computação – UFF.<br>
        Autor: <strong>Sérgio Luís de Oliveira Pires</strong><br>
        Orientador: <strong>Prof. Leandro Soares de Sousa</strong>
      </small>
    </footer>
  </div>

</body>
</html>
