def coloring(graph):
    result = {}

    # Проходим по всем вершинам графа в порядке их следования
    for node in graph:
        # Находим цвета, которые уже заняты соседями
        smj_colors = set()
        for smj in graph[node]:
            if smj in result:
                smj_colors.add(result[smj])
        
        color = 0
        while color in smj_colors:
            color += 1

        result[node] = color
        
    return result

graph = {}
colors = {0: 'Красный', 1: 'Зеленый', 2: 'Синий', 3: 'Желтый', 4: 'Фиолетовый', 5: 'Оранжевый', 6: 'Голубой', 7: 'Коричневый', 8: 'Розовый', 9: 'Серый'}
print("Введите ребра (например, 1 2). 'stop' для выхода:")

while True:
    try:
        line = input("> ").strip()
        if line.lower() == 'stop': break
        if not line: continue
        
        parts = line.split()
        if len(parts) != 2:
            print("Пропуск некорректной строки")
            continue
            
        u, v = parts
        if u not in graph: graph[u] = set()
        if v not in graph: graph[v] = set()
        
        if u != v:
            graph[u].add(v)
            graph[v].add(u)
            
    except EOFError:
        break

if not graph:
    print("\nГраф пуст.")
else:
    coloring = coloring(graph)
    print("Результат раскраски вершин:")
    for vertex, color in coloring.items():
        print(f"Вершина {vertex}: Цвет {colors.get(color, 'Не назначен')}")

import networkx as nx
import matplotlib.pyplot as plt




# Блок визуализации

if graph:
    G = nx.Graph()
    for node, neighbors in graph.items():
        G.add_node(node)
        for neighbor in neighbors:
            G.add_edge(node, neighbor)

    # Словарь соответствия русских названий английским (для matplotlib)
    color_map_translator = {
        'Красный': 'red', 'Зеленый': 'green', 'Синий': 'blue', 
        'Желтый': 'yellow', 'Фиолетовый': 'purple', 'Оранжевый': 'orange', 
        'Голубой': 'skyblue', 'Коричневый': 'brown', 'Розовый': 'pink', 'Серый': 'gray'
    }

    # Формируем список цветов, который точно совпадает с текстовым выводом
    node_colors_for_plot = []
    for node in G.nodes():
        color_id = coloring[node]
        russian_color = colors.get(color_id, 'Серый')
        english_color = color_map_translator.get(russian_color, 'gray')
        node_colors_for_plot.append(english_color)

    plt.figure(figsize=(8, 6))
    pos = nx.spring_layout(G)

    nx.draw(
        G, pos, 
        with_labels=True, 
        node_color=node_colors_for_plot,
        node_size=800, 
        font_weight='bold',
        edge_color='silver'
    )

    plt.title("Визуализация последовательной раскраски")
    plt.show()