<?php
declare(strict_types=1);

/**
 * Глобальный массив транзакций
 * @var array<int, array{id: int, date: string, amount: float, description: string, merchant: string}>
 */
$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" => "2026-05-10",
        "amount" => 250.00,
        "description" => "Online shopping",
        "merchant" => "TechStore",
    ],
];

/**
 * Вычисляет общую сумму всех транзакций.
 * 
 * @param array $transactions Массив транзакций
 * @return float Общая сумма
 */
function calculateTotalAmount(array $transactions): float {
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}

/**
 * Ищет транзакцию по части описания.
 * 
 * @param string $descriptionPart Часть описания для поиска
 * @return array Найденные транзакции
 */
function findTransactionByDescription(string $descriptionPart): array {
    global $transactions;
    return array_filter($transactions, function($transaction) use ($descriptionPart) {
        return mb_stripos($transaction['description'], $descriptionPart) !== false;
    });
}

/**
 * Ищет транзакцию по уникальному идентификатору (через foreach).
 * 
 * @param int $id Идентификатор транзакции
 * @return array|null Транзакция или null, если не найдена
 */
function findTransactionById(int $id): ?array {
    global $transactions;
    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}

/**
 * Ищет транзакцию по идентификатору с помощью array_filter (высшая оценка).
 * 
 * @param int $id Идентификатор транзакции
 * @return array|null Транзакция или null
 */
function findTransactionByIdAdvanced(int $id): ?array {
    global $transactions;
    $result = array_filter($transactions, fn($t) => $t['id'] === $id);
    return !empty($result) ? reset($result) : null;
}

/**
 * Возвращает количество дней между датой транзакции и текущим днем.
 * 
 * @param string $date Дата транзакции (YYYY-MM-DD)
 * @return int Количество дней
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime();
    $interval = $currentDate->diff($transactionDate);
    return (int)$interval->format('%a');
}

/**
 * Добавляет новую транзакцию в глобальный массив.
 * 
 * @param int $id Идентификатор
 * @param string $date Дата
 * @param float $amount Сумма
 * @param string $description Описание
 * @param string $merchant Продавец
 * @return void
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void {
    global $transactions;
    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

// Пример добавления новой транзакции
addTransaction(1, "2020-01-10", 100.00, "Grocery shopping", "Supermarket");
addTransaction(2, "2020-02-15", 75.50, "Dinner with friends", "Local Restaurant");
addTransaction(3, "2026-05-10", 250.00, "Online shopping", "TechStore");

// Сортировка транзакций по дате (от старых к новым)
usort($transactions, function($a, $b) {
    return strcmp($a['date'], $b['date']);
});

// Сортировка транзакций по сумме (по убыванию)
usort($transactions, function($a, $b) {
    return $b['amount'] <=> $a['amount'];
});
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №4</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Список банковских транзакций</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Организация (Merchant)</th>
                <th>Дней с момента транзакции</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $tx): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$tx['id']) ?></td>
                    <td><?= htmlspecialchars($tx['date']) ?></td>
                    <td><?= number_format($tx['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($tx['description']) ?></td>
                    <td><?= htmlspecialchars($tx['merchant']) ?></td>
                    <td><?= daysSinceTransaction($tx['date']) ?> дн.</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>Общая сумма:</strong></td>
                <td colspan="4"><strong><?= number_format(calculateTotalAmount($transactions), 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>