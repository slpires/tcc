<?php
/*
    /src/view/rodape_usuario.php
    [INCLUSÃO]
    Rodapé institucional dinâmico com dados do usuário autenticado.
    Exibido apenas em páginas internas do sistema (exceto seleção de perfil).
    Mostra identificação, perfil, setor e horário atualizado automaticamente.
*/

/* [INCLUSÃO] Caminhos institucionais para assets e links dinâmicos */
require_once __DIR__ . '/../../config/paths.php';

/* [SESSÃO] Inicialização idempotente */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* [VALIDAÇÃO] Exibe o rodapé apenas se o usuário estiver autenticado */
if (
    isset($_SESSION['nome'], $_SESSION['matricula'], $_SESSION['cargo'], $_SESSION['perfil'], $_SESSION['setor'])
) :
?>
  <footer class="footer-version app-footer">
    <small>
      <!-- [INCLUSÃO] Linha 1: Identificação principal do usuário (nome em negrito) -->
      <strong><?= htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
      – matrícula: <?= htmlspecialchars($_SESSION['matricula'], ENT_QUOTES, 'UTF-8') ?>
      – <?= htmlspecialchars($_SESSION['cargo'], ENT_QUOTES, 'UTF-8') ?><br>

      <!-- [INCLUSÃO] Linha 2: Perfil e setor -->
      Perfil: <?= htmlspecialchars($_SESSION['perfil'], ENT_QUOTES, 'UTF-8') ?>
      – Setor: <?= htmlspecialchars($_SESSION['setor'], ENT_QUOTES, 'UTF-8') ?><br>

      <!-- [INCLUSÃO] Linha 3: Data e hora atual com atualização automática -->
      <span id="data-hora-usuario">
        <?php
        date_default_timezone_set('America/Sao_Paulo');
        echo date('d/m/Y - H:i:s');
        ?>
      </span>
      <br>

      <!-- [INCLUSÃO] Botão de logout padronizado -->
      <a href="<?= $controller_url ?>/logout.php" class="btn-logout" aria-label="Encerrar sessão e retornar à página inicial">
        Sair do Sistema
      </a>
    </small>
  </footer>

  <!-- [INCLUSÃO] Script para atualização dinâmica da data/hora no rodapé -->
  <script>
    function atualizarRelogioRodape() {
      const agora = new Date();
      const doisDigitos = n => n.toString().padStart(2, '0');
      const data = [
        do
