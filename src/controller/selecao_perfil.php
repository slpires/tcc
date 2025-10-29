<?php
/*
    /src/controller/selecao_perfil.php
    [INCLUSÃO]
    Controlador responsável por processar a seleção de perfil do usuário no sistema SLPIRES.COM (TCC UFF).
    Inicializa a sessão, valida o perfil recebido e sorteia um empregado conforme as regras de negócio do perfil selecionado.
*/

// [INCLUSÃO] Caminhos institucionais para redirecionamento dinâmico
require_once __DIR__ . '/../../config/paths.php';

// [INCLUSÃO] Conexão ao banco institucional
require_once __DIR__ . '/../model/conexao.php';

// [SESSÃO] Inicialização idempotente
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// [VALIDAÇÃO] Somente POST é aceito para seleção de perfil
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$url_base}/index.php?pagina=sistema&erro=metodo_invalido");
    exit;
}

// [ENTRADA] Perfil recebido
$perfil = isset($_POST['perfil']) ? trim($_POST['perfil']) : '';
if ($perfil === '') {
    header("Location: {$url_base}/index.php?pagina=sistema&erro=perfil_invalido");
    exit;
}

$conn = conectar();

// [CONSULTA] Perfis válidos dinamicamente da tabela perfil_usuario
$perfis_validos = [];
$sql = "SELECT nome_perfil FROM perfil_usuario";
if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $perfis_validos[] = $row['nome_perfil'];
    }
    mysqli_free_result($result);
}

// [VALIDAÇÃO] Garante que o perfil enviado está entre os permitidos (case-insensitive)
if (!in_array(mb_strtolower($perfil), array_map('mb_strtolower', $perfis_validos), true)) {
    mysqli_close($conn);
    header("Location: {$url_base}/index.php?pagina=sistema&erro=perfil_invalido");
    exit;
}

// [NEGÓCIO] Seleção de empregado conforme perfil
$perfil_padronizado = mb_strtolower($perfil);
$selecionado = null;

if ($perfil_padronizado === 'administrador') {
    // Busca empregados ATIVOS do núcleo TI, priorizando cargos preferenciais
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO'
               AND nucleo_setor_sigla = 'TI'
          ORDER BY 
              CASE 
                  WHEN cargo = 'Coordenadora de TI' THEN 1
                  WHEN cargo = 'Arquiteto de Sistemas' THEN 2
                  ELSE 3
              END, matricula ASC";
    $result = mysqli_query($conn, $sql);

    $preferenciais = [];
    $outros = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['cargo'] === 'Coordenadora de TI' || $row['cargo'] === 'Arquiteto de Sistemas') {
                $preferenciais[] = $row;
            } else {
                $outros[] = $row;
            }
        }
        mysqli_free_result($result);
    }

    // Monta universo de até 2 empregados
    $universo = [];
    foreach ($preferenciais as $emp) {
        $universo[] = $emp;
        if (count($universo) === 2) break;
    }
    if (count($universo) < 2) {
        foreach ($outros as $emp) {
            $universo[] = $emp;
            if (count($universo) === 2) break;
        }
    }

    // Sorteia entre os disponíveis (até 2)
    if (count($universo) > 0) {
        $selecionado = $universo[array_rand($universo)];
    } else {
        // Não há TI: tenta Gerente do RH
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                  FROM empregado
                 WHERE status = 'ATIVO'
                   AND cargo = 'Gerente do RH'
              ORDER BY matricula ASC
                 LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && ($row3 = mysqli_fetch_assoc($result))) {
            $selecionado = $row3;
            mysqli_free_result($result);
        } else {
            // Não há Gerente do RH: pega empregado ativo mais antigo do setor RH
            $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                      FROM empregado
                     WHERE status = 'ATIVO'
                       AND nucleo_setor_sigla = 'RH'
                  ORDER BY matricula ASC
                     LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if ($result && ($row4 = mysqli_fetch_assoc($result))) {
                $selecionado = $row4;
                mysqli_free_result($result);
            } else {
                // Não há RH: pega o empregado ativo mais antigo da empresa
                $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                          FROM empregado
                         WHERE status = 'ATIVO'
                      ORDER BY matricula ASC
                         LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($result && ($row5 = mysqli_fetch_assoc($result))) {
                    $selecionado = $row5;
                    mysqli_free_result($result);
                } else {
                    // Nenhum empregado ATIVO em toda a empresa
                    mysqli_close($conn);
                    header("Location: {$url_base}/index.php?pagina=sistema&erro=nao_encontrado");
                    exit;
                }
            }
        }
    }

} elseif ($perfil_padronizado === 'rh') {
    // Sorteia qualquer empregado ATIVO do núcleo RH
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO'
               AND nucleo_setor_sigla = 'RH'
          ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $selecionado = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

} else {
    // EMPREGADO: sorteia qualquer empregado ATIVO
    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
              FROM empregado
             WHERE status = 'ATIVO'
          ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $selecionado = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
}

// [SAÍDA] Se houve seleção, grava em sessão e redireciona; senão, erro padronizado
if ($selecionado && !empty($selecionado['matricula'])) {
    // Segurança: regenerar ID ao assumir perfil
    session_regenerate_id(true);

    $_SESSION['perfil']    = $perfil;
    $_SESSION['nome']      = $selecionado['nome'] ?? '';
    $_SESSION['matricula'] = $selecionado['matricula'] ?? '';
    $_SESSION['cargo']     = $selecionado['cargo'] ?? '';
    $_SESSION['setor']     = $selecionado['nucleo_setor_sigla'] ?? '';

    mysqli_close($conn);
    // Padrão pós-seleção: voltar ao hub 'sistema' com indicador de sucesso
    header("Location: {$url_base}/index.php?pagina=sistema&sucesso=login_ok");
    exit;
}

mysqli_close($conn);
header("Location: {$url_base}/index.php?pagina=sistema&erro=nao_encontrado");
exit;
