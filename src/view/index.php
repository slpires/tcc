<?php
// [INCLUSÃO]
// Bloco PHP reservado para futura lógica de mensagens de sessão, erros, etc. No MVP, pode permanecer vazio.

// [INCLUSÃO] Caminho base dinâmico para assets (CSS, imagens, JS), conforme ambiente (dev/prod)
require_once __DIR__ . '/../../config/paths.php';

// [BLOCO] Mensagem de erro para feedback ao usuário
$mensagem_erro = '';
if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'perfil_invalido') {
        $mensagem_erro = "Perfil selecionado inválido. Por favor, escolha uma opção válida.";
    }
    if ($_GET['erro'] === 'nao_encontrado') {
        $mensagem_erro = "Nenhum empregado encontrado para o perfil escolhido. Tente outro perfil ou consulte o administrador.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>SLPIRES.COM – APP | Seleção de Perfil de Acesso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Ambiente de demonstração do sistema – Escolha de perfil de acesso | Sistema TCC SLPIRES.COM">
  <meta name="author" content="Sérgio Luís de Oliveira Pires">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#45763f">

  <!-- Favicon e CSS institucional -->
  <!-- [ALTERAÇÃO] Todos os caminhos de assets usam agora o $base_url dinâmico -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base_url ?>/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $base_url ?>/img/favicon-16x16.png">
  <link rel="shortcut icon" href="<?= $base_url ?>/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $base_url ?>/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $base_url ?>/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= $base_url ?>/img/android-chrome-512x512.png">
  <link rel="manifest" href="<?= $base_url ?>/img/site.webmanifest">

  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">

  <div class="sistema-container app">
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Bem-vindo ao Sistema</div>
    <div class="app-desc">
      Selecione abaixo o perfil desejado para testar as funcionalidades:
    </div>
    <!-- [INCLUSÃO] Exibição institucional de mensagem de erro, se houver -->
    <?php if ($mensagem_erro): ?>
      <div class="alert-erro">
        <?= htmlspecialchars($mensagem_erro) ?>
      </div>
    <?php endif; ?>
    <!-- [ALTERAÇÃO] Formulário de seleção de perfil -->
    <form method="post" action="../controller/selecao_perfil.php" class="app-btn-group" style="margin-top:1.5em;">
      <button class="app-btn" name="perfil" value="ADMINISTRADOR" type="submit">Administrador</button>
      <button class="app-btn" name="perfil" value="RH" type="submit">RH</button>
      <button class="app-btn" name="perfil" value="EMPREGADO" type="submit">Empregado</button>
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
