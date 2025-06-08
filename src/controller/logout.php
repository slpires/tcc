<?php
// /src/controller/logout.php
// [INCLUSÃO] Logout: limpa a sessão e redireciona para seleção de perfil
session_start();
session_unset();
session_destroy();
header("Location: ../view/index.php");
exit;
?>
