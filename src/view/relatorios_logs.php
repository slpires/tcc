<?php
/*
    /src/view/relatorios_logs.php
    [VIEW]
    Relatórios de Logs Técnicos.

    - Exibe um filtro de data em formato de lista (select), inspirado
      no catálogo de casos de teste do MÓDULO TESTES;
    - Lista os arquivos de log da data selecionada;
    - Permite download ao clicar no nome do arquivo.
*/

/* [INCLUSÃO] Verifica permissão de acesso ao módulo RELATORIOS */
require_once __DIR__ . '/../controller/verificar_permissao.php';

/* [INCLUSÃO] Caminhos dinâmicos ($base_url, $action_base, etc.) */
require_once __DIR__ . '/../../config/paths.php';

/* [NORMALIZAÇÃO] Estrutura esperada vinda do controller */
if (!isset($logs_arquivos) || !is_array($logs_arquivos)) {
    $logs_arquivos = [];
}

/* [HELPER] Escape HTML simples */
if (!function_exists('e')) {
    function e(string $valor): string
    {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }
}

/* ==========================================================
   [BLOCO] Construção da lista de datas únicas (Y-m-d)
   ========================================================== */
$datas_unicas = [];

foreach ($logs_arquivos as $log) {
    $dataCompleta = isset($log['data']) ? (string) $log['data'] : '';
    if ($dataCompleta === '' || $dataCompleta === '-') {
        continue;
    }

    // Considera apenas o componente de data (YYYY-MM-DD)
    $dataSomente = substr($dataCompleta, 0, 10);
    if ($dataSomente !== '' && !in_array($dataSomente, $datas_unicas, true)) {
        $datas_unicas[] = $dataSomente;
    }
}

/* Ordena em ordem decrescente (mais recente primeiro) */
if (!empty($datas_unicas)) {
    usort($datas_unicas, static function (string $a, string $b): int {
        // Como estão no formato YYYY-MM-DD, strcmp invertido atende
        return strcmp($b, $a);
    });
}

/* ==========================================================
   [BLOCO] Determinação da data selecionada
   - Paramêtro GET: d=YYYY-MM-DD
   - Caso não informado ou inválido, usa a primeira data da lista.
   ========================================================== */
$data_selecionada = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($data_selecionada === null || $data_selecionada === false || $data_selecionada === '') {
    $data_selecionada = $datas_unicas[0] ?? '';
}

/* Garante que a data selecionada é uma das datas disponíveis */
if ($data_selecionada !== '' && !in_array($data_selecionada, $datas_unicas, true)) {
    $data_selecionada = $datas_unicas[0] ?? '';
}

/* ==========================================================
   [BLOCO] Filtra os arquivos de log pela data selecionada
   ========================================================== */
$logs_filtrados = [];

if ($data_selecionada !== '') {
    foreach ($logs_arquivos as $log) {
        $dataCompleta = isset($log['data']) ? (string) $log['data'] : '';
        if ($dataCompleta === '' || $dataCompleta === '-') {
            continue;
        }

        $dataSomente = substr($dataCompleta, 0, 10);
        if ($dataSomente === $data_selecionada) {
            $logs_filtrados[] = $log;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Relatórios de Logs Técnicos | SLPIRES.COM</title>
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

    <!-- [INCLUSÃO] Mensagens institucionais -->
    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Relatórios de Logs Técnicos</div>

    <div class="app-desc">
      Selecione uma data para visualizar os arquivos de log gerados pelo sistema.
    </div>

    <?php if (!empty($datas_unicas)): ?>
      <!-- ======================================================
           [FORMULÁRIO] Seleção de data (padrão catálogo de testes)
           ====================================================== -->
      <form method="get"
            action="<?= e($action_base) ?>"
            class="app-form"
            style="margin-top: 1.2em; margin-bottom: 0.6em;">

        <!-- Roteamento via front controller -->
        <input type="hidden" name="r" value="relatorios">
        <input type="hidden" name="tipo" value="logs">

        <label for="data-log" class="app-label">
          Data dos logs:
        </label>
        <select id="data-log"
                name="d"
                class="app-select"
                onchange="this.form.submit();">
          <?php foreach ($datas_unicas as $data): ?>
            <option value="<?= e($data) ?>"<?= $data === $data_selecionada ? ' selected' : '' ?>>
              <?= e($data) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>

      <!-- ======================================================
           [TABELA] Arquivos de log da data selecionada
           ====================================================== -->
      <?php if (!empty($logs_filtrados)): ?>
        <div class="app-section" style="margin-top: 0.2em;">
          <div class="app-table-wrap">
              <table class="app-table">
                  <thead>
                      <tr>
                          <th scope="col">Nome do arquivo</th>
                          <th scope="col" style="width: 7rem; text-align:center;">
                              Tamanho<br><span style="font-size: 0.85em;">(bytes)</span>
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($logs_filtrados as $log): ?>
                          <?php
                              $nome    = isset($log['nome']) ? (string) $log['nome'] : '';
                              $tamanho = isset($log['tamanho']) ? (int) $log['tamanho'] : 0;
                              $hrefDownload = $action_base
                                  . '?r=relatorios'
                                  . '&acao=download'
                                  . '&arquivo=' . rawurlencode($nome);
                          ?>
                          <tr>
                              <td>
                                  <a href="<?= e($hrefDownload) ?>">
                                      <?= e($nome) ?>
                                  </a>
                                       </td>
                              <td style="text-align:right;">
                                  <?= number_format($tamanho, 0, ',', '.') ?>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
        </div>
      <?php else: ?>
        <div class="app-info" style="margin-top: 1.2em;">
          Não foram encontrados arquivos de log para a data selecionada.
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- Caso extremo: não há nenhuma data de log identificada -->
      <div class="app-info" style="margin-top: 1.5em;">
        No momento, não há arquivos de log técnicos disponíveis para consulta.
      </div>
    <?php endif; ?>

    <!-- [INCLUSÃO] Rodapé institucional do usuário logado -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
