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

if (isset($_POST['perfil'])) {
    $perfil = $_POST['perfil'];
    $conn = conectar();

    /* [INCLUSÃO] Consulta dinâmica dos perfis válidos da tabela perfil_usuario */
    $perfis_validos = [];
    $sql = "SELECT nome_perfil FROM perfil_usuario";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $perfis_validos[] = $row['nome_perfil'];
    }

    /* [VALIDAÇÃO] Garante que o perfil enviado está entre os permitidos, ignorando caixa */
    if (!in_array(mb_strtolower($perfil), array_map('mb_strtolower', $perfis_validos))) {
        mysqli_close($conn);
        header("Location: $url_base/index.php?erro=perfil_invalido");
        exit;
    }

    /* [BLOCO] Seleção de empregado conforme perfil */
    $perfil_padronizado = mb_strtolower($perfil);

    if ($perfil_padronizado === 'administrador') {
        /* [BLOCO] Busca empregados ATIVOS do núcleo TI, priorizando cargos preferenciais */
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
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['cargo'] == 'Coordenadora de TI' || $row['cargo'] == 'Arquiteto de Sistemas') {
                $preferenciais[] = $row;
            } else {
                $outros[] = $row;
            }
        }
        /* Monta universo de até 2 empregados */
        $universo = [];
        foreach ($preferenciais as $emp) {
            $universo[] = $emp;
            if (count($universo) == 2) break;
        }
        if (count($universo) < 2) {
            foreach ($outros as $emp) {
                $universo[] = $emp;
                if (count($universo) == 2) break;
            }
        }
        /* Sorteia entre os disponíveis (até 2) */
        if (count($universo) > 0) {
            $selecionado = $universo[array_rand($universo)];
        } else {
            /* [BLOCO] Não há empregado TI ativo: tenta Gerente do RH */
            $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                      FROM empregado
                     WHERE status = 'ATIVO'
                       AND cargo = 'Gerente do RH'
                  ORDER BY matricula ASC
                     LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if ($row3 = mysqli_fetch_assoc($result)) {
                $selecionado = $row3;
            } else {
                /* [BLOCO] Não há Gerente do RH: pega o empregado ativo mais antigo do setor RH */
                $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                          FROM empregado
                         WHERE status = 'ATIVO'
                           AND nucleo_setor_sigla = 'RH'
                      ORDER BY matricula ASC
                         LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if ($row4 = mysqli_fetch_assoc($result)) {
                    $selecionado = $row4;
                } else {
                    /* [BLOCO] Não há RH: pega o empregado ativo mais antigo da empresa */
                    $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                              FROM empregado
                             WHERE status = 'ATIVO'
                          ORDER BY matricula ASC
                             LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if ($row5 = mysqli_fetch_assoc($result)) {
                        $selecionado = $row5;
                    } else {
                        /* Nenhum empregado ATIVO em toda a empresa */
                        mysqli_close($conn);
                        header("Location: $url_base/index.php?erro=nao_encontrado");
                        exit;
                    }
                }
            }
        }
    } elseif ($perfil_padronizado === 'rh') {
        /* [BLOCO] Sorteia qualquer empregado ATIVO do núcleo RH */
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                  FROM empregado
                 WHERE status = 'ATIVO'
                   AND nucleo_setor_sigla = 'RH'
              ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $selecionado = mysqli_fetch_assoc($result);
    } else {
        /* [BLOCO] Sorteia qualquer empregado ATIVO (perfil EMPREGADO) */
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla
                  FROM empregado
                 WHERE status = 'ATIVO'
              ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $selecionado = mysqli_fetch_assoc($result);
    }

    /* [BLOCO] Se algum empregado foi selecionado, grava na sessão e redireciona */
    if ($selecionado && $selecionado['matricula']) {
        $_SESSION['perfil']    = $perfil;
        $_SESSION['nome']      = $selecionado['nome'];
        $_SESSION['matricula'] = $selecionado['matricula'];
        $_SESSION['cargo']     = $selecionado['cargo'];
        $_SESSION['setor']     = $selecionado['nucleo_setor_sigla'];
        mysqli_close($conn);
        header("Location: $url_base/index.php?pagina=modulos");
        exit;
    } else {
        mysqli_close($conn);
        header("Location: $url_base/index.php?erro=nao_encontrado");
        exit;
    }
}

/* [TRATAMENTO] Chamada direta ao controller (sem POST): retorna à seleção de perfil */
header("Location: $url_base/index.php");
exit;
