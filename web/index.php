<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$app = require_once __DIR__ . '/../src/web_bootstrap.php';
$app->run();