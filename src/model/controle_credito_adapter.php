<?php
/*
    /src/model/controle_credito_adapter.php
    [INCLUSÃO]
    Adaptador DRY-RUN para orquestrar a integração entre contracheques simulados
    e o ControleCreditoService, gerando o resultado do mês sem persistência.
*/

declare(strict_types=1);

require_once __DIR__ . '/controle_credito_service.php';

function cc_calcular_para_competencia(array $contracheques, array $estadoAnterior): array
{
    $out = [];

    foreach ($contracheques as $cc) {
        // Campos esperados no contracheque (stub da SIMULACAO_FOLHA)
        $mat    = (string)($cc['matricula'] ?? '');
        $nome   = (string)($cc['nome'] ?? '');
        $bruto  = (float)($cc['bruto'] ?? 0.0);
        $legais = (float)($cc['descontos_legais'] ?? 0.0);
        $nleg   = (float)($cc['descontos_nao_legais'] ?? 0.0);
        $liq    = (float)($cc['liquido'] ?? 0.0);

        $saldoAnterior = isset($estadoAnterior[$mat]['saldo'])
            ? (float)$estadoAnterior[$mat]['saldo']
            : 0.0;

        // 1) Margem e crédito automático usando sua classe
        $margem = ControleCreditoService::calcularMargem($bruto, $legais, $nleg);
        $auto   = ControleCreditoService::gerarCreditoAuto($liq, $margem['margemDisponivel']);

        // 2) Composição de saldo do mês (saldo anterior + crédito gerado agora)
        $saldoBase = round($saldoAnterior + ($auto['gerou'] ? $auto['saldoCredito'] : 0.0), 2);

        // 3) Abatimento do mês: limitado pela margem disponível e pelo saldo
        $abatimentoMes = round(min($margem['margemDisponivel'], $saldoBase), 2);

        // 4) Saldo atual após o abatimento
        $saldoAtual = round($saldoBase - $abatimentoMes, 2);

        // 5) Saída para RELATÓRIOS/VIEW
        $out[] = [
            'matricula'      => $mat,
            'nome'           => $nome,
            'credito_gerado' => $auto['gerou'] ? $auto['saldoCredito'] : 0.0,
            'abatimento_mes' => $abatimentoMes,
            'saldo_atual'    => $saldoAtual,
            // campos auxiliares (úteis a diagnósticos/prints)
            'margem'         => $margem,
            'liquido'        => $liq,
            'saldo_anterior' => $saldoAnterior,
        ];

        // 6) Log textual (mantendo DRY-RUN e rastreabilidade)
        cc_log_simulacao_linha(
            $cc,
            $auto['gerou'] ? $auto['saldoCredito'] : 0.0,
            $abatimentoMes,
            $saldoAnterior,
            $saldoAtual
        );
    }

    return $out;
}

/**
 * cc_log_simulacao_linha
 * ----------------------
 * [AJUSTE 20251118]
 * - Timestamp padronizado no início da linha: [AAAAMMDD_hhmmss]:
 * - Corpo no formato campo=valor;campo=valor;... até o final;
 * - Uso explícito do fuso horário America/Sao_Paulo;
 * - Nome do arquivo de saída mais intuitivo:
 *   controle_credito_dryrun_YYYYMMDD.txt
 */
function cc_log_simulacao_linha(array $cc, float $cred, float $abat, float $saldoAnt, float $saldoAtu): void
{
    // [AJUSTE 20251118] Timestamp local no padrão [AAAAMMDD_hhmmss]:
    $agora     = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    $dataHoje  = $agora->format('Ymd');
    $timestamp = $agora->format('Ymd_His');
    $prefixo   = sprintf('[%s]: ', $timestamp);

    // [AJUSTE 20251118] Corpo do log no padrão campo=valor;campo=valor;...
    $campos = [
        'competencia'    => $cc['competencia']        ?? '',
        'matricula'      => (string)($cc['matricula'] ?? ''),
        'nome'           => (string)($cc['nome']      ?? ''),
        'liquido'        => number_format((float)($cc['liquido'] ?? 0.0), 2, '.', ''),
        'credito'        => number_format($cred, 2, '.', ''),
        'abatimento'     => number_format($abat, 2, '.', ''),
        'saldo_anterior' => number_format($saldoAnt, 2, '.', ''),
        'saldo_atual'    => number_format($saldoAtu, 2, '.', ''),
        'modo'           => 'F2_DRYRUN',
    ];

    $partes = [];

    foreach ($campos as $chave => $valor) {
        // [AJUSTE 20251118] Garantir linha única e evitar ';' dentro do valor
        $valor = str_replace(["\r", "\n", ";"], [' ', ' ', ' '], (string)$valor);
        $partes[] = $chave . '=' . $valor;
    }

    // Linha final no padrão definido:
    // [AAAAMMDD_hhmmss]: campo=valor;campo=valor;...
    $linha = $prefixo . implode(';', $partes) . "\n";

    $logDir = __DIR__ . '/../../logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }

    // [AJUSTE 20251118] Nome do arquivo mais descritivo para o conteúdo
    $arquivo = $logDir . '/controle_credito_dryrun_' . $dataHoje . '.txt';

    @file_put_contents($arquivo, $linha, FILE_APPEND);
}
