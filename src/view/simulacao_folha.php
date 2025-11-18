<?php
/*
    /src/view/simulacao_folha.php
    View do módulo "Simulação da Folha de Pagamento" – SLPIRES.COM (TCC UFF).
    Inclui validação de permissão, paths dinâmicos, layout institucional e UI DRY-RUN.
*/

require_once __DIR__ . '/../controller/verificar_permissao.php';
require_once __DIR__ . '/../../config/paths.php';

/* [BLOCO] Dados de exibição (tolerante a ausência) */
$view           = $view           ?? [];
$competencia    = $view['competencia']    ?? '01/2019';
$erro           = $view['erro']           ?? null;
$resultado      = is_array($view['resultado'] ?? null)      ? $view['resultado']      : [];
$contracheques  = is_array($view['contracheques'] ?? null)  ? $view['contracheques']  : [];

/* [BLOCO] KPIs (resumo) */
$kpis = [
    'qtd_matriculas'             => 0,
    'qtd_contracheques'          => 0,
    'total_creditos_recuperar'   => 0.00, // soma dos líquidos negativos (módulo)
    'qtd_matriculas_com_credito' => 0,    // quantidade de matrículas com líquido < 0
];

if ($resultado) {
    // Quantidades
    $matriculas = array_map(fn($r) => (string)($r['matricula'] ?? ''), $resultado);
    $kpis['qtd_matriculas']    = count(array_unique(array_filter($matriculas, fn($m)=>$m!=='')));
    $kpis['qtd_contracheques'] = count($resultado);

    // Soma dos líquidos negativos da competência e contagem de matrículas
    $totalNegativos = 0.0;
    $matriculasNeg  = [];

    $pegarLiquido = static function(array $row): float {
        foreach (['liquido','liquido_mes','valor_liquido'] as $k) {
            if (isset($row[$k]) && is_numeric($row[$k])) {
                return (float)$row[$k];
            }
        }
        return 0.0;
    };

    if ($contracheques) {
        foreach ($contracheques as $c) {
            $liq = $pegarLiquido($c);
            if ($liq < 0) {
                $totalNegativos += abs($liq);
                $matriculasNeg[] = (string)($c['matricula'] ?? ($c['mat'] ?? ''));
            }
        }
    } else {
        // Fallback robusto usando $resultado
        foreach ($resultado as $r) {
            $liq = $pegarLiquido($r);
            if ($liq < 0) {
                $totalNegativos += abs($liq);
                $matriculasNeg[] = (string)($r['matricula'] ?? '');
            }
        }
    }

    $kpis['total_creditos_recuperar']   = round($totalNegativos, 2);
    $kpis['qtd_matriculas_com_credito'] = count(array_unique(array_filter($matriculasNeg, fn($m)=>$m!=='')));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Simulação da Folha | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- [INCLUSÃO] Favicon e Favibar -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base_url ?>/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $base_url ?>/img/favicon-16x16.png">
  <link rel="shortcut icon" href="<?= $base_url ?>/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $base_url ?>/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $base_url ?>/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= $base_url ?>/img/android-chrome-512x512.png">
  <link rel="manifest" href="<?= $base_url ?>/img/site.webmanifest">
  
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
  <script>
    /* [FIX DE EXIBIÇÃO NO TOPO]
       Com o CSS ajustado (sem scroll interno em .sistema-bg),
       apenas garantimos que o navegador não restaure scroll antigo. */
    (function () {
      if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
      window.addEventListener('pageshow', function(){ window.scrollTo(0,0); }, { once:true });
      window.addEventListener('load', function(){ window.scrollTo(0,0); }, { once:true });
    })();
  </script>
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <!-- Topo institucional -->
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Simulação da Folha de Pagamento</div>

    <!-- Mensagens -->
    <?php if ($erro === 'competencia_fechada'): ?>
      <div class="alert-erro">A competência atual já foi processada. Avance para a próxima.</div>
    <?php elseif ($erro === 'falha_execucao'): ?>
      <div class="alert-erro">Não foi possível concluir a simulação. Tente novamente em instantes.</div>
    <?php endif; ?>

    <!-- CARD: Cabeçalho operacional -->
    <div class="card">
      <div class="card-body">
        <div class="app-desc">
          Realiza a simulação, com abatimento automático de créditos a recuperar registrados para os empregados.<br>
          A execução não grava dados definitivos no banco de dados.
        </div>

        <div class="competencia-info">
          Próxima competência: <strong><?= htmlspecialchars($competencia, ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <form method="get" action="?r=simulacao" aria-label="Formulário de simulação de competência"
              onsubmit="(function(f){var b=f.querySelector('.btn-simular'); if(b){b.disabled=true;b.innerText='Processando...';}})(this)">
          <input type="hidden" name="r" value="simulacao">
          <input type="hidden" name="executar" value="1">
          <button class="btn btn-primary btn-simular" type="submit">Simular próxima competência</button>
        </form>

        <?php if (!$resultado): ?>
          <div class="status-simulacao">
            Nenhuma competência processada nesta sessão.<br>
            Clique em <em>“Simular próxima competência”</em> para iniciar.
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CARD: Resumo do processamento -->
    <?php if ($resultado): ?>
      <div class="card">
        <div class="card-title">Resultados da Competência Processada</div>
        <div class="card-body">
          <ul class="resumo-kpis">
            <li><span class="kpi-label">Matrículas processadas:</span> <span class="kpi-num"><?= (int)$kpis['qtd_matriculas'] ?></span></li>
            <li><span class="kpi-label">Contracheques gerados:</span> <span class="kpi-num"><?= (int)$kpis['qtd_contracheques'] ?></span></li>
            <li><span class="kpi-label">Créditos a recuperar:</span> <span class="kpi-num"><?= number_format($kpis['total_creditos_recuperar'], 2, ',', '.') ?></span></li>
            <li><span class="kpi-label">Total de matrículas com créditos a recuperar:</span> <span class="kpi-num"><?= (int)$kpis['qtd_matriculas_com_credito'] ?></span></li>
          </ul>

          <div class="muted regras">
            <p><strong>As regras aplicadas garantem:</strong></p>
            <ul>
              <li>Geração de crédito quando <code>líquido &lt; 0</code>;</li>
              <li><em>Abatimento ≤ saldo</em> e limitado à margem;</li>
              <li>Manutenção de saldos entre competências (sessão).</li>
            </ul>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Rodapé -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
