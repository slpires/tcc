<?php
// Ver o que o PHP entende como error_reporting
echo '<pre>';
echo "ini_get('error_reporting'): ";
var_dump(ini_get('error_reporting'));
echo "E_ALL: ";
var_dump(E_ALL);
echo "</pre>";

// Forçar um NOTICE
echo $variavel_que_nao_existe;

// Forçar um WARNING
array_key_exists('x', null);

// Forçar um WARNING customizado
trigger_error('Aviso de teste gerado manualmente.', E_USER_WARNING);
