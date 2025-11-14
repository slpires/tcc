<?php
/*
    /src/model/testes_service.php
    [INCLUSÃO]
    Serviço central do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades principais:
    - Consultar o catálogo oficial de casos de teste (tabela TESTE_AUTOMATIZADO).
    - Carregar casos individuais para execução.
    - Delegar a execução aos adapters específicos de cada módulo.
    - Registrar resultados na tabela TESTE_EXECUCAO e em arquivos de log.
    - Atualizar o status consolidado de cada teste no catálogo.

    Importante:
    - Todos os acessos ao banco utilizam a função conectar(), definida em conexao.php.
    - Este arquivo não deve conter HTML ou lógica de controller; é camada MODEL.
*/

require_once __DIR__ . '/conexao.php';

/* [CONSTANTES] Status padrão de execução de teste */
const TEST_STATUS_PASS  = 'PASS';
const TEST_STATUS_FAIL  = 'FAIL';
const TEST_STATUS_ERROR = 'ERROR';
const TEST_STATUS_SKIP  = 'SKIP';

/**
 * listarTestes
 * ------------
 * Finalidade:
 *   Retornar a lista de casos de teste cadastrados em TESTE_AUTOMATIZADO,
 *   aplicando filtros opcionais.
 *
 * Parâmetros:
 *   $filtro (array opcional) com chaves:
 *     - 'modulo'       => string
 *     - 'tipo_teste'   => string
 *     - 'prioridade'   => string ('alta','media','baixa')
 *     - 'status_teste' => string (enum da tabela)
 *     - 'ativo'        => int (1 ou 0)
 *
 * Retorno:
 *   array de arrays associativos.
 */
function listarTestes(array $filtro = []): array
{
    $conn = conectar();

    $sql = "
        SELECT
            id_teste,
            modulo,
            cenario,
            tipo_teste,
            prioridade,
            status_teste,
            ativo,
            criado_em,
            atualizado_em
        FROM teste_automatizado
        WHERE 1 = 1
    ";

    $params = [];
    $types  = '';

    // Filtros opcionais
    if (!empty($filtro['modulo'])) {
        $sql      .= " AND modulo = ?";
        $types    .= 's';
        $params[]  = $filtro['modulo'];
    }

    if (!empty($filtro['tipo_teste'])) {
        $sql      .= " AND tipo_teste = ?";
        $types    .= 's';
        $params[]  = $filtro['tipo_teste'];
    }

    if (!empty($filtro['prioridade'])) {
        $sql      .= " AND prioridade = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['prioridade'];
    }

    if (!empty($filtro['status_teste'])) {
        $sql      .= " AND status_teste = ?";
        $types    .= 's';
        $params[]  = $filtro['status_teste'];
    }

    if (isset($filtro['ativo']) && $filtro['ativo'] !== '') {
        $sql      .= " AND ativo = ?";
        $types    .= 'i';
        $params[]  = (int) $filtro['ativo'];
    }

    // Ordenação padrão: prioridade > módulo > cenário
    $sql .= " ORDER BY prioridade DESC, modulo ASC, cenario ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        throw new Exception('Erro ao preparar consulta de testes: ' . mysqli_error($conn));
    }

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception('Erro ao executar consulta de testes: ' . mysqli_error($conn));
    }

    $lista = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $lista[] = $row;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $lista;
}

/**
 * carregarTeste
 * -------------
 * Finalidade:
 *   Carregar um caso de teste específico do catálogo TESTE_AUTOMATIZADO.
 *
 * Observação:
 *   A tabela TESTE_AUTOMATIZADO guarda metadados do teste
 *   (módulo, cenário, prioridade, tipo_teste etc.).
 *   Os payloads de entrada/esperado são recebidos pelo controller/view
 *   e registrados em TESTE_EXECUCAO no momento da execução.
 *
 * Parâmetros:
 *   $id_teste (int)
 *
 * Retorno:
 *   array associativo com os campos do teste.
 *
 * Exceções:
 *   Exception se o teste não existir.
 */
function carregarTeste(int $id_teste): array
{
    $conn = conectar();

    $sql = "
        SELECT
            id_teste,
            modulo,
            cenario,
            tipo_teste,
            prioridade,
            descricao_teste,
            status_teste,
            ativo,
            criado_em,
            atualizado_em
        FROM teste_automatizado
        WHERE id_teste = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        throw new Exception('Erro ao preparar consulta de teste: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_teste);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception('Erro ao executar consulta de teste: ' . mysqli_error($conn));
    }

    $dados = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if (!$dados) {
        throw new Exception('Caso de teste não encontrado: ID ' . $id_teste);
    }

    /*
        Nota:
        - $dados NÃO possui entrada_json nem esperado_json, pois estes
          não fazem parte da tabela TESTE_AUTOMATIZADO neste momento.
        - O controller pode completar $dados com:
            $dados['entrada']  = [...];
            $dados['esperado'] = [...];
          antes de chamar executarTeste().
    */

    return $dados;
}

/**
 * executarTeste
 * -------------
 * Finalidade:
 *   - Resolver o adapter correto para o módulo.
 *   - Delegar a execução para o adapter.
 *   - Registrar o resultado (tabela TESTE_EXECUCAO) e log (arquivo).
 *
 * Parâmetros:
 *   $caso (array): normalmente resultado de carregarTeste(), podendo
 *                  conter também:
 *      - 'entrada'  => array|null (payload de entrada)
 *      - 'esperado' => array|null (resultado esperado)
 *
 *   $opcoes (array):
 *      - 'dry_run'   => bool|null (se null, assume true)
 *      - 'registrar' => bool (default true)
 *      - 'log'       => bool (default true)
 *
 * Retorno:
 *   array com:
 *      - status_execucao
 *      - saida_json
 *      - mensagem
 *      - duracao_ms
 *      - dry_run
 */
function executarTeste(array $caso, array $opcoes = []): array
{
    $modulo = $caso['modulo'] ?? '';
    if ($modulo === '') {
        throw new Exception('Caso de teste sem módulo definido.');
    }

    // Dry-run: se não vier nada, assume true (execução segura)
    $dryRun = array_key_exists('dry_run', $opcoes)
        ? (bool) $opcoes['dry_run']
        : true;

    $registrar = array_key_exists('registrar', $opcoes) ? (bool) $opcoes['registrar'] : true;
    $log       = array_key_exists('log', $opcoes)       ? (bool) $opcoes['log']       : true;

    // Resolução do adapter responsável pelo módulo
    $adapter = testes_resolver_adapter($modulo);
    if (!is_array($adapter) || !isset($adapter['run'])) {
        throw new Exception('Adapter inválido ou não carregado para o módulo: ' . $modulo);
    }

    $entrada  = $caso['entrada']  ?? null;
    $esperado = $caso['esperado'] ?? null;

    $inicio = microtime(true);

    try {
        /*
            Chamada padrão ao adapter:
            - Função: <modulo>_run_test_case(array $caso, array $opcoes): array

            O adapter deve retornar um array com ao menos:
            - 'status_execucao' => PASS|FAIL|ERROR|SKIP
            - 'saida'           => mixed (payload de saída)
            - 'mensagem'        => string (mensagem técnica)
        */
        $resultadoAdapter = call_user_func(
            $adapter['run'],
            $caso,
            [
                'dry_run' => $dryRun,
                'entrada' => $entrada,
                'esperado'=> $esperado,
            ]
        );

        $statusExecucao = $resultadoAdapter['status_execucao'] ?? TEST_STATUS_ERROR;
        $saidaPayload   = $resultadoAdapter['saida']          ?? null;
        $mensagem       = $resultadoAdapter['mensagem']       ?? '';

        $saidaJson = json_encode($saidaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (Throwable $e) {
        // Qualquer exceção no adapter é registrada como ERROR
        $statusExecucao = TEST_STATUS_ERROR;
        $saidaJson      = null;
        $mensagem       = 'Exceção na execução do teste: ' . $e->getMessage();
    }

    $duracaoMs = (int) round((microtime(true) - $inicio) * 1000);

    $resumo = [
        'status_execucao' => $statusExecucao,
        'saida_json'      => $saidaJson,
        'mensagem'        => $mensagem,
        'duracao_ms'      => $duracaoMs,
        'dry_run'         => $dryRun ? 1 : 0,
    ];

    // Persistência em TESTE_EXECUCAO
    if ($registrar) {
        registrarResultado(
            (int) $caso['id_teste'],
            [
                'status_execucao' => $statusExecucao,
                'entrada'         => $entrada,
                'esperado'        => $esperado,
                'saida_json'      => $saidaJson,
                'mensagem'        => $mensagem,
                'duracao_ms'      => $duracaoMs,
                'dry_run'         => $dryRun ? 1 : 0,
            ]
        );
    }

    // Log em arquivo texto
    if ($log) {
        testes_registrar_log($caso, $resumo, $entrada, $esperado);
    }

    return $resumo;
}

/**
 * registrarResultado
 * ------------------
 * Finalidade:
 *   Inserir um registro em TESTE_EXECUCAO e atualizar o status consolidado
 *   do teste em TESTE_AUTOMATIZADO.
 *
 * Parâmetros:
 *   $id_teste  (int)
 *   $resultado (array)
 *
 * Retorno:
 *   int ID da execução gerada (id_execucao).
 */
function registrarResultado(int $id_teste, array $resultado): int
{
    $conn = conectar();

    $sqlInsert = "
        INSERT INTO teste_execucao (
            id_teste,
            entrada_json,
            esperado_json,
            saida_json,
            status_execucao,
            duracao_ms,
            mensagem,
            dry_run,
            executado_em
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )
    ";

    $stmt = mysqli_prepare($conn, $sqlInsert);
    if ($stmt === false) {
        throw new Exception('Erro ao preparar insert de execução de teste: ' . mysqli_error($conn));
    }

    $entradaJson  = json_encode($resultado['entrada']  ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $esperadoJson = json_encode($resultado['esperado'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $saidaJson      = (string) ($resultado['saida_json']      ?? '');
    $statusExecucao = (string) ($resultado['status_execucao'] ?? TEST_STATUS_ERROR);
    $duracaoMs      = (int)    ($resultado['duracao_ms']      ?? 0);
    $mensagem       = (string) ($resultado['mensagem']        ?? '');
    $dryRun         = (int)    ($resultado['dry_run']         ?? 1);

    mysqli_stmt_bind_param(
        $stmt,
        'issssisi',
        $id_teste,
        $entradaJson,
        $esperadoJson,
        $saidaJson,
        $statusExecucao,
        $duracaoMs,
        $mensagem,
        $dryRun
    );

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt) !== 0) {
        $erro = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        throw new Exception('Erro ao registrar execução de teste: ' . $erro);
    }

    $idExecucao = (int) mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    /*
        [AJUSTE CRÍTICO]
        ----------------
        Mapear o status_execucao (PASS/FAIL/ERROR/SKIP) para o ENUM de
        status_teste em TESTE_AUTOMATIZADO:
          - 'nao_executado'
          - 'executando'
          - 'aprovado'
          - 'reprovado'
    */
    switch ($statusExecucao) {
        case TEST_STATUS_PASS:
            $statusCatalogo = 'aprovado';
            break;

        case TEST_STATUS_FAIL:
        case TEST_STATUS_ERROR:
            $statusCatalogo = 'reprovado';
            break;

        case TEST_STATUS_SKIP:
        default:
            $statusCatalogo = 'nao_executado';
            break;
    }

    // Atualização do status consolidado no catálogo
    $sqlUpdate = "
        UPDATE teste_automatizado
           SET status_teste = ?,
               atualizado_em = NOW()
         WHERE id_teste = ?
         LIMIT 1
    ";

    $stmtUpd = mysqli_prepare($conn, $sqlUpdate);
    if ($stmtUpd === false) {
        mysqli_close($conn);
        throw new Exception('Erro ao preparar update de status de teste: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmtUpd, 'si', $statusCatalogo, $id_teste);
    mysqli_stmt_execute($stmtUpd);
    mysqli_stmt_close($stmtUpd);

    mysqli_close($conn);

    return $idExecucao;
}

/**
 * testes_resolver_adapter
 * -----------------------
 * Finalidade:
 *   Resolver o adapter correto para o módulo informado.
 *
 * Convenção:
 *   Arquivo do adapter:
 *     /src/model/<modulo>_test_adapter.php
 *
 *   Função exposta pelo adapter:
 *     <modulo>_run_test_case(array $caso, array $opcoes): array
 */
function testes_resolver_adapter(string $modulo): array
{
    $modulo  = strtolower(trim($modulo));
    $arquivo = __DIR__ . '/' . $modulo . '_test_adapter.php';

    if (!is_file($arquivo)) {
        throw new Exception('Arquivo de adapter não encontrado para o módulo: ' . $modulo);
    }

    require_once $arquivo;

    $funcao = $modulo . '_run_test_case';
    if (!function_exists($funcao)) {
        throw new Exception('Função de execução de adapter não encontrada: ' . $funcao);
    }

    return [
        'file' => $arquivo,
        'run'  => $funcao,
    ];
}

/**
 * testes_registrar_log
 * --------------------
 * Finalidade:
 *   Registrar em arquivo texto um resumo da execução do teste,
 *   para fins de auditoria e governança.
 *
 * Arquivo de log:
 *   /logs/testes_YYYYMMDD.txt
 */
function testes_registrar_log(array $caso, array $resumo, $entrada = null, $esperado = null): void
{
    $dataHoje  = date('Ymd');
    $horaAgora = date('H:i:s');

    $linha = sprintf(
        "[%s] id_teste=%s modulo=%s cenario=%s tipo=%s prioridade=%s status=%s dry_run=%s duracao_ms=%s msg=%s\n",
        $horaAgora,
        $caso['id_teste']           ?? '?',
        $caso['modulo']             ?? '?',
        $caso['cenario']            ?? '?',
        $caso['tipo_teste']         ?? '?',
        $caso['prioridade']         ?? '?',
        $resumo['status_execucao']  ?? '?',
        $resumo['dry_run']          ?? '?',
        $resumo['duracao_ms']       ?? '?',
        substr((string) ($resumo['mensagem'] ?? ''), 0, 200)
    );

    $caminhoLog = __DIR__ . '/../../logs/testes_' . $dataHoje . '.txt';

    // Erros de escrita em log não devem interromper a execução do teste
    try {
        @file_put_contents($caminhoLog, $linha, FILE_APPEND | LOCK_EX);
    } catch (Throwable $e) {
        // Silencioso por segurança.
    }
}
