<?php
/*
    /src/model/controle_credito_service.php
    [INCLUSÃO]
    Serviço de regras (dry-run) do módulo CONTROLE_CREDITO.
    Não realiza persistência. Não produz saída direta (echo/HTML).
    Adota o padrão institucional de comentários e blocos.
*/

declare(strict_types=1);

class ControleCreditoService
{
    /**
     * [BLOCO] Cálculo de margem consignável disponível
     * Regra:
     *  - Base da margem = max(0, bruto - descontos_legais)
     *  - Margem teórica (30%) = 0.30 * base
     *  - Margem disponível = max(0, margem_teorica - descontos_nao_legais_atual)
     * Observação: descontos_nao_legais já consomem parte da margem naquele mês.
     *
     * @param  float $salarioBruto
     * @param  float $descontosLegais
     * @param  float $descontosNaoLegais
     * @return array{base: float, margemTeorica: float, margemDisponivel: float}
     */
    public static function calcularMargem(
        float $salarioBruto,
        float $descontosLegais,
        float $descontosNaoLegais
    ): array {
        /* [VALIDAÇÃO] Sanitização mínima */
        $salarioBruto       = max(0.0, $salarioBruto);
        $descontosLegais    = max(0.0, $descontosLegais);
        $descontosNaoLegais = max(0.0, $descontosNaoLegais);

        /* [REGRA] Base e margem teórica */
        $base          = max(0.0, $salarioBruto - $descontosLegais);
        $margemTeorica = round(0.30 * $base, 2);

        /* [REGRA] Margem disponível após compromissos não legais */
        $margemDisponivel = max(0.0, $margemTeorica - $descontosNaoLegais);
        $margemDisponivel = round($margemDisponivel, 2);

        return [
            'base'             => round($base, 2),
            'margemTeorica'    => $margemTeorica,
            'margemDisponivel' => $margemDisponivel,
        ];
    }

    /**
     * [BLOCO] Geração de crédito automático (AUTO) quando líquido < 0
     * - Se liquido >= 0: não gera crédito
     * - Valor do crédito = |líquido| (zera o líquido do mês da ocorrência)
     * - Parcela sugerida = min(margemDisponivel, saldoCredito)
     *
     * @param  float $liquido
     * @param  float $margemDisponivel
     * @return array{gerou: bool, saldoCredito: float, parcelaSugerida: float}
     */
    public static function gerarCreditoAuto(
        float $liquido,
        float $margemDisponivel
    ): array {
        $margemDisponivel = max(0.0, round($margemDisponivel, 2));

        if ($liquido >= 0) {
            return [
                'gerou'           => false,
                'saldoCredito'    => 0.0,
                'parcelaSugerida' => 0.0,
            ];
        }

        /* [REGRA] Crédito necessário para eliminar o líquido negativo */
        $saldo = round(abs($liquido), 2);

        /* [REGRA] Parcela limitada à margem disponível do mês */
        $parcelaSugerida = round(min($margemDisponivel, $saldo), 2);

        return [
            'gerou'           => true,
            'saldoCredito'    => $saldo,
            'parcelaSugerida' => $parcelaSugerida,
        ];
    }

    /**
     * [BLOCO] Simulação de amortização mensal (dry-run, sem juros)
     * - Parcela mensal = min(margemMensalMax, saldoRestante)
     * - Interrompe quando saldo chega a zero ou alcança $maxMeses
     *
     * @param  float $saldoCredito      Saldo total a amortizar
     * @param  float $margemMensalMax   Teto por mês (ex.: margem disponível)
     * @param  int   $maxMeses          Limite de parcelas (default 60)
     * @return array{parcelas: float[], meses: int, totalAmortizado: float, saldoFinal: float}
     */
    public static function simularAmortizacao(
        float $saldoCredito,
        float $margemMensalMax,
        int $maxMeses = 60
    ): array {
        /* [VALIDAÇÃO] Parâmetros */
        $saldoCredito    = round(max(0.0, $saldoCredito), 2);
        $margemMensalMax = round(max(0.0, $margemMensalMax), 2);
        $maxMeses        = max(1, $maxMeses);

        $parcelas = [];
        $saldo    = $saldoCredito;

        /* [REGRA] Loop de amortização sem juros */
        for ($i = 1; $i <= $maxMeses && $saldo > 0.0; $i++) {
            $parc = round(min($margemMensalMax, $saldo), 2);

            /* [VALIDAÇÃO] Evita laço infinito por arredondamento */
            if ($parc <= 0.0) {
                break;
            }

            $parcelas[] = $parc;
            $saldo      = round($saldo - $parc, 2);
        }

        $total = round(array_sum($parcelas), 2);

        return [
            'parcelas'        => $parcelas,
            'meses'           => count($parcelas),
            'totalAmortizado' => $total,
            'saldoFinal'      => round($saldo, 2),
        ];
    }

    /**
     * [BLOCO] Validação de políticas institucionais
     * - Nenhuma parcela pode exceder a margemMensalMax informada
     * - Campos numéricos básicos não podem ser negativos (exceto "liquido")
     * - "liquido" pode ser negativo; apenas precisa ser numérico
     *
     * @param  array $entrada Ex.:
     *   [
     *     'salarioBruto'=>..., 'descontosLegais'=>..., 'descontosNaoLegais'=>...,
     *     'liquido'=>..., 'margemMensalMax'=>..., 'parcelas'=>[...]
     *   ]
     * @return array{ok: bool, erros: string[]}
     */
    public static function validarPoliticas(array $entrada): array
    {
        $erros = [];

        /* [VALIDAÇÃO] Campos que não podem ser negativos */
        $naoNegativos = ['salarioBruto', 'descontosLegais', 'descontosNaoLegais', 'margemMensalMax'];
        foreach ($naoNegativos as $c) {
            if (!isset($entrada[$c]) || !is_numeric($entrada[$c]) || $entrada[$c] < 0) {
                $erros[] = "[VALIDAÇÃO] Campo inválido: {$c}";
            }
        }

        /* [VALIDAÇÃO] Líquido pode ser negativo; apenas garante que seja numérico */
        if (!isset($entrada['liquido']) || !is_numeric($entrada['liquido'])) {
            $erros[] = "[VALIDAÇÃO] Campo inválido: liquido";
        }

        /* [VALIDAÇÃO] Parcelas não podem exceder a margemMensalMax */
        if (isset($entrada['parcelas']) && is_array($entrada['parcelas'])) {
            $teto = max(0.0, (float) $entrada['margemMensalMax']);
            foreach ($entrada['parcelas'] as $idx => $p) {
                if (!is_numeric($p) || $p < 0) {
                    $erros[] = "[VALIDAÇÃO] Parcela inválida no índice {$idx}";
                } elseif ((float) $p > $teto) {
                    $erros[] = "[VALIDAÇÃO] Parcela excede a margem no índice {$idx}";
                }
            }
        }

        return [
            'ok'    => empty($erros),
            'erros' => $erros,
        ];
    }

    /**
     * [BLOCO] Orquestração de simulação (dry-run)
     * Entrada mínima (por empregado):
     *  - salarioBruto, descontosLegais, descontosNaoLegais, liquido
     * Saída:
     *  - margem (base/teórica/disponível), crédito AUTO (se aplicável),
     *    cronograma de amortização proposto e validações.
     *
     * @param  array $dadosEmpregado
     * @param  int   $maxMeses
     * @return array
     */
    public static function simularParaEmpregado(array $dadosEmpregado, int $maxMeses = 60): array
    {
        /* [VALIDAÇÃO] Campos esperados */
        $defaults = [
            'matricula'          => null,
            'salarioBruto'       => 0.0,
            'descontosLegais'    => 0.0,
            'descontosNaoLegais' => 0.0,
            'liquido'            => 0.0,
        ];
        $in = array_merge($defaults, $dadosEmpregado);

        $margem = self::calcularMargem(
            (float) $in['salarioBruto'],
            (float) $in['descontosLegais'],
            (float) $in['descontosNaoLegais']
        );

        $auto = self::gerarCreditoAuto(
            (float) $in['liquido'],
            (float) $margem['margemDisponivel']
        );

        /* [TRATAMENTO] Se não gerou crédito, devolve apenas diagnóstico de margem */
        if ($auto['gerou'] === false) {
            return [
                'matricula'   => $in['matricula'],
                'margem'      => $margem,
                'credito'     => $auto,
                'amortizacao' => [
                    'parcelas'        => [],
                    'meses'           => 0,
                    'totalAmortizado' => 0.0,
                    'saldoFinal'      => 0.0,
                ],
                'validacao'   => ['ok' => true, 'erros' => []],
                'modo'        => 'dry-run',
            ];
        }

        $amort = self::simularAmortizacao(
            (float) $auto['saldoCredito'],
            (float) $margem['margemDisponivel'],
            $maxMeses
        );

        $valid = self::validarPoliticas([
            'salarioBruto'       => (float) $in['salarioBruto'],
            'descontosLegais'    => (float) $in['descontosLegais'],
            'descontosNaoLegais' => (float) $in['descontosNaoLegais'],
            'liquido'            => (float) $in['liquido'],
            'margemMensalMax'    => (float) $margem['margemDisponivel'],
            'parcelas'           => $amort['parcelas'],
        ]);

        return [
            'matricula'   => $in['matricula'],
            'margem'      => $margem,
            'credito'     => $auto,
            'amortizacao' => $amort,
            'validacao'   => $valid,
            'modo'        => 'dry-run',
        ];
    }
}
