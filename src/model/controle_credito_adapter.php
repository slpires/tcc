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
            'matricula'        => $mat,
            'nome'             => $nome,
            'credito_gerado'   => $auto['gerou'] ? $auto['saldoCredito'] : 0.0,
            'abatimento_mes'   => $abatimentoMes,
            'saldo_atual'      => $saldoAtual,
            // campos auxiliares (úteis a diagnósticos/prints)
            'margem'           => $margem,
            'liquido'          => $liq,
            'saldo_anterior'   => $saldoAnterior,
        ];

        // 6) Log textual (mantendo DRY-RUN e rastreabilidade)
        cc_log_simulacao_linha($cc, $auto['gerou'] ? $auto['saldoCredito'] : 0.0, $abatimentoMes, $saldoAnterior, $saldoAtual);
    }

    return $out;
}

function cc_log_simulacao_linha(array $cc, float $cred, float $abat, float $saldoAnt, float $saldoAtu): void
{
    $linha = sprintf(
        "%s;%s;%s;%s;%.2f;%.2f;%.2f;%.2f;%.2f;%s\n",
        date('c'),
        $cc['competencia'] ?? '',
        (string)($cc['matricula'] ?? ''),
        (string)($cc['nome'] ?? ''),
        (float)($cc['liquido'] ?? 0.0),
        $cred,
        $abat,
        $saldoAnt,
        $saldoAtu,
        'F2_DRYRUN'
    );

    $logDir = __DIR__ . '/../../logs';
    if (!is_dir($logDir)) { @mkdir($logDir, 0775, true); }

    @file_put_contents($logDir . '/simulacao_' . date('Ymd') . '.txt', $linha, FILE_APPEND);
}
