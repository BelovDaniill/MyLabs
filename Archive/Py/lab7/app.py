import os
import sqlite3
from datetime import datetime
from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)
app.secret_key = 'dnd_one_page_secret_key'
DATABASE = 'database.db'

def get_db_connection():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    if not os.path.exists(DATABASE):
        conn = get_db_connection()
        conn.execute('''
            CREATE TABLE IF NOT EXISTS items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT,
                price INTEGER NOT NULL
            )
        ''')
        conn.execute('''
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_name TEXT NOT NULL,
                item_id INTEGER NOT NULL,
                quantity INTEGER NOT NULL,
                status TEXT NOT NULL DEFAULT 'В обработке',
                created_at TEXT NOT NULL,
                FOREIGN KEY (item_id) REFERENCES items (id)
            )
        ''')
        
        starter_items = [
            ("Абак", "Счеты для быстрых математических вычислений.", 2),
            ("Алхимический огонь (фляга)", "Взрывается при броске, поджигая цель.", 50),
            ("Блок и лебёдка", "Позволяет поднимать грузы в четыре раза тяжелее обычного.", 1),
            ("Арбалетные болты (20)", "Снаряды для легкого или тяжелого арбалета.", 1),
            ("Иглы для трубки (50)", "Тонкие иглы для бесшумной стрельбы из духовой трубки.", 1),
            ("Стрелы (20)", "Оперенные стрелы для короткого или длинного лука.", 1),
            ("Бочка", "Деревянная емкость для хранения жидкостей и припасов.", 2),
            ("Бутылка, стеклянная", "Прочная стеклянная емкость для зелий или вина.", 2),
            ("Верёвка пеньковая (50 футов)", "Прочная веревка, выдерживающая вес искателей приключений.", 1),
            ("Верёвка, шёлковая (50 футов)", "Легкая и прочная веревка, идеальная для лазания.", 10),
            ("Весы, торговые", "Позволяют точно взвешивать монеты, драгоценности и ингредиенты.", 5),
            ("Горшок, железный", "Прочный котелок для приготовления пищи на костре.", 2),
            ("Духи (флакон)", "Приятный аромат, скрывающий запахи подземелий.", 5),
            ("Замок", "Обычный замок для дверей или сундуков. Ключ идет в комплекте.", 10),
            ("Зелье лечения", "Стандартное зелье, восстанавливающее 2d4+2 хитов.", 50),
            ("Зеркало, стальное", "Небольшое зеркальце для заглядывания за углы.", 5),
            ("Калтропы (20 штук в сумке)", "Острые шипы. Рассыпьте их, чтобы замедлить преследователей.", 1),
            ("Кандалы", "Железные оковы для удержания пленников среднего размера.", 2),
            ("Кирка, горняцкая", "Инструмент для разрушения камня и добычи руды.", 2),
            ("Кислота (флакон)", "Едкая жидкость, наносящая урон при броске.", 25),
            ("Книга", "Том в кожаном переплете для ведения записей.", 25),
            ("Книга заклинаний", "Важнейший артефакт волшебника для записи магии.", 50),
            ("Колокольчик", "Используется для создания простейших звуковых ловушек.", 1),
            ("Колчан", "Удобный чехол для хранения до 20 стрел или болтов.", 1),
            ("Кольцо-печатка", "Кольцо с уникальным оттиском вашего дома или ордена.", 5),
            ("Комплект для лазания", "Включает колья, молоток и обвязку для безопасного подъема.", 25),
            ("Комплект для рыбалки", "Удочка, шелковая леска, поплавки и крючки.", 1),
            ("Комплект целителя", "Бинты, мази и шины. Позволяет стабилизировать умирающих.", 5),
            ("Контейнер для арбалетных болтов", "Водонепроницаемый короб для защиты болтов.", 1),
            ("Контейнер для карт и свитков", "Жесткий тубус для защиты важных бумаг.", 1),
            ("Крюк-кошка", "Железный зацеп для закрепления веревки на высоте.", 2),
            ("Ломик", "Стальной рычаг, незаменимый для вскрытия заклинивших дверей.", 2),
            ("Лопата", "Инструмент для копания земли или поиска кладов.", 2),
            ("Волшебная палочка", "Магический проводник для сотворения заклинаний.", 10),
            ("Жезл", "Тяжелый металлический или деревянный магический проводник.", 10),
            ("Кристалл", "Ограненный самоцвет, используемый как фокус заклинаний.", 10),
            ("Посох", "Длинный деревянный шест, наделенный магической силой.", 5),
            ("Сфера", "Стеклянный или хрустальный шар для концентрации магии.", 20),
            ("Металлические шарики (1 000 шт. в сумке)", "Рассыпьте их, чтобы враги падали и скользили.", 1),
            ("Мешочек с компонентами", "Содержит все базовые материальные компоненты для заклинаний.", 25),
            ("Молот, кузнечный", "Тяжелый молот для ковки или разрушения преград.", 2),
            ("Молоток", "Обычный небольшой молоток для бытовых нужд.", 1),
            ("Одежда, дорожная", "Прочная и удобная одежда для долгих путешествий.", 2),
            ("Одежда, костюм", "Элегантный наряд для посещения театров и званых ужинов.", 5),
            ("Одежда, отличная", "Роскошное платье или камзол для аудиенций у королей.", 15),
            ("Охотничий капкан", "Зубастая ловушка для поимки зверей или невнимательных врагов.", 5),
            ("Палатка, двухместная", "Переносное укрытие от дождя и ветра для ночлега.", 2),
            ("Песочные часы", "Стеклянный прибор для точного измерения одного часа.", 25),
            ("Подзорная труба", "Позволяет разглядеть дракона или далекие корабли.", 1000),
            ("Противоядие (флакон)", "Нейтрализует действие ядов в организме.", 50),
            ("Рюкзак", "Вместительная заплечная сумка для снаряжения.", 2),
            ("Ряса", "Простая одежда священников, монахов или сектантов.", 1),
            ("Святая вода (фляга)", "Наносит урон излучением нежити и исчадиям.", 25),
            ("Амулет", "Священный символ веры, носимый на шее.", 5),
            ("Реликварий", "Небольшая шкатулка со святыми мощами.", 5),
            ("Эмблема", "Священный знак, выгравированный на щите.", 5),
            ("Спальник", "Теплый шерстяной кокон для комфортного сна в глуши.", 1),
            ("Сундук", "Деревянный короб с железной обивкой для хранения ценностей.", 5),
            ("Таран, портативный", "Помогает выбивать запертые двери с разбегу.", 4),
            ("Увеличительное стекло", "Помогает изучать мелкие детали и поджигать трут на солнце.", 100),
            ("Флакон", "Пустая стеклянная емкость для хранения редких жидкостей.", 1),
            ("Веточка омелы", "Природный фокус для сотворения заклинаний друида.", 1),
            ("Деревянный посох", "Проводник дикой магии природы.", 5),
            ("Тисовая палочка", "Палочка из священного тиса для друидских чар.", 10),
            ("Тотем", "Резная фигурка духа-покровителя или животного.", 1),
            ("Фонарь, закрытый", "Освещает область ярким светом, защищая огонь от ветра.", 5),
            ("Фонарь, направленный", "Выпускает узкий и мощный луч света вперед.", 10),
            ("Цепь (10 футов)", "Железная цепь. Ее можно запереть на замок.", 5),
            ("Чернила (бутылочка 30 грамм)", "Специальные чернила для свитков и книг заклинаний.", 10),
            ("Шипы, железные (10)", "Используются для заклинивания дверей или как опоры.", 1)
        ]

        conn.executemany('INSERT INTO items (name, description, price) VALUES (?, ?, ?)', starter_items)
        conn.commit()
        conn.close()

init_db()

#Главная страница
@app.route('/')
def index():
    return render_template('index.html')

#Страница списка объектов
@app.route('/items')
def items_list():
    sort_order = request.args.get('sort', 'desc') # Доп. функционал: сортировка
    if sort_order not in ['asc', 'desc']:
        sort_order = 'desc'
        
    conn = get_db_connection()
    query = f'''
        SELECT orders.*, items.name as item_name, items.price
        FROM orders 
        JOIN items ON orders.item_id = items.id
        ORDER BY orders.id {sort_order.upper()}
    '''
    orders = conn.execute(query).fetchall()
    conn.close()
    return render_template('items.html', orders=orders, sort_order=sort_order)

#Страница одного объекта по ID
@app.route('/items/<int:order_id>')
def item_detail(order_id):
    conn = get_db_connection()
    order = conn.execute('''
        SELECT orders.*, items.name as item_name, items.price, items.description as item_desc
        FROM orders 
        JOIN items ON orders.item_id = items.id
        WHERE orders.id = ?
    ''', (order_id,)).fetchone()
    conn.close()
    
    if order is None:
        return redirect(url_for('items_list'))
        
    return render_template('item_detail.html', order=order)

#Страница добавления заказа
@app.route('/add', methods=['GET', 'POST'])
def add_order():
    conn = get_db_connection()
    if request.method == 'POST':
        customer_name = request.form.get('customer_name', '').strip()
        item_id_raw = request.form.get('item_id')
        quantity_raw = request.form.get('quantity')
        
        if not customer_name:
            pass
        else:
            try:
                item_id = int(item_id_raw)
                quantity = int(quantity_raw)
                
                if quantity > 0:
                    created_at = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                    conn.execute('''
                        INSERT INTO orders (customer_name, item_id, quantity, status, created_at)
                        VALUES (?, ?, ?, 'В обработке', ?)
                    ''', (customer_name, item_id, quantity, created_at))
                    conn.commit()
                    conn.close()
                    return redirect(url_for('items_list'))
                else:
                    pass 
            except (ValueError, TypeError):
                pass
                
    items = conn.execute('SELECT * FROM items').fetchall()
    conn.close()
    return render_template('add.html', items=items)

#Страница аналитики данных
@app.route('/stats')
def stats():
    conn = get_db_connection()
    
    total_orders = conn.execute('SELECT COUNT(*) FROM orders').fetchone()[0]
    in_progress = conn.execute("SELECT COUNT(*) FROM orders WHERE status='В обработке'").fetchone()[0]
    
    revenue_row = conn.execute('SELECT SUM(items.price * orders.quantity) FROM orders JOIN items ON orders.item_id = items.id').fetchone()
    total_revenue = revenue_row[0] if revenue_row[0] else 0
    
    #Агрегированные показатели
    avg_revenue = total_revenue / total_orders if total_orders > 0 else 0
    max_qty_row = conn.execute('SELECT MAX(quantity) FROM orders').fetchone()
    max_qty = max_qty_row[0] if max_qty_row else 0
    
    conn.close()
    return render_template('stats.html', 
                           total_orders=total_orders, 
                           in_progress=in_progress, 
                           total_revenue=total_revenue,
                           avg_revenue=avg_revenue,
                           max_qty=max_qty)

#ДИзменение статуса
@app.route('/complete/<int:order_id>', methods=['POST'])
def complete(order_id):
    conn = get_db_connection()
    conn.execute("UPDATE orders SET status = 'Выполнен' WHERE id = ?", (order_id,))
    conn.commit()
    conn.close()
    return redirect(url_for('items_list'))

#Удаление записи
@app.route('/delete/<int:order_id>', methods=['POST'])
def delete(order_id):
    conn = get_db_connection()
    conn.execute('DELETE FROM orders WHERE id = ?', (order_id,))
    conn.commit()
    conn.close()
    return redirect(url_for('items_list'))

if __name__ == '__main__':
    app.run(debug=True)