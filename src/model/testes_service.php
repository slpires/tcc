<?php
/*
    /src/model/testes_service.php
    [MODEL / SERVICE]
    Serviço central do MÓDULO TESTES do sistema SLPIRES.COM (TCC UFF).

    Responsabilidades principais:
    - Consultar o catálogo oficial de casos de teste (tabela teste_automatizado).
    - Carregar casos individuais para execução.
    - Delegar a execução aos adapters específicos de cada módulo.
    - Registrar resultados na tabela teste_execucao e em arquivos de log.
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
 *   Retornar a lista de casos de teste cadastrados em teste_automatizado,
 *   aplicando filtros opcionais e incluindo dados da última execução.
 *
 * Parâmetros:
 *   $filtro (array opcional) com chaves:
 *     - 'modulo'       => string
 *     - 'tipo_teste'   => string
 *     - 'prioridade'   => string ('alta','media','baixa')
 *     - 'status_teste' => string (enum da tabela)
 *     - 'cod_teste'    => string
 *     - 'ativo'        => int (1 ou 0)
 *
 * Retorno:
 *   array de arrays associativos com, no mínimo:
 *     - id_teste, cod_teste, modulo, cenario, tipo_teste,
 *       prioridade, status_teste, descricao_teste, ativo,
 *       criado_em, atualizado_em,
 *       executado_em (última execução, se existir),
 *       mensagem    (mensagem da última execução, se existir)
 */
function listarTestes(array $filtro = []): array
{
    $conn = conectar();

    $sql = "
        SELECT
            ta.id_teste,
            ta.cod_teste,
            ta.modulo,
            ta.cenario,
            ta.tipo_teste,
            ta.prioridade,
            ta.status_teste,
            ta.descricao_teste,
            ta.ativo,
            ta.criado_em,
            ta.atualizado_em,
            ult.executado_em,
            ult.mensagem
        FROM teste_automatizado ta
        LEFT JOIN (
            SELECT
                e1.id_teste,
                e1.executado_em,
                e1.mensagem
            FROM teste_execucao e1
            INNER JOIN (
                SELECT
                    id_teste,
                    MAX(executado_em) AS max_exec
                FROM teste_execucao
                GROUP BY id_teste
            ) e2
                ON e2.id_teste = e1.id_teste
               AND e2.max_exec = e1.executado_em
        ) ult
          ON ult.id_teste = ta.id_teste
        WHERE 1 = 1
    ";

    $params = [];
    $types  = '';

    // Filtros opcionais
    if (!empty($filtro['modulo'])) {
        $sql      .= " AND ta.modulo = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['modulo'];
    }

    if (!empty($filtro['tipo_teste'])) {
        $sql      .= " AND ta.tipo_teste = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['tipo_teste'];
    }

    if (!empty($filtro['prioridade'])) {
        $sql      .= " AND ta.prioridade = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['prioridade'];
    }

    if (!empty($filtro['status_teste'])) {
        $sql      .= " AND ta.status_teste = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['status_teste'];
    }

    if (!empty($filtro['cod_teste'])) {
        $sql      .= " AND ta.cod_teste = ?";
        $types    .= 's';
        $params[]  = (string) $filtro['cod_teste'];
    }

    if (isset($filtro['ativo']) && $filtro['ativo'] !== '') {
        $sql      .= " AND ta.ativo = ?";
        $types    .= 'i';
        $params[]  = (int) $filtro['ativo'];
    }

    // Ordenação padrão: prioridade > módulo > cenário
    $sql .= " ORDER BY ta.prioridade DESC, ta.modulo ASC, ta.cenario ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        $erro = mysqli_error($conn);
        mysqli_close($conn);
        throw new Exception('Erro ao preparar consulta de testes: ' . $erro);
    }

    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        $erro = mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        throw new Exception('Erro ao executar consulta de testes: ' . $erro);
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
 * listarCodigosTestes
 * -------------------
 * Finalidade:
 *   Retornar uma lista com todos os códigos de teste (cod_teste)
 *   ativos no catálogo, em ordem alfabética.
 *
 * Retorno:
 *   array de strings.
 */
function listarCodigosTestes(): array
{
    $conn = conectar();

    $sql = "
        SELECT DISTINCT cod_teste
          FROM teste_automatizado
         WHERE ativo = 1
         ORDER BY cod_teste ASC
    ";

    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        $erro = mysqli_error($conn);
        mysqli_close($conn);
        throw new Exception('Erro ao listar códigos de teste: ' . $erro);
    }

    $codigos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $codigo = isset($row['cod_teste']) ? trim((string) $row['cod_teste']) : '';
        if ($codigo !== '') {
            $codigos[] = $codigo;
        }
    }

    mysqli_free_result($result);
    mysqli_close($conn);

    return $codigos;
}

/**
 * carregarTeste
 * -------------
 * Finalidade:
 *   Carregar um caso de teste específico do catálogo teste_automatizado.
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
            cod_teste,
            modulo,
            cenario,
            tipo_teste,
            prioridade,
            descricao_teste,
            status_teste,
            data_execucao,
            observacoes,
            ativo,
            criado_em,
            atualizado_em
        FROM teste_automatizado
        WHERE id_teste = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        $erro = mysqli_error($conn);
        mysqli_close($conn);
        throw new Exception('Erro ao preparar consulta de teste: ' . $erro);
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_teste);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        $erro = mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        throw new Exception('Erro ao executar consulta de teste: ' . $erro);
    }

    $dados = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if (!$dados) {
        throw new Exception('Caso de teste não encontrado: ID ' . $id_teste);
    }

    return $dados;
}

/**
 * executarTeste
 * -------------
 * Finalidade:
 *   - Resolver o adapter correto para o módulo.
 *   - Delegar a execução para o adapter.
 *   - Registrar o resultado (tabela teste_execucao) e log (arquivo).
 *   - Retornar estrutura compatível com as views de resultado.
 *
 * Parâmetros:
 *   $caso (array): normalmente resultado de carregarTeste().
 *                  Pode conter, opcionalmente:
 *      - 'entrada'  => array|null (payload de entrada)
 *      - 'esperado' => array|null (resultado esperado)
 *
 *   $opcoes (array):
 *      - 'dry_run'   => bool|null (se null, assume true)
 *      - 'registrar' => bool (default true)
 *      - 'log'       => bool (default true)
 *
 * Retorno:
 *   array $resultado compatível com a view testes_resultado.php:
 *      - codigo_teste
 *      - rotulo
 *      - titulo
 *      - objetivo
 *      - ok
 *      - mensagem
 *      - inputs
 *      - expected
 *      - items
 *      - summary
 *
 *   + campos adicionais usados internamente:
 *      - status_execucao
 *      - saida_json
 *      - duracao_ms
 *      - dry_run
 *      - entrada
 *      - esperado
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
    $log       = array_key_exists('log',       $opcoes) ? (bool) $opcoes['log']       : true;

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
            Convenção de retorno do adapter:
            - 'status_execucao' => PASS|FAIL|ERROR|SKIP
            - 'saida'           => mixed (payload de saída)
            - 'mensagem'        => string (mensagem técnica/resumida)

            Opcionalmente, pode retornar:
            - 'items'   => array (itens verificados)
            - 'summary' => string (síntese geral)
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

        $items   = isset($resultadoAdapter['items']) && is_array($resultadoAdapter['items'])
            ? $resultadoAdapter['items']
            : [];
        $summary = isset($resultadoAdapter['summary'])
            ? (string) $resultadoAdapter['summary']
            : '';

        $saidaJson = json_encode($saidaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (Throwable $e) {
        // Qualquer exceção no adapter é registrada como ERROR
        $statusExecucao = TEST_STATUS_ERROR;
        $saidaJson      = null;
        $mensagem       = 'Exceção na execução do teste: ' . $e->getMessage();
        $items          = [];
        $summary        = '';
    }

    $duracaoMs = (int) round((microtime(true) - $inicio) * 1000);

    // Metadados de exibição (view)
    $codigoTeste = (string) (
        $caso['cod_teste']
        ?? $caso['codigo_teste']
        ?? $caso['codigo']
        ?? $caso['id_teste']
        ?? ''
    );
    $rotulo   = (string) ($caso['rotulo']          ?? $codigoTeste);
    $titulo   = (string) ($caso['descricao_teste'] ?? $caso['cenario'] ?? '');
    $objetivo = (string) ($caso['descricao_teste'] ?? '');

    $resultado = [
        // Metadados para a view
        'codigo_teste' => $codigoTeste,
        'rotulo'       => $rotulo,
        'titulo'       => $titulo,
        'objetivo'     => $objetivo,
        'ok'           => ($statusExecucao === TEST_STATUS_PASS),
        'mensagem'     => $mensagem,
        'inputs'       => is_array($entrada)  ? $entrada  : [],
        'expected'     => is_array($esperado) ? $esperado : [],
        'items'        => $items,
        'summary'      => $summary,

        // Campos técnicos auxiliares
        'status_execucao' => $statusExecucao,
        'saida_json'      => $saidaJson,
        'duracao_ms'      => $duracaoMs,
        'dry_run'         => $dryRun ? 1 : 0,
        'entrada'         => $entrada,
        'esperado'        => $esperado,
    ];

    // Persistência em teste_execucao
    // [AJUSTE DRY-RUN] Execuções em modo dry-run não devem persistir no banco.
    if ($registrar && !$dryRun) {
        registrarResultado(
            (int) $caso['id_teste'],
            $resultado
        );
    }

    // Log em arquivo texto
    if ($log) {
        testes_registrar_log($caso, $resultado, $entrada, $esperado);
    }

    return $resultado;
}

/**
 * registrarResultado
 * ------------------
 * Finalidade:
 *   Inserir um registro em teste_execucao e atualizar o status consolidado
 *   do teste em teste_automatizado.
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
        $erro = mysqli_error($conn);
        mysqli_close($conn);
        throw new Exception('Erro ao preparar insert de execução de teste: ' . $erro);
    }

    $entradaJson  = json_encode($resultado['entrada']  ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $esperadoJson = json_encode($resultado['esperado'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // [AJUSTE SAIDA_JSON] Garante JSON não vazio mesmo em cenários de erro
    $saidaJsonRaw = $resultado['saida_json'] ?? '{}';

    if (is_array($saidaJsonRaw) || is_object($saidaJsonRaw)) {
        $saidaJson = json_encode($saidaJsonRaw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        $saidaJson = (string) $saidaJsonRaw;
    }

    if (trim($saidaJson) === '') {
        $saidaJson = '{}';
    }

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
        Mapear o status_execucao (PASS/FAIL/ERROR/SKIP) para o ENUM de
        status_teste em teste_automatizado:
          - 'nao_executado'
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
           SET status_teste  = ?,
               data_execucao = NOW(), -- [AJUSTE DATA_EXECUCAO] Registro da última execução real
               atualizado_em = NOW()
         WHERE id_teste = ?
         LIMIT 1
    ";

    $stmtUpd = mysqli_prepare($conn, $sqlUpdate);
    if ($stmtUpd === false) {
        $erro = mysqli_error($conn);
        mysqli_close($conn);
        throw new Exception('Erro ao preparar update de status de teste: ' . $erro);
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
