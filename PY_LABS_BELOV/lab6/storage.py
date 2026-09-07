from datetime import datetime
from typing import List, Optional

_tasks = []
_id_counter = 1

def create_task(task_data) -> dict:
    global _id_counter
    task_dict = task_data.model_dump()
    
   
    task_dict["id"] = _id_counter
    task_dict["completed"] = False
    task_dict["created_at"] = datetime.now()
    task_dict["completed_at"] = None
    
    _tasks.append(task_dict)
    _id_counter += 1
    return task_dict

def title_exists(title: str) -> bool:
    return any(t["title"] == title for t in _tasks)

def get_task_by_id(task_id: int) -> Optional[dict]:
    for t in _tasks:
        if t["id"] == task_id:
            return t
    return None

def get_tasks_filtered(completed: Optional[bool], priority: Optional[str], limit: Optional[int], sort_by: Optional[str]) -> List[dict]:
    result = _tasks.copy()
    
    # Фильтрация
    if completed is not None:
        result = [t for t in result if t["completed"] == completed]
    if priority:
        result = [t for t in result if t["priority"] == priority]
        
    # Сортировка
    if sort_by == "created_at":
        result.sort(key=lambda x: x["created_at"])
    elif sort_by == "priority":
        # Простая сортировка по весу приоритета
        priority_weights = {"low": 1, "medium": 2, "high": 3}
        result.sort(key=lambda x: priority_weights.get(x["priority"], 0))
        
    # Лимит
    if limit is not None:
        result = result[:limit]
        
    return result

def search_tasks(keyword: Optional[str], priority: Optional[str], date_from: Optional[datetime], date_to: Optional[datetime]) -> List[dict]:
    result = _tasks.copy()
    
    if keyword:
        k = keyword.lower()
        result = [t for t in result if k in t["title"].lower() or (t["description"] and k in t["description"].lower())]
    if priority:
        result = [t for t in result if t["priority"] == priority]
    if date_from:
        result = [t for t in result if t["created_at"] >= date_from]
    if date_to:
        result = [t for t in result if t["created_at"] <= date_to]
        
    return result

def get_stats_data() -> dict:
    total = len(_tasks)
    completed = sum(1 for t in _tasks if t["completed"])
    return {
        "total_tasks": total,
        "completed_tasks": completed,
        "pending_tasks": total - completed
    }

def update_task_full(task_id: int, task_data) -> Optional[dict]:
    task = get_task_by_id(task_id)
    if not task:
        return None
        
    update_dict = task_data.model_dump()
    for key, value in update_dict.items():
        task[key] = value
    return task

def update_task_partial(task_id: int, task_data) -> Optional[dict]:
    task = get_task_by_id(task_id)
    if not task:
        return None
        
    # exclude_unset=True выкинет поля, которые клиент не прислал в запросе
    update_dict = task_data.model_dump(exclude_unset=True)
    for key, value in update_dict.items():
        task[key] = value
    return task

def complete_task(task_id: int) -> str:
    task = get_task_by_id(task_id)
    if not task:
        return "not_found"
    if task["completed"]:
        return "already_completed"
        
    task["completed"] = True
    task["completed_at"] = datetime.now()
    return "success"

def delete_task(task_id: int) -> bool:
    task = get_task_by_id(task_id)
    if not task:
        return False
    _tasks.remove(task)
    return True