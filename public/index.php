<?php

/*
    /public/index.php
    [INCLUSÃO]
    Landing page institucional do sistema SLPIRES.COM (TCC UFF).
    Aciona o front controller, carrega caminhos dinâmicos e exibe a interface principal pública.
*/

// [INCLUSÃO] Aciona o front controller centralizado do sistema
require_once __DIR__ . '/../src/controller/front_controller.php';

// [INCLUSÃO] Carrega definição do $base_url para assets e links institucionais
require_once __DIR__ . '/../config/paths.php';

/* [BLOCO] Exibe landing page apenas se ?pagina=home ou nenhum parâmetro for informado */
if (!isset($_GET['pagina']) || $_GET['pagina'] === 'home') {
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
  <meta property="og:image" content="<?= $base_url ?>/img/og_tcc_slpirescom.png">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="pt_BR">

  <!--
    [TRECHO ORIGINAL] CSS institucional unificado com base dinâmica
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
  -->

  <!-- [EXCEÇÃO TEMPORÁRIA] Caminho absoluto para garantir estilização em produção -->
  <link rel="stylesheet" href="/css/style.css">

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
      <!--
        [CÓDIGO ORIGINAL] Botão ativo removido, mantido como comentário para rastreabilidade.
        <a class="github btn btn-mvp" href="index.php?pagina=sistema" aria-label="Entrar no Sistema">
          🚀 Entrar no MVP do Sistema
        </a>
      -->
      <!-- [AJUSTE] Botão desabilitado com feedback institucional e UX aprimorada -->
      <a
        id="btn-mvp-desabilitado"
        class="github btn btn-mvp"
        href="#"
        aria-disabled="true"
        style="opacity: 0.5; cursor: not-allowed;"
        title="O sistema entrará no ar em breve"
      >
        🚀 Entrar no MVP do Sistema
      </a>
      <span id="msg-mvp" style="display:none; color:#b03535; font-weight:600; margin-top:8px;">O sistema entrará no ar em breve.</span>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btn-mvp-desabilitado');
        const msg = document.getElementById('msg-mvp');
        if(btn){
          btn.addEventListener('click', function(e){
            e.preventDefault();
            if(msg){
              msg.style.display = 'inline';
              setTimeout(function(){ msg.style.display = 'none'; }, 4000);
            }
            return false;
          });
          btn.addEventListener('focus', function(){
            if(msg){
              msg.style.display = 'inline';
              setTimeout(function(){ msg.style.display = 'none'; }, 4000);
            }
          });
        }
      });
    </script>

    <!-- Créditos institucionais -->
    <div class="credit" style="margin-bottom: 0;">
      © 2025 – Prova de Conceito acadêmica.
    </div>

    <!-- Rodapé final (Sistema versão + contato) -->
    <div class="footer-version">
      <small>
        Sistema versão <span id="sys-version">1.0.0</span> – Padrão SemVer
        (<a href="https://semver.org/lang/pt-BR/" target="_blank">saiba mais</a>)<br>
        Dúvidas ou sugestões? Entre em <a href="https://olink.ai/slpires" target="_blank" rel="noopener noreferrer">contato</a>.
      </small>
    </div>

    <!-- Bloco de agradecimentos e logos centralizado -->
    <div class="footer-agradecimentos">Agradecimentos:</div>
	<div class="footer-institucional">
	  <!--
		[TRECHO ORIGINAL]
		<a href="https://www.cecierj.edu.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Fundação CECIERJ">
		  <img src="<?= $base_url ?>/img/logo_cecierj.png" alt="Logo CECIERJ" class="logo-inst">
		</a>
		<a href="https://www.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Universidade Federal Fluminense">
		  <img src="<?= $base_url ?>/img/logo_uff_azul.png" alt="Logo UFF" class="logo-inst">
		</a>
		<a href="https://www.ic.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site do Instituto de Computação da UFF">
		  <img src="<?= $base_url ?>/img/logo_ic.png" alt="Logo Instituto de Computação UFF" class="logo-inst">
		</a>
	  -->

	  <!-- [EXCEÇÃO TEMPORÁRIA] Caminho absoluto para garantir exibição das logos institucionais em produção -->
	  <a href="https://www.cecierj.edu.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Fundação CECIERJ">
		<img src="/img/logo_cecierj.png" alt="Logo CECIERJ" class="logo-inst">
	  </a>
	  <a href="https://www.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site da Universidade Federal Fluminense">
		<img src="/img/logo_uff_azul.png" alt="Logo UFF" class="logo-inst">
	  </a>
	  <a href="https://www.ic.uff.br/" target="_blank" rel="noopener noreferrer" aria-label="Site do Instituto de Computação da UFF">
		<img src="/img/logo_ic.png" alt="Logo Instituto de Computação UFF" class="logo-inst">
	  </a>
	</div>

  </div>
  <!-- [INCLUSÃO] JS institucional unificado -->
  <script src="<?= $base_url ?>/js/main.js"></script>
</body>
</html>
<?php } /* Fim do bloco condicional da landing page */ ?>
