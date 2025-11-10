<?php
/*
    /src/controller/simulacao_folha.php
    [INCLUSÃO]
    Controller do módulo Simulação da Folha de Pagamento.
    Responsável por validação de permissão e inclusão da view correspondente.
*/
require_once __DIR__ . '/verificar_permissao.php';

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

require_once __DIR__ . '/../model/simulacao_folha_service.php';
require_once __DIR__ . '/../model/controle_credito_adapter.php';

/* [INICIALIZAÇÃO] Competência atual em sessão (idempotente) */
$competenciaInicial = sf_competencia_inicial();
if (!isset($_SESSION['competencia_atual']) || !is_string($_SESSION['competencia_atual'])) {
    $_SESSION['competencia_atual'] = $competenciaInicial;
}
$competenciaAtual = $_SESSION['competencia_atual'];

$erro          = null;
$resultado     = [];
$contracheques = [];

/* [ENTRADA] Gatilho */
$executar = filter_input(INPUT_GET, 'executar', FILTER_SANITIZE_NUMBER_INT);

/* [FLUXO] Execução (DRY-RUN) */
if ($executar === '1') {
    try {
        if (sf_competencia_fechada($competenciaAtual)) {
            $_SESSION['flash_erro'] = 'competencia_fechada';
        } else {
            $contracheques  = sf_gerar_contracheques_dryrun($competenciaAtual);
            $estadoAnterior = sf_estado_credito_anterior();

            $resultado = cc_calcular_para_competencia($contracheques, $estadoAnterior);

            sf_atualizar_estado_credito($resultado);
            sf_marcar_fechada($competenciaAtual);

            $_SESSION['competencia_atual'] = sf_proxima_competencia($competenciaAtual);

            /* [FLASH] Snapshot do resultado para a próxima requisição */
            $_SESSION['flash_resultado'] = [
                'linhas'        => $resultado,
                'competencia'   => $competenciaAtual,
                'gerado_em'     => date('Y-m-d H:i:s'),
            ];
        }

        session_write_close();
        header('Location: index.php?r=simulacao');
        exit;

    } catch (Throwable $e) {
        $_SESSION['flash_erro'] = 'falha_execucao';
        session_write_close();
        header('Location: index.php?r=simulacao');
        exit;
    }
}

/* [FLASH-READ] Recupera e limpa mensagens/resultado */
if (isset($_SESSION['flash_erro'])) {
    $erro = $_SESSION['flash_erro'];
    unset($_SESSION['flash_erro']);
}
if (isset($_SESSION['flash_resultado']) && is_array($_SESSION['flash_resultado'])) {
    $snap      = $_SESSION['flash_resultado'];
    $resultado = $snap['linhas'] ?? [];
    unset($_SESSION['flash_resultado']);
}

/* [SAÍDA] Variáveis da view */
$competenciaAtual = $_SESSION['competencia_atual'] ?? $competenciaInicial;

$view = [
    'competencia'   => $competenciaAtual,
    'erro'          => $erro,
    'resultado'     => $resultado,
    'contracheques' => $contracheques, // opcional (não exibido se vazio)
];

require_once __DIR__ . '/../view/simulacao_folha.php';
