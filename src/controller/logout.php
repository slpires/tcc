<?php
/*
    /src/controller/logout.php
    [INCLUSÃO]
    Controlador de logout do sistema SLPIRES.COM (TCC UFF).
    Responsável por destruir a sessão ativa do usuário e redirecionar para a tela inicial institucional,
    garantindo limpeza de dados e rastreabilidade de fluxo conforme melhores práticas de segurança.
*/

/* [BLOCO] Destruição da sessão de usuário */
session_unset();
session_destroy();

/*
    [OBSERVAÇÃO]
    O redirecionamento deve voltar SEMPRE para o ponto de entrada público do sistema,
    preferencialmente pela landing page (public/index.php) ou seleção de perfil se existir.
    Evite redirecionar diretamente para /src/view/index.php, pois viola o padrão MVC e bypassa o front controller.
*/

/* [INCLUSÃO] Redirecionamento seguro para a landing page institucional */
header("Location: ../../public/index.php");
exit;
?>
