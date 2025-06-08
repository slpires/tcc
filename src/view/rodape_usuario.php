<?php
// /src/view/rodape_usuario.php

// [INCLUSÃO]
// Rodapé institucional dinâmico com dados do usuário autenticado, exibido apenas em páginas internas do sistema (exceto seleção de perfil).
// Exibe também a data e horário atualizando automaticamente em formato "DD/MM/AAAA - hh:mm:ss".

if (
    isset($_SESSION['nome'], $_SESSION['matricula'], $_SESSION['cargo'], $_SESSION['perfil'], $_SESSION['setor'])
) : ?>
  <footer class="footer-version app-footer">
    <small>
      <!-- [INCLUSÃO] Linha 1: Identificação principal do usuário (nome em negrito) -->
      <strong><?= htmlspecialchars($_SESSION['nome']) ?></strong> – matrícula: <?= htmlspecialchars($_SESSION['matricula']) ?> – <?= htmlspecialchars($_SESSION['cargo']) ?><br>
      <!-- [INCLUSÃO] Linha 2: Perfil e setor -->
      Perfil: <?= htmlspecialchars($_SESSION['perfil']) ?> – Setor: <?= htmlspecialchars($_SESSION['setor']) ?><br>
      <!-- [INCLUSÃO] Linha 3: Data e hora atual com atualização automática -->
      <span id="data-hora-usuario">
        <?php
        date_default_timezone_set('America/Sao_Paulo');
        echo date('d/m/Y - H:i:s');
        ?>
      </span>
      <br>
      <!-- [INCLUSÃO] Botão de logout visível em todas as páginas internas -->
      <a href="../controller/logout.php" class="btn-logout">Sair do Sistema</a>
    </small>
  </footer>
  <!-- [INCLUSÃO] Script para atualização dinâmica da data/hora no rodapé -->
  <script>
    function atualizarRelogioRodape() {
      const agora = new Date();
      const doisDigitos = n => n.toString().padStart(2, '0');
      const data = [
        doisDigitos(agora.getDate()),
        doisDigitos(agora.getMonth() + 1),
        agora.getFullYear()
      ].join('/');
      const hora = [
        doisDigitos(agora.getHours()),
        doisDigitos(agora.getMinutes()),
        doisDigitos(agora.getSeconds())
      ].join(':');
      document.getElementById('data-hora-usuario').textContent = `${data} - ${hora}`;
    }
    setInterval(atualizarRelogioRodape, 1000);
    atualizarRelogioRodape();
  </script>
<?php endif; ?>
