<?php

namespace App\Config;

define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_USER', getenv('DB_USER') ?: 'etiquetador');
define('DB_PASS', getenv('DB_PASS') ?: 'password');
define('DB_NAME', getenv('DB_NAME') ?: 'etiquetador');

define('BASE_URL', '/'); // Front Controller takes care of routing
