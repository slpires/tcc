<?php
/*
    /src/controller/prepara_perfis.php
    [INCLUSÃO]
    Controller responsável por preparar a tela de seleção de perfil,
    buscando os perfis válidos no banco e repassando-os para a view.
    Padrão MVC do sistema SLPIRES.COM (TCC UFF).
*/

// [INCLUSÃO] Carrega conexão e função do Model
require_once __DIR__ . '/../model/conexao.php';
require_once __DIR__ . '/../model/perfil.php';

// [EXECUÇÃO] Busca perfis válidos e fecha conexão
$conn = conectar();
$perfis_validos = buscarPerfisValidos($conn);
mysqli_close($conn);

// [INCLUSÃO] Renderiza a view, já com $perfis_validos disponível
include __DIR__ . '/../view/index.php';
?>
