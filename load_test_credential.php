<?php

$dotenvPath = __DIR__ . '/.env';

if (file_exists($dotenvPath)) {
    $dotenv = new \Symfony\Component\Dotenv\Dotenv();
    $dotenv->load($dotenvPath);
}
