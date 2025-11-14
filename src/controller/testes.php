<?php
/*
    /src/controller/testes.php
    [CONTROLADOR]
    Controller do módulo TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades:
    - Garantir que apenas usuários autorizados acessem o MÓDULO TESTES.
    - Orquestrar a interação entre a camada MODEL (testes_service.php) e as views.
    - Disponibilizar ações de:
        - index   : listagem de casos de teste;
        - run     : execução de um caso específico;
        - runAll  : execução em lote por módulo/tipo de teste;
        - replay  : reexecução de casos reprovados.

    Observação:
    - Este controller segue o padrão funcional do projeto (sem OO).
*/

/* [INCLUSÃO] Verificação de permissão de acesso ao módulo TESTES */
require_once __DIR__ . '/verificar_permissao.php';

/* [INCLUSÃO] Serviços de acesso a dados e execução de testes */
require_once __DIR__ . '/../model/testes_service.php';

/**
 * index
 * -----
 * Finalidade:
 *   Exibir a lista de casos de teste cadastrados, permitindo filtros
 *   por módulo, tipo de teste, prioridade, status e ativo/inativo.
 *
 * Fluxo:
 *   1. Ler filtros de $_GET.
 *   2. Chamar listarTestes($filtro).
 *   3. Encaminhar lista e filtros para a view testes.php.
 */
function testes_index(): void
{
    // [ENTRADA] Captura de filtros via GET
    $filtro = [
        'modulo'       => $_GET['modulo']       ?? '',
        'tipo_teste'   => $_GET['tipo_teste']   ?? '',
        'prioridade'   => $_GET['prioridade']   ?? '',
        'status_teste' => $_GET['status_teste'] ?? '',
        'ativo'        => $_GET['ativo']        ?? '',
    ];

    // [PROCESSO] Consulta ao catálogo de testes
    try {
        $testes = listarTestes($filtro);
        $erro   = '';
    } catch (Exception $e) {
        $testes = [];
        $erro   = 'Erro ao listar testes: ' . $e->getMessage();
    }

    // [SAÍDA] Variáveis esperadas pela view
    $titulo_pagina     = 'Módulo de Testes – Catálogo';
    $mensagem_execucao = $_GET['msg'] ?? '';

    // [VIEW] Renderiza a view principal do módulo TESTES
    require __DIR__ . '/../view/testes.php';
}

/**
 * run
 * ---
 * Finalidade:
 *   Executar um caso de teste específico, identificado por id_teste.
 *
 * Fluxo:
 *   1. Carregar caso de teste (metadados) via carregarTeste().
 *   2. Ler, se existirem, entrada e esperado do formulário (POST) em JSON.
 *   3. Chamar executarTeste().
 *   4. Encaminhar resultado para a view de execução detalhada.
 *
 * Parâmetros:
 *   $id_teste (int) – normalmente recebido via GET (rota/dispatcher).
 */
function testes_run(int $id_teste): void
{
    $erro          = '';
    $resultado     = null;
    $caso          = null;
    $entradaForm   = null;
    $esperadoForm  = null;
    $entrada_json  = '';
    $esperado_json = '';

    try {
        // 1) Carregar metadados do teste
        $caso = carregarTeste($id_teste);

        // 2) Capturar entrada/esperado enviados via formulário (opcional)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrada_json  = $_POST['entrada_json']  ?? '';
            $esperado_json = $_POST['esperado_json'] ?? '';

            $entradaForm  = $entrada_json  !== '' ? json_decode($entrada_json, true)  : null;
            $esperadoForm = $esperado_json !== '' ? json_decode($esperado_json, true) : null;

            // O adapter poderá usar esses payloads.
            $caso['entrada']  = $entradaForm;
            $caso['esperado'] = $esperadoForm;
        }

        // 3) Executar o teste com dry_run verdadeiro por padrão
        $resultado = executarTeste($caso, [
            'dry_run'   => true,   // por padrão, não alterar dados reais
            'registrar' => true,   // registrar em teste_execucao
            'log'       => true,   // escrever em /logs/testes_YYYYMMDD.txt
        ]);

    } catch (Exception $e) {
        $erro = 'Erro ao executar teste: ' . $e->getMessage();
    }

    $titulo_pagina = 'Execução de Teste – Módulo de Testes';

    // View específica para a execução detalhada.
    // Esta view deve exibir:
    // - dados do $caso
    // - JSON de entrada/esperado
    // - $resultado (status, mensagem, duração)
    require __DIR__ . '/../view/testes_execucao.php';
}

/**
 * runAll
 * ------
 * Finalidade:
 *   Executar em lote até 50 casos de teste de um mesmo módulo/tipo_teste.
 *
 * Parâmetros:
 *   $modulo     (string)
 *   $tipo_teste (string)
 */
function testes_runAll(string $modulo, string $tipo_teste): void
{
    $erro       = '';
    $resultados = [];
    $totais     = [
        TEST_STATUS_PASS  => 0,
        TEST_STATUS_FAIL  => 0,
        TEST_STATUS_ERROR => 0,
        TEST_STATUS_SKIP  => 0,
    ];

    try {
        // 1) Buscar até 50 casos para o módulo/tipo informados
        $lista = listarTestes([
            'modulo'     => $modulo,
            'tipo_teste' => $tipo_teste,
            'ativo'      => 1,
        ]);

        $lista = array_slice($lista, 0, 50);

        // 2) Executar cada caso
        foreach ($lista as $caso) {
            $resumo = executarTeste($caso, [
                'dry_run'   => true,
                'registrar' => true,
                'log'       => true,
            ]);

            $status = $resumo['status_execucao'] ?? TEST_STATUS_ERROR;

            if (!isset($totais[$status])) {
                $totais[$status] = 0;
            }
            $totais[$status]++;

            $resultados[] = [
                'caso'   => $caso,
                'resumo' => $resumo,
            ];
        }

    } catch (Exception $e) {
        $erro = 'Erro ao executar lote de testes: ' . $e->getMessage();
    }

    $titulo_pagina = 'Execução em Lote – Módulo de Testes';

    // View específica para exibir o resultado do lote:
    // - tabela com casos e status
    // - resumo $totais
    require __DIR__ . '/../view/testes_lote.php';
}

/**
 * replay
 * ------
 * Finalidade:
 *   Reexecutar casos de teste que não foram aprovados, após correções de código
 *   ou ajustes em dados. Nesta versão, o critério é o status consolidado no
 *   catálogo TESTE_AUTOMATIZADO.
 *
 * Parâmetros:
 *   $modulo     (string)
 *   $tipo_teste (string)
 */
function testes_replay(string $modulo, string $tipo_teste): void
{
    $erro       = '';
    $resultados = [];
    $totais     = [
        TEST_STATUS_PASS  => 0,
        TEST_STATUS_FAIL  => 0,
        TEST_STATUS_ERROR => 0,
        TEST_STATUS_SKIP  => 0,
    ];

    try {
        // 1) Listar casos com status_teste reprovado
        $lista = listarTestes([
            'modulo'       => $modulo,
            'tipo_teste'   => $tipo_teste,
            'status_teste' => 'reprovado',
            'ativo'        => 1,
        ]);

        $lista = array_slice($lista, 0, 50);

        // 2) Reexecutar cada caso
        foreach ($lista as $caso) {
            $resumo = executarTeste($caso, [
                'dry_run'   => true,
                'registrar' => true,
                'log'       => true,
            ]);

            $status = $resumo['status_execucao'] ?? TEST_STATUS_ERROR;

            if (!isset($totais[$status])) {
                $totais[$status] = 0;
            }
            $totais[$status]++;

            $resultados[] = [
                'caso'   => $caso,
                'resumo' => $resumo,
            ];
        }

    } catch (Exception $e) {
        $erro = 'Erro ao reexecutar casos de teste: ' . $e->getMessage();
    }

    $titulo_pagina = 'Reexecução de Casos – Módulo de Testes';

    require __DIR__ . '/../view/testes_replay.php';
}

/*
    [DESPACHANTE SIMPLES]
    ---------------------
    Este bloco final define qual ação será chamada de acordo com
    o parâmetro 'acao' (ex.: testes.php?acao=run&id_teste=1).

    Se o front controller já faz esse despacho, este trecho pode
    ser adaptado ou removido. Para o MVP, ele garante funcionamento
    independente.
*/
$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'run':
        $id_teste = isset($_GET['id_teste']) ? (int) $_GET['id_teste'] : 0;
        testes_run($id_teste);
        break;

    case 'runAll':
        $modulo     = $_GET['modulo']     ?? '';
        $tipo_teste = $_GET['tipo_teste'] ?? '';
        testes_runAll($modulo, $tipo_teste);
        break;

    case 'replay':
        $modulo     = $_GET['modulo']     ?? '';
        $tipo_teste = $_GET['tipo_teste'] ?? '';
        testes_replay($modulo, $tipo_teste);
        break;

    case 'index':
    default:
        testes_index();
        break;
}
?>
