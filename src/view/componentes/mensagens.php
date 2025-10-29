<?php
/*
    /src/view/componentes/mensagens.php
    [INCLUSÃO]
    Componente institucional para exibição padronizada de mensagens de erro, sucesso e alerta com UX aprimorada.
    Pode ser incluído em qualquer view do sistema SLPIRES.COM (TCC UFF).
*/

/* [OPCIONAL/SEGURO] Caminhos institucionais.
   Útil caso, no futuro, este componente referencie assets com $base_url. */
$__paths = __DIR__ . '/../../../config/paths.php';
if (is_file($__paths)) {
    require_once $__paths;
}

/* [INCLUSÃO] Carrega mapas de mensagens institucionais, se não definidos externamente */
if (!isset($erros)) {
    $erros_file = __DIR__ . '/../../../config/erros.php';
    $erros = is_file($erros_file) ? require_once $erros_file : [];
}
if (!isset($sucessos)) {
    $sucessos_file = __DIR__ . '/../../../config/sucessos.php';
    $sucessos = is_file($sucessos_file) ? require_once $sucessos_file : [];
}
if (!isset($alertas)) {
    $alertas_file = __DIR__ . '/../../../config/alertas.php';
    $alertas = is_file($alertas_file) ? require_once $alertas_file : [];
}

/* [BLOCO] Permite sobrescrever mensagens diretamente via variável prévia */
if (!isset($mensagem_erro))    { $mensagem_erro    = ''; }
if (!isset($mensagem_sucesso)) { $mensagem_sucesso = ''; }
if (!isset($mensagem_alerta))  { $mensagem_alerta  = ''; }

/* [FUNÇÃO] Escape HTML robusto */
$e = static function ($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
};

/* [BLOCO] Busca mensagem via parâmetro na URL, caso não definida manualmente */
if ($mensagem_erro === '' && isset($_GET['erro'])) {
    $key = trim((string)$_GET['erro']);
    if ($key !== '' && isset($erros[$key])) {
        $mensagem_erro = $erros[$key];
    }
}
if ($mensagem_sucesso === '' && isset($_GET['sucesso'])) {
    $key = trim((string)$_GET['sucesso']);
    if ($key !== '' && isset($sucessos[$key])) {
        $mensagem_sucesso = $sucessos[$key];
    }
}
if ($mensagem_alerta === '' && isset($_GET['alerta'])) {
    $key = trim((string)$_GET['alerta']);
    if ($key !== '' && isset($alertas[$key])) {
        $mensagem_alerta = $alertas[$key];
    }
}
?>

<?php if ($mensagem_erro): ?>
  <div class="alert-erro" id="msgErro" role="alert" aria-live="assertive">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= $e($mensagem_erro) ?>
  </div>
  <script>
    setTimeout(function() {
      var el = document.getElementById('msgErro');
      if (el) el.style.display = 'none';
    }, 6000);
  </script>
<?php endif; ?>

<?php if ($mensagem_sucesso): ?>
  <div class="alert-sucesso" id="msgSucesso" role="status" aria-live="polite">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= $e($mensagem_sucesso) ?>
  </div>
  <script>
    setTimeout(function() {
      var el = document.getElementById('msgSucesso');
      if (el) el.style.display = 'none';
    }, 4500);
  </script>
<?php endif; ?>

<?php if ($mensagem_alerta): ?>
  <div class="alert-alerta" id="msgAlerta" role="status" aria-live="polite">
    <span class="close-msg" onclick="this.parentElement.style.display='none'" aria-label="Fechar mensagem">&times;</span>
    <?= $e($mensagem_alerta) ?>
  </div>
  <script>
    setTimeout(function() {
      var el = document.getElementById('msgAlerta');
      if (el) el.style.display = 'none';
    }, 5000);
  </script>
<?php endif; ?>
