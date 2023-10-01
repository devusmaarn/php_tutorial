<?php

declare(strict_types=1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

include_once  APP_PATH . 'App.php';
include_once  APP_PATH . 'helpers.php';

$files = getTransactionFiles(FILES_PATH);

$transactions = getTransactions($files[0], 'extractTransaction');

$totals = getTotals($transactions);

include_once VIEWS_PATH . 'transactions.php';