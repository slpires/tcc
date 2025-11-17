<?php
/*
    /src/model/te_test_adapter.php
    Adapter de testes para o módulo TESTES (TE) do sistema SLPIRES.COM (TCC UFF).

    Finalidade:
    - Validar a sanidade básica do próprio MÓDULO TESTES, verificando se:
        - o módulo informado no catálogo é TE;
        - o identificador do teste (id_teste) é válido;
        - o cenário foi informado;
        - a descrição do teste foi informada.

    Convenção de integração:
    - Este adapter é resolvido por testes_resolver_adapter(), definido em
      /src/model/testes_service.php, com base no nome do módulo em minúsculo.
    - Arquivo:  te_test_adapter.php
    - Função:   te_run_test_case(array $caso, array $opcoes = []): array

    Convenção de retorno do adapter:
    - 'status_execucao' => TEST_STATUS_PASS | TEST_STATUS_FAIL | TEST_STATUS_ERROR | TEST_STATUS_SKIP
    - 'saida'           => mixed (payload de saída para eventual inspeção)
    - 'mensagem'        => string (mensagem técnica/resumida)
    - 'items'           => array de verificações individuais (cada item com 'descricao', 'ok', 'mensagem')
    - 'summary'         => string (síntese geral do resultado)
*/

/**
 * te_run_test_case
 * ----------------
 * Caso de teste de sanidade do próprio MÓDULO TESTES (TE).
 *
 * Parâmetros:
 *   $caso (array): metadados do caso de teste, normalmente oriundos de
 *                  carregarTeste() em testes_service.php.
 *                  Utiliza, principalmente:
 *                    - id_teste
 *                    - modulo
 *                    - cenario
 *                    - prioridade
 *                    - descricao_teste
 *
 *   $opcoes (array opcional):
 *     - 'dry_run' => bool (indica se a execução é apenas de simulação)
 *
 * Retorno:
 *   array compatível com o contrato dos adapters de teste.
 */
function te_run_test_case(array $caso, array $opcoes = []): array
{
    $idTeste    = $caso['id_teste']        ?? null;
    $modulo     = $caso['modulo']          ?? '';
    $cenario    = $caso['cenario']         ?? '';
    $prioridade = $caso['prioridade']      ?? '';
    $descricao  = $caso['descricao_teste'] ?? '';

    $dryRun = !empty($opcoes['dry_run']);

    $items   = [];
    $okGeral = true;

    // Verificação 1: módulo deve ser TE
    $itemOk = ($modulo === 'TE');
    $items[] = [
        'descricao' => 'Módulo do caso de teste é TE.',
        'ok'        => $itemOk,
        'mensagem'  => $itemOk ? 'Módulo correto (TE).' : 'Módulo diferente de TE.',
    ];
    $okGeral = $okGeral && $itemOk;

    // Verificação 2: ID de teste válido (numérico e > 0)
    $itemOk = is_numeric($idTeste) && (int) $idTeste > 0;
    $items[] = [
        'descricao' => 'Identificador do teste é válido.',
        'ok'        => $itemOk,
        'mensagem'  => $itemOk ? 'ID de teste numérico e > 0.' : 'ID de teste inválido.',
    ];
    $okGeral = $okGeral && $itemOk;

    // Verificação 3: cenário não vazio
    $itemOk = trim((string) $cenario) !== '';
    $items[] = [
        'descricao' => 'Cenário do teste informado.',
        'ok'        => $itemOk,
        'mensagem'  => $itemOk ? 'Cenário preenchido.' : 'Cenário vazio.',
    ];
    $okGeral = $okGeral && $itemOk;

    // Verificação 4: descrição do teste não vazia
    $itemOk = trim((string) $descricao) !== '';
    $items[] = [
        'descricao' => 'Descrição do teste informada.',
        'ok'        => $itemOk,
        'mensagem'  => $itemOk ? 'Descrição preenchida.' : 'Descrição vazia.',
    ];
    $okGeral = $okGeral && $itemOk;

    // Payload de saída (para eventual inspeção na view de resultados)
    $saida = [
        'id_teste'   => $idTeste,
        'modulo'     => $modulo,
        'cenario'    => $cenario,
        'prioridade' => $prioridade,
        'descricao'  => $descricao,
        'dry_run'    => $dryRun,
    ];

    $statusExecucao = $okGeral ? TEST_STATUS_PASS : TEST_STATUS_FAIL;

    return [
        'status_execucao' => $statusExecucao,
        'saida'           => $saida,
        'mensagem'        => $okGeral
            ? 'Teste de sanidade do módulo TESTES executado com sucesso.'
            : 'Uma ou mais verificações de sanidade do módulo TESTES falharam.',
        'items'           => $items,
        'summary'         => $okGeral
            ? 'Sanidade básica do módulo TESTES considerada satisfatória.'
            : 'Sanidade básica do módulo TESTES apresentou problemas.',
    ];
}
