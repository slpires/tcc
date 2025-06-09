<?php
/*
    /config/erros.php
    [INCLUSÃO]
    Mapeamento centralizado de códigos institucionais de erro para o sistema SLPIRES.COM (TCC UFF).
    Todas as mensagens podem ser editadas neste arquivo para padronização, governança
    e rastreabilidade institucional.
    [OBSERVAÇÃO]
    Este arquivo deve ser incluído nas views onde mensagens de erro precisam ser exibidas.
    A expansão para multi-idiomas ou logging centralizado pode ser realizada a partir deste ponto.
*/

return [
    'perfil_invalido'     => 'Perfil selecionado é inválido ou sessão expirada.',
    'nao_encontrado'      => 'Nenhum empregado ativo encontrado para este perfil.',
    'sem_ti'              => 'Não há empregados ativos no setor de TI.',
    'sem_rh'              => 'Não há empregados ativos no setor de RH.',
    'modulo_invalido'     => 'Módulo não encontrado ou em desenvolvimento.',
    'logout_sessao'       => 'Não havia sessão ativa para encerrar.',
    'erro_generico'       => 'Ocorreu um erro inesperado. Tente novamente.',
    'acesso_negado'       => 'Você não tem permissão para acessar este módulo.',
    'modulo_inexistente'  => 'O módulo solicitado não existe ou está indisponível.',
    'falha_conexao_bd'    => 'Erro ao conectar ao banco de dados. Tente novamente mais tarde.',
];

/*
    [BLOCO] Instruções de uso (para documentação):
    - Para exibir uma mensagem de erro em uma view, inclua este arquivo com:
          $erros = require __DIR__ . '/../config/erros.php';
    - Em seguida, utilize o código do erro, por exemplo:
          if (isset($_GET['erro']) && isset($erros[$_GET['erro']])) {
              echo "<div class='alert-erro'>{$erros[$_GET['erro']]}</div>";
          }
*/
