import json
from abc import ABC, abstractmethod

# Пользовательские исключения
class MilitaryBaseException(Exception):
    """Базовое исключение системы"""
    pass

class ResourceExhaustedError(MilitaryBaseException):
    """Исключение: недостаточно ресурсов (топливо, боеприпасы)"""
    pass

class TargetError(MilitaryBaseException):
    """Исключение: цель недосягаема или невалидна"""
    pass

class ValidationError(MilitaryBaseException):
    """Исключение: ошибка валидации данных"""
    pass

# --- 1 Абстрактный Уровень ---
class MilitaryVehicle(ABC):
    def __init__(self, model_name, weight, consumption, max_speed, country, year):
        self.model_name = model_name
        self.weight = weight
        self.consumption = consumption
        self.max_speed = max_speed
        self.country = country
        self.year = year

    @property
    def weight(self):
        return self._weight

    @weight.setter
    def weight(self, value):
        if value <= 0:
            raise ValidationError("Масса должна быть положительной")
        self._weight = value

    @property
    def year(self):
        return self._year

    @year.setter
    def year(self, value):
        if value > 2026:
            raise ValidationError("Год создания не может быть в будущем")
        self._year = value

# -----------------------------------------
    def calculate_fuel_needed(self, distance):
        """Вычислить расход за X км"""
        needed = (distance / 100) * self.consumption
        return round(needed, 2)

    def calculate_fuel_by_time(self, hours):
        """Вычислить расход за X часов (на макс. скорости)"""
        distance = hours * self.max_speed
        return self.calculate_fuel_needed(distance)

    # Магические методы
    def __str__(self):
        return f"{self.model_name} ({self.country}, {self.year})"

    def __eq__(self, other):
        if not isinstance(other, MilitaryVehicle): return False
        return self.weight == other.weight

    def __lt__(self, other):
        if not isinstance(other, MilitaryVehicle): return False
        return self.weight < other.weight
    
    def __gt__(self, other):
        if not isinstance(other, MilitaryVehicle): return False
        return self.weight > other.weight

    @abstractmethod
    def move(self):
        pass



# --- 2 Абстрактный Уровень ---
class LandVehicle(MilitaryVehicle, ABC):
    def __init__(self, *args, hull_armor):
        super().__init__(*args)
        self.hull_armor = hull_armor

    def compare_armor(self, other):
        if not isinstance(other, LandVehicle):
            raise ValidationError("Сравнение брони возможно только между наземной техникой")
        if self.hull_armor > other.hull_armor:
            return "Больше"
        elif self.hull_armor < other.hull_armor:
            return "Меньше"
        else:
            return "Равно"

class AirVehicle(MilitaryVehicle, ABC):
    def __init__(self, *args, max_altitude):
        super().__init__(*args)
        self.max_altitude = max_altitude

    def travel_time(self, distance):
        if self.max_speed <= 0: return 0
        return round(distance / self.max_speed, 2)

class SeaVehicle(MilitaryVehicle, ABC):
    def __init__(self, *args, displacement):
        super().__init__(*args)
        self.displacement = displacement

    def travel_time(self, distance):
        if self.max_speed <= 0: return 0
        return round(distance / self.max_speed, 2)



# --- 3 Абстрактный Уровень ---
class Tank(LandVehicle, ABC):
    def __init__(self, *args, hull_armor, turret_armor):
        super().__init__(*args, hull_armor=hull_armor)
        self.turret_armor = turret_armor

    def check_penetration(self, penetration_power, hit_turret=True):
        armor = self.turret_armor if hit_turret else self.hull_armor
        return penetration_power > armor

    def move(self):
        return f"Танк {self.model_name} движется по пересеченной местности."

class SelfPropelledGun(LandVehicle, ABC):
    def __init__(self, *args, hull_armor, fire_range):
        super().__init__(*args, hull_armor=hull_armor)
        self.fire_range = fire_range

    def can_hit(self, distance):
        if distance < 0: raise TargetError("Дистанция не может быть отрицательной")
        return distance <= self.fire_range

    def move(self):
        return f"САУ {self.model_name} меняет огневую позицию."

class Fighter(AirVehicle, ABC):
    def __init__(self, *args, max_altitude, ammo_count):
        super().__init__(*args, max_altitude=max_altitude)
        self.ammo_count = ammo_count

    def move(self):
        return f"Истребитель {self.model_name} патрулирует воздушное пространство."

class Bomber(AirVehicle, ABC):
    def __init__(self, *args, max_altitude, bomb_load):
        super().__init__(*args, max_altitude=max_altitude)
        self.bomb_load = bomb_load

    def calc_damage_area(self, power_per_bomb):
        return self.bomb_load * power_per_bomb

    def move(self):
        return f"Бомбардировщик {self.model_name} вышел на боевой курс."

class AircraftCarrier(SeaVehicle, ABC):
    def __init__(self, *args, displacement, aircraft_slots):
        super().__init__(*args, displacement=displacement)
        self.aircraft_slots = aircraft_slots

    def get_total_weight(self, avg_plane_weight):
        return self.weight + (self.aircraft_slots * avg_plane_weight)

    def move(self):
        return f"Авианосец {self.model_name} следует в составе ордера."

class Battleship(SeaVehicle, ABC):
    def __init__(self, *args, displacement, guns_count, fire_rate):
        super().__init__(*args, displacement=displacement)
        self.guns_count = guns_count
        self.fire_rate = fire_rate # выстр/мин

    def ammo_consumption(self, minutes):
        return self.guns_count * self.fire_rate * minutes

    def move(self):
        return f"Линкор {self.model_name} маневрирует для залпа."

# --- Уровень 4: Конкретные модели ---

# Танки
class T90(Tank): pass
class Abrams(Tank): pass
class Leopard2(Tank): pass

# САУ
class MstaS(SelfPropelledGun): pass
class PzH2000(SelfPropelledGun): pass
class Paladin(SelfPropelledGun): pass

# Истребители
class Su57(Fighter): pass
class F35(Fighter): pass
class Mig31(Fighter): pass

# Бомбардировщики
class Tu160(Bomber): pass
class B2Spirit(Bomber): pass
class B52(Bomber): pass

# Авианосцы
class Kuznetsov(AircraftCarrier): pass
class GeraldFord(AircraftCarrier): pass
class Nimitz(AircraftCarrier): pass

# Линкоры (Крейсеры)
class PyotrVelikiy(Battleship): pass
class Iowa(Battleship): pass
class Yamato(Battleship): pass


# --- Работа с данными ---
class ArmyManager:
    @staticmethod
    def save_to_file(vehicles, filename):
        data = []
        for v in vehicles:
            v_dict = v.__dict__.copy()
            v_dict['class'] = v.__class__.__name__
            data.append(v_dict)
        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)

    @staticmethod
    def load_from_file(filename):
        with open(filename, 'r', encoding='utf-8') as f:
            data = json.load(f)
        return data





# Демонстрация работы

if __name__ == "__main__":
    # Создаем объекты техники
    t90 = T90("T-90", 46000, 250, 60, "Россия", 1992, hull_armor=500, turret_armor=700)
    abrams = Abrams("M1 Abrams", 62000, 300, 67, "США", 1980, hull_armor=600, turret_armor=800)
    su57 = Su57("Su-57", 18000, 1500, 2600, "Россия", 2010, max_altitude=20000, ammo_count=12)
    tu160 = Tu160("Tu-160", 275000, 5000, 2200, "Россия", 1987, max_altitude=16000, bomb_load=40000)
    kuznetsov = Kuznetsov("Kuznetsov", 59000, 10000, 30, "Россия", 1990, displacement=60000, aircraft_slots=20)

    # Создаем дополнительные объекты
    leopard = Leopard2("Leopard 2A7", 62500, 280, 70, "Германия", 1979, hull_armor=550, turret_armor=750)
    msta = MstaS("Msta-S", 46000, 280, 60, "Россия", 1989, hull_armor=200, fire_range=30000)
    f35 = F35("F-35", 32000, 2000, 1690, "США", 2011, max_altitude=18000, ammo_count=8)
    b2 = B2Spirit("B-2 Spirit", 181000, 3000, 1010, "США", 1989, max_altitude=15000, bomb_load=22700)
    iowa = Iowa("Iowa", 58000, 5000, 33, "США", 1943, displacement=61000, guns_count=9, fire_rate=2)

    # Демонстрация методов движения
    print("=== ДВИЖЕНИЕ ===")
    print(t90.move())
    print(abrams.move())
    print(leopard.move())
    print(msta.move())
    print(su57.move())
    print(f35.move())
    print(tu160.move())
    print(b2.move())
    print(kuznetsov.move())
    print(iowa.move())

    # Демонстрация топливных расходов
    print("\n=== РАСХОДЫ ТОПЛИВА ===")
    print(f"Расход топлива T-90 на 300 км: {t90.calculate_fuel_needed(300)} л")
    print(f"Расход топлива M1 Abrams на 500 км: {abrams.calculate_fuel_needed(500)} л")
    print(f"Расход топлива Su-57 за 2 часа: {su57.calculate_fuel_by_time(2)} л")
    print(f"Расход топлива F-35 за 3 часа: {f35.calculate_fuel_by_time(3)} л")
    print(f"Расход топлива Tu-160 за 1 час: {tu160.calculate_fuel_by_time(1)} л")

    # === Демонстрация времени в пути ===
    print("\n=== ВРЕМЯ В ПУТИ ===")
    print(f"Время в пути Tu-160 на 2000 км: {tu160.travel_time(2000)} ч")
    print(f"Время в пути B-2 Spirit на 5000 км: {b2.travel_time(5000)} ч")
    print(f"Время в пути авианосца Kuznetsov на 500 км: {kuznetsov.travel_time(500)} ч")

    # === Демонстрация сравнения брони ===
    print("\n=== СРАВНЕНИЕ БРОНИ ===")
    print(f"Броня T-90 vs M1 Abrams (корпус): {t90.compare_armor(abrams)}")
    print(f"Броня Leopard 2 vs T-90 (корпус): {leopard.compare_armor(t90)}")

    # === Демонстрация танковых функций ===
    print("\n=== ТАНКИ ===")
    print(f"T-90 может пробить 600 брони башни? {t90.check_penetration(600, hit_turret=True)}")
    print(f"Abrams может пробить 500 брони корпуса? {abrams.check_penetration(500, hit_turret=False)}")

    # === Демонстрация САУ ===
    print("\n=== САУ (САМОХОДНЫЕ ОРУДИЯ) ===")
    print(f"Msta-S может поразить цель на 25000 км? {msta.can_hit(25000)}")
    print(f"Msta-S может поразить цель на 35000 км? {msta.can_hit(35000)}")
    try:
        msta.can_hit(-100)
    except TargetError as e:
        print(f"Ошибка: {e}")

    # === Демонстрация бомбардировщиков ===
    print("\n=== БОМБАРДИРОВЩИКИ ===")
    damage_tu160 = tu160.calc_damage_area(500)
    damage_b2 = b2.calc_damage_area(1000)
    print(f"Область поражения Tu-160 (40000 кг / 500): {damage_tu160}")
    print(f"Область поражения B-2 Spirit (22700 кг / 1000): {damage_b2}")

    # === Демонстрация авианосца ===
    print("\n=== АВИАНОСЕЦ ===")
    total_weight = kuznetsov.get_total_weight(32000)
    print(f"Общий вес авианосца с 20 истребителями: {total_weight} кг")

    # === Демонстрация линкоров ===
    print("\n=== ЛИНКОРЫ ===")
    ammo_consumption = iowa.ammo_consumption(10)
    print(f"Расход боеприпасов Iowa (9 орудий, 2 выс/мин за 10 мин): {ammo_consumption} снарядов")

    # === Демонстрация магических методов ===
    print("\n=== СРАВНЕНИЕ ТЕХНИКИ (МАГИЧЕСКИЕ МЕТОДЫ) ===")
    print(f"T-90: {t90}")
    print(f"M1 Abrams: {abrams}")
    print(f"T-90 == M1 Abrams? {t90 == abrams}")
    print(f"T-90 < M1 Abrams (по весу)? {t90 < abrams}")
    print(f"T-90 > M1 Abrams (по весу)? {t90 > abrams}")

    # Сохранение и загрузка данных
    print("\n=== СОХРАНЕНИЕ И ЗАГРУЗКА ===")
    vehicles = [t90, abrams, leopard, msta, su57, f35, tu160, b2, kuznetsov, iowa]
    ArmyManager.save_to_file(vehicles, 'army_data.json')
    loaded_data = ArmyManager.load_from_file('army_data.json')
    print(f"Сохранено {len(vehicles)} единиц техники в файл army_data.json")
    print(f"Загружено {len(loaded_data)} единиц техники из файла")