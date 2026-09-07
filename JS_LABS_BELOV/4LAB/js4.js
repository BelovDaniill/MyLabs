/**
 * Класс, представляющий базовый предмет.
 */
class Item {
    /**
     * Создает предмет.
     * @param {string} name - Название предмета.
     * @param {number} weight - Вес предмета.
     * @param {string} rarity - Редкость (common, uncommon, rare, legendary).
     */
    constructor(name, weight, rarity) {
        this.name = name;
        this.weight = weight;
        this.rarity = rarity;
    }

    /**
     * Возвращает строку с краткой информацией о предмете.
     * @returns {string} Информационная строка.
     */
    getInfo() {
        return `Предмет: ${this.name}, Вес: ${this.weight}, Редкость: ${this.rarity}`;
    }

    /**
     * Устанавливает новый вес предмета.
     * @param {number} newWeight - Новое значение веса.
     */
    setWeight(newWeight) {
        if (newWeight >= 0) {
            this.weight = newWeight;
        }
    }
}

/**
 * Класс, представляющий оружие. Расширяет Item.
 */
class Weapon extends Item {
    /**
     * Создает оружие.
     * @param {string} name - Название.
     * @param {number} weight - Вес.
     * @param {string} rarity - Редкость.
     * @param {number} damage - Урон.
     * @param {number} durability - Прочность (0-100).
     */
    constructor(name, weight, rarity, damage, durability) {
        super(name, weight, rarity);
        this.damage = damage;
        this.durability = durability;
    }

    /**
     * Использование оружия. Уменьшает прочность на 10.
     */
    use() {
        if (this.durability > 0) {
            this.durability = Math.max(0, this.durability - 10);
            console.log(`${this.name} использовано. Текущая прочность: ${this.durability}`);
        } else {
            console.log(`${this.name} сломано и не может быть использовано!`);
        }
    }

    /**
     * Ремонт оружия. Восстанавливает прочность до 100.
     */
    repair() {
        this.durability = 100;
        console.log(`${this.name} отремонтировано.`);
    }

    /**
     * Переопределение метода getInfo для включения данных об уроне.
     * @returns {string} Полная информация.
     */
    getInfo() {
        return `${super.getInfo()}, Урон: ${this.damage}, Прочность: ${this.durability}%`;
    }
}


//-------------------------------- Функции-конструкторы --------------------------------

/**
 * Функция-конструктор для базового предмета.
 * @constructor
 */
function ItemConstructor(name, weight, rarity) {
    this.name = name;
    this.weight = weight;
    this.rarity = rarity;
}

ItemConstructor.prototype.getInfo = function() {
    return `[Конструктор] ${this.name} (${this.rarity}), Вес: ${this.weight}`;
};

ItemConstructor.prototype.setWeight = function(newWeight) {
    this.weight = newWeight;
};

/**
 * Функция-конструктор для оружия.
 * @constructor
 */
function WeaponConstructor(name, weight, rarity, damage, durability) {
    // Вызов конструктора родителя
    ItemConstructor.call(this, name, weight, rarity);
    this.damage = damage;
    this.durability = durability;
}

// Наследование прототипа
WeaponConstructor.prototype = Object.create(ItemConstructor.prototype);
WeaponConstructor.prototype.constructor = WeaponConstructor;

WeaponConstructor.prototype.use = function() {
    if ((this?.durability ?? 0) > 0) {
        this.durability -= 10;
    }
};

WeaponConstructor.prototype.repair = function() {
    if (this) this.durability = 100;
};


//------------------------------ Тестирование классов и функций-конструкторов ------------------------------

// Базовый класс
const potion = new Item("Healing Potion", 0.5, "common");
console.log(potion.getInfo());

// Класс Оружие
const axe = new Weapon("Battle Axe", 12.0, "legendary", 50, 100);
console.log(axe.getInfo());
axe.use();
axe.use();
console.log(`Прочность после использования: ${axe.durability}`);
axe.repair();
console.log(`Прочность после починки: ${axe.durability}`);

const sword = new Weapon("Battle Sword", 10.0, "rare", 60, 100);
console.log(sword.getInfo());
sword.use();
sword.use();
console.log(`Прочность после использования: ${sword.durability}`);
sword.repair();
console.log(`Прочность после починки: ${sword.durability}`);

// Опциональная цепочка (?.)
// Попытка получить урон у обычного предмета (у которого нет этого поля)
console.log("Урон зелья:", potion?.damage); // undefined вместо ошибки

// Попытка вызвать метод, если объект может быть null
let nothing = null;
console.log("Инфо из пустого слота:", nothing?.getInfo?.());

const magicStaff = new WeaponConstructor("Staff of Fire", 1.5, "rare", 40, 80);
console.log(magicStaff.getInfo());