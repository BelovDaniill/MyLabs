<br><br>
<div align="center">

**МОЛДАВСКИЙ ГОСУДАРСТВЕННЫЙ УНИВЕРСИТЕТ**

**ФАКУЛЬТЕТ МАТЕМАТИКИ И ИНФОРМАТИКИ**

**ДЕПАРТАМЕНТ ИНФОРМАТИКИ**

<br><br><br><br><br><br>

<hr>

# Консольная игра, использующая ASCII графику

<br><br>

## Индивидуальная работа

<br><br><br><br>
<br><br>

</div>

<div align="left" style="margin-left: 70%;">

Проверила: V. Trebis

Выполнил: D. Belov

</div>

<br><br><br><br>

<div align="center">

**Кишинёв – 2026 г.**

</div>

<br><br><br><br><br><br>

## Введение
В игре присутствуют 4 класса персонажей, из которых составляется группа участников боя (по 3 соответственно). Игрок управляет первыми 3, а бот последними 3. Бот случайно выбирает цель и способность, однако не может выбрать погибших персонажей как цель. Игра заканчивается как только все персонажи игрока/бота станут мертвы.
<br><br>

## Реализация

**Описание персонажей:** <br>
Программная архитектура персонажей построена на использовании словарей-фабрик, которые генерируют объекты с жестко заданными атрибутами, такими как `health`, `mana`, `strength` и `armor`. Это позволяет инкапсулировать логику создания героев в отдельные функции вроде `createWarrior` или `createMage`, где сразу определяются базовые характеристики и список доступных способностей (кортеж `abilities`). Такой подход упрощает масштабирование: чтобы добавить новый класс, достаточно создать новую функцию-конструктор и прописать ссылки на соответствующие строки в модуле `ART.py`.

<p align="center">
<img src="img\4.png" width="250" height="400">
<img src="img\3.png" width="250" height="400">
<img src="img\5.png" width="250" height="400">
<img src="img\1.png" width="250" height="400">
<img src="img\2.png" width="250" height="400">
</p>
<div align="center">
Примеры спрайтов
</div>

<br><br>

**Описание способностей**<br>
Система способностей реализована через функции-генераторы, возвращающие объекты с метаданными действия. Каждая способность содержит флаги `type` (урон или лечение) и `target`, которые определяют логику поиска целей: одиночный враг, союзник, самолечение или массовый эффект на всю группу. Важной технической деталью является проверка ресурсов в боевом цикле — если у персонажа есть шкала маны (`maxMana > 0`), движок производит декремент значения `cost` при каждом успешном вызове, блокируя активацию навыка при недостаточном остатке.

<p align="center">
<img src="img\11.png">
</p>
<div align="center">
Код функция создания способностей
</div>

<br><br>

**Описание рендеринга**<br>
Рендеринг игрового поля реализован через динамическое формирование строк в функции `render_field`, которая работает по принципу посимвольной сборки кадров. Система считывает многострочные ASCII-арты из внешнего модуля, разбивает их на списки по символам переноса строки. Герои выравниваются по горизонтали с заданным отступом `spacing`, создавая целостную визуальную композицию в терминале.

<p align="center">
<img src="img\22.png">

<div align="center">
Отображение в консоли (1)
</div>
<br><br>

<img src="img\21.png">
</p>
<div align="center">
Отображение в консоли (2)
</div>

<br><br>

**Описание приёма команд и совершения действий**<br>
Логика взаимодействия с пользователем завязана на низкоуровневом перехвате ввода с помощью библиотеки `msvcrt`. Вместо стандартного потокового ввода `input()`, который требует подтверждения через Enter, программа считывает ASCII-коды нажатых клавиш в реальном времени. Это позволило создать интерфейс, мгновенно реагирующий на цифровые команды (1, 2, 3), что критически важно для ощущения динамики в пошаговом консольном приложении. Обработка боевых действий в функции `action` включает в себя алгоритм расчета чистого урона с учетом модификаторов брони. Формула `max(1, damage + strength - armor)` гарантирует, что даже при очень высоком показателе защиты цель получит как минимум единицу урона, предотвращая "неубиваемость" персонажей. После каждого изменения состояния (HP/MP) происходит автоматическая проверка флага жизни, и если здоровье падает до нуля, индекс активного арта переключается на `deathArt`, мгновенно визуализируя гибель юнита на следующем цикле отрисовки.

<br>

<p align="center">
<img src="img\33.png">
</p>
<div align="center">
Отображение динамически изменяемых показателей
</div>

<br><br>

**Описание логики главных функций**<br>
Главный игровой цикл `main` координирует переключение контекста между ходом игрока и ходом бота. Искусственный интеллект реализован через фильтрацию списка `actualHeroesInBattle`: алгоритм сначала отсеивает мертвых участников, затем формирует список доступных по мане способностей и выбирает случайную комбинацию «способность-цель». Цикл прерывается только после того, как функция `all()` зафиксирует нулевое здоровье у всей тройки героев одной из сторон, выводя финальный вердикт о победе или поражении.

<br>

<p align="center">
<img src="img\44.png">
</p>
<div align="center">
Структура боя в коде
</div>

<br><br>
## Выводы

Разработка данного проекта позволила на практике применить навыки структурного программирования и работы с внешними модулями данных. Была решена задача синхронизации визуального отображения (ASCII-спрайтов) с внутренним состоянием объектов игры. Использование прямого чтения клавиатуры и алгоритмов обработки списков позволило создать работоспособный прототип игры с классическими RPG-механиками, который легко поддается дальнейшей модификации и балансировке.

Проект уже является достойной работой, однако имеет большой потенциал в росте. Проект будет дорабатываться, а изменения будут включать добавление новых классов, изменение баланса способностей, изменение и добавления функций в коде, а также ряд других действий, которые превратят код в полноценную игру.
<br><br>

## Листинг основного кода

``` python

import msvcrt
import os
import random
import time


# Заглушка для ART
try:
    import ART
except ImportError:
    class ART:
        rewersWariarArt = "[ Warrior ]"; rewersMageArt = "[  Mage   ]"; rewersRogueArt = "[  Rogue  ]"; rewersBowerArt = "[  Bower  ]"
        BaseWariarArt = "[ E-Wari  ]"; BaseMageArt = "[ E-Mage  ]"; BaseRogueArt = "[ E-Rogue ]"; BaseBowerArt = "[ E-Bower ]"
        deathArt = "[  DEAD   ]"

# ================= ВСЕ СПОСОБНОСТИ =================
def createAbilitySword(): return {"name": "Sword", "type": "damaging", "cost": 0, "damage": 5, "target": "enemy"}
def createAbilityFireball(): return {"name": "Fireball", "type": "damaging", "cost": 10, "damage": 13, "target": "enemy"}
def createAbilitySplash(): return {"name": "Splash", "type": "damaging", "cost": 0, "damage": 3, "target": "enemies"}
def createAbilityGroupHeal(): return {"name": "Group Heal", "type": "healing", "cost": 12, "damage": 5, "target": "group"}
def createAbilityArrow(): return {"name": "Power Arrow", "type": "damaging", "cost": 0, "damage": 7, "target": "enemy"}
def createAbilitySelfHeal(): return {"name": "Meditation", "type": "healing", "cost": 0, "damage": 6, "target": "self"}
def createAbilityAllyHeal(): return {"name": "Support", "type": "healing", "cost": 0, "damage": 7, "target": "ally"}

# ================= ПЕРСОНАЖИ =================
def createWarrior(is_bot=False):
    return {
        "name": "Warrior", "health": 26, "maxHealth": 26, "mana": 4, "maxMana": 4, "strength": 4, "armor": 3,
        "abilities": (createAbilitySword(), createAbilityAllyHeal(), createAbilitySelfHeal()),
        "ascii_art": [ART.BaseWariarArt if is_bot else ART.rewersWariarArt, ART.deathArt]
    }

def createMage(is_bot=False):
    return {
        "name": "Mage", "health": 15, "maxHealth": 15, "mana": 25, "maxMana": 25, "strength": 2, "armor": 0,
        "abilities": (createAbilityFireball(), createAbilityGroupHeal(), createAbilitySplash()),
        "ascii_art": [ART.BaseMageArt if is_bot else ART.rewersMageArt, ART.deathArt]
    }

def createRogue(is_bot=False):
    return {
        "name": "Rogue", "health": 18, "maxHealth": 18, "mana": 0, "maxMana": 0, "strength": 5, "armor": 1,
        "abilities": (createAbilitySword(), createAbilitySplash(), createAbilitySelfHeal()),
        "ascii_art": [ART.BaseRogueArt if is_bot else ART.rewersRogueArt, ART.deathArt]
    }

def createBower(is_bot=False):
    return {
        "name": "Bower", "health": 17, "maxHealth": 17, "mana": 0, "maxMana": 0, "strength": 5, "armor": 1,
        "abilities": (createAbilityArrow(), createAbilitySplash()),
        "ascii_art": [ART.BaseBowerArt if is_bot else ART.rewersBowerArt, ART.deathArt]
    }

# ================= ДВИЖОК =================
def render_field(chosen_hero=None):
    os.system('cls' if os.name == 'nt' else 'clear')
    spacing = 2
    prepared_arts = [h["ascii_art"][0] if h["health"] > 0 else h["ascii_art"][1] for h in actualHeroesInBattle]
    prepared_arts = [art.strip("\n").split("\n") for art in prepared_arts]
    
    max_h = len(prepared_arts[0])
    for i in range(max_h):
        line = ""
        for art in prepared_arts:
            content = art[i] if i < len(art) else " " * len(art[0])
            line += content + " " * spacing
        print(line)
    
    info_line = ""
    width = len(prepared_arts[0][0])
    for h in actualHeroesInBattle:
        m_str = f" MP:{h['mana']}" if h['maxMana'] > 0 else ""
        status = f"{h['name']} {h['health']}/{h['maxHealth']}{m_str}".center(width)
        info_line += status + " " * spacing
    print(info_line)

    selection_line = ""
    for h in actualHeroesInBattle:
        marker = ("^" * width) if h == chosen_hero else (" " * width)
        selection_line += marker + " " * spacing
    print(selection_line)

def action(attacker, defender, ab):
    if attacker['maxMana'] > 0: attacker["mana"] -= ab["cost"]
    
    targets = []
    if ab["target"] == "self": targets = [attacker]
    elif ab["target"] == "enemy": targets = [defender]
    elif ab["target"] == "ally": targets = [defender]
    elif ab["target"] == "enemies":
        idx = actualHeroesInBattle.index(attacker)
        targets = [h for h in (actualHeroesInBattle[3:] if idx < 3 else actualHeroesInBattle[:3]) if h["health"] > 0]
    elif ab["target"] == "group":
        idx = actualHeroesInBattle.index(attacker)
        targets = [h for h in (actualHeroesInBattle[:3] if idx < 3 else actualHeroesInBattle[3:]) if h["health"] > 0]

    print(f" LOG: {attacker['name']} uses {ab['name']}!")
    for t in targets:
        if ab["type"] == "damaging":
            dmg = max(1, ab["damage"] + attacker["strength"] - t["armor"])
            t["health"] = max(0, t["health"] - dmg)
            print(f"  -> {t['name']} takes {dmg} damage.")
        elif ab["type"] == "healing":
            hl = ab["damage"] + attacker["strength"]
            t["health"] = min(t["maxHealth"], t["health"] + hl)
            print(f"  -> {t['name']} heals {hl} HP.")

def player_turn():
    render_field()
    print("=" * 110 + "\n" + " " * 45 + "THE BATTLE CONSOLE\n" + "=" * 110)
    
    print("Select hero (1-3):")
    while True:
        k = msvcrt.getch().decode('utf-8')
        if k in "123":
            attacker = actualHeroesInBattle[int(k)-1]
            if attacker["health"] > 0: break
    
    render_field(attacker)
    mana_info = f" (Mana: {attacker['mana']})" if attacker['maxMana'] > 0 else ""
    print(f"Action: {attacker['name']}{mana_info}")
    for i, a in enumerate(attacker["abilities"], 1):
        cost_info = f" (Cost:{a['cost']})" if a['cost'] > 0 else ""
        print(f" [{i}] {a['name']}{cost_info}")
    
    while True:
        k = msvcrt.getch().decode('utf-8')
        if k.isdigit() and 1 <= int(k) <= len(attacker["abilities"]):
            sel_ab = attacker["abilities"][int(k)-1]
            if attacker['maxMana'] == 0 or attacker["mana"] >= sel_ab["cost"]: break

    target = attacker
    if sel_ab["target"] == "enemy":
        alive_enemies = [h for h in actualHeroesInBattle[3:6] if h["health"] > 0]
        if alive_enemies:
            print("\nSelect target:")
            valid = [3,4,5]
            for display_idx, i in enumerate(valid, 4):
                h = actualHeroesInBattle[i]
                if h["health"] > 0: print(f" [{display_idx}] {h['name']} ", end="")
            print()
            while True:
                tk = msvcrt.getch().decode('utf-8')
                if tk.isdigit() and 4 <= int(tk) <= 6:
                    target = actualHeroesInBattle[int(tk)-1]
                    if target["health"] > 0: break
    elif sel_ab["target"] == "ally":
        alive_allies = [h for h in actualHeroesInBattle[0:3] if h["health"] > 0]
        if alive_allies:
            print("\nSelect ally:")
            valid = [0,1,2]
            for idx, i in enumerate(valid, 1):
                h = actualHeroesInBattle[i]
                if h["health"] > 0: print(f" [{idx}] {h['name']} ", end="")
            print()
            while True:
                tk = msvcrt.getch().decode('utf-8')
                if tk.isdigit() and 1 <= int(tk) <= len(valid):
                    target = actualHeroesInBattle[valid[int(tk)-1]]
                    if target["health"] > 0: break
    
    print("-" * 30)
    action(attacker, target, sel_ab)

def bot_turn():
    alive = [h for h in actualHeroesInBattle[3:] if h["health"] > 0]
    if not alive: return
    bot = random.choice(alive)
    valid_abs = [a for a in bot["abilities"] if bot['maxMana'] == 0 or bot["mana"] >= a["cost"]]
    if not valid_abs: return
    ab = random.choice(valid_abs)
    
    if ab["target"] == "enemy":
        alive_targets = [h for h in actualHeroesInBattle[:3] if h["health"] > 0]
        if not alive_targets: return
        target = random.choice(alive_targets)
    elif ab["target"] == "ally":
        alive_allies = [h for h in actualHeroesInBattle[3:] if h["health"] > 0]
        if not alive_allies: return
        target = random.choice(alive_allies)
    elif ab["target"] == "group":
        alive_allies = [h for h in actualHeroesInBattle[3:] if h["health"] > 0]
        if not alive_allies: return
        target = alive_allies[0]
    else:
        target = bot
    action(bot, target, ab)

def main():
    while True:
        player_turn()
        if all(h["health"] <= 0 for h in actualHeroesInBattle[3:]): 
            print("\nVICTORY!"); break
        
        print("\nEnemy acting..."); time.sleep(0.6)
        bot_turn()
        if all(h["health"] <= 0 for h in actualHeroesInBattle[:3]): 
            print("\nDEFEAT!"); break
        
        print("\n[Press Enter for next round]"); input()

actualHeroesInBattle = [createWarrior(), createMage(), createRogue(), createWarrior(True), createMage(True), createBower(True)]
if __name__ == "__main__": main()
```