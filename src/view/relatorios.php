<?php
/*
    /src/view/relatorios.php
    [VIEW]
    Módulo de Relatórios.
    - Verifica permissão de acesso ao módulo;
    - Carrega paths institucionais;
    - Exibe, em formato de botões (responsivo), apenas os relatórios
      permitidos para o perfil da sessão, conforme matriz JSON.
*/

/* [INCLUSÃO] Verifica permissão de acesso ao módulo */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos institucionais ($base_url, $action_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Estruturas esperadas vindas do controller */
if (!isset($relatorios_permitidos) || !is_array($relatorios_permitidos)) {
    $relatorios_permitidos = [];
}
if (!isset($relatorios_config) || !is_array($relatorios_config)) {
    $relatorios_config = [];
}

/*
    Espera-se que o controller já tenha aplicado a filtragem por:
    - perfil da sessão; e
    - relatórios efetivamente implementados nesta release do MVP.

    Ainda assim, esta view só considera chaves que existam na configuração.
*/
$relatorios_disponiveis = [];

if (isset($relatorios_config['relatorios']) && is_array($relatorios_config['relatorios'])) {
    foreach ($relatorios_permitidos as $chave) {
        if (isset($relatorios_config['relatorios'][$chave]) && is_array($relatorios_config['relatorios'][$chave])) {
            $relatorios_disponiveis[$chave] = $relatorios_config['relatorios'][$chave];
        }
    }
}

/* [HELPER] Escape HTML simples (fallback local) */
if (!function_exists('view_escape')) {
    function view_escape(string $valor): string
    {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }
}
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

    <div class="app-desc">
      Selecione um dos relatórios abaixo:
    </div>

    <?php if (!empty($relatorios_disponiveis)): ?>
      <div class="app-desc" style="margin-top: 1.2em;">
        Selecione abaixo o tipo de relatório disponível para o seu perfil:
      </div>

      <!-- [LISTA] Botões de relatórios permitidos (responsivo / mobile first) -->
      <div class="app-btn-group" role="group" aria-label="Relatórios disponíveis para o seu perfil">
        <?php foreach ($relatorios_disponiveis as $chave => $dados): ?>
          <?php
            $nomeRelatorio = isset($dados['nome']) && $dados['nome'] !== ''
                ? (string) $dados['nome']
                : (string) $chave;

            // PADRÃO DO SISTEMA:
            // - usar $action_base como endpoint do front controller;
            // - montar query string com & (não &amp;);
            // - o browser se encarrega de enviar ?r=relatorios&tipo=logs.
            $href = $action_base . '?r=relatorios&tipo=' . urlencode($chave);
          ?>
          <a class="app-btn" href="<?= $href ?>">
            <?= view_escape($nomeRelatorio) ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="app-info" style="margin-top: 1.5em; margin-bottom: 1.5em;">
        Nenhum relatório está disponível para o seu perfil no momento.
      </div>
    <?php endif; ?>

    <!-- [INCLUSÃO] Rodapé institucional do usuário logado -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
