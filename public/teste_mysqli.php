<?php
echo 'PHP version: ' . PHP_VERSION . "<br>\n";
echo 'Loaded ini: ' . php_ini_loaded_file() . "<br>\n";
echo 'extension_dir: ' . ini_get('extension_dir') . "<br>\n";
echo 'mysqli loaded? ' . (extension_loaded('mysqli') ? 'SIM' : 'NAO') . "<br>\n";
