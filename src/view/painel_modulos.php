<?php
// /src/view/painel_modulos.php

// [INCLUSÃO]
// Inicializa a sessão e valida usuário autenticado
session_start();
if (!isset($_SESSION['perfil'], $_SESSION['nome'], $_SESSION['matricula'])) {
    header("Location: index.php");
    exit;
}

// [INCLUSÃO] Caminho base dinâmico para assets
require_once __DIR__ . '/../../config/paths.php';
// [INCLUSÃO] Conexão ao banco para buscar módulos permitidos do perfil
require_once __DIR__ . '/../model/conexao.php';
$conn = conectar();

$perfil = $_SESSION['perfil'];
$modulos_permitidos = [];

// [BLOCO] 1. Buscar o id_perfil a partir do nome_perfil
$sql_perfil = "SELECT id_perfil FROM PERFIL_USUARIO WHERE nome_perfil = ?";
$stmt_perfil = mysqli_prepare($conn, $sql_perfil);
mysqli_stmt_bind_param($stmt_perfil, 's', $perfil);
mysqli_stmt_execute($stmt_perfil);
mysqli_stmt_bind_result($stmt_perfil, $id_perfil);
if (mysqli_stmt_fetch($stmt_perfil)) {
    mysqli_stmt_close($stmt_perfil);

    // [BLOCO] 2. Buscar os módulos permitidos a partir do id_perfil
    $sql_modulo = "SELECT M.nome_modulo 
                   FROM PERFIL_MODULO PM
                   JOIN MODULO M ON PM.id_modulo = M.id_modulo
                   WHERE PM.id_perfil = ?";
    $stmt_modulo = mysqli_prepare($conn, $sql_modulo);
    mysqli_stmt_bind_param($stmt_modulo, 'i', $id_perfil);
    mysqli_stmt_execute($stmt_modulo);
    mysqli_stmt_bind_result($stmt_modulo, $nome_modulo);
    while (mysqli_stmt_fetch($stmt_modulo)) {
        $modulos_permitidos[] = strtoupper($nome_modulo);
    }
    mysqli_stmt_close($stmt_modulo);
}
mysqli_close($conn);

// [INCLUSÃO] Carrega nomes amigáveis dos módulos a partir do JSON institucional
$modulos_json = file_get_contents(__DIR__ . '/../../config/modulos_nome_amigavel.json');
$modulos_data = json_decode($modulos_json, true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel de Módulos | SLPIRES.COM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body class="sistema-bg">
  <div class="sistema-container app">
    <div class="app-logo">Slpires.COM</div>
    <div class="app-title">Painel de Módulos</div>
    <!-- [BLOCO] Exibe os módulos permitidos ao usuário -->
    <div class="app-btn-group" style="margin-top:1.5em;">
      <?php
      foreach ($modulos_data['modulos'] as $modulo) {
          if (in_array(strtoupper($modulo['nome_tecnico']), $modulos_permitidos)) {
              $href = strtolower($modulo['nome_tecnico']) . '.php';
              echo '<a class="app-btn" href="' . $href . '">' . htmlspecialchars($modulo['nome_amigavel']) . '</a>';
          }
      }
      ?>
    </div>
    <!-- [INCLUSÃO] Rodapé dinâmico institucional com info do usuário -->
    <?php require_once __DIR__ . '/rodape_usuario.php'; ?>
  </div>
</body>
</html>
