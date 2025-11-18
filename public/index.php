<?php
/*
    /public/index.php
    [INCLUSÃO]
    Landing page institucional do sistema SLPIRES.COM (TCC UFF).
    Carrega caminhos dinâmicos e, quando aplicável, aciona o front controller.
*/

/* [INCLUSÃO] Carrega definição do $base_url, $action_base e $url_base
   (necessário antes de qualquer <link> ou <script> que utilize $base_url) */
require_once __DIR__ . '/../config/paths.php';

/* [NOVO] Sessão idempotente: segura para componentes que venham a usar $_SESSION */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* ============================================================
   NORMALIZAÇÃO DE LEGADO (?pagina=...)
   - Converte qualquer uso antigo para a rota nova ?r=sistema
   ============================================================ */
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($pagina !== null && $pagina !== false) {
    // 'home' e 'sistema' antigos viram a rota nova 'sistema'
    if ($pagina === 'home' || $pagina === 'sistema') {
        header('Location: ' . $action_base . '?r=sistema', true, 302);
        exit;
    }
    // Qualquer outro valor legado também direciona para a seleção
    header('Location: ' . $action_base . '?r=sistema', true, 302);
    exit;
}

/* ============================================================
   [BLOCO C] ROTEADOR POR QUERY (?r=rota)
   - Aciona quando houver parâmetro 'r'
   ============================================================ */
$routeRaw = filter_input(INPUT_GET, 'r', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($routeRaw !== null && $routeRaw !== false && $routeRaw !== '') {

    /* [AJUSTE] Normalização e validação da rota
       - Converte para minúsculas e remove espaços.
       - Evita discrepâncias ('HOME', 'Simulação', etc.).
       - Mantém padrão defensivo 'sistema' como fallback. */
    $route = trim(strtolower($routeRaw));
    if ($route === '') {
        $route = 'sistema';
    }

    // Mapa de rotas:
    //  - VIEWS: abrem a view correspondente.
    //  - CONTROLLERS: delegam a execução ao controller correspondente.
    $map = [
        // VIEWS (home como alias temporário de sistema)
        'home'    => __DIR__ . '/../src/view/sistema.php',
        'sistema' => __DIR__ . '/../src/view/sistema.php',
        'painel'  => __DIR__ . '/../src/view/painel_modulos.php',

        // CONTROLLERS (rotas canônicas)
        'perfil'      => __DIR__ . '/../src/controller/selecao_perfil.php',
        'relatorios'  => __DIR__ . '/../src/controller/relatorios.php',
        'creditos'    => __DIR__ . '/../src/controller/controle_credito.php',
        'simulacao'   => __DIR__ . '/../src/controller/simulacao_folha.php',
        'testes'      => __DIR__ . '/../src/controller/testes.php',
        'logout'      => __DIR__ . '/../src/controller/logout.php',

        // ALIASES (nomes técnicos legados vindos de views/JSON)
        'simulacao_folha'  => __DIR__ . '/../src/controller/simulacao_folha.php',
        'controle_credito' => __DIR__ . '/../src/controller/controle_credito.php',
    ];

    // Se a rota não existir no mapa, responde 404 (sem front_controller legado)
    $file = $map[$route] ?? null;
    if ($file && is_file($file)) {
        require $file;
        exit;
    }

    /* [AJUSTE] Melhoria no tratamento de rotas inválidas
       - Define explicitamente o código HTTP 404.
       - Utiliza view institucional (src/view/404.php) para resposta amigável.
       - Mantém compatibilidade caso a view ainda não exista. */
    http_response_code(404);
    if (is_file(__DIR__ . '/../src/view/404.php')) {
        require __DIR__ . '/../src/view/404.php';
    } else {
        echo 'Rota não encontrada.';
    }
    exit;
}

/* ============================================================
   LANDING PAGE
   - Sem 'r' → renderiza a landing institucional diretamente
   ============================================================ */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>SLPIRES.COM – Sistema de Recuperação de Créditos | TCC UFF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Homepage oficial do TCC SLPIRES.COM: controle acadêmico, documentação, acesso ao sistema e acompanhamento institucional.">
  <meta name="author" content="Sérgio Luís de Oliveira Pires">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#45763f">

  <!-- [INCLUSÃO] Favicon e Favibar -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $base_url ?>/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $base_url ?>/img/favicon-16x16.png">
  <link rel="shortcut icon" href="<?= $base_url ?>/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $base_url ?>/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= $base_url ?>/img/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="<?= $base_url ?>/img/android-chrome-512x512.png">
  <link rel="manifest" href="<?= $base_url ?>/img/site.webmanifest">

  <!-- [INCLUSÃO] Open Graph / SEO social -->
  <meta property="og:title" content="SLPIRES.COM – Recuperação de Créditos | TCC UFF">
  <meta property="og:description" content="Acesse a documentação, acompanhe o Kanban e experimente o sistema. Projeto acadêmico oficial UFF.">
  <meta property="og:image" content="<?= $base_url ?>/img/logo_uff_azul.png">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="pt_BR">

  <!-- [INCLUSÃO] CSS institucional unificado -->
  <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">

  <!-- [INCLUSÃO] Favibar para navegação em dispositivos modernos -->
  <link rel="mask-icon" href="<?= $base_url ?>/img/safari-pinned-tab.svg" color="#45763f">
</head>
<body class="home-bg">

  <div class="home-container">
    <!-- [INCLUSÃO] Exibição padronizada de mensagens institucionais (erro, sucesso, alerta) -->
    <?php include __DIR__ . '/../src/view/componentes/mensagens.php'; ?>

    <!-- Logo textual institucional -->
    <h1 class="logo" aria-label="Logotipo textual institucional">Slpires.COM</h1>
    <div class="status">Ambiente Oficial – TCC UFF 2025 – Prova de Conceito</div>

    <h2 class="tcc-title" style="text-align:center;">
      <div>DESENVOLVIMENTO DE UM PROTÓTIPO WEB PARA RECUPERAÇÃO DE CRÉDITOS</div>
      <div>NA FOLHA DE PAGAMENTO DA SLPIRES.COM:</div>
      <div style="height:0.8em;"></div>
      <div>UMA PROVA DE CONCEITO PARA AUTOMAÇÃO NA GESTÃO DE OPERAÇÕES</div>
    </h2>

    <!-- Apresentação -->
    <div class="apresentacao-blocos">
      <div class="apresentacao-credenciais">
        <strong>Curso:</strong> Tecnologia em Sistemas de Computação – UFF<br>
        <strong>Autor:</strong> Sérgio Luís de Oliveira Pires
        <a href="http://lattes.cnpq.br/6560673234797856"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="CV Lattes de Sérgio Luís de Oliveira Pires"
           class="lattes-link"
        >📄</a>
        <br>
        <strong>Orientador:</strong> Prof. Leandro Soares de Sousa
        <a href="http://lattes.cnpq.br/5733271257229469"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="CV Lattes do Prof. Leandro Soares de Sousa"
           class="lattes-link"
        >📄</a>
      </div>
      <div style="height: 1em;"></div>
      <div class="apresentacao-boasvindas">
        Bem-vindo(a) ao portal acadêmico do TCC SLPIRES.COM!
      </div>
    </div>

    <!-- Chamadas de ação -->
    <p style="font-size:1.07rem; margin:18px 0 28px 0; font-weight:600;">
      Acesse a documentação completa, acompanhe o Kanban, consulte a Wiki técnica ou entre no sistema:
    </p>

    <!-- Grupo de botões principais -->
    <div class="github-group" style="margin-bottom:40px;">
      <a class="github" href="https://github.com/slpires/tcc" target="_blank" rel="noopener noreferrer" aria-label="Ver repositório no GitHub">
        🧠 Ver repositório no GitHub
      </a>
      <a class="github" href="https://github.com/users/slpires/projects/2" target="_blank" rel="noopener noreferrer" aria-label="Acompanhar quadro Kanban">
        🗂️ Acompanhar quadro Kanban
      </a>
      <a class="github" href="https://github.com/slpires/tcc/wiki" target="_blank" rel="noopener noreferrer" aria-label="Consultar documentação técnica na Wiki">
        📘 Documentação Técnica (Wiki)
      </a>
      <!-- [AJUSTE] usar roteador centralizado -->
      <a class="github btn btn-mvp" href="<?= $action_base ?>?r=sistema" aria-label="Entrar no Sistema">
        🚀 Entrar no MVP do Sistema
      </a>
    </div>

    <!-- Créditos institucionais -->
    <div class="credit" style="margin-bottom: 0;">
      © 2025 – Prova de Conceito acadêmica.
    </div>

    <!-- Rodapé final (Sistema versão + contato) -->
    <div class="footer-version">
      <small>
        Sistema versão <span id="sys-version">0.9.0</span> – Padrão SemVer
        (<a href="https://semver.org/lang/pt-BR/" target="_blank">saiba mais</a>)<br>
        Dúvidas ou sugestões? Entre em <a href="https://olink.ai/slpires" target="_blank" rel="noopener noreferrer">contato</a>.
      </small>
    </div>

    <!-- Bloco de agradecimentos e logos centralizado -->
    <div class="footer-agradecimentos">Agradecimentos:</div>
    <div class="footer-institucional">
      <a href="https://www.cecierj.edu.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Fundação CECIERJ">
        <img src="<?= $base_url ?>/img/logo_cecierj.png" alt="Logo CECIERJ" class="logo-inst">
      </a>
      <a href="https://www.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Universidade Federal Fluminense">
        <img src="<?= $base_url ?>/img/logo_uff_azul.png" alt="Logo UFF" class="logo-inst">
      </a>
      <a href="https://www.ic.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site do Instituto de Computação da UFF">
        <img src="<?= $base_url ?>/img/logo_ic.png" alt="Logo Instituto de Computação UFF" class="logo-inst">
      </a>
    </div>

  </div>
  <!-- [INCLUSÃO] JS institucional unificado -->
  <script src="<?= $base_url ?>/js/main.js"></script>
</body>
</html>
<?php
    // Fim da landing page
    exit;
