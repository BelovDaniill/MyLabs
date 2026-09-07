import sys

matrix_size = 100
graph_matrix = [[-1] * matrix_size for _ in range(matrix_size)]
unic_points = set()

print("Вводите рёбра в формате: 'вершина1 вершина2 вес' (например, 1 2 23).")
print("Для окончания ввода напишите 'стоп'.")

while True:
    user_input = input("Ребро: ").strip()
    if user_input == "стоп":
        break
    
    try:
        u, v, w = map(int, user_input.split())
        
        if w < 0:
            print("Ошибка: вес ребра не может быть отрицательным! Попробуйте снова.")
            continue
            
        graph_matrix[u][v] = w
        graph_matrix[v][u] = w
        unic_points.add(u)
        unic_points.add(v)
    except ValueError:
        print("Неверный формат! Введите три числа через пробел или 'стоп'.")

if not unic_points:
    print("Граф пуст. Выход.")
    sys.exit()

print("\nИСХОДНЫЙ ГРАФ (Матрица смежности)")
sorted_v = sorted(list(unic_points))
print("     " + " ".join(f"{v:3}" for v in sorted_v))
for u in sorted_v:
    row_str = " ".join(f"{graph_matrix[u][v]:3}" for v in sorted_v)
    print(f"{u:3}: {row_str}")
print("-----------------------------------------\n")

while True:
    try:
        start_point = int(input(f"Введите стартовую вершину из доступных {sorted_v}: "))
        if start_point in unic_points:
            break
        print("Такой вершины нет в графе! Попробуйте еще раз.")
    except ValueError:
        print("Введите корректное число.")





processed = {start_point}
ost = []
total_weight = 0

while len(processed) < len(unic_points):
    min_edge = None
    min_weight = float('inf')
    
    for u in processed:
        for v in unic_points:
            weight = graph_matrix[u][v]
            if v not in processed and weight != -1 and weight < min_weight:
                min_weight = weight
                min_edge = (u, v, weight)
                
    if min_edge is None:
        print("\nОшибка: Граф несвязный! Не удалось обойти все вершины.")
        break
        
    u, v, weight = min_edge
    processed.add(v)
    ost.append((u, v, weight))
    total_weight += weight

print("\n РЕЗУЛЬТАТ (Минимальное остовное дерево)")
for u, v, w in ost:
    print(f"Ребро: {u} - {v}, Вес: {w}")
print(f"Общий вес дерева: {total_weight}")
print("-----------------------------------------------")