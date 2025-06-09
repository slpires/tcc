<?php
/*
    /src/view/componentes/mensagens.php
    [INCLUSÃO]
    Componente institucional para exibição padronizada de mensagens de erro, sucesso e alerta com UX aprimorada.
    Pode ser incluído em qualquer view do sistema SLPIRES.COM (TCC UFF).
*/

/* [INCLUSÃO] Carrega arrays de mensagens institucionais, se não definidos externamente */
if (!isset($erros))     $erros     = require __DIR__ . '/../../../config/erros.php';
if (!isset($sucessos))  $sucessos  = require __DIR__ . '/../../../config/sucessos.php';
if (!isset($alertas))   $alertas   = require __DIR__ . '/../../../config/alertas.php';

/* [BLOCO] Permite sobrescrever mensagens diretamente via variável prévia */
if (!isset($mensagem_erro))    $mensagem_erro    = '';
if (!isset($mensagem_sucesso)) $mensagem_sucesso = '';
if (!isset($mensagem_alerta))  $mensagem_alerta  = '';

/* [BLOCO] Busca mensagem via parâmetro na URL, caso não definida manualmente */
if ($mensagem_erro === '' && isset($_GET['erro']) && isset($erros[$_GET['erro']])) {
    $mensagem_erro = $erros[$_GET['erro']];
}
if ($mensagem_sucesso === '' && isset($_GET['sucesso']) && isset($sucessos[$_GET['sucesso']])) {
    $mensagem_sucesso = $sucessos[$_GET['sucesso']];
}
if ($mensagem_alerta === '' && isset($_GET['alerta']) && isset($alertas[$_GET['alerta']])) {
    $mensagem_alerta = $alertas[$_GET['alerta']];
}
?>

<?php if ($mensagem_erro): ?>
  <div class="alert-erro" id="msgErro" role="alert" aria-live="assertive">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= htmlspecialchars($mensagem_erro) ?>
  </div>
  <script>
    setTimeout(function() {
      var erro = document.getElementById('msgErro');
      if (erro) erro.style.display = 'none';
    }, 6000);
  </script>
<?php endif; ?>

<?php if ($mensagem_sucesso): ?>
  <div class="alert-sucesso" id="msgSucesso" role="alert" aria-live="polite">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= htmlspecialchars($mensagem_sucesso) ?>
  </div>
  <script>
    setTimeout(function() {
      var sucesso = document.getElementById('msgSucesso');
      if (sucesso) sucesso.style.display = 'none';
    }, 4500);
  </script>
<?php endif; ?>

<?php if ($mensagem_alerta): ?>
  <div class="alert-alerta" id="msgAlerta" role="alert" aria-live="polite">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= htmlspecialchars($mensagem_alerta) ?>
  </div>
  <script>
    setTimeout(function() {
      var alerta = document.getElementById('msgAlerta');
      if (alerta) alerta.style.display = 'none';
    }, 5000);
  </script>
<?php endif; ?>
