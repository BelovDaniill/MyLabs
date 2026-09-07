def minimize_dominating_set(graph, dominating_set):
    """
    Удаляет лишние вершины из доминирующего множества, 
    делая его минимальным по включению.
    """
    minimal_set = list(dominating_set)
    all_nodes = set(graph.keys())
    
    for node in list(minimal_set):
        minimal_set.remove(node)
        
        current_covered = set()
        for active_node in minimal_set:
            current_covered.add(active_node)
            current_covered.update(graph[active_node])

        if current_covered != all_nodes:
            minimal_set.append(node)
            
    return set(minimal_set)

def find_dominating_set_greedy(graph):
    uncovered = set(graph.keys())
    dominating_set = set()
    
    while uncovered:
        best_node = None
        max_newly_covered = -1
        
        for node in graph:
            newly_covered = (graph[node] | {node}) & uncovered
            if len(newly_covered) > max_newly_covered:
                max_newly_covered = len(newly_covered)
                best_node = node
        
        if best_node is None: break
        
        dominating_set.add(best_node)
        uncovered -= (graph[best_node] | {best_node})
        
    return dominating_set

graph = {}
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
    # 1. Получаем жадное множество
    greedy_res = find_dominating_set_greedy(graph)
    
    # 2. Минимизируем его (убираем лишние вершины)
    final_res = minimize_dominating_set(graph, greedy_res)
    
    print("\n--- РЕЗУЛЬТАТ ---")
    res_nodes = ", ".join(final_res)
    print(f"Доминирующее множество: {res_nodes}")