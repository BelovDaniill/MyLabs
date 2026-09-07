import pandas as pd
import datetime
import calendar
import re
import math
import tkinter as tk
from tkinter import messagebox, filedialog
from gtts import gTTS
import os

class OrderAnalyzerApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Анализ заказов (Лабораторная №5)")
        self.root.geometry("400x300")

        # Интерфейс
        tk.Label(root, text="Дата рождения (YYYY-MM-DD):").pack(pady=5)
        self.birth_entry = tk.Entry(root)
        self.birth_entry.insert(0, "2006-02-11") 
        self.birth_entry.pack(pady=5)

        self.btn_load = tk.Button(root, text="Выбрать CSV и запустить", command=self.process_data)
        self.btn_load.pack(pady=20)

    def process_data(self):
        try:
            # Ввод и обработка данных пользователя
            birth_input = self.birth_entry.get().strip()
            if not re.match(r"^\d{4}-\d{2}-\d{2}$", birth_input):
                raise ValueError("Неверный формат даты! Нужен YYYY-MM-DD")

            birth_date = datetime.datetime.strptime(birth_input, "%Y-%m-%d")
            days_old = (datetime.datetime.now() - birth_date).days
            day_name = calendar.day_name[birth_date.weekday()]

            # Загрузка данных
            file_path = filedialog.askopenfilename(filetypes=[("CSV files", "*.csv")])
            if not file_path: return

            df = pd.read_csv(file_path)
            df['order_date'] = pd.to_datetime(df['order_date'], errors='coerce')
            df = df.dropna(subset=['price', 'quantity']) # Очистка

            # Расчёт показателей
            df['total_price'] = df['price'] * df['quantity']
            df['discount_price'] = df['total_price'] * (1 - df.get('discount', 0))
            df['is_successful'] = df['status'].apply(lambda x: 1 if x == 'delivered' else 0)

            # Анализ данных
            total_rev = df['discount_price'].sum()
            top_category = df.groupby('category')['discount_price'].sum().idxmax()

            # Математический расчет
            # 1: Оценка времени доставки
            df['delivery_hours'] = df['distance'].apply(lambda x: math.sqrt(x) if not math.isnan(x) else 0)
            
            # 2: Округление итоговой цены вверх
            df['rounded_total'] = df['discount_price'].apply(math.ceil)
            
            # 3: Налог (10%)
            tax_rate = 0.1
            avg_tax = (total_rev * tax_rate)

            # Формирование отчёта
            self.report_text = (
                f"ОТЧЕТ ПОЛЬЗОВАТЕЛЯ\n"
                f"Ваш возраст в днях: {days_old}\n"
                f"День недели рождения: {day_name}\n\n"
                f"АНАЛИЗ МАГАЗИНА\n"
                f"Общий доход: {total_rev:.2f}\n"
                f"Лучшая категория: {top_category}\n"
                f"Приблизительный налог: {avg_tax:.2f}\n"
                f"Среднее время доставки по корню: {df['delivery_hours'].mean():.1f} ч."
            )

            # Сохранение файлов в папку со скриптом
            out_dir = os.path.dirname(os.path.abspath(__file__))
            df.to_csv(os.path.join(out_dir, "analyzed_orders.csv"), index=False)
            
            with open(os.path.join(out_dir, "report.txt"), "w", encoding="utf-8") as f:
                f.write(self.report_text)

            # Озвучивание
            tts = gTTS(text=self.report_text, lang='ru')
            tts.save(os.path.join(out_dir, "report.mp3"))

            messagebox.showinfo("Готово", f"Все файлы созданы в:\n{out_dir}")
            os.startfile(out_dir)

        except Exception as e:
            messagebox.showerror("Ошибка", str(e))

if __name__ == "__main__":
    root = tk.Tk()
    app = OrderAnalyzerApp(root)
    root.mainloop()