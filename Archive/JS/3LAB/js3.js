/**
 * @typedef {Object} Transaction
 * @property {string} transaction_id - Уникальный идентификатор
 * @property {string} transaction_date - Дата (YYYY-MM-DD)
 * @property {number} transaction_amount - Сумма
 * @property {string} transaction_type - Тип (debit/credit)
 * @property {string} transaction_description - Описание
 * @property {string} merchant_name - Магазин
 * @property {string} card_type - Тип карты
 */

/**
 * Массив транзакций для тестирования
 * @type {Transaction[]}
 */
const transactions = [
    {
        transaction_id: "1",
        transaction_date: "2019-01-01",
        transaction_amount: 100.0,
        transaction_type: "debit",
        transaction_description: "Payment for groceries",
        merchant_name: "SuperMart",
        card_type: "Visa",
    },
    {
        transaction_id: "2",
        transaction_date: "2019-01-02",
        transaction_amount: 50.0,
        transaction_type: "credit",
        transaction_description: "Refund for returned item",
        merchant_name: "OnlineShop",
        card_type: "MasterCard",
    },
    {
        transaction_id: "3",
        transaction_date: "2019-01-03",
        transaction_amount: 75.0,
        transaction_type: "debit",
        transaction_description: "Dinner with friends",
        merchant_name: "RestaurantABC",
        card_type: "Amex",
    },
    {
        transaction_id: "4",
        transaction_date: "2019-01-04",
        transaction_amount: 120.0,
        transaction_type: "debit",
        transaction_description: "Shopping at Mall",
        merchant_name: "FashionStoreXYZ",
        card_type: "Discover",
    },
    {
        transaction_id: "5",
        transaction_date: "2019-01-05",
        transaction_amount: 25.0,
        transaction_type: "credit",
        transaction_description: "Returned defective product",
        merchant_name: "ElectronicsShop",
        card_type: "Visa",
    },
    {
        transaction_id: "6",
        transaction_date: "2019-01-06",
        transaction_amount: 60.0,
        transaction_type: "debit",
        transaction_description: "Gasoline refill",
        merchant_name: "GasStationXYZ",
        card_type: "MasterCard",
    },
    {
        transaction_id: "7",
        transaction_date: "2019-01-07",
        transaction_amount: 40.0,
        transaction_type: "debit",
        transaction_description: "Lunch with colleagues",
        merchant_name: "Cafe123",
        card_type: "Visa",
    }
];

//Реализация функций 

/**
 * Возвращает массив уникальных типов транзакций.
 * @param {Transaction[]} transactions 
 * @returns {string[]}
 */
function getUniqueTransactionTypes(transactions) {
    return [...new Set(transactions.map(t => t.transaction_type))];
}

/**
 * Вычисляет общую сумму всех транзакций.
 * @param {Transaction[]} transactions 
 * @returns {number}
 */
function calculateTotalAmount(transactions) {
    return transactions.reduce((sum, t) => sum + t.transaction_amount, 0);
}

/**
 * [extra] Вычисляет сумму транзакций за указанный год, месяц и день.
 * @param {Transaction[]} transactions 
 * @param {number} [year] 
 * @param {number} [month] 
 * @param {number} [day] 
 * @returns {number}
 */
function calculateTotalAmountByDate(transactions, year, month, day) {
    return transactions.filter(t => {
        const date = new Date(t.transaction_date);
        const matchYear = year ? date.getFullYear() === year : true;
        const matchMonth = month ? (date.getMonth() + 1) === month : true;
        const matchDay = day ? date.getDate() === day : true;
        return matchYear && matchMonth && matchDay;
    }).reduce((sum, t) => sum + t.transaction_amount, 0);
}

/**
 * Возвращает транзакции указанного типа.
 * @param {Transaction[]} transactions 
 * @param {string} type - 'debit' или 'credit'
 * @returns {Transaction[]}
 */
function getTransactionByType(transactions, type) {
    return transactions.filter(t => t.transaction_type === type);
}

/**
 * Возвращает транзакции в диапазоне дат.
 * @param {Transaction[]} transactions 
 * @param {string} startDate 
 * @param {string} endDate 
 * @returns {Transaction[]}
 */
function getTransactionsInDateRange(transactions, startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return transactions.filter(t => {
        const current = new Date(t.transaction_date);
        return current >= start && current <= end;
    });
}

/**
 * Возвращает транзакции по названию магазина.
 * @param {Transaction[]} transactions 
 * @param {string} merchantName 
 * @returns {Transaction[]}
 */
function getTransactionsByMerchant(transactions, merchantName) {
    return transactions.filter(t => t.merchant_name === merchantName);
}

/**
 * Вычисляет среднее значение транзакции.
 * @param {Transaction[]} transactions 
 * @returns {number}
 */
function calculateAverageTransactionAmount(transactions) {
    if (transactions.length === 0) return 0;
    return calculateTotalAmount(transactions) / transactions.length;
}

/**
 * Возвращает транзакции в диапазоне сумм.
 * @param {Transaction[]} transactions 
 * @param {number} minAmount 
 * @param {number} maxAmount 
 * @returns {Transaction[]}
 */
function getTransactionsByAmountRange(transactions, minAmount, maxAmount) {
    return transactions.filter(t => t.transaction_amount >= minAmount && t.transaction_amount <= maxAmount);
}

/**
 * Вычисляет общую сумму дебетовых транзакций.
 * @param {Transaction[]} transactions 
 * @returns {number}
 */
function calculateTotalDebitAmount(transactions) {
    return transactions
        .filter(t => t.transaction_type === 'debit')
        .reduce((sum, t) => sum + t.transaction_amount, 0);
}

/**
 * Находит месяц с наибольшим количеством транзакций.
 * @param {Transaction[]} transactions 
 * @returns {string}
 */
function findMostTransactionsMonth(transactions) {
    const counts = transactions.reduce((acc, t) => {
        const month = t.transaction_date.slice(0, 7); // Формат YYYY-MM
        acc[month] = (acc[month] || 0) + 1;
        return acc;
    }, {});
    return Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b, "N/A");
}

/**
 * Находит месяц с наибольшим количеством дебетовых транзакций.
 * @param {Transaction[]} transactions 
 * @returns {string}
 */
function findMostDebitTransactionMonth(transactions) {
    const debitOnly = transactions.filter(t => t.transaction_type === 'debit');
    return findMostTransactionsMonth(debitOnly);
}

/**
 * Определяет, какого типа транзакций больше.
 * @param {Transaction[]} transactions 
 * @returns {string} 'debit', 'credit' или 'equal'
 */
function mostTransactionTypes(transactions) {
    const debitCount = getTransactionByType(transactions, 'debit').length;
    const creditCount = getTransactionByType(transactions, 'credit').length;
    if (debitCount > creditCount) return 'debit';
    if (creditCount > debitCount) return 'credit';
    return 'equal';
}

/**
 * Возвращает транзакции до указанной даты.
 * @param {Transaction[]} transactions 
 * @param {string} date 
 * @returns {Transaction[]}
 */
function getTransactionsBeforeDate(transactions, date) {
    const targetDate = new Date(date);
    return transactions.filter(t => new Date(t.transaction_date) < targetDate);
}

/**
 * Поиск транзакции по ID.
 * @param {Transaction[]} transactions 
 * @param {string} id 
 * @returns {Transaction|undefined}
 */
function findTransactionById(transactions, id) {
    return transactions.find(t => t.transaction_id === id);
}

/**
 * Массив всех описаний транзакций.
 * @param {Transaction[]} transactions 
 * @returns {string[]}
 */
function mapTransactionDescriptions(transactions) {
    return transactions.map(t => t.transaction_description);
}

// === ТЕСТИРОВАНИЕ ===
console.log("--- Тест: Основной набор данных ---");
console.log("1. Уникальные типы:", getUniqueTransactionTypes(transactions));
console.log("2. Общая сумма:", calculateTotalAmount(transactions));
console.log("3. Сумма за дату (2019-01-01):", calculateTotalAmountByDate(transactions, 2019, 1, 1));
console.log("4. По типу (debit):", getTransactionByType(transactions, "debit").length);
console.log("5. В диапазоне дат:", getTransactionsInDateRange(transactions, "2019-01-01", "2019-01-03").length);
console.log("6. По магазину (SuperMart):", getTransactionsByMerchant(transactions, "SuperMart").length);
console.log("7. Средняя сумма:", calculateAverageTransactionAmount(transactions));
console.log("8. В диапазоне сумм (50-100):", getTransactionsByAmountRange(transactions, 50, 100).length);
console.log("9. Общий дебет:", calculateTotalDebitAmount(transactions));
console.log("10. Месяц с макс. транзакций:", findMostTransactionsMonth(transactions));
console.log("11. Месяц с макс. дебетом:", findMostDebitTransactionMonth(transactions));
console.log("12. Преобладающий тип:", mostTransactionTypes(transactions));
console.log("13. До даты (2019-01-04):", getTransactionsBeforeDate(transactions, "2019-01-04").length);
console.log("14. По ID (id:3):", findTransactionById(transactions, "3")?.transaction_description);
console.log("15. Описания:", mapTransactionDescriptions(transactions));

// === ТЕСТИРОВАНИЕ: ПУСТОЙ МАССИВ [EXTRA] ===
console.log("--- Тест: Пустой массив ---");
const empty = [];
console.log("1. Уникальные типы:", getUniqueTransactionTypes(empty));
console.log("2. Общая сумма:", calculateTotalAmount(empty));
console.log("3. Сумма за дату:", calculateTotalAmountByDate(empty, 2019, 1, 1));
console.log("4. По типу:", getTransactionByType(empty, "debit").length);
console.log("5. В диапазоне дат:", getTransactionsInDateRange(empty, "2019-01-01", "2019-01-03").length);
console.log("6. По магазину:", getTransactionsByMerchant(empty, "SuperMart").length);
console.log("7. Средняя сумма:", calculateAverageTransactionAmount(empty));
console.log("8. В диапазоне сумм:", getTransactionsByAmountRange(empty, 50, 100).length);
console.log("9. Общий дебет:", calculateTotalDebitAmount(empty));
console.log("10. Месяц с макс. транзакций:", findMostTransactionsMonth(empty));
console.log("11. Месяц с макс. дебетом:", findMostDebitTransactionMonth(empty));
console.log("12. Преобладающий тип:", mostTransactionTypes(empty));
console.log("13. До даты:", getTransactionsBeforeDate(empty, "2019-01-04").length);
console.log("14. По ID:", findTransactionById(empty, "3"));
console.log("15. Описания:", mapTransactionDescriptions(empty));

// === ТЕСТИРОВАНИЕ: ОДНА ТРАНЗАКЦИЯ [EXTRA] ===
console.log("--- Тест: Одна транзакция ---");
const single = [transactions[0]];
console.log("1. Уникальные типы:", getUniqueTransactionTypes(single));
console.log("2. Общая сумма:", calculateTotalAmount(single));
console.log("3. Сумма за дату:", calculateTotalAmountByDate(single, 2019, 1, 1));
console.log("4. По типу:", getTransactionByType(single, "debit").length);
console.log("5. В диапазоне дат:", getTransactionsInDateRange(single, "2019-01-01", "2019-01-03").length);
console.log("6. По магазину:", getTransactionsByMerchant(single, "SuperMart").length);
console.log("7. Средняя сумма:", calculateAverageTransactionAmount(single));
console.log("8. В диапазоне сумм:", getTransactionsByAmountRange(single, 50, 100).length);
console.log("9. Общий дебет:", calculateTotalDebitAmount(single));
console.log("10. Месяц с макс. транзакций:", findMostTransactionsMonth(single));
console.log("11. Месяц с макс. дебетом:", findMostDebitTransactionMonth(single));
console.log("12. Преобладающий тип:", mostTransactionTypes(single));
console.log("13. До даты:", getTransactionsBeforeDate(single, "2019-01-04").length);
console.log("14. По ID:", findTransactionById(single, "1")?.transaction_id);
console.log("15. Описания:", mapTransactionDescriptions(single));