<?php
/*
    /src/model/perfil.php
    [INCLUSÃO]
    Model responsável pela busca dinâmica dos perfis de acesso cadastrados na tabela perfil_usuario do sistema SLPIRES.COM (TCC UFF).
*/

function buscarPerfisValidos($conn) {
    $perfis = [];
    $sql = "SELECT nome_perfil FROM perfil_usuario";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $perfis[] = $row['nome_perfil'];
    }
    return $perfis;
}
?>
