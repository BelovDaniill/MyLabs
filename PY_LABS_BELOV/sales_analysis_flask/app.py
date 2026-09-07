import os
import io
import base64
import pandas as pd
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
from flask import Flask, render_template, request

app = Flask(__name__)

# Путь к данным
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
CSV_PATH = os.path.join(BASE_DIR, 'data', 'sales_data_sample.csv')

def create_plot(pivot_data):
    plt.figure(figsize=(10, 5))
    
    # Создаем столбчатую диаграмму
    plt.bar(pivot_data['Категория'], pivot_data['Сумма продаж'], color='#3498db')
    
    plt.title('Продажи по категориям', fontsize=14)
    plt.xlabel('Категории', fontsize=12)
    plt.ylabel('Сумма продаж в $', fontsize=12)
    plt.xticks()
    plt.grid(axis='y', linestyle='--', alpha=0.7)
    
    plt.tight_layout()

    img = io.BytesIO()
    plt.savefig(img, format='png', bbox_inches='tight')
    img.seek(0)
    
    plot_url = base64.b64encode(img.getvalue()).decode('utf8')
    plt.close()
    return plot_url

def get_processed_data(city_filter=None):
    """Функция для загрузки и анализа данных"""
    if not os.path.exists(CSV_PATH):
        return None, "Файл sales_data_sample.csv не найден в папке data/"

    try:
        df = pd.read_csv(CSV_PATH, encoding='unicode_escape')

        # Обработка пустых значений
        df = df.fillna({'STATE': 'N/A', 'POSTALCODE': 'N/A'})

        # Расчет стоимости
        df['общая_стоимость_заказа'] = df['QUANTITYORDERED'] * df['PRICEEACH']
        
        unique_cities = sorted(df['CITY'].unique())
        
        if city_filter and city_filter != 'Все':
            df_final = df[df['CITY'] == city_filter]
        else:
            df_final = df

        stats = {
            'total_orders': len(df_final),
            'total_revenue': round(df_final['общая_стоимость_заказа'].sum(), 2),
            'best_product': df_final.groupby('PRODUCTCODE')['QUANTITYORDERED'].sum().idxmax() if not df_final.empty else "-",
            'top_category': df_final.groupby('PRODUCTLINE')['общая_стоимость_заказа'].sum().idxmax() if not df_final.empty else "-",
            'top_city': df_final['CITY'].value_counts().idxmax() if not df_final.empty else "-"
        }

        pivot_table = df_final.groupby('PRODUCTLINE').agg({
            'общая_стоимость_заказа': 'sum',
            'QUANTITYORDERED': 'sum'
        }).reset_index()
        pivot_table.columns = ['Категория', 'Сумма продаж', 'Количество товаров']

        # Генерация графика
        chart_base64 = create_plot(pivot_table)

        return {
            'df': df_final,
            'stats': stats,
            'pivot': pivot_table,
            'cities': unique_cities,
            'chart_image': chart_base64
        }, None

    except Exception as e:
        return None, f"Ошибка при обработке данных: {e}"

@app.route('/', methods=['GET', 'POST'])
def index():
    selected_city = request.form.get('city_filter', 'Все')
    data, error = get_processed_data(selected_city)

    if error:
        # Статистика на случай ошибки
        empty_stats = {'total_orders': 0, 'total_revenue': 0, 'best_product': '-', 'top_category': '-', 'top_city': '-'}
        return render_template(
            'index.html', 
            error=error, 
            stats=empty_stats, 
            main_table="", 
            pivot_table="", 
            cities=[], 
            selected_city=selected_city,
            chart_image=None
        )

    return render_template(
        'index.html',
        stats=data['stats'],
        main_table=data['df'].to_html(classes='data-table', index=False),
        pivot_table=data['pivot'].to_html(classes='pivot-table', index=False),
        cities=data['cities'],
        selected_city=selected_city,
        chart_image=data['chart_image']
    )

if __name__ == '__main__':
    app.run(debug=True)