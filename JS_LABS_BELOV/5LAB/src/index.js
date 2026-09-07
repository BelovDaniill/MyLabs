/**
 * Главный модуль приложения. Инициализирует обработчики событий
 * @module index
 */

import { generateId, formatDate } from './utils.js';
import { addTransaction, deleteTransactionFromStorage, calculateTotal, findTransactionById } from './transactions.js';
import { renderTransactionRow, updateTotalDisplay, showTransactionDetails, hideTransactionDetails, clearErrors, showFieldError } from './ui.js';

// DOM Элементы
const form = document.getElementById('transaction-form');
const table = document.getElementById('transactions-table');

/**
 * Обрабатывает отправку формы добавления транзакции с валидацией
 * @param {Event} event - Объект события отправки формы
 */
function handleFormSubmit(event) {
    event.preventDefault();
    clearErrors();

    const amountInput = document.getElementById('amount');
    const categorySelect = document.getElementById('category');
    const descriptionTextarea = document.getElementById('description');

    let isValid = true;

    // Валидация суммы
    const amount = parseFloat(amountInput.value);
    if (isNaN(amount) || amount === 0) {
        showFieldError('amount', 'Введите корректную сумму (не равную 0).');
        isValid = false;
    }

    // Валидация категории
    const category = categorySelect.value;
    if (!category) {
        showFieldError('category', 'Пожалуйста, выберите категорию.');
        isValid = false;
    }

    // Валидация описания
    const description = descriptionTextarea.value.trim();
    if (!description) {
        showFieldError('description', 'Описание не должно быть пустым.');
        isValid = false;
    }

    if (!isValid) return;

    // Создание объекта транзакции
    const newTransaction = {
        id: generateId(),
        date: formatDate(new Date()),
        amount: amount,
        category: category,
        description: description
    };

    // Сохранение и отрисовка
    addTransaction(newTransaction);
    renderTransactionRow(newTransaction);
    
    // Обновление баланса
    updateTotalDisplay(calculateTotal());

    // Очистка формы
    form.reset();
}

/**
 * Делегированный обработчик кликов по таблице
 * Обрабатывает удаление и просмотр деталей
 * @param {MouseEvent} event - Объект события клика
 */
function handleTableClick(event) {
    const target = event.target;
    
    // Находим строку tr, по которой или внутри которой кликнули
    const row = target.closest('tr');
    if (!row || target.tagName === 'TH') return;

    const transactionId = row.dataset.id;

    // Проверяем, нажата ли кнопка удаления
    if (target.classList.contains('delete-btn')) {
        // Чтобы клик на кнопку удаления не открывал детали транзакции
        event.stopPropagation(); 

        // Удаление из данных и DOM дерева
        deleteTransactionFromStorage(transactionId);
        row.remove();

        // Пересчет баланса
        updateTotalDisplay(calculateTotal());
        
        // Скрываем детали, если удалили текущую просматриваемую транзакцию
        const detailIdEl = document.getElementById('detail-id');
        if (detailIdEl.textContent === transactionId) {
            hideTransactionDetails();
        }
    } else {
        // Если кликнули на саму строку, то показываем полное описание
        const transaction = findTransactionById(transactionId);
        if (transaction) {
            showTransactionDetails(transaction);
        }
    }
}

// Инициализация слушателей событий при старте приложения
form.addEventListener('submit', handleFormSubmit);
table.addEventListener('click', handleTableClick);