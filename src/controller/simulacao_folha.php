<?php
/*
    /src/controller/simulacao_folha.php
    [INCLUSÃO]
    Controller do módulo Simulação da Folha de Pagamento.
    Responsável por validação de permissão e inclusão da view correspondente.
*/
require_once __DIR__ . '/verificar_permissao.php';
// [BLOCO] Aqui podem ser adicionadas regras e integrações futuras do módulo.
require_once __DIR__ . '/../view/simulacao_folha.php';
?>
