<?php
/*
    /src/controller/controle_credito.php
    [INCLUSÃO]
    Controller do módulo CONTROLE_CREDITO (Fase 1 – dry-run).
    - Valida permissão (inclusão institucional).
    - Coleta e sanitiza entradas.
    - Orquestra a chamada ao Service.
    - Encaminha dados para a View.
    - Não grava no banco, não produz echo/HTML aqui.
*/

declare(strict_types=1);

/* [INCLUSÃO] Verificação de permissão institucional */
require_once __DIR__ . '/verificar_permissao.php';

/* [INCLUSÃO] Service de regra (dry-run) */
require_once __DIR__ . '/../model/controle_credito_service.php';

/* [BLOCO] Estado local do controller */
$__erros = [];
$__dados = null;
$__saida = null;

/* [BLOCO] Sanitizadores mínimos */
function cc_num(string $key, float $default = 0.0): float
{
    if (!isset($_POST[$key])) {
        return $default;
    }

    $v = trim((string) $_POST[$key]);

    /* [TRATAMENTO] Converte pt-BR quando houver vírgula (mantém ponto decimal quando já vier em formato en-US) */
    if (strpos($v, ',') !== false) {
        $v = str_replace(['.', ' '], '', $v); // remove milhar e espaços
        $v = str_replace(',', '.', $v);       // vírgula -> ponto
    }

    return is_numeric($v) ? (float) $v : $default;
}

function cc_str(string $key, ?string $default = null): ?string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

/* [BLOCO] Fluxo principal (dry-run) */
$acao = $_POST['acao'] ?? 'form';

if ($acao === 'simular') {
    /* [VALIDAÇÃO] Coleta segura dos campos (dry-run) */
    $__dados = [
        'matricula'          => cc_str('matricula'),
        'salarioBruto'       => cc_num('salarioBruto', 0.0),
        'descontosLegais'    => cc_num('descontosLegais', 0.0),
        'descontosNaoLegais' => cc_num('descontosNaoLegais', 0.0),
        'liquido'            => cc_num('liquido', 0.0),
    ];

    /* [VALIDAÇÃO] Regras defensivas mínimas (líquido pode ser negativo por definição do módulo) */
    foreach (['salarioBruto', 'descontosLegais', 'descontosNaoLegais'] as $c) {
        if ($__dados[$c] < 0) {
            $__erros[] = "Valor inválido em {$c}.";
        }
    }

    /* [BLOCO] Execução da simulação quando sem erros */
    if (empty($__erros)) {
        $__saida = ControleCreditoService::simularParaEmpregado($__dados, 60);
    }
}

/* [REDIRECIONAMENTO] Encaminha para a view com $__dados/$__saida/$__erros */
require __DIR__ . '/../view/controle_credito.php';
