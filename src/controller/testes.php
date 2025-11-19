<?php
/*
    /src/controller/testes.php
    [CONTROLADOR]
    Módulo TESTES do sistema SLPIRES.COM (TCC UFF).

    Ações disponíveis:
    - index : seleção de código de teste, exibição de detalhes e botão Executar teste;
    - run   : execução individual de um caso de teste.
*/

require_once __DIR__ . '/verificar_permissao.php';
require_once __DIR__ . '/../model/testes_service.php';

/**
 * View principal do módulo de testes.
 * - Carrega os códigos disponíveis;
 * - Garante um código padrão (primeiro da lista, ex.: [CT-00] - TE-SANI);
 * - Carrega o teste correspondente ao código selecionado.
 */
function testes_index(): void
{
    $titulo_pagina     = 'Catálogo de Casos de Testes';
    $mensagem_execucao = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $erro              = '';
    $teste_selecionado = null;

    // Código recebido por GET (opcional)
    $cod_teste_param = filter_input(INPUT_GET, 'cod_teste', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($cod_teste_param === null || $cod_teste_param === false) {
        $cod_teste_param = '';
    }

    try {
        // Domínio de códigos de teste para o combo
        $codigos_teste = listarCodigosTestes(); // ex.: ["[CT-00] - TE-SANI", "[CT-01] - ..."]

        if (!empty($codigos_teste)) {
            // Se não veio nada ou veio um código inválido, usa o primeiro da lista
            if ($cod_teste_param === '' || !in_array($cod_teste_param, $codigos_teste, true)) {
                $cod_teste = $codigos_teste[0];
            } else {
                $cod_teste = $cod_teste_param;
            }

            // Filtro simplificado
            $filtro = ['cod_teste' => $cod_teste];

            // Consulta catálogo
            $lista = listarTestes($filtro);

            if (!empty($lista)) {
                $teste_selecionado = $lista[0];
            } else {
                $erro = 'Nenhum teste encontrado para o código selecionado.';
            }
        } else {
            $codigos_teste = [];
            $erro          = 'Nenhum teste cadastrado no catálogo de testes automatizados.';
        }
    } catch (Exception $e) {
        $codigos_teste     = [];
        $teste_selecionado = null;
        $erro              = 'Erro ao carregar catálogo de testes: ' . $e->getMessage();
    }

    require __DIR__ . '/../view/testes.php';
}

/**
 * Execução individual de um teste.
 */
function testes_run(int $id_teste): void
{
    if ($id_teste <= 0) {
        $erro          = 'Identificador de teste inválido.';
        $titulo_pagina = 'Erro na Execução de Teste – Módulo de Testes';
        require __DIR__ . '/../view/testes_erro.php';
        return;
    }

    try {
        $caso = carregarTeste($id_teste);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrada_json  = trim((string)(filter_input(INPUT_POST, 'entrada_json',  FILTER_UNSAFE_RAW) ?? ''));
            $esperado_json = trim((string)(filter_input(INPUT_POST, 'esperado_json', FILTER_UNSAFE_RAW) ?? ''));

            $entradaForm  = $entrada_json  !== '' ? json_decode($entrada_json, true)  : null;
            $esperadoForm = $esperado_json !== '' ? json_decode($esperado_json, true) : null;

            $caso['entrada']  = $entradaForm;
            $caso['esperado'] = $esperadoForm;
        }

        $resultado = executarTeste($caso, [
            'dry_run'   => false,   // <<< agora grava em banco
            'registrar' => true,
            'log'       => true,
        ]);

        $titulo_pagina = 'Execução de Teste – Módulo de Testes';
        require __DIR__ . '/../view/testes_resultado.php';
        return;

    } catch (Exception $e) {
        $erro = 'Erro ao executar teste: ' . $e->getMessage();

        $codigo_teste = (is_array($caso ?? null) && isset($caso['cod_teste']))
            ? (string)$caso['cod_teste']
            : '';

        $erro_execucao = [
            'codigo_teste' => $codigo_teste,
            'mensagem'     => 'Erro ao executar teste.',
            'detalhe'      => $e->getMessage(),
        ];

        $titulo_pagina = 'Erro na Execução de Teste – Módulo de Testes';
        require __DIR__ . '/../view/testes_erro.php';
        return;
    }
}

/*
    Despachante simples
*/
$acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($acao === null || $acao === false || $acao === '') {
    $acao = 'index';
}

switch ($acao) {
    case 'run':
        $id_teste = filter_input(
            INPUT_GET,
            'id_teste',
            FILTER_VALIDATE_INT,
            ['options' => ['default' => 0, 'min_range' => 0]]
        );
        testes_run((int)$id_teste);
        break;

    case 'index':
    default:
        testes_index();
        break;
}
?>
