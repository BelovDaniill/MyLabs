/**
 * Модуль для работы с DOM-деревом (интерфейс пользователя)
 * @module ui
 */

import { formatDate, getShortDescription } from './utils.js';

// Получение ссылок на DOM элементы
const tableBody = document.getElementById('transactions-body');
const totalAmountEl = document.getElementById('total-amount');
const detailsBlock = document.getElementById('details-block');

/**
 * Отрисовывает одну транзакцию в таблице DOM
 * @param {Object} transaction - Объект добавляемой транзакции
 */
export function renderTransactionRow(transaction) {
    const row = document.createElement('tr');
    
    // Записываем ID в data-атрибут строки для последующего извлечения при клике
    row.dataset.id = transaction.id;

    const rowClass = transaction.amount >= 0 ? 'row-income' : 'row-expense';
    row.classList.add(rowClass);

    // Получаем укороченное описание
    const shortDesc = getShortDescription(transaction.description, 4);

    row.innerHTML = `
        <td>${transaction.date}</td>
        <td>${transaction.category}</td>
        <td>${shortDesc}</td>
        <td><button class="delete-btn">Удалить</button></td>
    `;

    tableBody.appendChild(row);
}

/**
 * Обновляет отображение общего баланса на странице
 * @param {number} total - Значение баланса
 */
export function updateTotalDisplay(total) {
    totalAmountEl.textContent = total.toFixed(2);
    
    // Смена цвета текста общего баланса
    if (total >= 0) {
        totalAmountEl.style.color = '#2e7d32';
    } else {
        totalAmountEl.style.color = '#c62828';
    }
}

/**
 * Отображает подробную информацию о транзакции в блоке деталей
 * @param {Object} transaction - Объект выбранной транзакции
 */
export function showTransactionDetails(transaction) {
    document.getElementById('detail-id').textContent = transaction.id;
    document.getElementById('detail-date').textContent = transaction.date;
    document.getElementById('detail-amount').textContent = transaction.amount.toFixed(2);
    document.getElementById('detail-category').textContent = transaction.category;
    document.getElementById('detail-desc-text').textContent = transaction.description;

    detailsBlock.classList.remove('hidden');
}

/**
 * Скрывает блок детальной информации
 */
export function hideTransactionDetails() {
    detailsBlock.classList.add('hidden');
}

/**
 * Очищает сообщения об ошибках в форме валидации
 */
export function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
}

/**
 * Отображает сообщение об ошибке для конкретного поля ввода
 * @param {string} fieldId - ID элемента ошибки
 * @param {string} message - Текст ошибки
 */
export function showFieldError(fieldId, message) {
    document.getElementById(`error-${fieldId}`).textContent = message;
}