<?php
/*
    /src/model/simulacao_folha_service.php
    [INCLUSÃO]
    Serviço DRY-RUN de simulação de contracheques e controle de competência.
    Mantém estado em sessão; não persiste em banco de dados.
*/

declare(strict_types=1);

/* [BLOCO] Inicialização de sessão controlada */
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

/* [BLOCO] Competência utilitária */
function sf_competencia_inicial(): string {
    return $_SESSION['competencia_atual'] ?? '01/2019';
}
function sf_proxima_competencia(string $mmAAAA): string {
    [$mm, $aaaa] = explode('/', $mmAAAA);
    $m = (int)$mm; $a = (int)$aaaa;
    $m++; if ($m > 12) { $m = 1; $a++; }
    return str_pad((string)$m, 2, '0', STR_PAD_LEFT) . '/' . $a;
}

/* [BLOCO] Fechamento (impede reprocessamento da competência) */
function sf_competencia_fechada(string $mmAAAA): bool {
    return !empty($_SESSION['sf_fechadas'][$mmAAAA]);
}
function sf_marcar_fechada(string $mmAAAA): void {
    $_SESSION['sf_fechadas'][$mmAAAA] = true;
}

/* [BLOCO] Stub DRY-RUN de contracheques da competência */
function sf_gerar_contracheques_dryrun(string $competencia): array {
    // 3 empregados; 1 com líquido negativo para forçar crédito automático
    return [
        [
            'competencia'          => $competencia,
            'matricula'            => '001',
            'nome'                 => 'Ana Silva',
            'bruto'                => 5000.00,
            'descontos_legais'     => 1200.00,
            'descontos_nao_legais' => 800.00,
            'liquido'              => 3000.00
        ],
        [
            'competencia'          => $competencia,
            'matricula'            => '002',
            'nome'                 => 'Carlos Souza',
            'bruto'                => 4200.00,
            'descontos_legais'     => 1300.00,
            'descontos_nao_legais' => 1700.00,
            'liquido'              => 1200.00
        ],
        [
            'competencia'          => $competencia,
            'matricula'            => '003',
            'nome'                 => 'Beatriz Nunes',
            'bruto'                => 3800.00,
            'descontos_legais'     => 1100.00,
            'descontos_nao_legais' => 2900.00,
            'liquido'              => -200.00
        ],
    ];
}

/* [BLOCO] Estado de saldo de crédito (em memória) */
function sf_estado_credito_anterior(): array {
    return $_SESSION['cc_estado'] ?? [];
}
function sf_atualizar_estado_credito(array $resultado): void {
    foreach ($resultado as $r) {
        $_SESSION['cc_estado'][$r['matricula']]['saldo'] = $r['saldo_atual'];
    }
}
