<?php
// /src/controller/selecao_perfil.php

// [INCLUSÃO]
// Inicializa sessão e importa função de conexão ao banco
session_start();
require_once __DIR__ . '/../model/conexao.php';

// [BLOCO] Verifica se o formulário de seleção de perfil foi enviado
if (isset($_POST['perfil'])) {
    $perfil = $_POST['perfil'];
    $perfis_validos = ['ADMINISTRADOR', 'RH', 'EMPREGADO'];

    // [VALIDAÇÃO] Garante que o perfil enviado está entre os permitidos
    if (!in_array($perfil, $perfis_validos)) {
        header("Location: ../view/index.php?erro=perfil_invalido");
        exit;
    }

    // [INCLUSÃO] Conexão com banco para buscar empregado sorteado conforme perfil
    $conn = conectar();
    $sql = "";

    // [BLOCO] Monta SQL segundo regra de negócio/modelo conceitual
    if ($perfil === 'ADMINISTRADOR') {
        // [REGRA] Sorteia empregado ATIVO com cargo e núcleo TI
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla FROM empregado 
                WHERE (cargo = 'Coordenadora de TI' OR cargo = 'Arquiteto de Sistemas') 
                  AND nucleo_setor_sigla = 'TI' AND status = 'ATIVO' 
                ORDER BY RAND() LIMIT 1";
    } elseif ($perfil === 'RH') {
        // [REGRA] Sorteia empregado ATIVO do núcleo RH
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla FROM empregado 
                WHERE nucleo_setor_sigla = 'RH' AND status = 'ATIVO' 
                ORDER BY RAND() LIMIT 1";
    } else {
        // [REGRA] Sorteia qualquer empregado ATIVO (perfil EMPREGADO)
        $sql = "SELECT matricula, nome, cargo, nucleo_setor_sigla FROM empregado 
                WHERE status = 'ATIVO' 
                ORDER BY RAND() LIMIT 1";
    }

    $result = mysqli_query($conn, $sql);

    // [BLOCO] Se encontrou empregado compatível, grava os dados na sessão
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['perfil']    = $perfil;
        $_SESSION['nome']      = $row['nome'];
        $_SESSION['matricula'] = $row['matricula'];
        $_SESSION['cargo']     = $row['cargo'];
        $_SESSION['setor']     = $row['nucleo_setor_sigla'];
        mysqli_close($conn);

        // [REDIRECIONAMENTO] Para painel de módulos após seleção bem sucedida
        header("Location: ../view/painel_modulos.php");
        exit;
    } else {
        mysqli_close($conn);
        // [TRATAMENTO] Nenhum empregado encontrado — retorna para seleção com erro
        header("Location: ../view/index.php?erro=nao_encontrado");
        exit;
    }
}

// [TRATAMENTO] Chamada direta ao controller (sem POST): retorna à seleção de perfil
header("Location: ../view/index.php");
exit;
?>
