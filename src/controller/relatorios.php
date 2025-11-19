<?php
/*
    /src/controller/relatorios.php
    [CONTROLADOR]
    Módulo RELATORIOS do sistema SLPIRES.COM (TCC UFF).

    Nesta versão do MVP:
      - A página principal do módulo exibe botões de relatórios disponíveis
        para o perfil (view: /src/view/relatorios.php).
      - Apenas o relatório de logs técnicos está implementado, com:
          * listagem de arquivos na pasta /logs;
          * download controlado de cada arquivo.
*/

require_once __DIR__ . '/verificar_permissao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$perfilSessao = isset($_SESSION['perfil']) ? strtoupper((string) $_SESSION['perfil']) : '';
$isAdmin      = ($perfilSessao === 'ADMINISTRADOR');

/**
 * Carrega a configuração de relatórios a partir do JSON.
 *
 * Estrutura esperada:
 * {
 *   "relatorios": {
 *     "logs": {
 *       "nome": "Relatórios de Logs Técnicos",
 *       "perfis": ["ADMINISTRADOR"]
 *     },
 *     ...
 *   }
 * }
 *
 * @return array<string,mixed>
 */
function relatorios_carregar_config(): array
{
    $caminho = __DIR__ . '/../../config/relatorios_matriz_perfil.json';

    if (!is_file($caminho) || !is_readable($caminho)) {
        return [];
    }

    $json = file_get_contents($caminho);
    if ($json === false || trim($json) === '') {
        return [];
    }

    $dados = json_decode($json, true);
    if (!is_array($dados)) {
        return [];
    }

    return $dados;
}

/**
 * Retorna a lista de IDs de relatórios permitidos para o perfil
 * na configuração informada, considerando apenas os relatórios
 * efetivamente implementados nesta release.
 *
 * @param string $perfil
 * @param array<string,mixed> $config
 * @param array<int,string> $implementados
 * @return array<int,string>
 */
function relatorios_obter_permitidos(string $perfil, array $config, array $implementados): array
{
    $permitidos = [];

    if (!isset($config['relatorios']) || !is_array($config['relatorios'])) {
        return $permitidos;
    }

    $perfilUpper = strtoupper($perfil);
    $isAdmin     = ($perfilUpper === 'ADMINISTRADOR');

    foreach ($config['relatorios'] as $id => $dados) {
        // Apenas relatórios considerados implementados nesta release
        if (!in_array($id, $implementados, true)) {
            continue;
        }

        $perfisCfg = [];
        if (isset($dados['perfis']) && is_array($dados['perfis'])) {
            $perfisCfg = array_map('strtoupper', $dados['perfis']);
        }

        // ADMIN vê tudo que está implementado; demais perfis seguem a matriz
        if ($isAdmin || in_array($perfilUpper, $perfisCfg, true)) {
            $permitidos[] = $id;
        }
    }

    return $permitidos;
}

/**
 * Obtém o diretório canônico da pasta de logs do sistema.
 *
 * @return string
 */
function relatorios_obter_diretorio_logs(): string
{
    $baseDir = realpath(__DIR__ . '/../../logs');

    if ($baseDir === false || !is_dir($baseDir)) {
        return '';
    }

    return $baseDir;
}

/**
 * Tenta inferir uma data/hora a partir do nome do arquivo de log.
 *
 * @param string $nome
 * @return string
 */
function relatorios_inferir_data_arquivo(string $nome): string
{
    $base = preg_replace('/\\.[^.]+$/', '', $nome);

    if (preg_match('/(20\\d{6})_(\\d{4})/', $base, $m)) {
        $data = $m[1];
        $hora = $m[2];
        $dt   = \DateTime::createFromFormat('YmdHi', $data . $hora);
        if ($dt instanceof \DateTime) {
            return $dt->format('Y-m-d H:i');
        }
    }

    if (preg_match('/(20\\d{6})/', $base, $m)) {
        $dt = \DateTime::createFromFormat('Ymd', $m[1]);
        if ($dt instanceof \DateTime) {
            return $dt->format('Y-m-d');
        }
    }

    return '';
}

/**
 * Infere um tipo aproximado de log a partir do nome do arquivo.
 *
 * @param string $nome
 * @return string
 */
function relatorios_inferir_tipo_log(string $nome): string
{
    $nomeLower = mb_strtolower($nome, 'UTF-8');

    if (strpos($nomeLower, 'teste') !== false) {
        return 'testes';
    }

    if (strpos($nomeLower, 'simulacao') !== false || strpos($nomeLower, 'simulação') !== false || strpos($nomeLower, 'folha') !== false) {
        return 'simulacao_folha';
    }

    if (strpos($nomeLower, 'credito') !== false || strpos($nomeLower, 'crédito') !== false) {
        return 'controle_credito';
    }

    return 'outros';
}

/**
 * Lista os arquivos de log disponíveis na pasta /logs.
 *
 * @return array<int,array<string,mixed>>
 */
function relatorios_listar_logs(): array
{
    $dir = relatorios_obter_diretorio_logs();
    if ($dir === '') {
        return [];
    }

    $handle = @opendir($dir);
    if ($handle === false) {
        return [];
    }

    $arquivos = [];

    while (($entry = readdir($handle)) !== false) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }

        $caminho = $dir . DIRECTORY_SEPARATOR . $entry;

        if (!is_file($caminho)) {
            continue;
        }

        if ($entry === '.htaccess') {
            continue;
        }

        $tipo = relatorios_inferir_tipo_log($entry);

        $dataInferida = relatorios_inferir_data_arquivo($entry);
        if ($dataInferida === '') {
            $mtime = @filemtime($caminho);
            if ($mtime !== false) {
                $dataInferida = date('Y-m-d H:i', $mtime);
            } else {
                $dataInferida = '-';
            }
        }

        $tamanho = @filesize($caminho);
        if ($tamanho === false) {
            $tamanho = 0;
        }

        $arquivos[] = [
            'nome'    => $entry,
            'data'    => $dataInferida,
            'tipo'    => $tipo,
            'tamanho' => $tamanho,
        ];
    }

    closedir($handle);

    // Ordenação simples por data (texto) crescente
    usort($arquivos, static function (array $a, array $b): int {
        return strcmp((string) $a['data'], (string) $b['data']);
    });

    return $arquivos;
}

/**
 * Função auxiliar para aplicar rawurlencode apenas no basename.
 *
 * @param string $nome
 * @return string
 */
function rawbasename(string $nome): string
{
    $nome = basename(str_replace(['\\', '/'], '/', $nome));
    return rawurlencode($nome);
}

/**
 * Ação de download de arquivo de log.
 *
 * Verifica permissões com base na configuração e nos relatórios
 * implementados (apenas "logs" no MVP).
 *
 * @param array<string,mixed> $config
 * @param array<int,string> $implementados
 * @return void
 */
function relatorios_download(array $config, array $implementados): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $perfilSessao = isset($_SESSION['perfil']) ? strtoupper((string) $_SESSION['perfil']) : '';

    $permitidos = relatorios_obter_permitidos($perfilSessao, $config, $implementados);

    if (!in_array('logs', $permitidos, true)) {
        http_response_code(403);
        echo 'Ação não permitida para o seu perfil.';
        exit;
    }

    $dir = relatorios_obter_diretorio_logs();
    if ($dir === '') {
        http_response_code(500);
        echo 'Diretório de logs não encontrado ou inacessível.';
        exit;
    }

    $param = isset($_GET['arquivo']) ? (string) $_GET['arquivo'] : '';

    if (trim($param) === '') {
        http_response_code(400);
        echo 'Parâmetro de arquivo inválido.';
        exit;
    }

    $param   = str_replace(['\\', '/'], '', $param);
    $arquivo = basename($param);

    if ($arquivo === '' || $arquivo === '.htaccess') {
        http_response_code(400);
        echo 'Nome de arquivo de log inválido.';
        exit;
    }

    $caminho = $dir . DIRECTORY_SEPARATOR . $arquivo;

    if (!is_file($caminho)) {
        http_response_code(404);
        echo 'Arquivo de log não encontrado.';
        exit;
    }

    $tamanho = @filesize($caminho);
    if ($tamanho === false) {
        $tamanho = 0;
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . rawbasename($arquivo) . '"');
    header('Content-Transfer-Encoding: binary');
    if ($tamanho > 0) {
        header('Content-Length: ' . $tamanho);
    }

    if (ob_get_length()) {
        @ob_end_clean();
    }

    readfile($caminho);
    exit;
}

/* ============================================================
   ROTEAMENTO LOCAL DO MÓDULO RELATORIOS
   ------------------------------------------------------------
   - Sem parâmetros ou sem "tipo" => tela de seleção de relatórios
                                     (view: relatorios.php)
   - ?tipo=logs                    => lista de arquivos de log
                                     (view: relatorios_logs.php)
   - ?acao=download                => download de arquivo de log
   ============================================================ */

$configRelatorios        = relatorios_carregar_config();
$relatoriosImplementados = ['logs']; // apenas logs no MVP

// Leitura simplificada dos parâmetros, alinhada ao restante do sistema
$acao = isset($_GET['acao']) ? trim((string) $_GET['acao']) : '';
$tipo = isset($_GET['tipo']) ? trim((string) $_GET['tipo']) : '';

/* [RAMO] Download de log */
if ($acao === 'download') {
    relatorios_download($configRelatorios, $relatoriosImplementados);
    exit;
}

/* Calcula relatórios permitidos para o perfil da sessão */
$relatorios_permitidos = relatorios_obter_permitidos($perfilSessao, $configRelatorios, $relatoriosImplementados);

/* [RAMO] Tela específica de relatório (logs) */
if ($tipo === 'logs') {
    if (!in_array('logs', $relatorios_permitidos, true)) {
        http_response_code(403);
        echo 'Relatório de logs não disponível para o seu perfil.';
        exit;
    }

    $logs_arquivos = relatorios_listar_logs();

    require_once __DIR__ . '/../view/relatorios_logs.php';
    exit;
}

/* [RAMO PADRÃO] Tela de seleção de relatórios */
$relatorios_config = $configRelatorios;
require_once __DIR__ . '/../view/relatorios.php';
