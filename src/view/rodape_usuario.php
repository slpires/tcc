<?php
/*
    /src/view/rodape_usuario.php
    [INCLUSÃO]
    Rodapé institucional dinâmico com dados do usuário autenticado.
    Exibido apenas em páginas internas do sistema (exceto seleção de perfil).
    Mostra identificação, perfil, setor e horário atualizado automaticamente.
*/

/* [SESSÃO] Idempotente */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* [INCLUSÃO] Caminhos institucionais ($base_url, $action_base, $url_base) */
require_once __DIR__ . '/../../config/paths.php';

/* [VALIDAÇÃO] Exibe o rodapé apenas se o usuário estiver autenticado */
$autenticado = isset(
    $_SESSION['nome'],
    $_SESSION['matricula'],
    $_SESSION['cargo'],
    $_SESSION['perfil'],
    $_SESSION['setor']
);

if (!$autenticado) {
    // Visitante ou sessão inconsistente → não renderiza rodapé
    return;
}

/* [NORMALIZAÇÃO] Dados do usuário com escape */
$nome      = htmlspecialchars((string) ($_SESSION['nome'] ?? ''),      ENT_QUOTES, 'UTF-8');
$matricula = htmlspecialchars((string) ($_SESSION['matricula'] ?? ''), ENT_QUOTES, 'UTF-8');
$cargo     = htmlspecialchars((string) ($_SESSION['cargo'] ?? ''),     ENT_QUOTES, 'UTF-8');
$perfil    = htmlspecialchars((string) ($_SESSION['perfil'] ?? ''),    ENT_QUOTES, 'UTF-8');
$setor     = htmlspecialchars((string) ($_SESSION['setor'] ?? ''),     ENT_QUOTES, 'UTF-8');

/* [DATA/HORA] Inicial (fuso de São Paulo) */
date_default_timezone_set('America/Sao_Paulo');
$agora_inicial = date('d/m/Y - H:i:s');

/* [AJUSTE] Montagem segura da URL de logout
   - Preferência: $action_base (quando definido em paths.php)
   - Fallback: $base_url/index.php?r=logout
   - Fallback mínimo: ./index.php?r=logout
*/
if (isset($action_base) && $action_base !== '') {
    $logout_href = $action_base . '?r=logout';
} elseif (isset($base_url) && $base_url !== '') {
    $logout_href = rtrim($base_url, '/') . '/index.php?r=logout';
} else {
    $logout_href = './index.php?r=logout';
}
$logout_href = htmlspecialchars($logout_href, ENT_QUOTES, 'UTF-8');
?>
<footer class="footer-version app-footer">
  <small>
    <!-- Linha 1: Identificação principal -->
    <strong><?= $nome ?></strong>
    – matrícula: <?= $matricula ?>
    – <?= $cargo ?><br>

    <!-- Linha 2: Perfil e setor -->
    Perfil: <?= $perfil ?> – Setor: <?= $setor ?><br>

    <!-- Linha 3: Data e hora com atualização automática -->
    <span id="data-hora-usuario"><?= $agora_inicial ?></span><br><br>

    <!-- Botão centralizado: Sair do Sistema -->
    <div style="text-align:center; margin-top:0.8rem;">
      <a href="<?= $logout_href ?>"
         class="btn-logout"
         aria-label="Encerrar sessão e retornar à página inicial"
         style="display:inline-block; padding:0.5em 1.2em; border-radius:6px;
                background-color:#45763f; color:#fff; text-decoration:none; font-weight:600;">
         Sair do Sistema
      </a>
    </div>
  </small>
</footer>

<!-- Script para atualização dinâmica da data/hora no rodapé -->
<script>
  (function () {
    const alvo = document.getElementById('data-hora-usuario');
    if (!alvo) return;

    function dois(n) { return n.toString().padStart(2, '0'); }

    function atualizar() {
      const agora = new Date();
      const dd = dois(agora.getDate());
      const mm = dois(agora.getMonth() + 1);
      const yyyy = agora.getFullYear();
      const hh = dois(agora.getHours());
      const mi = dois(agora.getMinutes());
      const ss = dois(agora.getSeconds());
      alvo.textContent = `${dd}/${mm}/${yyyy} - ${hh}:${mi}:${ss}`;
    }

    atualizar();
    setInterval(atualizar, 1000);
  })();
</script>
