<?php
/*
    /src/view/controle_credito.php
    [INCLUSÃO]
    View do módulo CONTROLE_CREDITO (Fase 1 – dry-run).
    - Exibe formulário e resultado de simulação.
    - Recebe $__dados, $__saida, $__erros do controller.
*/

require_once __DIR__ . '/../../config/paths.php';

/* [BLOCO] Normalização de variáveis esperadas */
$__erros = $__erros ?? [];
$__dados = $__dados ?? null;
$__saida = $__saida ?? null;

/* [BLOCO] Helper de saída segura */
function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Controle de Créditos | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- [INCLUSÃO] CSS institucional -->
  <link rel="stylesheet" href="<?= e($base_url) ?>/css/style.css">

  <!-- [BLOCO] Máscaras e normalização numérica (pt-BR) -->
  <script>
    /* ===== Helpers numéricos (pt-BR) ===== */
    function stripToNumberStringBR(v) {
      if (v === undefined || v === null) return "0";
      return String(v).replace(/\s+/g, "").replace(/\./g, "").replace(",", ".");
    }
    function parseBR(v) {
      const n = parseFloat(stripToNumberStringBR(v));
      return isNaN(n) ? 0 : n;
    }
    function formatBR(n) {
      try {
        return Number(n).toLocaleString("pt-BR", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      } catch {
        return (Math.round((Number(n) || 0) * 100) / 100).toString();
      }
    }

    /* ===== Campo -> modo edição/visual ===== */
    function toEdit(el) { el.value = stripToNumberStringBR(el.value); }
    function toView(el) { el.value = formatBR(parseBR(el.value)); }

    /* ===== Recalcula o líquido sempre que houver mudança ===== */
    function calcLiquido() {
      const bruto   = parseBR(document.getElementById("salarioBruto").value);
      const legais  = parseBR(document.getElementById("descontosLegais").value);
      const nlegais = parseBR(document.getElementById("descontosNaoLegais").value);
      const liquido = bruto - (legais + nlegais);
      const $liq    = document.getElementById("liquido");
      $liq.value = formatBR(liquido);
    }

    /* ===== Submissão: normaliza todos os campos numéricos ===== */
    function normalizeOnSubmit() {
      ["salarioBruto", "descontosLegais", "descontosNaoLegais", "liquido"].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = stripToNumberStringBR(el.value); // ex.: "10000.00"
      });
    }

    document.addEventListener("DOMContentLoaded", function () {
      const ids = ["salarioBruto", "descontosLegais", "descontosNaoLegais"];

      /* [BLOCO] Eventos de máscara e recálculo */
      ids.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        el.addEventListener("focus", () => toEdit(el));
        el.addEventListener("blur",  () => { toView(el); calcLiquido(); });
        el.addEventListener("input", calcLiquido);

        /* Inicializa formato visual quando houver valor pré-carregado */
        if (el.value !== "") toView(el);
      });

      /* Inicializa o líquido ao carregar */
      calcLiquido();

      /* Normalização antes do submit */
      const form = document.getElementById("form-creditos");
      if (form) form.addEventListener("submit", normalizeOnSubmit);
    });
  </script>
</head>
<body class="sistema-bg">
  <div class="sistema-container app wrap">

    <?php include __DIR__ . '/componentes/mensagens.php'; ?>

    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Controle de Créditos</div>
    <div class="app-desc" style="margin-bottom: 1.5em;">
      Simule a geração e a amortização de créditos quando o líquido do mês ficar negativo (modo <strong>dry-run</strong>).
    </div>

    <?php if (!empty($__erros)): ?>
      <div class="alert alert-erro">
        <?php foreach ($__erros as $e): ?>
          <div><?= e($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="panel">
      <form method="post" action="" id="form-creditos">
        <input type="hidden" name="acao" value="simular">

        <div class="grid">
          <div>
            <label for="matricula">Matrícula</label>
            <input
              id="matricula"
              name="matricula"
              type="text"
              value="<?= isset($__dados['matricula']) ? e($__dados['matricula']) : '' ?>"
            >
          </div>

          <div>
            <label for="salarioBruto">Salário Bruto</label>
            <input
              id="salarioBruto"
              name="salarioBruto"
              type="text"
              inputmode="decimal"
              value="<?= isset($__dados['salarioBruto']) ? e(number_format((float) $__dados['salarioBruto'], 2, ',', '.')) : '' ?>"
            >
          </div>

          <div>
            <label for="descontosLegais">Descontos Legais</label>
            <input
              id="descontosLegais"
              name="descontosLegais"
              type="text"
              inputmode="decimal"
              value="<?= isset($__dados['descontosLegais']) ? e(number_format((float) $__dados['descontosLegais'], 2, ',', '.')) : '' ?>"
            >
          </div>

          <div>
            <label for="descontosNaoLegais">Descontos Não Legais</label>
            <input
              id="descontosNaoLegais"
              name="descontosNaoLegais"
              type="text"
              inputmode="decimal"
              value="<?= isset($__dados['descontosNaoLegais']) ? e(number_format((float) $__dados['descontosNaoLegais'], 2, ',', '.')) : '' ?>"
            >
          </div>

          <div>
            <label for="liquido">Líquido do Mês</label>
            <input
              id="liquido"
              name="liquido"
              type="text"
              inputmode="decimal"
              readonly
              aria-readonly="true"
              value="<?= isset($__dados['liquido']) ? e(number_format((float) $__dados['liquido'], 2, ',', '.')) : '' ?>"
            >
          </div>
        </div>

        <div class="actions">
          <button class="btn btn-primary" type="submit">Simular</button>
        </div>
      </form>
    </div>

    <?php if (!empty($__saida)): ?>
      <h2 style="margin-top: 24px;">Diagnóstico</h2>

      <?php if (!empty($__saida['validacao']['ok'])): ?>
        <div class="alert alert-ok">Simulação válida segundo as políticas do módulo.</div>
      <?php else: ?>
        <div class="alert alert-erro">Foram encontrados problemas de validação.</div>
      <?php endif; ?>

      <h3>Margem</h3>
      <table class="table">
        <tr><th>Base (bruto - legais)</th><td>R$ <?= number_format($__saida['margem']['base'], 2, ',', '.') ?></td></tr>
        <tr><th>Margem Teórica (30%)</th><td>R$ <?= number_format($__saida['margem']['margemTeorica'], 2, ',', '.') ?></td></tr>
        <tr><th>Margem Disponível</th><td>R$ <?= number_format($__saida['margem']['margemDisponivel'], 2, ',', '.') ?></td></tr>
      </table>

      <h3>Crédito Automático</h3>
      <table class="table">
        <tr><th>Gerou?</th><td style="text-align: left;"><?= $__saida['credito']['gerou'] ? 'Sim' : 'Não' ?></td></tr>
        <tr><th>Saldo do Crédito</th><td>R$ <?= number_format($__saida['credito']['saldoCredito'], 2, ',', '.') ?></td></tr>
        <tr><th>Parcela Sugerida</th><td>R$ <?= number_format($__saida['credito']['parcelaSugerida'], 2, ',', '.') ?></td></tr>
      </table>

      <h3>Amortização (Simulação)</h3>
      <table class="table">
        <tr><th>#</th><th>Valor Parcela (R$)</th></tr>
        <?php foreach ($__saida['amortizacao']['parcelas'] as $i => $p): ?>
          <tr>
            <td style="text-align: left;"><?= $i + 1 ?></td>
            <td><?= number_format($p, 2, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
        <tr><th>Total Amortizado</th><td>R$ <?= number_format($__saida['amortizacao']['totalAmortizado'], 2, ',', '.') ?></td></tr>
        <tr><th>Saldo Final</th><td>R$ <?= number_format($__saida['amortizacao']['saldoFinal'], 2, ',', '.') ?></td></tr>
      </table>

      <h3>Payload (JSON)</h3>
      <pre><?= e(json_encode($__saida, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
    <?php endif; ?>

    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
