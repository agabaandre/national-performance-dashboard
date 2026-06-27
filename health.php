<?php
header('Content-Type: text/plain; charset=utf-8');
echo 'OK PHP ' . PHP_VERSION . ' (' . PHP_SAPI . ')' . PHP_EOL;
echo 'SCRIPT_FILENAME=' . ($_SERVER['SCRIPT_FILENAME'] ?? 'n/a') . PHP_EOL;
echo 'DOCUMENT_ROOT=' . ($_SERVER['DOCUMENT_ROOT'] ?? 'n/a') . PHP_EOL;
echo 'SCRIPT_NAME=' . ($_SERVER['SCRIPT_NAME'] ?? 'n/a') . PHP_EOL;
echo 'REQUEST_URI=' . ($_SERVER['REQUEST_URI'] ?? 'n/a') . PHP_EOL;
echo 'PATH_INFO=' . ($_SERVER['PATH_INFO'] ?? '(empty)') . PHP_EOL;
echo 'index.php exists=' . (is_file(__DIR__ . '/index.php') ? 'yes' : 'no') . PHP_EOL;
echo 'public/index.php exists=' . (is_file(__DIR__ . '/public/index.php') ? 'yes' : 'no') . PHP_EOL;
