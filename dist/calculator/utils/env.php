<?php

$path = $_SERVER['DOCUMENT_ROOT'] . '/calculator/.env';
if (file_exists($path)) {
    $data = file_get_contents($path);
    $lines = explode("\n", $data);

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}
