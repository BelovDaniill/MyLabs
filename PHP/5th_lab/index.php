<?php
declare(strict_types=1);

session_start();

/**
 * Класс Transaction
 */
class Transaction 
{
    public function __construct(
        private int $id,
        private DateTime $date,
        private float $amount,
        private string $description,
        private string $merchant,
        private string $category = 'Общее'
    ) {}

    public function getId(): int { return $this->id; }
    public function getDate(): DateTime { return $this->date; }
    public function getAmount(): float { return $this->amount; }
    public function getDescription(): string { return $this->description; }
    public function getMerchant(): string { return $this->merchant; }
    public function getCategory(): string { return $this->category; }

    public function getDaysSinceTransaction(): int
    {
        $now = new DateTime();
        $interval = $this->date->diff($now);
        return (int) $interval->days;
    }
}

/**
 * Интерфейс хранилища
 */
interface TransactionStorageInterface
{
    public function addTransaction(Transaction $transaction): void;
    public function removeTransactionById(int $id): void;
    public function getAllTransactions(): array;
    public function findById(int $id): ?Transaction;
}

/**
 * Репозиторий транзакций (работает с сессией для сохранения состояния)
 */
class TransactionRepository implements TransactionStorageInterface
{
    public function __construct() {
        if (!isset($_SESSION['transactions'])) {
            $this->seedInitialData();
        }
    }

    private function seedInitialData(): void {
        $initial = [
            [1, '2026-10-20', 120.50, 'Покупка техники', 'Amazon', 'Электроника'],
            [2, '2026-10-15', 15.99, 'Подписка Premium', 'Netflix', 'Подписки'],
            [3, '2026-10-10', 84.00, 'Продукты', 'Walmart', 'Продукты'],
            [4, '2026-09-28', 250.00, 'Обслуживание авто', 'AutoService', 'Услуги'],
            [5, '2026-09-15', 45.00, 'Видеоигра', 'Steam', 'Развлечения'],
            [6, '2026-09-01', 550.00, 'Хостинг', 'DigitalOcean', 'Услуги'],
            [7, '2026-08-20', 12.50, 'Кофе', 'Starbucks', 'Продукты'],
            [8, '2026-08-11', 199.99, 'Монитор', 'Amazon', 'Электроника'],
            [9, '2026-07-30', 65.00, 'Билеты на концерт', 'Ticketmaster', 'Развлечения'],
            [10, '2026-07-15', 30.00, 'Книги', 'OReilly', 'Покупки']
        ];

        $_SESSION['transactions'] = [];
        foreach ($initial as $item) {
            $_SESSION['transactions'][] = [
                'id' => $item[0],
                'date' => $item[1],
                'amount' => $item[2],
                'description' => $item[3],
                'merchant' => $item[4],
                'category' => $item[5]
            ];
        }
    }

    public function addTransaction(Transaction $transaction): void
    {
        $_SESSION['transactions'][] = [
            'id' => $transaction->getId(),
            'date' => $transaction->getDate()->format('Y-m-d'),
            'amount' => $transaction->getAmount(),
            'description' => $transaction->getDescription(),
            'merchant' => $transaction->getMerchant(),
            'category' => $transaction->getCategory()
        ];
    }

    public function removeTransactionById(int $id): void
    {
        $_SESSION['transactions'] = array_values(array_filter(
            $_SESSION['transactions'],
            fn($t) => $t['id'] !== $id
        ));
    }

    public function getAllTransactions(): array
    {
        $list = [];
        foreach ($_SESSION['transactions'] as $t) {
            $list[] = new Transaction(
                $t['id'],
                new DateTime($t['date']),
                (float)$t['amount'],
                $t['description'],
                $t['merchant'],
                $t['category']
            );
        }
        return $list;
    }

    public function findById(int $id): ?Transaction
    {
        foreach ($this->getAllTransactions() as $t) {
            if ($t->getId() === $id) return $t;
        }
        return null;
    }

    public function getNextId(): int
    {
        $max = 0;
        foreach ($_SESSION['transactions'] as $t) {
            if ($t['id'] > $max) $max = $t['id'];
        }
        return $max + 1;
    }
}

/**
 * Менеджер вычислений, поиска и сортировки
 */
class TransactionManager
{
    public function __construct(private TransactionStorageInterface $repository) {}

    public function calculateTotalAmount(array $list = null): float
    {
        $total = 0.0;
        foreach (($list ?? $this->repository->getAllTransactions()) as $t) {
            $total += $t->getAmount();
        }
        return $total;
    }

    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->setTime(23, 59, 59);

        $total = 0.0;
        foreach ($this->repository->getAllTransactions() as $t) {
            $date = $t->getDate();
            if ($date >= $start && $date <= $end) {
                $total += $t->getAmount();
            }
        }
        return $total;
    }

    public function countTransactionsByMerchant(string $merchant): int
    {
        $count = 0;
        $search = strtolower(trim($merchant));
        foreach ($this->repository->getAllTransactions() as $t) {
            if (str_contains(strtolower($t->getMerchant()), $search)) {
                $count++;
            }
        }
        return $count;
    }

    public function searchTransactions(array $transactions, string $query): array
    {
        if (trim($query) === '') return $transactions;
        $q = strtolower(trim($query));
        return array_filter($transactions, fn($t) => 
            str_contains(strtolower($t->getMerchant()), $q) || 
            str_contains(strtolower($t->getDescription()), $q) ||
            str_contains(strtolower($t->getCategory()), $q)
        );
    }

    public function sortTransactions(array $transactions, string $by): array
    {
        if ($by === 'date_desc') {
            usort($transactions, fn($a, $b) => $b->getDate() <=> $a->getDate());
        } elseif ($by === 'date_asc') {
            usort($transactions, fn($a, $b) => $a->getDate() <=> $b->getDate());
        } elseif ($by === 'amount_desc') {
            usort($transactions, fn($a, $b) => $b->getAmount() <=> $a->getAmount());
        } elseif ($by === 'amount_asc') {
            usort($transactions, fn($a, $b) => $a->getAmount() <=> $b->getAmount());
        }
        return $transactions;
    }
}

/**
 * Рендерер HTML-таблицы
 */
final class TransactionTableRenderer
{
    public function render(array $transactions): string
    {
        if (empty($transactions)) {
            return "<p class='text-gray-500 p-4'>Транзакции не найдены.</p>";
        }

        $html = "
        <div class='overflow-x-auto'>
            <table class='w-full text-left text-sm border-collapse border border-gray-200'>
                <thead class='bg-gray-100 border-b border-gray-200'>
                    <tr>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>ID</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>Дата</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>Сумма</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>Описание</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>Получатель</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700'>Категория</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700 text-center'>Прошло дней</th>
                        <th class='p-3 border border-gray-200 font-semibold text-gray-700 text-center'>Действие</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($transactions as $t) {
            $html .= "
                    <tr class='hover:bg-gray-50 border-b border-gray-200'>
                        <td class='p-3 border border-gray-200 font-mono'>#" . $t->getId() . "</td>
                        <td class='p-3 border border-gray-200'>" . $t->getDate()->format('Y-m-d') . "</td>
                        <td class='p-3 border border-gray-200 font-bold text-emerald-700'>$" . number_format($t->getAmount(), 2) . "</td>
                        <td class='p-3 border border-gray-200'>" . htmlspecialchars($t->getDescription(), ENT_QUOTES, 'UTF-8') . "</td>
                        <td class='p-3 border border-gray-200'>" . htmlspecialchars($t->getMerchant(), ENT_QUOTES, 'UTF-8') . "</td>
                        <td class='p-3 border border-gray-200'>" . htmlspecialchars($t->getCategory(), ENT_QUOTES, 'UTF-8') . "</td>
                        <td class='p-3 border border-gray-200 text-center'>" . $t->getDaysSinceTransaction() . " дн.</td>
                        <td class='p-3 border border-gray-200 text-center'>
                            <a href='?action=delete&id=" . $t->getId() . "' class='text-red-600 hover:underline font-medium'>Удалить</a>
                        </td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>
        </div>";

        return $html;
    }
}

// Обработка действий (Добавление / Удаление)
$repository = new TransactionRepository();
$manager = new TransactionManager($repository);
$renderer = new TransactionTableRenderer();

// Обработка удаления
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $repository->removeTransactionById((int)$_GET['id']);
    header('Location: index.php');
    exit;
}

// Обработка добавления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id = $repository->getNextId();
    $amount = (float)$_POST['amount'];
    $date = new DateTime($_POST['date']);
    $desc = trim($_POST['description']);
    $merchant = trim($_POST['merchant']);
    $category = trim($_POST['category']) ?: 'Общее';

    $repository->addTransaction(new Transaction($id, $date, $amount, $desc, $merchant, $category));
    header('Location: index.php');
    exit;
}

// Получение списка и применение поиска/сортировки
$transactions = $repository->getAllTransactions();

$searchQuery = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'date_desc';
$selectedStat = $_GET['stat'] ?? 'count_all';

$transactions = $manager->searchTransactions($transactions, $searchQuery);
$transactions = $manager->sortTransactions($transactions, $sortBy);

// Вычисления общих показателей
$totalAll = $manager->calculateTotalAmount($repository->getAllTransactions());
$countAll = count($repository->getAllTransactions());
$avgAll = $countAll > 0 ? $totalAll / $countAll : 0;

// Определение значения для выбранной статистики
$statValue = match($selectedStat) {
    'total_amount' => '$' . number_format($totalAll, 2),
    'avg_amount' => '$' . number_format($avgAll, 2),
    'amazon_count' => (string)$manager->countTransactionsByMerchant('Amazon'),
    default => (string)$countAll,
};

$statTitle = match($selectedStat) {
    'total_amount' => 'Общая сумма',
    'avg_amount' => 'Средний чек',
    'amazon_count' => 'Amazon транзакций',
    default => 'Всего транзакций',
};
?>













<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система управления банковскими транзакциями</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 p-6">

    <div class="max-w-6xl mx-auto space-y-6">
        
        <h1 class="text-2xl font-bold text-gray-900 border-b pb-3 border-gray-300">
            Система управления банковскими транзакциями
        </h1>

        
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <form action="index.php" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <?php if ($searchQuery !== ''): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
                <?php endif; ?>
                <?php if ($sortBy !== 'date_desc'): ?>
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">
                <?php endif; ?>

                <div>
                    <span class="text-xs text-gray-500 uppercase font-semibold block mb-1"><?= $statTitle ?></span>
                    <p class="text-2xl font-bold text-gray-800"><?= $statValue ?></p>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Выберите показатель:</label>
                    <select name="stat" onchange="this.form.submit()" class="border p-2 rounded text-xs bg-gray-50">
                        <option value="count_all" <?= $selectedStat === 'count_all' ? 'selected' : '' ?>>Всего транзакций</option>
                        <option value="total_amount" <?= $selectedStat === 'total_amount' ? 'selected' : '' ?>>Общая сумма</option>
                        <option value="avg_amount" <?= $selectedStat === 'avg_amount' ? 'selected' : '' ?>>Средний чек</option>
                        <option value="amazon_count" <?= $selectedStat === 'amazon_count' ? 'selected' : '' ?>>Amazon транзакций</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            
            <div class="bg-white p-5 rounded-lg shadow-sm space-y-4">
                <h2 class="font-bold text-md text-gray-800 border-b pb-2">Добавить транзакцию</h2>
                <form action="index.php" method="POST" class="space-y-3 text-xs">
                    <input type="hidden" name="action" value="add">
                    <div>
                        <label class="block mb-1 font-medium">Сумма ($)</label>
                        <input type="number" step="0.01" name="amount" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Дата</label>
                        <input type="date" name="date" value="<?= date('Y-m-d') ?>" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Получатель</label>
                        <input type="text" name="merchant" placeholder="Напр. Amazon" required class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Категория</label>
                        <input type="text" name="category" placeholder="Напр. Электроника" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Описание</label>
                        <input type="text" name="description" placeholder="Напр. Покупка наушников" required class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" class="bg-gray-600 w-full py-2 text-white font-medium rounded">Сохранить</button>
                </form>
            </div>

            
            <div class="md:col-span-2 space-y-4">
                
                <div class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-center justify-between">
                    
                    <form action="index.php" method="GET" class="flex gap-2 items-center text-xs">
                        <?php if ($selectedStat !== 'count_all'): ?>
                            <input type="hidden" name="stat" value="<?= htmlspecialchars($selectedStat) ?>">
                        <?php endif; ?>
                        <?php if ($sortBy !== 'date_desc'): ?>
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">
                        <?php endif; ?>
                        <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Поиск..." class="border p-2 rounded w-48">
                        <button type="submit" class="bg-gray-600 text-white px-3 py-2 rounded">Найти</button>
                        <?php if ($searchQuery !== ''): ?>
                            <a href="index.php" class="text-gray-500 hover:underline">Сброс</a>
                        <?php endif; ?>
                    </form>

                    
                    <form action="index.php" method="GET" class="flex gap-2 items-center text-xs">
                        <?php if ($searchQuery !== ''): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
                        <?php endif; ?>
                        <?php if ($selectedStat !== 'count_all'): ?>
                            <input type="hidden" name="stat" value="<?= htmlspecialchars($selectedStat) ?>">
                        <?php endif; ?>
                        <select name="sort" onchange="this.form.submit()" class="border p-2 rounded">
                            <option value="date_desc" <?= $sortBy === 'date_desc' ? 'selected' : '' ?>>Сначала новые</option>
                            <option value="date_asc" <?= $sortBy === 'date_asc' ? 'selected' : '' ?>>Сначала старые</option>
                            <option value="amount_desc" <?= $sortBy === 'amount_desc' ? 'selected' : '' ?>>Сумма (убывание)</option>
                            <option value="amount_asc" <?= $sortBy === 'amount_asc' ? 'selected' : '' ?>>Сумма (возрастание)</option>
                        </select>
                    </form>
                </div>

                
                <div class="bg-white p-5 rounded-lg shadow-sm space-y-3">
                    <h2 class="font-bold text-md text-gray-800">Список транзакций</h2>
                    <?= $renderer->render($transactions) ?>
                </div>

            </div>

        </div>

    </div>

</body>
</html>