/**
 * Модуль для управления бизнес логикой и данными транзакций
 * @module transactions
 */

/**
 * @typedef {Object} Transaction
 * @property {string} id - Уникальный идентификатор
 * @property {string} date - Дата и время добавления в виде строки
 * @property {number} amount - Сумма транзакции (положительная или отрицательная)
 * @property {string} category - Категория транзакции
 * @property {string} description - Полное описание транзакции
 */

/** @type {Transaction[]} Массив для хранения всех транзакций */
let transactions = [];

/**
 * Возвращает копию массива транзакций
 * @returns {Transaction[]} Список транзакций
 */
export function getTransactions() {
    return [...transactions];
}

/**
 * Добавляет новую транзакцию в массив
 * @param {Transaction} transaction - Объект транзакции
 */
export function addTransaction(transaction) {
    transactions.push(transaction);
}

/**
 * Удаляет транзакцию из массива по её ID
 * @param {string} id - Идентификатор удаляемой транзакции
 */
export function deleteTransactionFromStorage(id) {
    transactions = transactions.filter(t => t.id !== id);
}

/**
 * Находит транзакцию по её ID
 * @param {string} id - Идентификатор транзакции
 * @returns {Transaction|undefined} Объект транзакции или undefined
 */
export function findTransactionById(id) {
    return transactions.find(t => t.id === id);
}

/**
 * Вычисляет общую сумму всех транзакций в массиве
 * @returns {number} Общий баланс
 */
export function calculateTotal() {
    return transactions.reduce((sum, current) => sum + current.amount, 0);
}