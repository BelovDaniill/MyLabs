/**
 * Модуль вспомогательных функций
 * @module utils
 */

/**
 * Генерирует уникальный строковый идентификатор (UUID v4-подобный)
 * @returns {string} Уникальный ID
 */
export function generateId() {
    return Math.random().toString(36).substring(2, 9) + Date.now().toString(36);
}

/**
 * Форматирует объект даты в читаемую строку (ДД.ММ.ГГГГ ЧЧ:ММ)
 * @param {Date} date - Объект даты
 * @returns {string} Отформатированная строка даты и времени
 */
export function formatDate(date) {
    const options = {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleString('ru-RU', options);
}

/**
 * Обрезает строку до указанного количества слов
 * @param {string} text - Исходный текст
 * @param {number} maxWords - Максимальное количество слов
 * @returns {string} Обрезанный текст с многоточием, если слов больше лимита
 */
export function getShortDescription(text, maxWords = 4) {
    const words = text.trim().split(/\s+/);
    if (words.length <= maxWords) {
        return text;
    }
    return words.slice(0, maxWords).join(' ') + '...';
}