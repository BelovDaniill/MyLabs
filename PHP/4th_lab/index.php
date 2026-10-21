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
 * Ищет транзакцию по части описания с сохранением исходных индексов.
 * 
 * @param string $descriptionPart Часть описания для поиска
 * @return array Найденные транзакции [индекс => транзакция]
 */
function findTransactionByDescription(string $descriptionPart): array {
    global $transactions;
    $results = [];
    foreach ($transactions as $index => $transaction) {
        // Используем stripos вместо mb_stripos для максимальной совместимости
        if (stripos($transaction['description'], $descriptionPart) !== false) {
            $results[$index] = $transaction;
        }
    }
    return $results;
}

/**
 * Ищет транзакцию по уникальному идентификатору с помощью foreach.
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
 * Ищет транзакцию по идентификатору с помощью array_filter.
 * 
 * @param int $id Идентификатор транзакции
 * @return array|null Транзакция или null
 */
function findTransactionByIdAdvanced(int $id): ?array {
    global $transactions;
    $result = array_filter($transactions, function($transaction) use ($id) {
        return $transaction['id'] === $id;
    });
    $values = array_values($result);
    return !empty($values) ? $values[0] : null;
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
addTransaction(4, "2020-01-26", 110.00, "Delivery", "Posta Moldovei");
addTransaction(5, "2020-11-15", 575.50, "Dinner with family", "New Restaurant");
addTransaction(6, "2024-05-10", 50.00, "Offline shopping", "Drugstore");

// Получаем параметры из GET-запроса
$sortType = $_GET['sort'] ?? 'date';
$searchQuery = trim($_GET['search'] ?? '');

// Сортировка транзакций
if ($sortType === 'amount') {
    usort($transactions, function($a, $b) {
        return $b['amount'] <=> $a['amount'];
    });
} else {
    usort($transactions, function($a, $b) {
        return strcmp($a['date'], $b['date']);
    });
}

// Поиск по подстроке, если введен запрос
$searchResults = [];
if ($searchQuery !== '') {
    $searchResults = findTransactionByDescription($searchQuery);
}

// Формируем текст для поля вывода результата
$searchResultText = '';
if ($searchQuery !== '') {
    if (!empty($searchResults)) {
        $searchResultText = 'Индекс: ' . implode(', ', array_keys($searchResults));
    } else {
        $searchResultText = 'Не найдено';
    }
}
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
        .controls-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .btn-container { display: flex; gap: 10px; }
        .btn { 
            padding: 8px 16px; 
            text-decoration: none; 
            background-color: #f0f0f0; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            color: #333;
            display: inline-block;
        }
        .btn.active { background-color: #007bff; color: white; border-color: #0056b3; }
        .btn:hover { background-color: #e0e0e0; }
        .search-form { display: flex; gap: 8px; align-items: center; }
        .search-form input[type="text"] { padding: 7px; border: 1px solid #ccc; border-radius: 4px; width: 180px; }
        .search-form .result-input { width: 120px; background-color: #e9ecef; font-weight: bold; color: #495057; text-align: center; }
        .search-form button { padding: 7px 14px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-form button:hover { background-color: #218838; }
    </style>
</head>
<body>

    <h2>Список банковских транзакций</h2>

    <!-- Панель управления -->
    <div class="controls-container">
        <div class="btn-container">
            <a href="?sort=date&search=<?= urlencode($searchQuery) ?>" class="btn <?= ($sortType === 'date') ? 'active' : '' ?>">Сортировать по дате</a>
            <a href="?sort=amount&search=<?= urlencode($searchQuery) ?>" class="btn <?= ($sortType === 'amount') ? 'active' : '' ?>">Сортировать по сумме</a>
        </div>

        <form method="GET" class="search-form">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sortType) ?>">
            <input type="text" name="search" placeholder="Поиск в описании..." value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Найти</button>
            <input type="text" readonly class="result-input" placeholder="Результат" value="<?= htmlspecialchars($searchResultText) ?>">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Индекс</th>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Организация (Merchant)</th>
                <th>Дней с момента транзакции</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $index => $tx): ?>
                <tr>
                    <td><?= $index ?></td>
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
                <td colspan="5"><strong><?= number_format(calculateTotalAmount($transactions), 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>