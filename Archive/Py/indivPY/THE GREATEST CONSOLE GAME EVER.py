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