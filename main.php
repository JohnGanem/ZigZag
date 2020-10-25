<?php

include __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use ZigZag\Initialisation;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

(new Initialisation)();
