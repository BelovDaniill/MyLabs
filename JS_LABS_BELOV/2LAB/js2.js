/**
 * 1.1. Вывод массива в консоль (Развернутый формат)
 * @param {Array} array - Исходный массив
 */
function printArray(array) {
    if (!Array.isArray(array)) return;
    for (let i = 0; i < array.length; i++) {
        console.log(`Element ${i}: value ${array[i]}`);
    }
}

/**
 * 1.1. Вывод массива в консоль (Краткий формат)
 * @param {Array} array - Исходный массив
 */
function printArray1(array) {
    if (!Array.isArray(array)) return;
    for (let i = 0; i < array.length; i++) {
        console.log(`${i}: ${array[i]}`);
    }
}

/**
 * 1.2. Функция forEach
 * Выполняет колбэк для каждого элемента. Ничего не возвращает.
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 */
function forEach(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    for (let i = 0; i < array.length; i++) {
        callback(array[i], i, array);
    }
}

/**
 * 2. Функция map
 * Создает новый массив с результатами вызова колбэка.
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 * @returns {Array} Новый трансформированный массив
 */
function map(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    const result = [];
    for (let i = 0; i < array.length; i++) {
        result.push(callback(array[i], i, array));
    }
    return result;
}

/**
 * 3. Функция filter
 * Создает новый массив из элементов, прошедших проверку (true).
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 * @returns {Array} Отфильтрованный массив
 */
function filter(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    const result = [];
    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            result.push(array[i]);
        }
    }
    return result;
}

/**
 * 4. Функция find
 * Возвращает первый элемент, удовлетворяющий условию.
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 * @returns {*} Элемент или undefined
 */
function find(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            return array[i];
        }
    }
    return undefined;
}

/**
 * 5. Функция some
 * Проверяет, подходит ли хотя бы один элемент под условие.
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 * @returns {boolean}
 */
function some(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            return true;
        }
    }
    return false;
}

/**
 * 6. Функция every
 * Проверяет, все ли элементы подходят под условие.
 * @param {Array} array 
 * @param {Function} callback - (element, index, array)
 * @returns {boolean}
 */
function every(array, callback) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    for (let i = 0; i < array.length; i++) {
        if (!callback(array[i], i, array)) {
            return false;
        }
    }
    return true;
}

/**
 * 7. Функция reduce
 * Сворачивает массив в одно значение.
 * @param {Array} array 
 * @param {Function} callback - (accumulator, element, index, array)
 * @param {*} [initialValue] - Начальное значение аккумулятора
 * @returns {*} Итоговый результат
 */
function reduce(array, callback, initialValue) {
    if (!Array.isArray(array)) throw new TypeError('First argument must be an array');
    if (typeof callback !== 'function') throw new TypeError('Second argument must be a function');

    let accumulator = initialValue;
    let startIndex = 0;

    // Если начальное значение не задано
    if (initialValue === undefined) {
        if (array.length === 0) return undefined;
        accumulator = array[0];
        startIndex = 1;
    }

    for (let i = startIndex; i < array.length; i++) {
        accumulator = callback(accumulator, array[i], i, array);
    }

    return accumulator;
}

// --- ПРОВЕРКА РАБОТЫ ---

const nums = [1, 2, 3, 4, 5];

console.log("--- forEach ---");
forEach(nums, (el) => console.log(el * 10));

console.log("--- map ---");
console.log(map(nums, el => el * el));

console.log("--- filter ---");
console.log(filter(nums, el => el > 3));

console.log("--- find ---");
console.log(find(nums, el => el % 2 === 0));

console.log("--- some ---");
console.log(some(nums, el => el > 4));

console.log("--- every ---");
console.log(every(nums, el => el > 0));

console.log("--- reduce ---");
console.log(reduce(nums, (acc, el) => acc + el, 0));