<?php

declare(strict_types = 1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file):
        if (is_dir($file)):
            continue;
        endif;

        $files[] = $dirPath . $file;
    endforeach;

    return $files;
}

function getTransactions(string $filePath, ?callable $transactionHandler = null): array
{

    if(! file_exists($filePath)):
        trigger_error('Error: ' . $filePath . 'does not exist', E_USER_ERROR);
    endif;

    $transactions = [];
    $file = fopen($filePath, 'r');

    fgetcsv($file);

    while (($transaction = fgetcsv($file)) !== false):
        $transactions[] = $transactionHandler
            ? $transactionHandler($transaction)
            : $transaction;
    endwhile;

    return $transactions;
}

function extractTransaction(array $transaction): array
{
    [$date, $checkNumber, $description, $amount] = $transaction;

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => (float) str_replace(['$', ','], '', $amount)
    ];
}

function getTotals(array $transactions): array
{
    $totals = ['net' => 0, 'income' => 0, 'expense' => 0];
    foreach ($transactions as $transaction):
        $totals['net'] += abs($transaction['amount']);

        if ($transaction['amount'] <= 0):
            $totals['expense'] += $transaction['amount'];
        else:
            $totals['income'] += $transaction['amount'];
        endif;
    endforeach;

    return $totals;
}